@vite('resources/css/admin/ppid.css')
@extends('layouts.admin')

@section('title', 'Tamu PPID')

@section('content')

<div class="admin-page ppid-page">

    <!-- HEADER -->
    <div class="page-header">

        <div>

            <h1>
                Tamu PPID
            </h1>

            <p>
                Daftar tamu yang melakukan kunjungan PPID.
            </p>

        </div>

    </div>


    <!-- CARD -->
    <div class="content-card">

        <div class="card-header">

            <div>

                <h2>
                    Daftar Tamu
                </h2>

                <span>
                    Data kunjungan tamu PPID
                </span>

            </div>


            <div class="total-data">

                Total:

                <strong>
                    {{ $guests->total() }}
                </strong>

            </div>

        </div>


        <!-- TABLE -->

        <div class="table-wrapper">

            <table class="data-table">

                <thead>

                    <tr>

                        <th>No</th>

                        <th>Tanggal</th>

                        <th>Nama</th>

                        <th>WhatsApp</th>

                        <th>Pekerjaan</th>

                        <th>Asal Instansi</th>

                        <th>Tujuan</th>

                        <th>Status</th>
                        <th>Waktu Panggil</th>
                        <th>Waktu Selesai</th>
                        <th>Aksi</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse ($guests as $index => $guest)

                    <tr>

                        {{-- NO --}}
                        <td>
                            {{ $guests->firstItem() + $index }}
                        </td>


                        {{-- TANGGAL --}}
                        <td>
                            {{ $guest->tanggal?->format('d/m/Y') ?? '-' }}
                        </td>


                        {{-- NAMA --}}
                        <td>
                            <strong>
                                {{ $guest->nama }}
                            </strong>
                        </td>


                        {{-- WHATSAPP --}}
                        <td>
                            {{ $guest->whatsapp }}
                        </td>


                        {{-- PEKERJAAN --}}
                        <td>
                            {{ $guest->pekerjaan }}
                        </td>


                        {{-- ASAL INSTANSI --}}
                        <td>
                            {{ $guest->asal_instansi }}
                        </td>


                        {{-- TUJUAN --}}
                        <td>
                            {{ $guest->tujuan }}
                        </td>


                        {{-- STATUS --}}
                        <td>

                            @if ($guest->status === 'menunggu')

                            <span class="ppid-status waiting">
                                ● Menunggu
                            </span>

                            @elseif ($guest->status === 'dipanggil')

                            <span class="ppid-status called">
                                ● Dipanggil
                            </span>

                            @elseif ($guest->status === 'selesai')

                            <span class="ppid-status completed">
                                ● Selesai
                            </span>

                            @endif

                        </td>


                        {{-- WAKTU PANGGIL --}}
                        <td>

                            @if ($guest->called_at)

                            <span class="ppid-time">
                                {{ $guest->called_at->format('H:i') }}
                            </span>

                            @else

                            <span class="ppid-time empty">
                                -
                            </span>

                            @endif

                        </td>


                        {{-- WAKTU SELESAI --}}
                        <td>

                            @if ($guest->completed_at)

                            <span class="ppid-time">
                                {{ $guest->completed_at->format('H:i') }}
                            </span>

                            @else

                            <span class="ppid-time empty">
                                -
                            </span>

                            @endif

                        </td>

                        {{-- AKSI --}}
                        <td>

                            <div class="action-buttons">

                                {{-- PANGGIL --}}
                                @if ($guest->status === 'menunggu')

                                <form
                                    action="{{ route('admin.ppid.panggil', $guest->id) }}"
                                    method="POST"
                                    style="display: inline;">

                                    @csrf

                                    <button
                                        type="submit"
                                        class="action-btn call"
                                        title="Panggil Tamu">

                                        <svg viewBox="0 0 24 24">
                                            <path d="M11 5 6 9H3v6h3l5 4V5Z" />
                                            <path d="M15.5 8.5a5 5 0 0 1 0 7" />
                                            <path d="M18.5 5.5a9 9 0 0 1 0 13" />
                                        </svg>

                                    </button>

                                </form>

                                @endif


                                {{-- SELESAI --}}
                                @if ($guest->status === 'dipanggil')

                                <form
                                    action="{{ route('admin.ppid.selesai', $guest->id) }}"
                                    method="POST"
                                    style="display: inline;">

                                    @csrf

                                    <button
                                        type="submit"
                                        class="action-btn complete"
                                        title="Selesaikan Pelayanan">

                                        <svg viewBox="0 0 24 24">
                                            <path d="m5 12 4 4L19 6" />
                                        </svg>

                                    </button>

                                </form>

                                @endif


                                {{-- HAPUS --}}
                                <form
                                    action="{{ route('admin.ppid.destroy', $guest->id) }}"
                                    method="POST"
                                    style="display: inline;"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus data tamu {{ $guest->nama }}?')">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="action-btn delete"
                                        title="Hapus Tamu">

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
                            colspan="10"
                            class="empty-data">

                            Belum ada data tamu PPID.

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        <!-- PAGINATION -->

        @if ($guests->hasPages())

        <div class="pagination-wrapper">

            {{ $guests->links() }}

        </div>

        @endif

    </div>

</div>

@endsection