<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Status Antrean - BPS Kolaka Utara</title>

    @vite('resources/css/pengunjung/status-antrian.css')
</head>

<body>

    <main class="status-page">

        <section class="status-card">

            <h1 class="status-title">
                Status Antrean
            </h1>

            <p class="queue-label">
                Nomor Antrean
            </p>

            <h2 class="queue-number">
                {{ $queue->queue_number }}
            </h2>


            <div class="queue-info">

                {{-- Antrean di depan --}}
                <div class="info-row">

                    <span class="info-label">
                        Antrean di Depan Anda
                    </span>

                    <span class="info-value">
                        @if ($queuesAhead > 0)

                            {{ $queuesAhead }} antrean

                        @else

                            Tidak ada antrean di depan Anda

                        @endif
                    </span>

                </div>


                {{-- Layanan --}}
                <div class="info-row">

                    <span class="info-label">
                        Layanan
                    </span>

                    <span class="info-value">
                        {{ $queue->service->name }}
                    </span>

                </div>


                {{-- Status --}}
                <div class="info-row">

                    <span class="info-label">
                        Status
                    </span>

                    <span class="status-badge status-{{ $queue->status }}">
                        {{ $queue->status_label }}
                    </span>

                </div>

            </div>

        </section>

    </main>

</body>

</html>