@extends('layouts.admin')

@push('styles')
@vite('resources/css/admin/layanan.css')
@endpush

@section('content')

<div class="layanan-page">

    <!-- HEADER -->
    <div class="page-header">

        <div>
            <h1>Layanan</h1>

            <p>
                Kelola layanan yang tersedia pada sistem
                antrian BPS Kolaka Utara.
            </p>
        </div>

        <button
            type="button"
            class="btn-primary"
            onclick="openLayananModal()">

            <span class="btn-icon">＋</span>
            Tambah Layanan

        </button>

    </div>


    <!-- STATISTIK -->
    <div class="stats-grid">

        <div class="stat-card">

            <div>
                <div class="stat-title">
                    Total Layanan
                </div>

                <div class="stat-number">
                    {{ $services->count() }}
                </div>

                <div class="stat-desc">
                    Semua layanan
                </div>
            </div>

            <div class="stat-icon">
                🏢
            </div>

        </div>


        <div class="stat-card">

            <div>
                <div class="stat-title">
                    Layanan Aktif
                </div>

                <div class="stat-number">
                    {{ $services->where('is_active', 1)->count() }}
                </div>

                <div class="stat-desc">
                    Dapat dipilih masyarakat
                </div>
            </div>

            <div class="stat-icon active">
                ✓
            </div>

        </div>


        <div class="stat-card">

            <div>
                <div class="stat-title">
                    Layanan Nonaktif
                </div>

                <div class="stat-number">
                    {{ $services->where('is_active', 0)->count() }}
                </div>

                <div class="stat-desc">
                    Tidak tersedia
                </div>
            </div>

            <div class="stat-icon inactive">
                —
            </div>

        </div>

    </div>


    <!-- DAFTAR LAYANAN -->
    <div class="table-card">

        <div class="table-header">

            <div>
                <h2>
                    Daftar Layanan
                </h2>

                <p>
                    Layanan yang tersedia dalam sistem antrian
                </p>
            </div>

            <div class="service-count">
                {{ $services->count() }} layanan
            </div>

        </div>


        <div class="table-wrapper">

            <table>

                <thead>

                    <tr>
                        <th width="8%">NO</th>
                        <th>LAYANAN</th>
                        <th>DESKRIPSI</th>
                        <th width="15%">STATUS</th>
                        <th width="15%">AKSI</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($services as $service)

                    <tr>

                        <td>
                            {{ $loop->iteration }}
                        </td>


                        <td>

                            <div class="service-name">

                                <div>
                                    {{ $service->name }}
                                </div>

                            </div>

                        </td>


                        <td>

                            <span class="service-description">

                                {{ $service->description ?? '-' }}

                            </span>

                        </td>


                        <td>

                            @if($service->is_active)

                            <span class="status active">
                                <span></span>
                                Aktif
                            </span>

                            @else

                            <span class="status inactive">
                                <span></span>
                                Nonaktif
                            </span>

                            @endif

                        </td>


                        <td>

                            <div class="action-buttons">

                                <a
                                    href="{{ route('admin.layanan.edit', $service->id) }}"
                                    class="action-btn edit"
                                    title="Edit Layanan">

                                    <svg viewBox="0 0 24 24">
                                        <path d="M12 20h9" />
                                        <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z" />
                                    </svg>

                                </a>


                                <form
                                    action="{{ route('admin.layanan.destroy', $service->id) }}"
                                    method="POST"
                                    style="display: inline;"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus layanan {{ $service->name }}?')">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="action-btn delete"
                                        title="Hapus Layanan">

                                        <svg viewBox="0 0 24 24">
                                            <path d="M3 6h18" />
                                            <path d="M8 6V4h8v2" />
                                            <path d="M19 6l-1 14H6L5 6" />
                                            <path d="M10 11v5" />
                                            <path d="M14 11v5" />
                                        </svg>

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td
                            colspan="5"
                            class="empty-cell">

                            <div class="empty-state">

                                <div class="empty-icon">
                                    🏢
                                </div>

                                <h3>
                                    Belum Ada Layanan
                                </h3>

                                <p>
                                    Tambahkan layanan untuk mulai
                                    menggunakan sistem antrian.
                                </p>

                                <button
                                    type="button"
                                    class="btn-primary"
                                    onclick="openLayananModal()">

                                    ＋ Tambah Layanan

                                </button>

                            </div>

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


<!-- =====================================
     MODAL TAMBAH LAYANAN
===================================== -->

<div
    class="modal"
    id="layananModal">

    <div class="modal-box">

        <div class="modal-header">

            <div>

                <h2>
                    Tambah Layanan
                </h2>

                <p>
                    Tambahkan layanan baru ke sistem.
                </p>

            </div>


            <button
                type="button"
                class="close-btn"
                onclick="closeLayananModal()">

                ×

            </button>

        </div>


        <form
            method="POST"
            action="{{ route('admin.layanan.store') }}">

            @csrf

            <div class="modal-body">

                <div class="form-group">

                    <label>
                        Nama Layanan
                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-input"
                        placeholder="Contoh: Pelayanan Konsultasi"
                        value="{{ old('name') }}"
                        required>

                </div>

                <div class="form-group">

                    <label for="code">
                        Kode Layanan
                    </label>

                    <input
                        type="text"
                        id="code"
                        name="code"
                        class="form-input @error('code') is-invalid @enderror"
                        value="{{ old('code') }}"
                        placeholder="Contoh: E"
                        maxlength="10"
                        required>

                    <small class="form-help">
                        Kode harus unik dan maksimal 2 karakter.
                    </small>

                    @error('code')
                    <div class="error-message">
                        {{ $message }}
                    </div>
                    @enderror

                </div>


                <div class="form-group">

                    <label>
                        Deskripsi
                    </label>

                    <textarea
                        name="description"
                        class="form-input"
                        rows="3"
                        placeholder="Deskripsi layanan (opsional)">{{ old('description') }}</textarea>

                </div>


                <div class="form-group">

                    <label>
                        Status
                    </label>

                    <select
                        name="is_active"
                        class="form-input"
                        required>

                        <option value="1">
                            Aktif
                        </option>

                        <option value="0">
                            Nonaktif
                        </option>

                    </select>

                </div>

            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="btn-cancel"
                    onclick="closeLayananModal()">

                    Batal

                </button>


                <button
                    type="submit"
                    class="btn-primary">

                    Simpan Layanan

                </button>

            </div>

        </form>

    </div>

</div>


<script>
    function openLayananModal() {
        document
            .getElementById('layananModal')
            .classList
            .add('show');
    }


    function closeLayananModal() {
        document
            .getElementById('layananModal')
            .classList
            .remove('show');
    }


    document
        .getElementById('layananModal')
        .addEventListener('click', function(event) {
            if (event.target === this) {
                closeLayananModal();
            }
        });
</script>

@endsection