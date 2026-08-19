<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Models\Service;
use App\Services\QueueScheduleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class QueueController extends Controller
{
    private QueueScheduleService $schedule;

    public function __construct(QueueScheduleService $schedule)
    {
        $this->schedule = $schedule;
    }

    public function adminIndex()
    {
        $antrians = \App\Models\Queue::with([
            'user',
            'service'
        ])
            ->latest()
            ->get();

        return view('admin.antrean', compact('antrians'));
    }

    /*
    |--------------------------------------------------------------------------
    | HALAMAN LAYANAN
    |--------------------------------------------------------------------------
    */

    public function layanan()
    {
        $services = Service::where('is_active', true)
            ->orderBy('id')
            ->get();

        return view('layanan', compact('services'));
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS ANTREAN PENGUNJUNG
    |--------------------------------------------------------------------------
    */

    public function status(string $public_token)
    {
        /*
        |--------------------------------------------------------------------------
        | CARI ANTREAN PENGUNJUNG
        |--------------------------------------------------------------------------
        */

        $queue = Queue::with('service')
            ->where('public_token', $public_token)
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | TENTUKAN PERIODE ANTREAN
        |--------------------------------------------------------------------------
        | Mengikuti hari operasional saat antrean tersebut dibuat.
        */

        $queueTime = $queue->created_at
            ->copy()
            ->timezone(config('antrian.timezone'));

        $periodStart = $queueTime
            ->copy()
            ->startOfDay()
            ->setTimeFromTimeString(config('antrian.open_time'))
            ->utc();

        $periodEnd = $queueTime
            ->copy()
            ->startOfDay()
            ->setTimeFromTimeString(config('antrian.close_time'))
            ->utc();


        /*
        |--------------------------------------------------------------------------
        | HITUNG ANTREAN DI DEPAN
        |--------------------------------------------------------------------------
        */

        $queuesAhead = Queue::where(
            'service_id',
            $queue->service_id
        )
            ->whereBetween(
                'created_at',
                [$periodStart, $periodEnd]
            )
            ->where(
                'id',
                '<',
                $queue->id
            )
            ->whereIn(
                'status',
                [
                    'waiting',
                    'called',
                    'serving',
                ]
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | TAMPILKAN HALAMAN STATUS
        |--------------------------------------------------------------------------
        */

        return view(
            'status-antrian',
            compact(
                'queue',
                'queuesAhead'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | AMBIL ANTREAN
    |--------------------------------------------------------------------------
    */

    public function ambilAntrian(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | CEK JAM OPERASIONAL
        |--------------------------------------------------------------------------
        | Antrean hanya dapat diambil pukul 07:00 - 16:59 WITA.
        */

        if (!$this->schedule->isOpen()) {
            return response()->json([
                'success' => false,
                'message' => 'Pengambilan antrean telah ditutup. Silakan kembali besok pukul 07.00 WITA.',
            ], 403);
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI DATA
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'service_id' => 'required|exists:services,id',
            'photo' => 'required|string',
        ]);


        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA LAYANAN
        |--------------------------------------------------------------------------
        */

        $service = Service::findOrFail(
            $request->service_id
        );


        /*
        |--------------------------------------------------------------------------
        | PERIODE ANTREAN HARI INI
        |--------------------------------------------------------------------------
        | Contoh:
        |
        | 18 Agustus 2026
        | 07:00 - 17:00 WITA
        |
        | Digunakan agar nomor antrean kembali ke 001 pada hari berikutnya.
        */

        $periodStart = $this->schedule
            ->periodStart()
            ->utc();

        $periodEnd = $this->schedule
            ->periodEnd()
            ->utc();


        /*
        |--------------------------------------------------------------------------
        | CARI ANTREAN TERAKHIR
        |--------------------------------------------------------------------------
        | Mencari antrean terakhir berdasarkan layanan pada periode hari ini.
        */

        $lastQueue = Queue::where(
            'service_id',
            $service->id
        )
            ->where(
                'created_at',
                '>=',
                $periodStart
            )
            ->where(
                'created_at',
                '<',
                $periodEnd
            )
            ->orderByDesc('id')
            ->first();


        /*
        |--------------------------------------------------------------------------
        | BUAT NOMOR ANTREAN BERIKUTNYA
        |--------------------------------------------------------------------------
        |
        | Contoh:
        |
        | A-001
        | A-002
        | A-003
        |
        */

        $lastNumber = 0;

        if ($lastQueue) {
            $parts = explode(
                '-',
                $lastQueue->queue_number
            );

            $lastNumber = (int) end($parts);
        }

        $nextNumber = $lastNumber + 1;

        $queueNumber =
            $service->code
            . '-'
            . str_pad(
                $nextNumber,
                3,
                '0',
                STR_PAD_LEFT
            );


        /*
        |--------------------------------------------------------------------------
        | PROSES FOTO
        |--------------------------------------------------------------------------
        */

        $photoData = $request->photo;


        /*
        |--------------------------------------------------------------------------
        | CEK FORMAT FOTO BASE64
        |--------------------------------------------------------------------------
        |
        | Contoh:
        |
        | data:image/jpeg;base64,/9j/4AAQ...
        |
        */

        if (
            preg_match(
                '/^data:image\/(\w+);base64,/',
                $photoData,
                $matches
            )
        ) {

            /*
            |--------------------------------------------------------------------------
            | AMBIL DATA BASE64
            |--------------------------------------------------------------------------
            */

            $photoData = substr(
                $photoData,
                strpos($photoData, ',') + 1
            );


            /*
            |--------------------------------------------------------------------------
            | DECODE BASE64
            |--------------------------------------------------------------------------
            */

            $photoData = base64_decode(
                $photoData
            );


            if ($photoData === false) {
                return response()->json([
                    'success' => false,
                    'message' => 'Foto tidak dapat diproses.',
                ], 422);
            }


            /*
            |--------------------------------------------------------------------------
            | BUAT NAMA FILE FOTO
            |--------------------------------------------------------------------------
            |
            | Contoh:
            |
            | photos/2026/08/18/queue_xxxxx.jpg
            |
            */

            $fileName =
                'photos/'
                . $this->schedule->now()->format('Y/m/d')
                . '/'
                . uniqid('queue_')
                . '.jpg';


            /*
            |--------------------------------------------------------------------------
            | SIMPAN FOTO
            |--------------------------------------------------------------------------
            */

            Storage::disk('public')->put(
                $fileName,
                $photoData
            );
        } else {

            return response()->json([
                'success' => false,
                'message' => 'Format foto tidak valid.',
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | SIMPAN ANTREAN KE DATABASE
        |--------------------------------------------------------------------------
        */

        $queue = Queue::create([
            'service_id' => $service->id,

            'queue_number' => $queueNumber,

            'public_token' => (string) Str::uuid(),

            'photo' => $fileName,

            'status' => 'waiting',
        ]);


        /*
        |--------------------------------------------------------------------------
        | KIRIM HASIL KE JAVASCRIPT
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'success' => true,

            'message' => 'Nomor antrean berhasil dibuat.',

            'queue' => [
                'id' => $queue->id,

                'number' => $queue->queue_number,

                'service' => $service->name,

                'photo' => asset(
                    'storage/' . $fileName
                ),

                'public_token' => $queue->public_token,

                'status_url' => route(
                    'status.antrian',
                    $queue->public_token
                ),
            ],
        ]);
    }
    /*
    |--------------------------------------------------------------------------
    | HAPUS ANTREAN ADMIN
    |--------------------------------------------------------------------------
    */

    public function destroyAdmin($id)
    {
        $antrian = Queue::findOrFail($id);

        $antrian->delete();

        return redirect()->route('admin.antrean');
    }
}
