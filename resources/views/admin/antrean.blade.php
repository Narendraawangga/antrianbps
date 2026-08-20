@extends('layouts.admin')

@push('styles')
@vite('resources/css/admin/antrean.css')
@endpush

@section('content')

<div class="antrean-page">

    {{-- HEADER --}}
    <div class="page-header">

        <div>
            <h1>Manajemen Antrean</h1>

            <p>
                Kelola dan pantau antrean pelayanan BPS Kolaka Utara.
            </p>
        </div>

        <div class="page-date">
            📅 {{ now()->format('d/m/Y') }}
        </div>

    </div>


    {{-- STATISTIK --}}
    <div class="stats-grid">

        <div class="stat-card">

            <div>
                <div class="stat-title">
                    Total Antrean
                </div>

                <div class="stat-number">
                    {{ $antrians->count() }}
                </div>

                <div class="stat-desc">
                    Semua antrean
                </div>
            </div>

            <div class="stat-icon blue">
                🎫
            </div>

        </div>


        <div class="stat-card">

            <div>
                <div class="stat-title">
                    Menunggu
                </div>

                <div class="stat-number">
                    {{ $antrians->where('status', 'waiting')->count() }}
                </div>

                <div class="stat-desc">
                    Antrean menunggu
                </div>
            </div>

            <div class="stat-icon orange">
                ⏳
            </div>

        </div>


        <div class="stat-card">

            <div>
                <div class="stat-title">
                    Sedang Dilayani
                </div>

                <div class="stat-number">
                    {{ $antrians->whereIn('status', ['called', 'serving'])->count() }}
                </div>

                <div class="stat-desc">
                    Sedang diproses
                </div>
            </div>

            <div class="stat-icon purple">
                👤
            </div>

        </div>

    </div>


    {{-- FILTER --}}
    <div class="filter-card">

        <div class="filter-group">

            <label>
                Cari Antrean
            </label>

            <input
                type="text"
                id="searchQueue"
                class="filter-input"
                placeholder="Cari nomor antrean...">

        </div>


        <div class="filter-group">

            <label>
                Status
            </label>

            <select
                id="statusFilter"
                class="filter-input">

                <option value="all">
                    Semua Status
                </option>

                <option value="waiting">
                    Menunggu
                </option>

                <option value="called">
                    Dipanggil
                </option>

                <option value="serving">
                    Sedang Dilayani
                </option>

            </select>

        </div>


        <button
            type="button"
            class="btn-filter"
            onclick="filterQueue()">

            🔎 Filter

        </button>

    </div>


    {{-- TABEL --}}
    <div class="table-card">

        <div class="table-header">

            <div>
                <h2>
                    Daftar Antrean
                </h2>

                <p>
                    Data antrean pelayanan hari ini
                </p>
            </div>

            <div class="queue-count">

                {{ $antrians->count() }} antrean

            </div>

        </div>


        <div class="table-wrapper">

            <table>

                <thead>

                    <tr>

                        <th>NO. ANTREAN</th>

                        <th>LAYANAN</th>

                        <th>WAKTU</th>

                        <th>STATUS</th>

                        <th>AKSI</th>

                    </tr>

                </thead>


                <tbody id="queueTable">

                    @forelse($antrians as $antrian)

                    <tr
                        data-number="{{ strtolower($antrian->queue_number) }}"
                        data-status="{{ $antrian->status }}">

                        {{-- NOMOR --}}
                        <td>

                            <div class="queue-number">

                                {{ $antrian->queue_number }}

                            </div>

                        </td>


                        {{-- LAYANAN --}}
                        <td>

                            <div class="service-name">

                                {{ $antrian->service?->name ?? '-' }}

                            </div>

                        </td>


                        {{-- WAKTU --}}
                        <td>

                            <div class="queue-time">

                                {{ $antrian->created_at?->format('H:i') ?? '-' }}

                            </div>

                            <div class="queue-date">

                                {{ $antrian->created_at?->format('d/m/Y') ?? '-' }}

                            </div>

                        </td>


                        {{-- STATUS --}}
                        <td>

                            @if($antrian->status === 'waiting')

                            <span class="status-badge waiting">
                                <span class="status-dot"></span>
                                Menunggu
                            </span>

                            @elseif($antrian->status === 'called')

                            <span class="status-badge called">
                                <span class="status-dot"></span>
                                Dipanggil
                            </span>

                            @elseif($antrian->status === 'serving')

                            <span class="status-badge serving">
                                <span class="status-dot"></span>
                                Sedang Dilayani
                            </span>

                            @else

                            <span class="status-badge">
                                {{ ucfirst($antrian->status) }}
                            </span>

                            @endif

                        </td>


                        {{-- AKSI --}}
                        <td>

                            <div class="action-buttons">

                                <form
                                    action="{{ route('admin.antrean.destroy', $antrian->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus antrean {{ $antrian->queue_number }}?')">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="action-btn delete"
                                        title="Hapus antrean">
                                        <svg
                                            viewBox="0 0 24 24"
                                            aria-hidden="true">
                                            <path d="M3 6h18"></path>
                                            <path d="M8 6V4h8v2"></path>
                                            <path d="M19 6l-1 14H6L5 6"></path>
                                            <path d="M10 11v5"></path>
                                            <path d="M14 11v5"></path>
                                        </svg>
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="5">

                            <div class="empty-state">

                                <div class="empty-icon">
                                    🎫
                                </div>

                                <h3>
                                    Belum Ada Antrean
                                </h3>

                                <p>
                                    Belum ada data antrean pelayanan.
                                </p>

                            </div>

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


{{-- MODAL DETAIL --}}
<div
    class="queue-modal"
    id="queueModal">

    <div class="queue-modal-box">

        <div class="queue-modal-header">

            <h2>
                Detail Antrean
            </h2>

            <button
                type="button"
                onclick="closeQueueDetail()">

                ×

            </button>

        </div>


        <div class="queue-modal-body">

            <div class="detail-number">
                <span id="detailNumber">-</span>
            </div>


            <div class="detail-item">

                <span>
                    Layanan
                </span>

                <strong id="detailService">
                    -
                </strong>

            </div>


            <div class="detail-item">

                <span>
                    Waktu
                </span>

                <strong id="detailTime">
                    -
                </strong>

            </div>


            <div class="detail-item">

                <span>
                    Status
                </span>

                <strong id="detailStatus">
                    -
                </strong>

            </div>

        </div>


        <div class="queue-modal-footer">

            <button
                type="button"
                class="btn-cancel"
                onclick="closeQueueDetail()">

                Tutup

            </button>

        </div>

    </div>

</div>


<script>
    function showQueueDetail(number, service, time, status) {
        document.getElementById('detailNumber').textContent = number;

        document.getElementById('detailService').textContent = service;

        document.getElementById('detailTime').textContent = time;

        let statusText = status;

        if (status === 'waiting') {
            statusText = 'Menunggu';
        }

        if (status === 'called') {
            statusText = 'Dipanggil';
        }

        if (status === 'serving') {
            statusText = 'Sedang Dilayani';
        }

        document.getElementById('detailStatus').textContent = statusText;

        document
            .getElementById('queueModal')
            .classList
            .add('show');
    }


    function closeQueueDetail() {
        document
            .getElementById('queueModal')
            .classList
            .remove('show');
    }


    function filterQueue() {
        const search =
            document
            .getElementById('searchQueue')
            .value
            .toLowerCase();

        const status =
            document
            .getElementById('statusFilter')
            .value;

        const rows =
            document.querySelectorAll(
                '#queueTable tr[data-number]'
            );

        rows.forEach(function(row) {
            const number =
                row.dataset.number;

            const rowStatus =
                row.dataset.status;

            const matchSearch =
                number.includes(search);

            const matchStatus =
                status === 'all' ||
                rowStatus === status;

            row.style.display =
                matchSearch && matchStatus ?
                '' :
                'none';
        });
    }


    document
        .getElementById('searchQueue')
        .addEventListener(
            'input',
            filterQueue
        );


    document
        .getElementById('statusFilter')
        .addEventListener(
            'change',
            filterQueue
        );


    document
        .getElementById('queueModal')
        .addEventListener(
            'click',
            function(event) {
                if (event.target === this) {
                    closeQueueDetail();
                }
            }
        );
</script>

@endsection