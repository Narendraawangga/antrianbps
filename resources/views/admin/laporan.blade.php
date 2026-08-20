@extends('layouts.admin')

@push('styles')
@vite('resources/css/admin/laporan.css')
@endpush

@section('content')

<div class="laporan-page">

    <!-- HEADER -->
    <div class="page-header">

        <div>
            <h1>Laporan Antrean</h1>

            <p>
                Rekap dan analisis pelayanan antrean
                BPS Kolaka Utara.
            </p>
        </div>

    </div>


    <!-- FILTER -->
    <div class="filter-card">

        <form
            action="{{ route('admin.laporan') }}"
            method="GET"
            class="filter-form">

            <div class="filter-group">

                <label>
                    Tanggal Mulai
                </label>

                <input
                    type="date"
                    name="start_date"
                    value="{{ $startDate }}"
                    class="filter-input">

            </div>



            <div class="filter-group">

                <label>
                    Tanggal Selesai
                </label>

                <input
                    type="date"
                    name="end_date"
                    value="{{ $endDate }}"
                    class="filter-input">

            </div>


            <div class="filter-group">

                <label>
                    Layanan
                </label>

                <select
                    name="service_id"
                    class="filter-input">

                    <option value="">
                        Semua Layanan
                    </option>

                    @foreach ($services as $service)

                    <option
                        value="{{ $service->id }}"
                        {{ $serviceId == $service->id ? 'selected' : '' }}>

                        {{ $service->code }}
                        -
                        {{ $service->name }}

                    </option>

                    @endforeach

                </select>

            </div>


            <div class="filter-actions">

                <button
                    type="submit"
                    class="btn-filter">

                    🔎 Filter

                </button>


                <a
                    href="{{ route('admin.laporan') }}"
                    class="btn-reset">

                    Reset

                </a>

                <a
                    href="{{ route('admin.laporan.cetak', [
        'start_date' => $startDate,
        'end_date' => $endDate,
        'service_id' => $serviceId
    ]) }}"
                    target="_blank"
                    class="btn-print">

                    🖨 Cetak Laporan

                </a>

            </div>

        </form>

    </div>




    <!-- STATISTIK -->
    <div class="stats-grid">

        <div class="stat-card">

            <div>
                <span class="stat-title">
                    Total Antrean
                </span>

                <strong>
                    {{ $total }}
                </strong>

                <small>
                    Semua antrean
                </small>
            </div>

            <div class="stat-icon blue">
                🎫
            </div>

        </div>


        <div class="stat-card">

            <div>
                <span class="stat-title">
                    Menunggu
                </span>

                <strong>
                    {{ $waiting }}
                </strong>

                <small>
                    Antrean menunggu
                </small>
            </div>

            <div class="stat-icon orange">
                ⏳
            </div>

        </div>


        <div class="stat-card">

            <div>
                <span class="stat-title">
                    Sedang Dilayani
                </span>

                <strong>
                    {{ $serving }}
                </strong>

                <small>
                    Sedang diproses
                </small>
            </div>

            <div class="stat-icon purple">
                👤
            </div>

        </div>


        <div class="stat-card">

            <div>
                <span class="stat-title">
                    Selesai
                </span>

                <strong>
                    {{ $completed }}
                </strong>

                <small>
                    Berhasil dilayani
                </small>
            </div>

            <div class="stat-icon green">
                ✓
            </div>

        </div>


        <div class="stat-card">

            <div>
                <span class="stat-title">
                    Dilewati
                </span>

                <strong>
                    {{ $skipped }}
                </strong>

                <small>
                    Antrean dilewati
                </small>
            </div>

            <div class="stat-icon red">
                ↷
            </div>

        </div>

    </div>


    <!-- REKAP LAYANAN -->
    <div class="table-card">

        <div class="table-header">

            <div>

                <h2>
                    Rekapitulasi Layanan
                </h2>

                <p>
                    Ringkasan antrean berdasarkan layanan.
                </p>

            </div>

        </div>


        <div class="table-wrapper">

            <table>

                <thead>

                    <tr>

                        <th>KODE</th>
                        <th>LAYANAN</th>
                        <th>TOTAL</th>
                        <th>MENUNGGU</th>
                        <th>DILAYANI</th>
                        <th>SELESAI</th>
                        <th>DILEWATI</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse ($rekapLayanan as $rekap)

                    <tr>

                        <td>
                            <span class="code-badge">
                                {{ $rekap['service']->code }}
                            </span>
                        </td>

                        <td>
                            <strong>
                                {{ $rekap['service']->name }}
                            </strong>
                        </td>

                        <td>
                            {{ $rekap['total'] }}
                        </td>

                        <td>
                            {{ $rekap['waiting'] }}
                        </td>

                        <td>
                            {{ $rekap['serving'] }}
                        </td>

                        <td>
                            {{ $rekap['completed'] }}
                        </td>

                        <td>
                            {{ $rekap['skipped'] }}
                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td
                            colspan="7"
                            class="empty-cell">

                            Belum ada data antrean.

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    <!-- DETAIL -->
    <div class="table-card">

        <div class="table-header">

            <div>

                <h2>
                    Detail Antrean
                </h2>

                <p>
                    Daftar antrean berdasarkan periode yang dipilih.
                </p>

            </div>

            <span class="total-badge">
                {{ $antrians->count() }} antrean
            </span>

        </div>


        <div class="table-wrapper">

            <table>

                <thead>

                    <tr>

                        <th>NO. ANTREAN</th>
                        <th>LAYANAN</th>
                        <th>WAKTU</th>
                        <th>STATUS</th>
                        <th>PETUGAS</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse ($antrians as $antrian)

                    <tr>

                        <td>

                            <span class="queue-number">
                                {{ $antrian->queue_number }}
                            </span>

                        </td>


                        <td>

                            {{ $antrian->service?->name ?? '-' }}

                        </td>


                        <td>

                            {{ $antrian->created_at?->format('H:i') ?? '-' }}

                            <br>

                            <small>
                                {{ $antrian->created_at?->format('d/m/Y') ?? '-' }}
                            </small>

                        </td>


                        <td>

                            <span
                                class="status-badge status-{{ $antrian->status }}">

                                {{ $antrian->status_label }}

                            </span>

                        </td>


                        <td>

                            {{ $antrian->servedBy?->name ?? '-' }}

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td
                            colspan="5"
                            class="empty-cell">

                            Belum ada data antrean pada periode ini.

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection