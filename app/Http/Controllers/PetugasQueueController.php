<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Models\Service;
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


        if (!$user->service_id) {

            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Akun petugas belum memiliki pelayanan.'
                );
        }


        $serviceId = $user->service_id;


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
        | ANTREAN AKTIF
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
        | SELESAI HARI INI
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
        | TOTAL YANG DITANGANI
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

        $petugasService = $user->service;


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
    | HALAMAN ANTREAN PETUGAS
    |--------------------------------------------------------------------------
    */

    public function antrean()
    {
        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | PETUGAS WAJIB PUNYA PELAYANAN
        |--------------------------------------------------------------------------
        */

        if (!$user->service_id) {

            return redirect()
                ->route('petugas.dashboard')
                ->with(
                    'error',
                    'Akun petugas belum memiliki pelayanan.'
                );
        }


        $serviceId = $user->service_id;


        $periodStart = $this->schedule
            ->periodStart()
            ->utc();


        $periodEnd = $this->schedule
            ->periodEnd()
            ->utc();


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
        | ANTREAN MENUNGGU
        |--------------------------------------------------------------------------
        |
        | Hanya pelayanan milik petugas.
        |
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
        | ANTREAN DILEWATI
        |--------------------------------------------------------------------------
        |
        | Hanya pelayanan milik petugas.
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
        | INFORMASI PELAYANAN
        |--------------------------------------------------------------------------
        */

        $petugasService = $user->service;


        return view(
            'petugas.antrean',
            compact(
                'currentQueue',
                'waitingQueues',
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
        $user = auth()->user();

        if (!$user->service_id) {
            return redirect()
                ->route('petugas.dashboard')
                ->with(
                    'error',
                    'Pelayanan petugas belum ditentukan.'
                );
        }

        $serviceId = $user->service_id;

        $periodStart = $this->schedule
            ->periodStart()
            ->utc();

        $periodEnd = $this->schedule
            ->periodEnd()
            ->utc();

        /*
        |--------------------------------------------------------------------------
        | TRANSAKSI PANGGIL ANTREAN
        |--------------------------------------------------------------------------
        |
        | Service dikunci terlebih dahulu agar dua petugas dengan pelayanan
        | yang sama tidak dapat memanggil dua antrean secara bersamaan.
        |
        */

        $result = DB::transaction(
            function () use (
                $serviceId,
                $periodStart,
                $periodEnd,
                $user
            ) {
                /*
                |------------------------------------------------------------------
                | KUNCI PELAYANAN
                |------------------------------------------------------------------
                */

                Service::whereKey($serviceId)
                    ->lockForUpdate()
                    ->firstOrFail();

                /*
                |------------------------------------------------------------------
                | CEK ANTREAN AKTIF PADA PELAYANAN
                |------------------------------------------------------------------
                |
                | Tidak menggunakan served_by, sehingga satu pelayanan hanya
                | boleh memiliki satu antrean berstatus called/serving.
                |
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
                    ->whereIn(
                        'status',
                        [
                            'called',
                            'serving'
                        ]
                    )
                    ->exists();

                if ($activeQueue) {
                    return 'active';
                }

                /*
                |------------------------------------------------------------------
                | AMBIL ANTREAN PALING AWAL SESUAI PELAYANAN
                |------------------------------------------------------------------
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

                if (!$queue) {
                    return 'empty';
                }

                $queue->update([
                    'status' => 'called',
                    'called_at' => $this->schedule
                        ->now()
                        ->utc(),
                    'served_by' => $user->id,
                ]);

                return 'called';
            }
        );

        if ($result === 'active') {
            return redirect()
                ->route('petugas.antrean')
                ->with(
                    'error',
                    'Pelayanan ini masih memiliki antrean aktif. Selesaikan terlebih dahulu.'
                );
        }

        if ($result === 'empty') {
            return redirect()
                ->route('petugas.antrean')
                ->with(
                    'error',
                    'Tidak ada antrean menunggu untuk pelayanan Anda.'
                );
        }

        return redirect()
            ->route('petugas.antrean')
            ->with(
                'success',
                'Antrean berhasil dipanggil.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | MULAI PELAYANAN
    |--------------------------------------------------------------------------
    */

    public function mulai()
    {
        $user = auth()->user();


        if (!$user->service_id) {

            return redirect()
                ->route('petugas.dashboard');
        }


        $periodStart = $this->schedule
            ->periodStart()
            ->utc();


        $periodEnd = $this->schedule
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
                ->route('petugas.antrean')
                ->with(
                    'error',
                    'Tidak ada antrean yang sedang dipanggil.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | MULAI MELAYANI
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
            ->route('petugas.antrean');
    }


    /*
    |--------------------------------------------------------------------------
    | LEWATI ANTREAN
    |--------------------------------------------------------------------------
    */

    public function lewati()
    {
        $user = auth()->user();


        if (!$user->service_id) {

            return redirect()
                ->route('petugas.dashboard');
        }


        $periodStart = $this->schedule
            ->periodStart()
            ->utc();


        $periodEnd = $this->schedule
            ->periodEnd()
            ->utc();


        /*
        |--------------------------------------------------------------------------
        | CARI ANTREAN YANG SEDANG DIPANGGIL
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
                ->route('petugas.antrean')
                ->with(
                    'error',
                    'Tidak ada antrean yang dapat dilewati.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | UBAH STATUS
        |--------------------------------------------------------------------------
        */

        $queue->update([

            'status' =>
                'skipped',

        ]);


        return redirect()
            ->route('petugas.antrean');
    }


    /*
    |--------------------------------------------------------------------------
    | PANGGIL ULANG ANTREAN DILEWATI
    |--------------------------------------------------------------------------
    */

    public function panggilUlang(
        int $id
    ) {
        $user = auth()->user();

        if (!$user->service_id) {
            return redirect()
                ->route('petugas.dashboard');
        }

        $serviceId = $user->service_id;

        $periodStart = $this->schedule
            ->periodStart()
            ->utc();

        $periodEnd = $this->schedule
            ->periodEnd()
            ->utc();

        $result = DB::transaction(
            function () use (
                $id,
                $user,
                $serviceId,
                $periodStart,
                $periodEnd
            ) {
                /*
                |------------------------------------------------------------------
                | KUNCI PELAYANAN
                |------------------------------------------------------------------
                */

                Service::whereKey($serviceId)
                    ->lockForUpdate()
                    ->firstOrFail();

                /*
                |------------------------------------------------------------------
                | CEK ANTREAN AKTIF PADA PELAYANAN
                |------------------------------------------------------------------
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
                    ->whereIn(
                        'status',
                        [
                            'called',
                            'serving'
                        ]
                    )
                    ->exists();

                if ($activeQueue) {
                    return 'active';
                }

                /*
                |------------------------------------------------------------------
                | CARI ANTREAN DILEWATI SESUAI PELAYANAN PETUGAS
                |------------------------------------------------------------------
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
                        $serviceId
                    )
                    ->where(
                        'status',
                        'skipped'
                    )
                    ->lockForUpdate()
                    ->first();

                if (!$queue) {
                    return 'not_found';
                }

                $queue->update([
                    'status' => 'called',
                    'called_at' => $this->schedule
                        ->now()
                        ->utc(),
                    'served_by' => $user->id,
                ]);

                return 'called';
            }
        );

        if ($result === 'active') {
            return redirect()
                ->route('petugas.antrean')
                ->with(
                    'error',
                    'Pelayanan ini masih memiliki antrean aktif. Selesaikan terlebih dahulu.'
                );
        }

        if ($result === 'not_found') {
            return redirect()
                ->route('petugas.antrean')
                ->with(
                    'error',
                    'Antrean yang ingin dipanggil ulang tidak ditemukan.'
                );
        }

        return redirect()
            ->route('petugas.antrean')
            ->with(
                'success',
                'Antrean berhasil dipanggil ulang.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SELESAIKAN PELAYANAN
    |--------------------------------------------------------------------------
    */

    public function selesai()
    {
        $user = auth()->user();


        if (!$user->service_id) {

            return redirect()
                ->route('petugas.dashboard');
        }


        $periodStart = $this->schedule
            ->periodStart()
            ->utc();


        $periodEnd = $this->schedule
            ->periodEnd()
            ->utc();


        /*
        |--------------------------------------------------------------------------
        | HANYA STATUS SERVING
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
                ->route('petugas.antrean')
                ->with(
                    'error',
                    'Tidak ada pelayanan aktif yang dapat diselesaikan.'
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
            ->route('petugas.antrean');
    }


    /*
    |--------------------------------------------------------------------------
    | RIWAYAT LAYANAN
    |--------------------------------------------------------------------------
    */

    public function riwayat()
    {
        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | RIWAYAT HANYA MILIK PETUGAS YANG LOGIN
        |--------------------------------------------------------------------------
        */

        $riwayat = Queue::with('service')

            ->where(
                'served_by',
                $user->id
            )

            ->whereIn(
                'status',
                [
                    'completed',
                    'skipped',
                ]
            )

            ->orderByDesc(
                'completed_at'
            )

            ->get();


        $totalRiwayat =
            $riwayat->count();


        $totalSelesai =
            $riwayat
                ->where(
                    'status',
                    'completed'
                )
                ->count();


        $totalDilewati =
            $riwayat
                ->where(
                    'status',
                    'skipped'
                )
                ->count();


        return view(
            'petugas.riwayat',
            compact(
                'riwayat',
                'totalRiwayat',
                'totalSelesai',
                'totalDilewati'
            )
        );
    }
}