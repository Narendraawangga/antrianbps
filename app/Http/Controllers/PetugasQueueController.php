<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Services\QueueScheduleService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PetugasQueueController extends Controller
{
    private QueueScheduleService $schedule;

    public function __construct(QueueScheduleService $schedule)
    {
        $this->schedule = $schedule;
    }


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD PETUGAS
    |--------------------------------------------------------------------------
    */

    public function dashboard()
    {
        $periodStart = $this->schedule
            ->periodStart()
            ->utc();

        $periodEnd = $this->schedule
            ->periodEnd()
            ->utc();


        /*
        |--------------------------------------------------------------------------
        | ANTREAN MENUNGGU
        |--------------------------------------------------------------------------
        */

        $waitingQueues = Queue::with('service')
            ->whereBetween(
                'created_at',
                [$periodStart, $periodEnd]
            )
            ->where(
                'status',
                'waiting'
            )
            ->orderBy(
                'created_at',
                'asc'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | ANTREAN YANG SEDANG DIPANGGIL / DILAYANI PETUGAS INI
        |--------------------------------------------------------------------------
        */

        $currentQueue = Queue::with('service')
            ->whereBetween(
                'created_at',
                [$periodStart, $periodEnd]
            )
            ->where(
                'served_by',
                Auth::id()
            )
            ->whereIn(
                'status',
                [
                    'called',
                    'serving',
                ]
            )
            ->orderByDesc('called_at')
            ->first();


        /*
        |--------------------------------------------------------------------------
        | JUMLAH PELAYANAN SELESAI HARI INI
        |--------------------------------------------------------------------------
        */

        $completedCount = Queue::whereBetween(
            'created_at',
            [$periodStart, $periodEnd]
        )
            ->where(
                'served_by',
                Auth::id()
            )
            ->where(
                'status',
                'completed'
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | ANTREAN YANG DILEWATI
        |--------------------------------------------------------------------------
        */

        $skippedQueues = Queue::with('service')
            ->whereBetween(
                'created_at',
                [$periodStart, $periodEnd]
            )
            ->where(
                'status',
                'skipped'
            )
            ->orderBy(
                'created_at',
                'asc'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | TOTAL PELAYANAN HARI INI
        |--------------------------------------------------------------------------
        */

        $totalServiceCount = Queue::whereBetween(
            'created_at',
            [$periodStart, $periodEnd]
        )
            ->where(
                'served_by',
                Auth::id()
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | KIRIM DATA KE DASHBOARD
        |--------------------------------------------------------------------------
        */

        return view(
            'petugas.dashboard',
            compact(
                'waitingQueues',
                'currentQueue',
                'completedCount',
                'totalServiceCount',
                'skippedQueues'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PANGGIL ANTREAN BERIKUTNYA
    |--------------------------------------------------------------------------
    */

    public function panggil()
    {
        $periodStart = $this->schedule
            ->periodStart()
            ->utc();

        $periodEnd = $this->schedule
            ->periodEnd()
            ->utc();


        /*
        |--------------------------------------------------------------------------
        | CEK APAKAH PETUGAS MASIH PUNYA ANTREAN AKTIF
        |--------------------------------------------------------------------------
        */

        $activeQueue = Queue::whereBetween(
            'created_at',
            [$periodStart, $periodEnd]
        )
            ->where(
                'served_by',
                Auth::id()
            )
            ->whereIn(
                'status',
                [
                    'called',
                    'serving',
                ]
            )
            ->exists();


        if ($activeQueue) {

            return redirect()
                ->route('petugas.dashboard')
                ->with(
                    'error',
                    'Selesaikan antrean yang sedang aktif terlebih dahulu.'
                );
        }


        DB::transaction(function () use (
            $periodStart,
            $periodEnd
        ) {

            $queue = Queue::whereBetween(
                'created_at',
                [$periodStart, $periodEnd]
            )
                ->where(
                    'status',
                    'waiting'
                )
                ->orderBy(
                    'created_at',
                    'asc'
                )
                ->lockForUpdate()
                ->first();


            if (!$queue) {
                return;
            }


            $queue->update([
                'status' => 'called',

                'called_at' => $this->schedule
                    ->now()
                    ->utc(),

                'served_by' => Auth::id(),
            ]);
        });


        return redirect()
            ->route('petugas.dashboard');
    }


    /*
    |--------------------------------------------------------------------------
    | MULAI PELAYANAN
    |--------------------------------------------------------------------------
    */

    public function mulai()
    {
        $periodStart = $this->schedule
            ->periodStart()
            ->utc();

        $periodEnd = $this->schedule
            ->periodEnd()
            ->utc();


        /*
        |--------------------------------------------------------------------------
        | CARI ANTREAN YANG SUDAH DIPANGGIL
        |--------------------------------------------------------------------------
        */

        $queue = Queue::whereBetween(
            'created_at',
            [$periodStart, $periodEnd]
        )
            ->where(
                'served_by',
                Auth::id()
            )
            ->where(
                'status',
                'called'
            )
            ->orderByDesc('called_at')
            ->first();


        /*
        |--------------------------------------------------------------------------
        | JIKA TIDAK ADA ANTREAN
        |--------------------------------------------------------------------------
        */

        if (!$queue) {

            return redirect()
                ->route('petugas.dashboard');
        }


        /*
        |--------------------------------------------------------------------------
        | UBAH MENJADI SEDANG DILAYANI
        |--------------------------------------------------------------------------
        */

        $queue->update([
            'status' => 'serving',

            'started_at' => $this->schedule
                ->now()
                ->utc(),
        ]);


        return redirect()
            ->route('petugas.dashboard');
    }


    /*
    |--------------------------------------------------------------------------
    | LEWATI ANTREAN
    |--------------------------------------------------------------------------
    */

    public function lewati()
    {
        $periodStart = $this->schedule
            ->periodStart()
            ->utc();

        $periodEnd = $this->schedule
            ->periodEnd()
            ->utc();


        /*
        |--------------------------------------------------------------------------
        | CARI ANTREAN YANG SUDAH DIPANGGIL
        |--------------------------------------------------------------------------
        */

        $queue = Queue::whereBetween(
            'created_at',
            [$periodStart, $periodEnd]
        )
            ->where(
                'served_by',
                Auth::id()
            )
            ->where(
                'status',
                'called'
            )
            ->orderByDesc('called_at')
            ->first();


        /*
        |--------------------------------------------------------------------------
        | JIKA TIDAK ADA ANTREAN
        |--------------------------------------------------------------------------
        */

        if (!$queue) {

            return redirect()
                ->route('petugas.dashboard');
        }


        /*
        |--------------------------------------------------------------------------
        | UBAH STATUS MENJADI DILEWATI
        |--------------------------------------------------------------------------
        */

        $queue->update([
            'status' => 'skipped',
        ]);


        return redirect()
            ->route('petugas.dashboard');
    }


    /*
    |--------------------------------------------------------------------------
    | PANGGIL ULANG ANTREAN YANG DILEWATI
    |--------------------------------------------------------------------------
    */

    public function panggilUlang(int $id)
    {
        $periodStart = $this->schedule
            ->periodStart()
            ->utc();

        $periodEnd = $this->schedule
            ->periodEnd()
            ->utc();


        /*
        |--------------------------------------------------------------------------
        | CEK ANTREAN AKTIF PETUGAS
        |--------------------------------------------------------------------------
        */

        $activeQueue = Queue::whereBetween(
            'created_at',
            [$periodStart, $periodEnd]
        )
            ->where(
                'served_by',
                Auth::id()
            )
            ->whereIn(
                'status',
                [
                    'called',
                    'serving',
                ]
            )
            ->exists();


        if ($activeQueue) {

            return redirect()
                ->route('petugas.dashboard')
                ->with(
                    'error',
                    'Selesaikan antrean aktif terlebih dahulu.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | CARI ANTREAN YANG DIPILIH
        |--------------------------------------------------------------------------
        */

        $queue = Queue::where(
            'id',
            $id
        )
            ->whereBetween(
                'created_at',
                [$periodStart, $periodEnd]
            )
            ->where(
                'status',
                'skipped'
            )
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | PANGGIL KEMBALI
        |--------------------------------------------------------------------------
        */

        $queue->update([
            'status' => 'called',

            'called_at' => $this->schedule
                ->now()
                ->utc(),

            'served_by' => Auth::id(),
        ]);


        return redirect()
            ->route('petugas.dashboard');
    }


    /*
    |--------------------------------------------------------------------------
    | SELESAIKAN ANTREAN
    |--------------------------------------------------------------------------
    */

    public function selesai()
    {
        $periodStart = $this->schedule
            ->periodStart()
            ->utc();

        $periodEnd = $this->schedule
            ->periodEnd()
            ->utc();


        $queue = Queue::whereBetween(
            'created_at',
            [$periodStart, $periodEnd]
        )
            ->where(
                'served_by',
                Auth::id()
            )
            ->whereIn(
                'status',
                [
                    'called',
                    'serving',
                ]
            )
            ->orderByDesc('called_at')
            ->first();


        if (!$queue) {

            return redirect()
                ->route('petugas.dashboard');
        }


        $queue->update([
            'status' => 'completed',

            'completed_at' => $this->schedule
                ->now()
                ->utc(),
        ]);


        return redirect()
            ->route('petugas.dashboard');
    }
}
