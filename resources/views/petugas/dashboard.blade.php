@extends('layouts.petugas')

@section('title', 'Dashboard Petugas - BPS Kolaka Utara')


@push('styles')
    @vite('resources/css/petugas/dashboard.css')
@endpush


@section('content')

<div class="petugas-dashboard">

    <!-- =========================================================
         HEADER HALAMAN
    ========================================================== -->
    <div class="page-header">

        <div>

            <h1>
                Dashboard Petugas
            </h1>

            <p>
                Selamat datang,
                <strong>
                    {{ Auth::user()->name ?? 'Petugas' }}
                </strong>.
                Kelola antrean pelayanan Anda di sini.
            </p>

        </div>


        <div class="current-date">

            <div class="date-icon">
                📅
            </div>

            <div>

                <div id="currentDate">
                    -
                </div>

                <div id="currentTime">
                    -
                </div>

            </div>

        </div>

    </div>



    <!-- =========================================================
         STATISTIK
    ========================================================== -->
    <div class="stats-grid">


        <!-- ANTREAN MENUNGGU -->
        <div class="stat-card waiting">

            <div class="stat-info">

                <div class="stat-label">
                    Antrean Menunggu
                </div>

                <div class="stat-number">
                    {{ $waitingQueues->count() }}
                </div>

                <div class="stat-description">
                    Pelanggan menunggu
                </div>

            </div>

            <div class="stat-icon">
                🎫
            </div>

        </div>



        <!-- ANTREAN AKTIF -->
        <div class="stat-card called">

            <div class="stat-info">

                <div class="stat-label">
                    Antrean Aktif
                </div>

                <div class="stat-number">
                    {{ $currentQueue ? 1 : 0 }}
                </div>

                <div class="stat-description">

                    @if ($currentQueue)

                        {{ $currentQueue->status_label }}

                    @else

                        Belum ada pelayanan

                    @endif

                </div>

            </div>

            <div class="stat-icon">
                📢
            </div>

        </div>



        <!-- SELESAI -->
        <div class="stat-card completed">

            <div class="stat-info">

                <div class="stat-label">
                    Selesai Hari Ini
                </div>

                <div class="stat-number">
                    {{ $completedCount }}
                </div>

                <div class="stat-description">
                    Pelayanan selesai
                </div>

            </div>

            <div class="stat-icon">
                ✅
            </div>

        </div>



        <!-- TOTAL -->
        <div class="stat-card total">

            <div class="stat-info">

                <div class="stat-label">
                    Total Pelayanan
                </div>

                <div class="stat-number">
                    {{ $totalServiceCount }}
                </div>

                <div class="stat-description">
                    Pelayanan hari ini
                </div>

            </div>

            <div class="stat-icon">
                📊
            </div>

        </div>

    </div>



    <!-- =========================================================
         AREA ANTREAN
    ========================================================== -->
    <div class="dashboard-grid">


        <!-- =====================================================
             ANTREAN SAAT INI
        ====================================================== -->
        <div class="queue-panel">


            <div class="panel-header">

                <div>

                    <h2>
                        Antrean Saat Ini
                    </h2>

                    <p>
                        Antrean yang sedang Anda tangani
                    </p>

                </div>


                <span class="live-status">

                    <span class="live-dot"></span>

                    ONLINE

                </span>

            </div>



            <!-- NOMOR ANTREAN AKTIF -->
            <div class="current-queue">

                <div class="queue-label">
                    NOMOR ANTREAN
                </div>


                <div class="queue-number">

                    @if ($currentQueue)

                        {{ $currentQueue->queue_number }}

                    @else

                        -

                    @endif

                </div>


                <div class="queue-service">

                    @if ($currentQueue)

                        {{ $currentQueue->service->name }}

                    @else

                        Belum ada antrean yang dipanggil

                    @endif

                </div>


                @if ($currentQueue)

                    <div class="queue-current-status">

                        Status:
                        <strong>
                            {{ $currentQueue->status_label }}
                        </strong>

                    </div>

                @endif

            </div>



            <!-- =================================================
                 TOMBOL AKSI
            ================================================== -->
            <div class="queue-actions">


                <!-- =============================================
                     PANGGIL ANTREAN
                ============================================== -->
                <form
                    action="{{ route('petugas.antrean.panggil') }}"
                    method="POST"
                >

                    @csrf


                    <button
                        type="submit"
                        class="btn-call"
                        @disabled(
                            $currentQueue ||
                            $waitingQueues->isEmpty()
                        )
                    >

                        <span
                            class="btn-icon"
                            aria-hidden="true"
                        >

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >

                                <path
                                    d="M4 10.5V13.5C4 14.3284 4.67157 15 5.5 15H7L11 18V6L7 9H5.5C4.67157 9 4 9.67157 4 10.5Z"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />

                                <path
                                    d="M14.5 9.5C15.8807 10.214 16.75 11.5351 16.75 13C16.75 14.4649 15.8807 15.786 14.5 16.5"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />

                                <path
                                    d="M16.5 7C18.6421 8.17384 20 10.4355 20 13C20 15.5645 18.6421 17.8262 16.5 19"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />

                            </svg>

                        </span>


                        <span class="btn-text">
                            Panggil Antrean
                        </span>

                    </button>

                </form>



                <!-- =============================================
                     MULAI PELAYANAN
                ============================================== -->
                @if (
                    $currentQueue &&
                    $currentQueue->status === 'called'
                )

                    {{-- MULAI PELAYANAN --}}
                    <form
                        action="{{ route('petugas.antrean.mulai') }}"
                        method="POST"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="btn-start"
                        >
                            Mulai Pelayanan
                        </button>

                    </form>


                    {{-- LEWATI ANTREAN --}}
                    <form
                        action="{{ route('petugas.antrean.lewati') }}"
                        method="POST"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="btn-skip"
                        >
                            Lewati Antrean
                        </button>

                    </form>

                @endif



                <!-- =============================================
                     SELESAIKAN
                ============================================== -->
                <form
                    action="{{ route('petugas.antrean.selesai') }}"
                    method="POST"
                >

                    @csrf


                    <button
                        type="submit"
                        class="btn-finish"
                        @disabled(
                            !$currentQueue ||
                            $currentQueue->status !== 'serving'
                        )
                    >

                        Selesaikan

                    </button>

                </form>

            </div>

        </div>



        <!-- =====================================================
             ANTREAN BERIKUTNYA
        ====================================================== -->
        <div class="next-panel">


            <div class="panel-header">

                <div>

                    <h2>
                        Antrean Berikutnya
                    </h2>

                    <p>
                        Daftar antrean yang menunggu
                    </p>

                </div>


                <a
                    href="#"
                    class="view-all"
                >
                    Lihat Semua
                </a>

            </div>



            <!-- DAFTAR ANTREAN -->
            <div class="queue-list">


                @forelse ($waitingQueues as $item)


                    <div class="queue-item">


                        <!-- NOMOR -->
                        <div class="queue-code">

                            {{ $item->queue_number }}

                        </div>



                        <!-- DETAIL -->
                        <div class="queue-detail">

                            <div class="queue-name">

                                {{ $item->service->name }}

                            </div>


                            <div class="queue-time">

                                {{ $item->created_at
                                    ->timezone(
                                        config('antrian.timezone')
                                    )
                                    ->format('H:i')
                                }}

                                WITA

                            </div>

                        </div>



                        <!-- STATUS -->
                        <span
                            class="queue-status waiting-status"
                        >

                            {{ $item->status_label }}

                        </span>

                    </div>


                @empty


                    <div class="queue-empty">

                        <div class="empty-icon">
                            🎫
                        </div>

                        <div>
                            Belum ada antrean
                        </div>

                    </div>


                @endforelse


            </div>

        </div>

    </div>

    <!-- =========================================================
        ANTREAN DILEWATI
    ========================================================== -->
    <div class="skipped-panel">

        <div class="panel-header">

            <div>

                <h2>
                    Antrean Dilewati
                </h2>

                <p>
                    Antrean yang belum hadir saat dipanggil
                </p>

            </div>

        </div>


        <div class="queue-list">

            @forelse ($skippedQueues as $item)

                <div class="queue-item">

                    <div class="queue-code">

                        {{ $item->queue_number }}

                    </div>


                    <div class="queue-detail">

                        <div class="queue-name">

                            {{ $item->service->name }}

                        </div>

                        <div class="queue-time">

                            Status: Dilewati

                        </div>

                    </div>


                    <form
                        action="{{ route(
                            'petugas.antrean.panggil-ulang',
                            $item->id
                        ) }}"
                        method="POST"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="btn-recall"
                            @disabled($currentQueue)
                        >
                            Panggil Ulang
                        </button>

                    </form>

                </div>

            @empty

                <div class="queue-empty">

                    <div class="empty-icon">
                        ✓
                    </div>

                    <div>
                        Tidak ada antrean yang dilewati
                    </div>

                </div>

            @endforelse

        </div>

    </div>

    <!-- =========================================================
         INFORMASI PELAYANAN
    ========================================================== -->
    <div class="service-panel">


        <div class="panel-header">

            <div>

                <h2>
                    Informasi Pelayanan
                </h2>

                <p>
                    Informasi layanan yang sedang Anda tangani
                </p>

            </div>

        </div>



        <div class="service-grid">


            <!-- LAYANAN -->
            <div class="service-info">

                <div class="service-icon">
                    🏢
                </div>

                <div>

                    <div class="service-label">
                        Layanan
                    </div>


                    <div class="service-value">

                        @if ($currentQueue)

                            {{ $currentQueue->service->name }}

                        @else

                            Belum ditentukan

                        @endif

                    </div>

                </div>

            </div>



            <!-- PETUGAS -->
            <div class="service-info">

                <div class="service-icon">
                    👤
                </div>

                <div>

                    <div class="service-label">
                        Petugas
                    </div>


                    <div class="service-value">

                        {{ Auth::user()->name ?? 'Petugas' }}

                    </div>

                </div>

            </div>



            <!-- JAM PELAYANAN -->
            <div class="service-info">

                <div class="service-icon">
                    🕐
                </div>

                <div>

                    <div class="service-label">
                        Jam Pelayanan
                    </div>


                    <div class="service-value">
                        07.00 - 17.00 WITA
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection



@push('scripts')
    @vite('resources/js/petugas/dashboard.js')
@endpush