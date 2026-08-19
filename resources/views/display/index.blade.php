<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Display Antrean - BPS Kolaka Utara
    </title>

    @vite([
    'resources/css/display/index.css',
    'resources/js/display/index.js',
])
</head>

<body>

    <div class="display-page">

        <!-- HEADER -->
        <header class="display-header">

            <div class="display-title">

                <h1>
                    Sistem Antrean BPS Kolaka Utara
                </h1>

                <p>
                    Informasi antrean pelayanan
                </p>

            </div>


            <div class="display-clock">

                <div
                    class="display-time"
                    id="displayTime"
                >
                    --:--:--
                </div>

                <div
                    class="display-date"
                    id="displayDate"
                >
                    -
                </div>

            </div>

        </header>


        <!-- CONTENT -->
        <main class="display-content">

            <div class="queue-heading">

                <h2>
                    Antrean Sedang Dipanggil
                </h2>

                <p>
                    Silakan menuju layanan sesuai nomor antrean Anda
                </p>

            </div>


            @if ($currentQueues->isNotEmpty())

                <div class="queue-grid">

                    @foreach ($currentQueues as $queue)

                        <div class="queue-card">

                            <div class="queue-number">
                                {{ $queue->queue_number }}
                            </div>

                            <div class="queue-service">
                                {{ $queue->service->name }}
                            </div>

                            <div
                                class="queue-status
                                status-{{ $queue->status }}"
                            >
                                {{ $queue->status_label }}
                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="queue-empty">

                    <div class="queue-empty-icon">
                        🎫
                    </div>

                    <h2>
                        Belum Ada Antrean Dipanggil
                    </h2>

                    <p>
                        Nomor antrean akan tampil di sini
                        ketika petugas melakukan pemanggilan.
                    </p>

                </div>

            @endif

        </main>

    </div>

</body>

</html>