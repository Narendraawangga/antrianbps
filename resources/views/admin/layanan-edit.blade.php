@extends('layouts.admin')

@push('styles')
@vite('resources/css/admin/layanan.css')
@endpush

@section('content')

<div class="layanan-page">

    <!-- HEADER -->
    <div class="page-header">

        <div>
            <h1>Edit Layanan</h1>

            <p>
                Perbarui informasi layanan pelayanan BPS Kolaka Utara.
            </p>
        </div>

        <a
            href="{{ route('admin.layanan') }}"
            class="btn-secondary">

            ← Kembali

        </a>

    </div>


    <!-- FORM -->
    <div class="form-card">

        <div class="form-header">

            <h2>
                Informasi Layanan
            </h2>

            <p>
                Ubah data layanan kemudian simpan perubahan.
            </p>

        </div>


        <form
            action="{{ route('admin.layanan.update', $service->id) }}"
            method="POST">

            @csrf

            @method('PUT')


            <!-- NAMA -->
            <div class="form-group">

                <label for="name">
                    Nama Layanan
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    class="form-input @error('name') is-invalid @enderror"
                    value="{{ old('name', $service->name) }}"
                    placeholder="Contoh: Pelayanan Konsultasi"
                    required>

                @error('name')
                <div class="error-message">
                    {{ $message }}
                </div>
                @enderror

            </div>


            <!-- KODE -->
            <div class="form-group">

                <label for="code">
                    Kode Layanan
                </label>

                <input
                    type="text"
                    id="code"
                    name="code"
                    class="form-input @error('code') is-invalid @enderror"
                    value="{{ old('code', $service->code) }}"
                    placeholder="Contoh: A"
                    maxlength="10"
                    required>

                <small>
                    Maksimal 10 karakter.
                </small>

                @error('code')
                <div class="error-message">
                    {{ $message }}
                </div>
                @enderror

            </div>


            <!-- DESKRIPSI -->
            <div class="form-group">

                <label for="description">
                    Deskripsi
                </label>

                <textarea
                    id="description"
                    name="description"
                    class="form-input"
                    rows="4"
                    placeholder="Masukkan deskripsi layanan">{{ old('description', $service->description) }}</textarea>

                @error('description')
                <div class="error-message">
                    {{ $message }}
                </div>
                @enderror

            </div>


            <!-- STATUS -->
            <div class="form-group">

                <label for="is_active">
                    Status Layanan
                </label>

                <select
                    id="is_active"
                    name="is_active"
                    class="form-input"
                    required>

                    <option
                        value="1"
                        {{ old('is_active', $service->is_active) == 1 ? 'selected' : '' }}>

                        Aktif

                    </option>

                    <option
                        value="0"
                        {{ old('is_active', $service->is_active) == 0 ? 'selected' : '' }}>

                        Tidak Aktif

                    </option>

                </select>

            </div>


            <!-- FOOTER -->
            <div class="form-footer">

                <a
                    href="{{ route('admin.layanan') }}"
                    class="btn-cancel">

                    Batal

                </a>


                <button
                    type="submit"
                    class="btn-primary">

                    ✓ Simpan Perubahan

                </button>

            </div>

        </form>

    </div>

</div>

@endsection