@vite('resources/css/petugas/antrean.css')

<div class="petugas-antrean-page">

    {{-- NAVBAR --}}
    @include('layouts.navbar-petugas')

    <div class="petugas-layout">

        {{-- SIDEBAR --}}
        @include('layouts.sidebar-petugas')


        {{-- CONTENT --}}
        <main class="petugas-antrean-content">

            {{-- HEADER --}}
            <div class="antrean-page-header">

                <div>
                    <h1>Antrean Petugas</h1>

                    <p>
                        Kelola antrean pelayanan Anda sebagai petugas BPS Kolaka Utara.
                    </p>
                </div>

                <div class="antrean-online">
                    <span></span>
                    ONLINE
                </div>

            </div>


            {{-- GRID UTAMA --}}
            <div class="antrean-main-grid">


                {{-- ANTREAN SAAT INI --}}
                <section class="antrean-card current-card">

                    <div class="antrean-card-header">

                        <div>
                            <h2>Antrean Saat Ini</h2>

                            <p>
                                Antrean yang sedang Anda tangani
                            </p>
                        </div>

                        <span class="live-badge">
                            ● Aktif
                        </span>

                    </div>


                    @if($currentQueue)

                    <div class="current-queue-body">

                        <div class="queue-label">
                            NOMOR ANTREAN
                        </div>

                        <div class="big-queue-number">
                            {{ $currentQueue->queue_number }}
                        </div>

                        <div class="current-service">
                            {{ $currentQueue->service->name ?? 'Layanan' }}
                        </div>


                        <div class="current-status">

                            @if($currentQueue->status === 'called')

                            <span class="status-called">
                                Dipanggil
                            </span>

                            @elseif($currentQueue->status === 'serving')

                            <span class="status-serving">
                                Sedang Dilayani
                            </span>

                            @endif

                        </div>

                    </div>


                    {{-- AKSI --}}
                    <div class="antrean-actions">

                        @if($currentQueue->status === 'called')

                        <form
                            method="POST"
                            action="{{ route('petugas.antrean.mulai') }}">

                            @csrf

                            <button
                                type="submit"
                                class="btn-action btn-start">

                                ▶
                                Mulai Melayani

                            </button>

                        </form>


                        <form
                            method="POST"
                            action="{{ route('petugas.antrean.lewati') }}">

                            @csrf

                            <button
                                type="submit"
                                class="btn-action btn-skip">

                                ⏭
                                Lewati

                            </button>

                        </form>

                        @elseif($currentQueue->status === 'serving')

                        <form
                            method="POST"
                            action="{{ route('petugas.antrean.selesai') }}">

                            @csrf

                            <button
                                type="submit"
                                class="btn-action btn-finish">

                                ✓
                                Selesaikan Pelayanan

                            </button>

                        </form>

                        @endif

                    </div>

                    @else

                    <div class="empty-current">

                        <div class="empty-icon">
                            🎫
                        </div>

                        <h3>
                            Belum Ada Antrean Aktif
                        </h3>

                        <p>
                            Panggil antrean berikutnya untuk memulai pelayanan.
                        </p>

                        <form
                            method="POST"
                            action="{{ route('petugas.antrean.panggil') }}">

                            @csrf

                            <button
                                type="submit"
                                class="btn-call-main">

                                🔊
                                Panggil Antrean Berikutnya

                            </button>

                        </form>

                    </div>

                    @endif

                </section>



                {{-- ANTREAN BERIKUTNYA --}}
                <section class="antrean-card next-card">

                    <div class="antrean-card-header">

                        <div>
                            <h2>Antrean Berikutnya</h2>

                            <p>
                                Daftar antrean yang sedang menunggu
                            </p>
                        </div>

                        <span class="queue-count">
                            {{ $waitingQueues->count() }} Antrean
                        </span>

                    </div>


                    @if($waitingQueues->count())

                    <div class="waiting-list">

                        @foreach($waitingQueues as $queue)

                        <div class="waiting-item">

                            <div class="waiting-number">

                                {{ $queue->queue_number }}

                            </div>


                            <div class="waiting-info">

                                <div class="waiting-service">

                                    {{ $queue->service->name ?? 'Layanan' }}

                                </div>

                                <div class="waiting-time">

                                    {{ $queue->created_at->timezone('Asia/Makassar')->format('H:i') }}
                                    WITA

                                </div>

                            </div>


                            <span class="waiting-badge">
                                Menunggu
                            </span>

                        </div>

                        @endforeach

                    </div>


                    @if(!$currentQueue)

                    <div class="next-action">

                        <form
                            method="POST"
                            action="{{ route('petugas.antrean.panggil') }}">

                            @csrf

                            <button
                                type="submit"
                                class="btn-call-main">

                                🔊
                                Panggil Antrean Berikutnya

                            </button>

                        </form>

                    </div>

                    @endif

                    @else

                    <div class="empty-list">

                        <div class="empty-icon">
                            📭
                        </div>

                        <p>
                            Tidak ada antrean yang menunggu.
                        </p>

                    </div>

                    @endif

                </section>

            </div>



            {{-- ANTREAN DILEWATI --}}
            <section class="antrean-card skipped-card">

                <div class="antrean-card-header">

                    <div>
                        <h2>Antrean Dilewati</h2>

                        <p>
                            Antrean yang belum hadir saat dipanggil
                        </p>
                    </div>

                    <span class="skipped-count">
                        {{ $skippedQueues->count() }} Dilewati
                    </span>

                </div>


                @if($skippedQueues->count())

                <div class="skipped-list">

                    @foreach($skippedQueues as $queue)

                    <div class="skipped-item">

                        <div class="skipped-number">

                            {{ $queue->queue_number }}

                        </div>


                        <div class="skipped-info">

                            <strong>
                                {{ $queue->service->name ?? 'Layanan' }}
                            </strong>

                            <small>
                                Antrean dilewati
                            </small>

                        </div>


                        <form
                            method="POST"
                            action="{{ route('petugas.antrean.panggil-ulang', $queue->id) }}">

                            @csrf

                            <button
                                type="submit"
                                class="btn-recall">

                                🔊
                                Panggil Ulang

                            </button>

                        </form>

                    </div>

                    @endforeach

                </div>

                @else

                <div class="empty-skipped">

                    <span>✓</span>

                    Tidak ada antrean yang dilewati.

                </div>

                @endif

            </section>

        </main>

    </div>

</div>