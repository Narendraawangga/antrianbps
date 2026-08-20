<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Services\QueueScheduleService;
use Illuminate\Support\Facades\DB;

class PetugasQueueController extends Controller
{
    private QueueScheduleService $schedule;

    public function __construct(
        QueueScheduleService $schedule
    ) {
        $this->schedule = $schedule;
    }


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD PETUGAS
    |--------------------------------------------------------------------------
    */

    public function dashboard()
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | CEK PELAYANAN PETUGAS
        |--------------------------------------------------------------------------
        */

        if (!$user->service_id) {

            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Akun petugas belum memiliki pelayanan.'
                );
        }


        $serviceId =
            $user->service_id;


        $periodStart =
            $this->schedule
                ->periodStart()
                ->utc();

        $periodEnd =
            $this->schedule
                ->periodEnd()
                ->utc();


        /*
        |--------------------------------------------------------------------------
        | ANTREAN MENUNGGU SESUAI PELAYANAN PETUGAS
        |--------------------------------------------------------------------------
        */

        $waitingQueues = Queue::with('service')

            ->whereBetween(
                'created_at',
                [
                    $periodStart,
                    $periodEnd
                ]
            )

            ->where(
                'service_id',
                $serviceId
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
        | ANTREAN AKTIF PETUGAS
        |--------------------------------------------------------------------------
        */

        $currentQueue = Queue::with('service')

            ->whereBetween(
                'created_at',
                [
                    $periodStart,
                    $periodEnd
                ]
            )

            ->where(
                'service_id',
                $serviceId
            )

            ->where(
                'served_by',
                $user->id
            )

            ->whereIn(
                'status',
                [
                    'called',
                    'serving',
                ]
            )

            ->orderByDesc(
                'called_at'
            )

            ->first();


        /*
        |--------------------------------------------------------------------------
        | JUMLAH SELESAI HARI INI
        |--------------------------------------------------------------------------
        */

        $completedCount = Queue::whereBetween(
            'created_at',
            [
                $periodStart,
                $periodEnd
            ]
        )

            ->where(
                'service_id',
                $serviceId
            )

            ->where(
                'served_by',
                $user->id
            )

            ->where(
                'status',
                'completed'
            )

            ->count();


        /*
        |--------------------------------------------------------------------------
        | ANTREAN DILEWATI
        |--------------------------------------------------------------------------
        |
        | Hanya tampil antrean dilewati dari pelayanan petugas.
        |
        */

        $skippedQueues = Queue::with('service')

            ->whereBetween(
                'created_at',
                [
                    $periodStart,
                    $periodEnd
                ]
            )

            ->where(
                'service_id',
                $serviceId
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
        | TOTAL ANTREAN YANG DITANGANI PETUGAS
        |--------------------------------------------------------------------------
        */

        $totalServiceCount = Queue::whereBetween(
            'created_at',
            [
                $periodStart,
                $periodEnd
            ]
        )

            ->where(
                'service_id',
                $serviceId
            )

            ->where(
                'served_by',
                $user->id
            )

            ->count();


        /*
        |--------------------------------------------------------------------------
        | PELAYANAN PETUGAS
        |--------------------------------------------------------------------------
        */

        $petugasService =
            $user->service;


        /*
        |--------------------------------------------------------------------------
        | KIRIM KE VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'petugas.dashboard',
            compact(
                'waitingQueues',
                'currentQueue',
                'completedCount',
                'totalServiceCount',
                'skippedQueues',
                'petugasService'
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
        $user =
            auth()->user();


        if (!$user->service_id) {

            return redirect()
                ->route('petugas.dashboard')
                ->with(
                    'error',
                    'Pelayanan petugas belum ditentukan.'
                );
        }


        $serviceId =
            $user->service_id;


        $periodStart =
            $this->schedule
                ->periodStart()
                ->utc();

        $periodEnd =
            $this->schedule
                ->periodEnd()
                ->utc();


        /*
        |--------------------------------------------------------------------------
        | CEK ANTREAN AKTIF
        |--------------------------------------------------------------------------
        */

        $activeQueue = Queue::whereBetween(
            'created_at',
            [
                $periodStart,
                $periodEnd
            ]
        )

            ->where(
                'service_id',
                $serviceId
            )

            ->where(
                'served_by',
                $user->id
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
                ->route(
                    'petugas.dashboard'
                )
                ->with(
                    'error',
                    'Selesaikan antrean aktif terlebih dahulu.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | AMBIL ANTREAN TERLAMA SESUAI PELAYANAN
        |--------------------------------------------------------------------------
        */

        $queueFound = false;


        DB::transaction(
            function () use (
                $periodStart,
                $periodEnd,
                $serviceId,
                $user,
                &$queueFound
            ) {

                $queue = Queue::whereBetween(
                    'created_at',
                    [
                        $periodStart,
                        $periodEnd
                    ]
                )

                    ->where(
                        'service_id',
                        $serviceId
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


                /*
                |--------------------------------------------------------------------------
                | TIDAK ADA ANTREAN
                |--------------------------------------------------------------------------
                */

                if (!$queue) {
                    return;
                }


                $queueFound =
                    true;


                /*
                |--------------------------------------------------------------------------
                | UBAH STATUS MENJADI DIPANGGIL
                |--------------------------------------------------------------------------
                */

                $queue->update([

                    'status' =>
                        'called',

                    'called_at' =>
                        $this->schedule
                            ->now()
                            ->utc(),

                    'served_by' =>
                        $user->id,

                ]);
            }
        );


        if (!$queueFound) {

            return redirect()
                ->route(
                    'petugas.dashboard'
                )
                ->with(
                    'error',
                    'Tidak ada antrean menunggu untuk pelayanan Anda.'
                );
        }


        return redirect()
            ->route(
                'petugas.dashboard'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | MULAI PELAYANAN
    |--------------------------------------------------------------------------
    */

    public function mulai()
    {
        $user =
            auth()->user();


        if (!$user->service_id) {

            return redirect()
                ->route(
                    'petugas.dashboard'
                );
        }


        $periodStart =
            $this->schedule
                ->periodStart()
                ->utc();

        $periodEnd =
            $this->schedule
                ->periodEnd()
                ->utc();


        /*
        |--------------------------------------------------------------------------
        | CARI ANTREAN CALLED MILIK PETUGAS
        |--------------------------------------------------------------------------
        */

        $queue = Queue::whereBetween(
            'created_at',
            [
                $periodStart,
                $periodEnd
            ]
        )

            ->where(
                'service_id',
                $user->service_id
            )

            ->where(
                'served_by',
                $user->id
            )

            ->where(
                'status',
                'called'
            )

            ->orderByDesc(
                'called_at'
            )

            ->first();


        if (!$queue) {

            return redirect()
                ->route(
                    'petugas.dashboard'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | UBAH KE SEDANG DILAYANI
        |--------------------------------------------------------------------------
        */

        $queue->update([

            'status' =>
                'serving',

            'started_at' =>
                $this->schedule
                    ->now()
                    ->utc(),

        ]);


        return redirect()
            ->route(
                'petugas.dashboard'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | LEWATI ANTREAN
    |--------------------------------------------------------------------------
    */

    public function lewati()
    {
        $user =
            auth()->user();


        if (!$user->service_id) {

            return redirect()
                ->route(
                    'petugas.dashboard'
                );
        }


        $periodStart =
            $this->schedule
                ->periodStart()
                ->utc();

        $periodEnd =
            $this->schedule
                ->periodEnd()
                ->utc();


        /*
        |--------------------------------------------------------------------------
        | CARI ANTREAN CALLED MILIK PETUGAS
        |--------------------------------------------------------------------------
        */

        $queue = Queue::whereBetween(
            'created_at',
            [
                $periodStart,
                $periodEnd
            ]
        )

            ->where(
                'service_id',
                $user->service_id
            )

            ->where(
                'served_by',
                $user->id
            )

            ->where(
                'status',
                'called'
            )

            ->orderByDesc(
                'called_at'
            )

            ->first();


        if (!$queue) {

            return redirect()
                ->route(
                    'petugas.dashboard'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | STATUS DILEWATI
        |--------------------------------------------------------------------------
        */

        $queue->update([

            'status' =>
                'skipped',

        ]);


        return redirect()
            ->route(
                'petugas.dashboard'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | PANGGIL ULANG ANTREAN DILEWATI
    |--------------------------------------------------------------------------
    */

    public function panggilUlang(
        int $id
    ) {
        $user =
            auth()->user();


        if (!$user->service_id) {

            return redirect()
                ->route(
                    'petugas.dashboard'
                );
        }


        $periodStart =
            $this->schedule
                ->periodStart()
                ->utc();

        $periodEnd =
            $this->schedule
                ->periodEnd()
                ->utc();


        /*
        |--------------------------------------------------------------------------
        | CEK ANTREAN AKTIF PETUGAS
        |--------------------------------------------------------------------------
        */

        $activeQueue = Queue::whereBetween(
            'created_at',
            [
                $periodStart,
                $periodEnd
            ]
        )

            ->where(
                'service_id',
                $user->service_id
            )

            ->where(
                'served_by',
                $user->id
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
                ->route(
                    'petugas.dashboard'
                )
                ->with(
                    'error',
                    'Selesaikan antrean aktif terlebih dahulu.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | CARI ANTREAN DILEWATI
        |--------------------------------------------------------------------------
        |
        | PENTING:
        | service_id harus sama dengan pelayanan petugas.
        |
        */

        $queue = Queue::where(
            'id',
            $id
        )

            ->whereBetween(
                'created_at',
                [
                    $periodStart,
                    $periodEnd
                ]
            )

            ->where(
                'service_id',
                $user->service_id
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

            'status' =>
                'called',

            'called_at' =>
                $this->schedule
                    ->now()
                    ->utc(),

            'served_by' =>
                $user->id,

        ]);


        return redirect()
            ->route(
                'petugas.dashboard'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SELESAIKAN ANTREAN
    |--------------------------------------------------------------------------
    */

    public function selesai()
    {
        $user =
            auth()->user();


        if (!$user->service_id) {

            return redirect()
                ->route(
                    'petugas.dashboard'
                );
        }


        $periodStart =
            $this->schedule
                ->periodStart()
                ->utc();

        $periodEnd =
            $this->schedule
                ->periodEnd()
                ->utc();


        /*
        |--------------------------------------------------------------------------
        | HANYA ANTREAN YANG SUDAH SERVING
        |--------------------------------------------------------------------------
        */

        $queue = Queue::whereBetween(
            'created_at',
            [
                $periodStart,
                $periodEnd
            ]
        )

            ->where(
                'service_id',
                $user->service_id
            )

            ->where(
                'served_by',
                $user->id
            )

            ->where(
                'status',
                'serving'
            )

            ->orderByDesc(
                'called_at'
            )

            ->first();


        if (!$queue) {

            return redirect()
                ->route(
                    'petugas.dashboard'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | SELESAI
        |--------------------------------------------------------------------------
        */

        $queue->update([

            'status' =>
                'completed',

            'completed_at' =>
                $this->schedule
                    ->now()
                    ->utc(),

        ]);


        return redirect()
            ->route(
                'petugas.dashboard'
            );
    }
}