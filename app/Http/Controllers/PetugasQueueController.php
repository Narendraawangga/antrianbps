<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Models\Service;
use App\Services\QueueScheduleService;
use Illuminate\Http\Request;
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
    | REDIRECT SESUAI ASAL HALAMAN
    |--------------------------------------------------------------------------
    |
    | Jika tombol diklik dari:
    |
    | dashboard -> kembali dashboard
    | antrean   -> kembali halaman antrean
    |
    */

    private function redirectBySource(
        Request $request,
        ?string $message = null,
        string $type = 'success'
    ) {
        $route =
            $request->input('source') === 'dashboard'
                ? 'petugas.dashboard'
                : 'petugas.antrean';


        $redirect =
            redirect()->route($route);


        if ($message !== null) {

            $redirect->with(
                $type,
                $message
            );

        }


        return $redirect;
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


        /*
        |--------------------------------------------------------------------------
        | PERIODE HARI INI
        |--------------------------------------------------------------------------
        */

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
        | ANTREAN MENUNGGU
        |--------------------------------------------------------------------------
        */

        $waitingQueues =
            Queue::with('service')

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

        $currentQueue =
            Queue::with('service')

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

        $completedCount =
            Queue::whereBetween(
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

        $skippedQueues =
            Queue::with('service')

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

        $totalServiceCount =
            Queue::whereBetween(
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
        | TAMPILKAN DASHBOARD
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
    | HALAMAN ANTREAN PETUGAS
    |--------------------------------------------------------------------------
    */

    public function antrean()
    {
        $user =
            auth()->user();


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


        $serviceId =
            $user->service_id;


        /*
        |--------------------------------------------------------------------------
        | PERIODE
        |--------------------------------------------------------------------------
        */

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
        | ANTREAN AKTIF PETUGAS
        |--------------------------------------------------------------------------
        */

        $currentQueue =
            Queue::with('service')

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
        */

        $waitingQueues =
            Queue::with('service')

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
        */

        $skippedQueues =
            Queue::with('service')

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

        $petugasService =
            $user->service;


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

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

    public function panggil(
        Request $request
    ) {
        $user =
            auth()->user();


        /*
        |--------------------------------------------------------------------------
        | CEK PELAYANAN
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | PERIODE
        |--------------------------------------------------------------------------
        */

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
        | TRANSAKSI PANGGIL ANTREAN
        |--------------------------------------------------------------------------
        */

        $result =
            DB::transaction(
                function () use (
                    $serviceId,
                    $periodStart,
                    $periodEnd,
                    $user
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | KUNCI PELAYANAN
                    |--------------------------------------------------------------------------
                    */

                    Service::whereKey(
                        $serviceId
                    )
                        ->lockForUpdate()
                        ->firstOrFail();


                    /*
                    |--------------------------------------------------------------------------
                    | CEK ANTREAN AKTIF
                    |--------------------------------------------------------------------------
                    |
                    | Satu pelayanan hanya boleh memiliki satu
                    | antrean called / serving.
                    |
                    */

                    $activeQueue =
                        Queue::whereBetween(
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
                    |--------------------------------------------------------------------------
                    | AMBIL ANTREAN PALING AWAL
                    |--------------------------------------------------------------------------
                    */

                    $queue =
                        Queue::whereBetween(
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

                        return 'empty';

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | UBAH MENJADI CALLED
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


                    return 'called';
                }
            );


        /*
        |--------------------------------------------------------------------------
        | MASIH ADA ANTREAN AKTIF
        |--------------------------------------------------------------------------
        */

        if ($result === 'active') {

            return $this->redirectBySource(
                $request,
                'Pelayanan ini masih memiliki antrean aktif. Selesaikan terlebih dahulu.',
                'error'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | TIDAK ADA ANTREAN MENUNGGU
        |--------------------------------------------------------------------------
        */

        if ($result === 'empty') {

            return $this->redirectBySource(
                $request,
                'Tidak ada antrean menunggu untuk pelayanan Anda.',
                'error'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | BERHASIL
        |--------------------------------------------------------------------------
        */

        return $this->redirectBySource(
            $request,
            'Antrean berhasil dipanggil.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | MULAI PELAYANAN
    |--------------------------------------------------------------------------
    */

    public function mulai(
        Request $request
    ) {
        $user =
            auth()->user();


        /*
        |--------------------------------------------------------------------------
        | CEK PELAYANAN
        |--------------------------------------------------------------------------
        */

        if (!$user->service_id) {

            return redirect()
                ->route('petugas.dashboard')
                ->with(
                    'error',
                    'Pelayanan petugas belum ditentukan.'
                );

        }


        /*
        |--------------------------------------------------------------------------
        | PERIODE
        |--------------------------------------------------------------------------
        */

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

        $queue =
            Queue::whereBetween(
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


        /*
        |--------------------------------------------------------------------------
        | TIDAK DITEMUKAN
        |--------------------------------------------------------------------------
        */

        if (!$queue) {

            return $this->redirectBySource(
                $request,
                'Tidak ada antrean yang sedang dipanggil.',
                'error'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | MULAI PELAYANAN
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


        /*
        |--------------------------------------------------------------------------
        | KEMBALI KE HALAMAN ASAL
        |--------------------------------------------------------------------------
        */

        return $this->redirectBySource(
            $request,
            'Pelayanan dimulai.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | LEWATI ANTREAN
    |--------------------------------------------------------------------------
    */

    public function lewati(
        Request $request
    ) {
        $user =
            auth()->user();


        /*
        |--------------------------------------------------------------------------
        | CEK PELAYANAN
        |--------------------------------------------------------------------------
        */

        if (!$user->service_id) {

            return redirect()
                ->route('petugas.dashboard')
                ->with(
                    'error',
                    'Pelayanan petugas belum ditentukan.'
                );

        }


        /*
        |--------------------------------------------------------------------------
        | PERIODE
        |--------------------------------------------------------------------------
        */

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
        | CARI ANTREAN CALLED
        |--------------------------------------------------------------------------
        */

        $queue =
            Queue::whereBetween(
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


        /*
        |--------------------------------------------------------------------------
        | ANTREAN TIDAK ADA
        |--------------------------------------------------------------------------
        */

        if (!$queue) {

            return $this->redirectBySource(
                $request,
                'Tidak ada antrean yang dapat dilewati.',
                'error'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | UBAH STATUS MENJADI SKIPPED
        |--------------------------------------------------------------------------
        */

        $queue->update([

            'status' =>
                'skipped',

        ]);


        /*
        |--------------------------------------------------------------------------
        | KEMBALI KE HALAMAN ASAL
        |--------------------------------------------------------------------------
        */

        return $this->redirectBySource(
            $request,
            'Antrean berhasil dilewati.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PANGGIL ULANG ANTREAN DILEWATI
    |--------------------------------------------------------------------------
    */

    public function panggilUlang(
        Request $request,
        int $id
    ) {
        $user =
            auth()->user();


        /*
        |--------------------------------------------------------------------------
        | CEK PELAYANAN
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | PERIODE
        |--------------------------------------------------------------------------
        */

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
        | TRANSAKSI
        |--------------------------------------------------------------------------
        */

        $result =
            DB::transaction(
                function () use (
                    $id,
                    $user,
                    $serviceId,
                    $periodStart,
                    $periodEnd
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | KUNCI PELAYANAN
                    |--------------------------------------------------------------------------
                    */

                    Service::whereKey(
                        $serviceId
                    )
                        ->lockForUpdate()
                        ->firstOrFail();


                    /*
                    |--------------------------------------------------------------------------
                    | CEK ANTREAN AKTIF
                    |--------------------------------------------------------------------------
                    */

                    $activeQueue =
                        Queue::whereBetween(
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
                    |--------------------------------------------------------------------------
                    | CARI ANTREAN DILEWATI
                    |--------------------------------------------------------------------------
                    */

                    $queue =
                        Queue::where(
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


                    /*
                    |--------------------------------------------------------------------------
                    | TIDAK DITEMUKAN
                    |--------------------------------------------------------------------------
                    */

                    if (!$queue) {

                        return 'not_found';

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | PANGGIL ULANG
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


                    return 'called';
                }
            );


        /*
        |--------------------------------------------------------------------------
        | MASIH ADA ANTREAN AKTIF
        |--------------------------------------------------------------------------
        */

        if ($result === 'active') {

            return $this->redirectBySource(
                $request,
                'Pelayanan ini masih memiliki antrean aktif. Selesaikan terlebih dahulu.',
                'error'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | ANTREAN TIDAK DITEMUKAN
        |--------------------------------------------------------------------------
        */

        if ($result === 'not_found') {

            return $this->redirectBySource(
                $request,
                'Antrean yang ingin dipanggil ulang tidak ditemukan.',
                'error'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | BERHASIL
        |--------------------------------------------------------------------------
        */

        return $this->redirectBySource(
            $request,
            'Antrean berhasil dipanggil ulang.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SELESAIKAN PELAYANAN
    |--------------------------------------------------------------------------
    */

    public function selesai(
        Request $request
    ) {
        $user =
            auth()->user();


        /*
        |--------------------------------------------------------------------------
        | CEK PELAYANAN
        |--------------------------------------------------------------------------
        */

        if (!$user->service_id) {

            return redirect()
                ->route('petugas.dashboard')
                ->with(
                    'error',
                    'Pelayanan petugas belum ditentukan.'
                );

        }


        /*
        |--------------------------------------------------------------------------
        | PERIODE
        |--------------------------------------------------------------------------
        */

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
        | CARI PELAYANAN BERSTATUS SERVING
        |--------------------------------------------------------------------------
        */

        $queue =
            Queue::whereBetween(
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


        /*
        |--------------------------------------------------------------------------
        | TIDAK ADA PELAYANAN
        |--------------------------------------------------------------------------
        */

        if (!$queue) {

            return $this->redirectBySource(
                $request,
                'Tidak ada pelayanan aktif yang dapat diselesaikan.',
                'error'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | SELESAIKAN
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


        /*
        |--------------------------------------------------------------------------
        | KEMBALI KE HALAMAN ASAL
        |--------------------------------------------------------------------------
        */

        return $this->redirectBySource(
            $request,
            'Pelayanan berhasil diselesaikan.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RIWAYAT LAYANAN
    |--------------------------------------------------------------------------
    */

    public function riwayat()
    {
        $user =
            auth()->user();


        /*
        |--------------------------------------------------------------------------
        | AMBIL FILTER DARI URL
        |--------------------------------------------------------------------------
        */

        $periode =
            request()->query(
                'periode',
                'semua'
            );


        $search =
            request()->query(
                'search',
                ''
            );


        /*
        |--------------------------------------------------------------------------
        | QUERY DASAR
        |--------------------------------------------------------------------------
        |
        | Hanya riwayat milik petugas yang sedang login.
        |
        */

        $query =
            Queue::with('service')

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
                );


        /*
        |--------------------------------------------------------------------------
        | FILTER HARI INI
        |--------------------------------------------------------------------------
        */

        if ($periode === 'hari_ini') {

            $start =
                $this->schedule
                    ->now()
                    ->copy()
                    ->startOfDay()
                    ->utc();


            $end =
                $this->schedule
                    ->now()
                    ->copy()
                    ->endOfDay()
                    ->utc();


            $query->whereBetween(
                'created_at',
                [
                    $start,
                    $end
                ]
            );

        }


        /*
        |--------------------------------------------------------------------------
        | FILTER KEMARIN
        |--------------------------------------------------------------------------
        */

        elseif ($periode === 'kemarin') {

            $start =
                $this->schedule
                    ->now()
                    ->copy()
                    ->subDay()
                    ->startOfDay()
                    ->utc();


            $end =
                $this->schedule
                    ->now()
                    ->copy()
                    ->subDay()
                    ->endOfDay()
                    ->utc();


            $query->whereBetween(
                'created_at',
                [
                    $start,
                    $end
                ]
            );

        }


        /*
        |--------------------------------------------------------------------------
        | FILTER PENCARIAN
        |--------------------------------------------------------------------------
        */

        if (!empty($search)) {

            $query->where(
                'queue_number',
                'like',
                '%' . $search . '%'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA
        |--------------------------------------------------------------------------
        */

        $riwayat =
            $query
                ->orderByDesc(
                    'created_at'
                )
                ->get();


        /*
        |--------------------------------------------------------------------------
        | STATISTIK
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'petugas.riwayat',
            compact(
                'riwayat',
                'totalRiwayat',
                'totalSelesai',
                'totalDilewati',
                'periode',
                'search'
            )
        );
    }
}