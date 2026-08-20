@vite('resources/css/petugas/antrean.css')

<div class="queue-desk-page">

    {{-- NAVBAR --}}
    @include('layouts.navbar-petugas')


    <div class="queue-desk-layout">

        {{-- SIDEBAR --}}
        @include('layouts.sidebar-petugas')


        {{-- =====================================================
            CONTENT
        ====================================================== --}}

        <main class="queue-desk-content">


            {{-- =================================================
                HEADER
            ================================================== --}}

            <section class="desk-header">

                <div>

                    <span class="desk-eyebrow">
                        QUEUE CONTROL DESK
                    </span>

                    <h1>
                        Antrean Pelayanan
                    </h1>

                    <p>
                        Panggil dan kelola antrean pelayanan Anda.
                    </p>

                </div>


                <div class="desk-online">

                    <span class="online-dot"></span>

                    Sistem Online

                </div>

            </section>



            {{-- =================================================
                IDENTITAS PETUGAS
            ================================================== --}}

            <section class="operator-bar">


                <div class="operator-item">

                    <div class="operator-icon">
                        👤
                    </div>

                    <div>

                        <span class="operator-label">
                            Petugas
                        </span>

                        <strong>
                            {{ Auth::user()->name ?? 'Petugas' }}
                        </strong>

                    </div>

                </div>



                <div class="operator-divider"></div>



                <div class="operator-item">

                    <div class="operator-icon">
                        🏢
                    </div>

                    <div>

                        <span class="operator-label">
                            Pelayanan
                        </span>

                        <strong>
                            {{
                                Auth::user()->service?->name
                                ?? 'Pelayanan belum ditentukan'
                            }}
                        </strong>

                    </div>

                </div>



                <div class="operator-divider"></div>



                <div class="operator-item">

                    <div class="operator-icon">
                        🎫
                    </div>

                    <div>

                        <span class="operator-label">
                            Menunggu
                        </span>

                        <strong>
                            {{ $waitingQueues->count() }} antrean
                        </strong>

                    </div>

                </div>

            </section>



            {{-- =================================================
                CONTROL DESK
            ================================================== --}}

            <div class="control-grid">


                {{-- =============================================
                    ANTREAN AKTIF
                ============================================== --}}

                <section class="control-current">


                    <div class="section-heading">

                        <div>

                            <span class="section-kicker">
                                LOKET ANDA
                            </span>

                            <h2>
                                Sedang Ditangani
                            </h2>

                        </div>


                        @if ($currentQueue)

                            <span
                                class="
                                    active-status
                                    status-{{ $currentQueue->status }}
                                "
                            >

                                {{ $currentQueue->status_label }}

                            </span>

                        @endif

                    </div>



                    @if ($currentQueue)


                        {{-- NOMOR AKTIF --}}

                        <div class="active-queue">


                            <span class="active-label">
                                NOMOR ANTREAN
                            </span>


                            <div class="active-number">

                                {{ $currentQueue->queue_number }}

                            </div>


                            <div class="active-service">

                                {{
                                    $currentQueue->service->name
                                    ?? 'Pelayanan'
                                }}

                            </div>

                        </div>



                        {{-- ACTION --}}

                        <div class="main-actions">


                            {{-- CALLED --}}

                            @if (
                                $currentQueue->status
                                === 'called'
                            )

                                <form
                                    method="POST"
                                    action="{{
                                        route(
                                            'petugas.antrean.mulai'
                                        )
                                    }}"
                                >

                                    @csrf

                                    <button
                                        type="submit"
                                        class="desk-btn btn-start"
                                    >

                                        <span>
                                            ▶
                                        </span>

                                        Mulai Melayani

                                    </button>

                                </form>


                                <form
                                    method="POST"
                                    action="{{
                                        route(
                                            'petugas.antrean.lewati'
                                        )
                                    }}"
                                >

                                    @csrf

                                    <button
                                        type="submit"
                                        class="desk-btn btn-skip"
                                    >

                                        <span>
                                            ⏭
                                        </span>

                                        Lewati

                                    </button>

                                </form>

                            @endif



                            {{-- SERVING --}}

                            @if (
                                $currentQueue->status
                                === 'serving'
                            )

                                <form
                                    method="POST"
                                    action="{{
                                        route(
                                            'petugas.antrean.selesai'
                                        )
                                    }}"
                                >

                                    @csrf

                                    <button
                                        type="submit"
                                        class="desk-btn btn-finish"
                                    >

                                        <span>
                                            ✓
                                        </span>

                                        Selesaikan Pelayanan

                                    </button>

                                </form>

                            @endif


                        </div>


                    @else


                        {{-- BELUM ADA ANTREAN AKTIF --}}

                        <div class="active-empty">


                            <div class="empty-symbol">
                                🎫
                            </div>


                            <h3>
                                Loket Siap Melayani
                            </h3>


                            <p>

                                Belum ada antrean yang sedang
                                Anda tangani.

                            </p>


                            <form
                                method="POST"
                                action="{{
                                    route(
                                        'petugas.antrean.panggil'
                                    )
                                }}"
                            >

                                @csrf

                                <button
                                    type="submit"
                                    class="call-next-btn"
                                    @disabled(
                                        $waitingQueues->isEmpty()
                                    )
                                >

                                    <span>
                                        🔊
                                    </span>

                                    Panggil Antrean Berikutnya

                                </button>

                            </form>


                            @if ($waitingQueues->isEmpty())

                                <small class="empty-note">

                                    Belum ada pengunjung
                                    yang menunggu.

                                </small>

                            @endif


                        </div>


                    @endif


                </section>



                {{-- =============================================
                    WAITING LIST
                ============================================== --}}

                <section class="waiting-panel">


                    <div class="section-heading">


                        <div>

                            <span class="section-kicker">
                                DAFTAR TUNGGU
                            </span>

                            <h2>
                                Antrean Berikutnya
                            </h2>

                        </div>


                        <span class="waiting-counter">

                            {{ $waitingQueues->count() }}

                        </span>

                    </div>



                    <div class="waiting-table-header">

                        <span>
                            POSISI
                        </span>

                        <span>
                            ANTREAN
                        </span>

                        <span>
                            WAKTU
                        </span>

                        <span>
                            STATUS
                        </span>

                    </div>



                    <div class="waiting-scroll">


                        @forelse (
                            $waitingQueues
                            as $index => $queue
                        )


                            <div class="waiting-row">


                                <div class="waiting-position">

                                    {{ $index + 1 }}

                                </div>


                                <div class="waiting-code">

                                    {{ $queue->queue_number }}

                                </div>


                                <div class="waiting-clock">

                                    {{
                                        $queue->created_at
                                            ->timezone(
                                                'Asia/Makassar'
                                            )
                                            ->format('H:i')
                                    }}

                                    <small>
                                        WITA
                                    </small>

                                </div>


                                <div>

                                    <span class="waiting-status">
                                        Menunggu
                                    </span>

                                </div>


                            </div>


                        @empty


                            <div class="waiting-empty">

                                <div class="waiting-empty-icon">
                                    📭
                                </div>

                                <strong>
                                    Tidak ada antrean menunggu
                                </strong>

                                <span>
                                    Daftar antrean akan muncul
                                    ketika pengunjung mengambil
                                    nomor antrean.
                                </span>

                            </div>


                        @endforelse


                    </div>



                    {{-- PANGGIL DARI LIST --}}

                    @if (
                        !$currentQueue
                        &&
                        $waitingQueues->isNotEmpty()
                    )

                        <div class="waiting-footer">

                            <form
                                method="POST"
                                action="{{
                                    route(
                                        'petugas.antrean.panggil'
                                    )
                                }}"
                            >

                                @csrf

                                <button
                                    type="submit"
                                    class="call-next-btn"
                                >

                                    <span>
                                        🔊
                                    </span>

                                    Panggil Nomor
                                    {{ $waitingQueues->first()->queue_number }}

                                </button>

                            </form>

                        </div>

                    @endif


                </section>


            </div>



            {{-- =================================================
                ANTREAN DILEWATI
            ================================================== --}}

            <section class="skipped-section">


                <div class="section-heading">


                    <div>

                        <span class="section-kicker">
                            PERLU TINDAKAN
                        </span>

                        <h2>
                            Antrean Dilewati
                        </h2>

                        <p>

                            Pengunjung yang belum hadir
                            saat nomor dipanggil.

                        </p>

                    </div>


                    <span class="skipped-counter">

                        {{ $skippedQueues->count() }}

                    </span>

                </div>



                <div class="skipped-list">


                    @forelse (
                        $skippedQueues
                        as $queue
                    )


                        <div class="skipped-row">


                            <div class="skipped-number">

                                {{ $queue->queue_number }}

                            </div>


                            <div class="skipped-detail">

                                <strong>

                                    {{
                                        $queue->service->name
                                        ?? 'Pelayanan'
                                    }}

                                </strong>

                                <span>
                                    Antrean dilewati
                                </span>

                            </div>


                            <form
                                method="POST"
                                action="{{
                                    route(
                                        'petugas.antrean.panggil-ulang',
                                        $queue->id
                                    )
                                }}"
                            >

                                @csrf

                                <button
                                    type="submit"
                                    class="recall-btn"
                                    @disabled($currentQueue)
                                >

                                    🔊 Panggil Ulang

                                </button>

                            </form>


                        </div>


                    @empty


                        <div class="skipped-empty">

                            <span class="skipped-check">
                                ✓
                            </span>

                            <div>

                                <strong>
                                    Semua antrean tertangani
                                </strong>

                                <p>
                                    Tidak ada antrean yang dilewati.
                                </p>

                            </div>

                        </div>


                    @endforelse


                </div>

            </section>


        </main>

    </div>

</div>