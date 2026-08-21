<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Models\Service;
use App\Services\QueueScheduleService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class QueueController extends Controller
{
    private QueueScheduleService $schedule;


    public function __construct(
        QueueScheduleService $schedule
    )
    {
        $this->schedule = $schedule;
    }


    /*
    |--------------------------------------------------------------------------
    | ADMIN ANTREAN
    |--------------------------------------------------------------------------
    */

    public function adminIndex()
    {
        $antrians = Queue::with([
            'user',
            'service'
        ])
            ->latest()
            ->get();


        return view(
            'admin.antrean',
            compact('antrians')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | HALAMAN LAYANAN PENGUNJUNG
    |--------------------------------------------------------------------------
    */

    public function layanan()
    {
        $services = Service::where(
            'is_active',
            true
        )
            ->orderBy('id')
            ->get();


        return view(
            'layanan',
            compact('services')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STATUS ANTREAN PENGUNJUNG
    |--------------------------------------------------------------------------
    */

    public function status(
        string $public_token
    )
    {
        /*
        |--------------------------------------------------------------------------
        | CARI ANTREAN
        |--------------------------------------------------------------------------
        */

        $queue = Queue::with('service')
            ->where(
                'public_token',
                $public_token
            )
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | WAKTU ANTREAN
        |--------------------------------------------------------------------------
        */

        $queueTime = $queue->created_at
            ->copy()
            ->timezone(
                config(
                    'antrian.timezone'
                )
            );


        /*
        |--------------------------------------------------------------------------
        | PERIODE ANTREAN
        |--------------------------------------------------------------------------
        */

        $periodStart = $queueTime
            ->copy()
            ->startOfDay()
            ->setTimeFromTimeString(
                config(
                    'antrian.open_time'
                )
            )
            ->utc();


        $periodEnd = $queueTime
            ->copy()
            ->startOfDay()
            ->setTimeFromTimeString(
                config(
                    'antrian.close_time'
                )
            )
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
                [
                    $periodStart,
                    $periodEnd
                ]
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
                    'serving'
                ]
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | TAMPILKAN STATUS
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

    public function ambilAntrian(
        Request $request
    )
    {
        /*
        |--------------------------------------------------------------------------
        | CEK JAM OPERASIONAL
        |--------------------------------------------------------------------------
        */

        if (
            !$this->schedule->isOpen()
        ) {

            return response()->json([
                'success' => false,

                'message' =>
                    'Pengambilan antrean telah ditutup. Silakan kembali besok pukul 07.00 WITA.',
            ], 403);
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'visitor_name' =>
                'required|string|min:2|max:100',

            'service_id' =>
                'required|exists:services,id',

            'photo' =>
                'required|string',
        ]);


        /*
        |--------------------------------------------------------------------------
        | BERSIHKAN NAMA PENGUNJUNG
        |--------------------------------------------------------------------------
        */

        $visitorName = trim(
            $request->visitor_name
        );


        /*
        |--------------------------------------------------------------------------
        | CEK LAYANAN AKTIF
        |--------------------------------------------------------------------------
        */

        $service = Service::whereKey(
            $request->service_id
        )
            ->where(
                'is_active',
                true
            )
            ->first();


        if (!$service) {

            return response()->json([
                'success' => false,

                'message' =>
                    'Layanan yang dipilih sedang tidak aktif.',
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | PERIODE ANTREAN HARI INI
        |--------------------------------------------------------------------------
        */

        $periodStart = $this->schedule
            ->periodStart()
            ->utc();


        $periodEnd = $this->schedule
            ->periodEnd()
            ->utc();


        /*
        |--------------------------------------------------------------------------
        | FOTO BASE64
        |--------------------------------------------------------------------------
        */

        $photoData = $request->photo;


        /*
        |--------------------------------------------------------------------------
        | CEK FORMAT FOTO
        |--------------------------------------------------------------------------
        */

        if (
            !preg_match(
                '/^data:image\/(\w+);base64,/',
                $photoData
            )
        ) {

            return response()->json([
                'success' => false,

                'message' =>
                    'Format foto tidak valid.',
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | HAPUS PREFIX BASE64
        |--------------------------------------------------------------------------
        */

        $photoData = substr(
            $photoData,
            strpos(
                $photoData,
                ','
            ) + 1
        );


        /*
        |--------------------------------------------------------------------------
        | DECODE FOTO
        |--------------------------------------------------------------------------
        */

        $photoData = base64_decode(
            $photoData,
            true
        );


        if (
            $photoData === false
        ) {

            return response()->json([
                'success' => false,

                'message' =>
                    'Foto tidak dapat diproses.',
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | NAMA FILE FOTO
        |--------------------------------------------------------------------------
        */

        $fileName =
            'photos/'
            . $this->schedule
                ->now()
                ->format('Y/m/d')
            . '/'
            . uniqid(
                'queue_',
                true
            )
            . '.jpg';


        /*
        |--------------------------------------------------------------------------
        | SIMPAN FOTO
        |--------------------------------------------------------------------------
        */

        Storage::disk('public')
            ->put(
                $fileName,
                $photoData
            );


        /*
        |--------------------------------------------------------------------------
        | BUAT ANTREAN
        |--------------------------------------------------------------------------
        */

        try {

            $queue = DB::transaction(
                function () use (
                    $service,
                    $visitorName,
                    $periodStart,
                    $periodEnd,
                    $fileName
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | KUNCI LAYANAN
                    |--------------------------------------------------------------------------
                    |
                    | Mencegah PC dan HP mendapatkan nomor sama
                    | ketika mengambil antrean bersamaan.
                    |
                    */

                    Service::whereKey(
                        $service->id
                    )
                        ->lockForUpdate()
                        ->firstOrFail();


                    /*
                    |--------------------------------------------------------------------------
                    | ANTREAN TERAKHIR
                    |--------------------------------------------------------------------------
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
                        ->lockForUpdate()
                        ->first();


                    /*
                    |--------------------------------------------------------------------------
                    | NOMOR TERAKHIR
                    |--------------------------------------------------------------------------
                    */

                    $lastNumber = 0;


                    if ($lastQueue) {

                        $parts = explode(
                            '-',
                            $lastQueue->queue_number
                        );


                        $lastNumber =
                            (int) end($parts);
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | NOMOR BARU
                    |--------------------------------------------------------------------------
                    */

                    $nextNumber =
                        $lastNumber + 1;


                    /*
                    |--------------------------------------------------------------------------
                    | FORMAT NOMOR
                    |--------------------------------------------------------------------------
                    |
                    | Contoh:
                    |
                    | A-001
                    | A-002
                    | A-003
                    |
                    */

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
                    | SIMPAN DATABASE
                    |--------------------------------------------------------------------------
                    */

                    return Queue::create([

                        'service_id' =>
                            $service->id,


                        'queue_number' =>
                            $queueNumber,


                        'visitor_name' =>
                            $visitorName,


                        'public_token' =>
                            (string) Str::uuid(),


                        'photo' =>
                            $fileName,


                        'status' =>
                            'waiting',
                    ]);
                }
            );

        } catch (
            \Throwable $error
        ) {

            /*
            |--------------------------------------------------------------------------
            | HAPUS FOTO JIKA DATABASE GAGAL
            |--------------------------------------------------------------------------
            */

            Storage::disk('public')
                ->delete(
                    $fileName
                );


            report(
                $error
            );


            return response()->json([
                'success' => false,

                'message' =>
                    'Antrean gagal dibuat. Silakan coba kembali.',
            ], 500);
        }


        /*
        |--------------------------------------------------------------------------
        | RESPONSE KE HALAMAN LAYANAN
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'success' =>
                true,


            'message' =>
                'Nomor antrean berhasil dibuat.',


            'queue' => [

                'id' =>
                    $queue->id,


                'number' =>
                    $queue->queue_number,


                /*
                |--------------------------------------------------------------------------
                | NAMA PENGUNJUNG
                |--------------------------------------------------------------------------
                */

                'visitor_name' =>
                    $queue->visitor_name,


                /*
                |--------------------------------------------------------------------------
                | LAYANAN
                |--------------------------------------------------------------------------
                */

                'service' =>
                    $service->name,


                /*
                |--------------------------------------------------------------------------
                | FOTO
                |--------------------------------------------------------------------------
                */

                'photo' =>
                    asset(
                        'storage/'
                        . $fileName
                    ),


                /*
                |--------------------------------------------------------------------------
                | TOKEN
                |--------------------------------------------------------------------------
                */

                'public_token' =>
                    $queue->public_token,


                /*
                |--------------------------------------------------------------------------
                | URL STATUS
                |--------------------------------------------------------------------------
                */

                'status_url' =>
                    route(
                        'status.antrian',
                        $queue->public_token
                    ),
            ],
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | LAPORAN ADMIN
    |--------------------------------------------------------------------------
    */

    public function laporan(
        Request $request
    )
    {
        $services = Service::orderBy(
            'name'
        )
            ->get();


        $startDate = $request->input(
            'start_date',
            now()->format('Y-m-d')
        );


        $endDate = $request->input(
            'end_date',
            now()->format('Y-m-d')
        );


        $serviceId = $request->input(
            'service_id'
        );


        /*
        |--------------------------------------------------------------------------
        | QUERY
        |--------------------------------------------------------------------------
        */

        $query = Queue::with([
            'service',
            'servedBy'
        ])
            ->whereDate(
                'created_at',
                '>=',
                $startDate
            )
            ->whereDate(
                'created_at',
                '<=',
                $endDate
            );


        if ($serviceId) {

            $query->where(
                'service_id',
                $serviceId
            );
        }


        $antrians = $query
            ->latest('created_at')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | STATISTIK
        |--------------------------------------------------------------------------
        */

        $total =
            $antrians->count();


        $waiting =
            $antrians
                ->where(
                    'status',
                    'waiting'
                )
                ->count();


        $called =
            $antrians
                ->where(
                    'status',
                    'called'
                )
                ->count();


        $serving =
            $antrians
                ->where(
                    'status',
                    'serving'
                )
                ->count();


        $completed =
            $antrians
                ->where(
                    'status',
                    'completed'
                )
                ->count();


        $skipped =
            $antrians
                ->where(
                    'status',
                    'skipped'
                )
                ->count();


        /*
        |--------------------------------------------------------------------------
        | REKAP LAYANAN
        |--------------------------------------------------------------------------
        */

        $rekapLayanan = $antrians
            ->groupBy('service_id')

            ->map(
                function ($queues) {

                    $service =
                        $queues
                            ->first()
                            ->service;


                    return [

                        'service' =>
                            $service,


                        'total' =>
                            $queues
                                ->count(),


                        'waiting' =>
                            $queues
                                ->where(
                                    'status',
                                    'waiting'
                                )
                                ->count(),


                        'called' =>
                            $queues
                                ->where(
                                    'status',
                                    'called'
                                )
                                ->count(),


                        'serving' =>
                            $queues
                                ->where(
                                    'status',
                                    'serving'
                                )
                                ->count(),


                        'completed' =>
                            $queues
                                ->where(
                                    'status',
                                    'completed'
                                )
                                ->count(),


                        'skipped' =>
                            $queues
                                ->where(
                                    'status',
                                    'skipped'
                                )
                                ->count(),
                    ];
                }
            )
            ->values();


        return view(
            'admin.laporan',
            compact(
                'services',
                'antrians',
                'rekapLayanan',
                'startDate',
                'endDate',
                'serviceId',
                'total',
                'waiting',
                'called',
                'serving',
                'completed',
                'skipped'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | HAPUS ANTREAN ADMIN
    |--------------------------------------------------------------------------
    */

    public function destroyAdmin(
        $id
    )
    {
        $antrian =
            Queue::findOrFail(
                $id
            );


        $antrian->delete();


        return redirect()
            ->route(
                'admin.antrean'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | CETAK LAPORAN ADMIN
    |--------------------------------------------------------------------------
    */

    public function cetakLaporan(
        Request $request
    )
    {
        $startDate = $request->input(
            'start_date',
            now()->format('Y-m-d')
        );


        $endDate = $request->input(
            'end_date',
            now()->format('Y-m-d')
        );


        $serviceId = $request->input(
            'service_id'
        );


        $query = Queue::with([
            'service',
            'servedBy'
        ])
            ->whereDate(
                'created_at',
                '>=',
                $startDate
            )
            ->whereDate(
                'created_at',
                '<=',
                $endDate
            );


        if ($serviceId) {

            $query->where(
                'service_id',
                $serviceId
            );
        }


        $antrians = $query
            ->orderBy('created_at')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | STATISTIK
        |--------------------------------------------------------------------------
        */

        $total =
            $antrians->count();


        $waiting =
            $antrians
                ->where(
                    'status',
                    'waiting'
                )
                ->count();


        $serving =
            $antrians
                ->where(
                    'status',
                    'serving'
                )
                ->count();


        $completed =
            $antrians
                ->where(
                    'status',
                    'completed'
                )
                ->count();


        $skipped =
            $antrians
                ->where(
                    'status',
                    'skipped'
                )
                ->count();


        return view(
            'admin.laporan-cetak',
            compact(
                'antrians',
                'startDate',
                'endDate',
                'serviceId',
                'total',
                'waiting',
                'serving',
                'completed',
                'skipped'
            )
        );
    }
}