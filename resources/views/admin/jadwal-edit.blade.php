@extends('layouts.admin')


@push('styles')

@vite('resources/css/admin/jadwal.css')

@endpush


@section('content')


<div class="jadwal-page">


    <div class="page-header">

        <div>

            <h1>
                Edit Jadwal Petugas
            </h1>

            <p>
                Perbarui jadwal piket petugas pelayanan
                BPS Kolaka Utara.
            </p>

        </div>

    </div>



    @if ($errors->any())

    <div class="alert-error">

        <strong>
            Terjadi kesalahan:
        </strong>

        <ul>

            @foreach ($errors->all() as $error)

            <li>
                {{ $error }}
            </li>

            @endforeach

        </ul>

    </div>

    @endif



    <div class="table-card">


        <div class="table-header">

            <div>

                <h2>
                    Edit Jadwal
                </h2>

                <p>
                    Silakan ubah informasi jadwal.
                </p>

            </div>

        </div>



        <form
            method="POST"
            action="{{ route(
                'admin.jadwal.update',
                $schedule->id
            ) }}">

            @csrf

            @method('PUT')


            <div class="modal-body">


                {{-- PETUGAS --}}

                <div class="form-group">

                    <label>
                        Petugas
                    </label>

                    <select
                        name="user_id"
                        class="form-input"
                        required>

                        @foreach ($petugas as $user)

                        <option
                            value="{{ $user->id }}"
                            {{ $schedule->user_id == $user->id ? 'selected' : '' }}>

                            {{ $user->name }}

                        </option>

                        @endforeach

                    </select>

                </div>



                {{-- TANGGAL --}}

                <div class="form-group">

                    <label>
                        Tanggal
                    </label>

                    <input
                        type="date"
                        name="date"
                        class="form-input"
                        value="{{ old(
                            'date',
                            $schedule->date->format('Y-m-d')
                        ) }}"
                        required>

                </div>



                {{-- JAM --}}

                <div class="form-row">


                    <div class="form-group">

                        <label>
                            Jam Mulai
                        </label>

                        <input
                            type="time"
                            name="start_time"
                            class="form-input"
                            value="{{ old(
                                'start_time',
                                substr($schedule->start_time, 0, 5)
                            ) }}"
                            required>

                    </div>



                    <div class="form-group">

                        <label>
                            Jam Selesai
                        </label>

                        <input
                            type="time"
                            name="end_time"
                            class="form-input"
                            value="{{ old(
                                'end_time',
                                substr($schedule->end_time, 0, 5)
                            ) }}"
                            required>

                    </div>


                </div>



                {{-- KETERANGAN --}}

                <div class="form-group">

                    <label>
                        Keterangan
                    </label>

                    <textarea
                        name="notes"
                        class="form-input"
                        rows="4"
                        placeholder="Keterangan tambahan (opsional)">{{ old(
                        'notes',
                        $schedule->notes
                    ) }}</textarea>

                </div>


            </div>



            <div class="modal-footer">


                <a
                    href="{{ route('admin.jadwal') }}"
                    class="btn-cancel">

                    Batal

                </a>


                <button
                    type="submit"
                    class="btn-primary">

                    Simpan Perubahan

                </button>


            </div>


        </form>

    </div>

</div>


@endsection