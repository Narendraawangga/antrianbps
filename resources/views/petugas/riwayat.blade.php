<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Riwayat Layanan - Petugas</title>

    @vite([
        'resources/css/layouts/sidebar-petugas.css',
        'resources/css/petugas/riwayat.css'
    ])
</head>

<body>
    @php
    $periode = $periode ?? request('periode', 'semua');
    $search = $search ?? request('search', '');
@endphp

    {{-- NAVBAR PETUGAS --}}
    @include('layouts.navbar-petugas')

    {{-- SIDEBAR PETUGAS --}}
    @include('layouts.sidebar-petugas')


    <main class="petugas-content">

        {{-- =====================================================
            HEADER
        ====================================================== --}}
        <section class="page-header">

            <div>
                <span class="page-kicker">
                    SERVICE HISTORY
                </span>

                <h1>
                    Riwayat Layanan
                </h1>

                <p>
                    Lihat kembali antrean yang pernah Anda tangani.
                </p>
            </div>

            <div class="service-badge">
                <span>🏢</span>

                <div>
                    <small>Pelayanan Anda</small>
                    <strong>
                        {{ Auth::user()->service?->name ?? 'Belum ditentukan' }}
                    </strong>
                </div>
            </div>

        </section>


        {{-- =====================================================
            STATISTIK
        ====================================================== --}}
        <section class="stats-grid">

            <article class="stat-card">
                <div class="stat-icon">
                    📊
                </div>

                <div class="stat-info">
                    <span>Total Riwayat</span>
                    <strong>{{ $totalRiwayat }}</strong>
                </div>
            </article>


            <article class="stat-card">
                <div class="stat-icon success">
                    ✓
                </div>

                <div class="stat-info">
                    <span>Selesai</span>
                    <strong>{{ $totalSelesai }}</strong>
                </div>
            </article>


            <article class="stat-card">
                <div class="stat-icon warning">
                    ↷
                </div>

                <div class="stat-info">
                    <span>Dilewati</span>
                    <strong>{{ $totalDilewati }}</strong>
                </div>
            </article>

        </section>


        {{-- =====================================================
            FILTER
        ====================================================== --}}
        <section class="filter-card">

            <div class="filter-left">

                <a
                    href="{{ route('petugas.riwayat', ['periode' => 'semua', 'search' => $search]) }}"
                    class="filter-button {{ $periode === 'semua' ? 'active' : '' }}"
                >
                    Semua
                </a>

                <a
                    href="{{ route('petugas.riwayat', ['periode' => 'hari_ini', 'search' => $search]) }}"
                    class="filter-button {{ $periode === 'hari_ini' ? 'active' : '' }}"
                >
                    Hari Ini
                </a>

                <a
                    href="{{ route('petugas.riwayat', ['periode' => 'kemarin', 'search' => $search]) }}"
                    class="filter-button {{ $periode === 'kemarin' ? 'active' : '' }}"
                >
                    Kemarin
                </a>

            </div>


            <form
                method="GET"
                action="{{ route('petugas.riwayat') }}"
                class="search-form"
            >

                <input
                    type="hidden"
                    name="periode"
                    value="{{ $periode }}"
                >

                <div class="search-box">

                    <span class="search-icon">
                        🔍
                    </span>

                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari nomor antrean..."
                        autocomplete="off"
                    >

                </div>

                <button
                    type="submit"
                    class="search-button"
                >
                    Cari
                </button>

                @if($search)
                    <a
                        href="{{ route('petugas.riwayat', ['periode' => $periode]) }}"
                        class="reset-button"
                    >
                        Reset
                    </a>
                @endif

            </form>

        </section>


        {{-- =====================================================
            TABEL RIWAYAT
        ====================================================== --}}
        <section class="history-card">

            <div class="history-header">

                <div>
                    <span class="history-kicker">
                        DATA PELAYANAN
                    </span>

                    <h2>
                        Daftar Riwayat
                    </h2>

                    <p>
                        Riwayat hanya menampilkan antrean yang pernah Anda tangani.
                    </p>
                </div>

                <div class="history-count">
                    {{ $riwayat->count() }} data
                </div>

            </div>


            @if($riwayat->count() > 0)

                <div class="table-wrapper">

                    <table class="history-table">

                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nomor Antrean</th>
                                <th>Pelayanan</th>
                                <th>Dipanggil</th>
                                <th>Selesai</th>
                                <th>Status</th>
                            </tr>
                        </thead>


                        <tbody>

                            @foreach($riwayat as $item)

                                @php
                                    $timezone = config('antrian.timezone', 'Asia/Makassar');

                                    $createdAt = $item->created_at
                                        ? $item->created_at->copy()->timezone($timezone)
                                        : null;

                                    $calledAt = $item->called_at
                                        ? \Carbon\Carbon::parse($item->called_at)->timezone($timezone)
                                        : null;

                                    $completedAt = $item->completed_at
                                        ? \Carbon\Carbon::parse($item->completed_at)->timezone($timezone)
                                        : null;
                                @endphp

                                <tr>

                                    <td class="table-number">
                                        {{ $loop->iteration }}
                                    </td>


                                    <td>
                                        <div class="date-cell">
                                            <strong>
                                                {{ $createdAt?->format('d/m/Y') ?? '-' }}
                                            </strong>

                                            <span>
                                                {{ $createdAt?->translatedFormat('l') ?? '-' }}
                                            </span>
                                        </div>
                                    </td>


                                    <td>
                                        <span class="queue-number">
                                            {{ $item->queue_number }}
                                        </span>
                                    </td>


                                    <td>
                                        <div class="service-name">
                                            {{ $item->service?->name ?? '-' }}
                                        </div>
                                    </td>


                                    <td>
                                        <div class="time-cell">
                                            <strong>
                                                {{ $calledAt?->format('H:i') ?? '-' }}
                                            </strong>

                                            @if($calledAt)
                                                <span>WITA</span>
                                            @endif
                                        </div>
                                    </td>


                                    <td>
                                        <div class="time-cell">
                                            <strong>
                                                {{ $completedAt?->format('H:i') ?? '-' }}
                                            </strong>

                                            @if($completedAt)
                                                <span>WITA</span>
                                            @endif
                                        </div>
                                    </td>


                                    <td>

                                        @if($item->status === 'completed')

                                            <span class="status completed">
                                                <span class="status-dot"></span>
                                                Selesai
                                            </span>

                                        @elseif($item->status === 'skipped')

                                            <span class="status skipped">
                                                <span class="status-dot"></span>
                                                Dilewati
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="empty">

                    <div class="empty-icon">
                        📊
                    </div>

                    <h3>
                        Riwayat Tidak Ditemukan
                    </h3>

                    <p>
                        @if($search)
                            Tidak ada riwayat dengan nomor antrean
                            "<strong>{{ $search }}</strong>".
                        @elseif($periode === 'hari_ini')
                            Belum ada riwayat pelayanan hari ini.
                        @elseif($periode === 'kemarin')
                            Tidak ada riwayat pelayanan kemarin.
                        @else
                            Belum ada antrean yang selesai atau dilewati oleh Anda.
                        @endif
                    </p>

                </div>

            @endif

        </section>

    </main>

</body>

</html>