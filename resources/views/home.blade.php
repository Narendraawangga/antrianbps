<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sistem Antrean Pelayanan - BPS Kolaka Utara</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(120deg, #f5f8ff, #f1fbf7);
            color: #111;
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* =========================
           HEADER
        ========================= */

        .top-wrapper {
            width: 100%;
            padding: 28px 20px 0;
        }

        .header {
            width: min(980px, 92%);
            margin: auto;
            min-height: 145px;
            background: #fff;
            border-radius: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 70px;
            padding: 22px 45px;
            box-shadow: 0 8px 25px rgba(30, 70, 130, .05);
        }

        /* Logo BPS placeholder */
        .bps-logo {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .bps-symbol {
            width: 75px;
            height: 75px;
            border-radius: 50%;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 18px;
            color: #fff;
            background: linear-gradient(135deg,
                    #00a8e8 0 33%,
                    #82bc00 33% 66%,
                    #f5a000 66%);
            box-shadow: 0 4px 10px rgba(0, 0, 0, .12);
        }

        .bps-text {
            color: #1645a5;
            font-size: 20px;
            font-weight: 800;
            line-height: 1.2;
        }

        .bps-text span {
            display: block;
        }

        /* STARLA / SISTEM */
        .system-logo {
            display: flex;
            align-items: center;
            gap: 13px;
        }

        .system-symbol {
            width: 76px;
            height: 76px;
            border-radius: 50%;
            border: 11px solid #2346b5;
            border-left-color: #f5a800;
            border-bottom-color: #00a4d6;
            transform: rotate(-25deg);
            position: relative;
        }

        .system-name {
            color: #2346b5;
            font-size: 43px;
            font-weight: 700;
            line-height: .9;
        }

        .system-name span {
            color: #f2a000;
        }

        .system-subtitle {
            font-size: 12px;
            font-weight: 600;
            color: #111;
            margin-top: 7px;
            letter-spacing: .3px;
        }

        /* =========================
           WELCOME
        ========================= */

        .welcome {
            text-align: center;
            margin: 28px auto 45px;
            color: #2449ae;
            font-size: clamp(25px, 3vw, 38px);
            font-weight: 800;
        }

        /* =========================
           HERO / AMBIL ANTRIAN
        ========================= */

        .queue-section {
            width: min(1540px, 92%);
            min-height: 560px;
            margin: auto;
            background: #ffc400;
            border-radius: 18px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 7px 15px rgba(0, 0, 0, .10);
            padding: 55px 70px 0;
        }

        .queue-title {
            text-align: center;
            color: #2449b3;
            font-size: clamp(45px, 5vw, 78px);
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 70px;
        }

        .queue-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 100px;
            max-width: 1200px;
            margin: auto;
            align-items: start;
        }

        .instruction {
            position: relative;
            text-align: center;
        }

        .bubble {
            background: #2848ad;
            color: white;
            width: 350px;
            min-height: 180px;
            margin: auto;
            border-radius: 48px;
            padding: 30px 28px;
            font-size: 28px;
            font-weight: 700;
            line-height: 1.2;
            position: relative;
            box-shadow: 0 9px 0 rgba(27, 54, 139, .12);
        }

        .bubble::after {
            content: "";
            position: absolute;
            bottom: -38px;
            left: 50%;
            transform: translateX(-50%);
            border-left: 35px solid transparent;
            border-right: 35px solid transparent;
            border-top: 40px solid #2848ad;
        }

        .yellow-text {
            color: #ffd000;
        }

        /* Ilustrasi sederhana */
        .illustration {
            height: 250px;
            position: relative;
            margin-top: 55px;
        }

        .person {
            position: absolute;
            bottom: 0;
            width: 90px;
            height: 155px;
        }

        .person.left {
            left: 12%;
        }

        .person.right {
            right: 12%;
        }

        .head {
            width: 48px;
            height: 48px;
            background: #e4a36b;
            border-radius: 50%;
            margin: auto;
            position: relative;
            z-index: 2;
        }

        .hair {
            position: absolute;
            width: 52px;
            height: 28px;
            background: #202020;
            border-radius: 50% 50% 25% 25%;
            top: -3px;
            left: -2px;
        }

        .body {
            width: 78px;
            height: 90px;
            background: #1647b8;
            border-radius: 20px 20px 5px 5px;
            margin: -2px auto 0;
        }

        .machine {
            position: absolute;
            bottom: 0;
            left: 43%;
            transform: translateX(-50%);
            width: 125px;
            height: 180px;
            background: #f3f3f3;
            border-radius: 12px;
            box-shadow: 0 8px 12px rgba(0, 0, 0, .2);
            transform-origin: bottom;
            rotate: 8deg;
        }

        .machine-screen {
            width: 85px;
            height: 100px;
            background: #ffc400;
            margin: 35px auto;
            border-radius: 5px;
            border: 4px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2449ae;
            font-size: 13px;
            font-weight: 800;
            text-align: center;
        }

        .arrow {
            position: absolute;
            left: 50%;
            bottom: 45px;
            transform: translateX(-50%);
            font-size: 70px;
            color: #2449ae;
            font-weight: 800;
        }

        .desk {
            position: absolute;
            right: 3%;
            bottom: 0;
            width: 290px;
            height: 75px;
            background: #704c2b;
            border-radius: 8px 8px 0 0;
        }

        /* Click effect */
        .queue-link {
            display: block;
            transition: transform .25s ease;
        }

        .queue-link:hover {
            transform: translateY(-4px);
        }

        /* =========================
           SURVEY
        ========================= */

        .survey-section {
            width: min(1540px, 92%);
            margin: 42px auto 0;
            min-height: 630px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 70px;
            align-items: center;
            padding: 35px 55px;
        }

        .survey-left {
            text-align: center;
        }

        .survey-small {
            font-size: 28px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .survey-title {
            color: #2449ae;
            font-size: clamp(42px, 4vw, 67px);
            line-height: 1.08;
            font-weight: 600;
        }

        .ppid {
            width: 320px;
            height: 145px;
            background: white;
            margin: 70px auto 0;
            border-radius: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 15px rgba(0, 0, 0, .15);
            font-size: 62px;
            font-weight: 800;
            color: #009ddd;
        }

        .ppid span:nth-child(2) {
            color: #81be16;
        }

        .ppid span:nth-child(3) {
            color: #f29b00;
        }

        .survey-card {
            background: #2949ad;
            border-radius: 18px;
            min-height: 600px;
            padding: 55px 45px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: 0 8px 18px rgba(0, 0, 0, .12);
        }

        .qr-box {
            width: 380px;
            max-width: 90%;
            background: white;
            color: #2449ae;
            border-radius: 35px;
            padding: 28px;
            display: flex;
            align-items: center;
            gap: 25px;
            margin-bottom: 30px;
        }

        .qr {
            width: 145px;
            height: 145px;
            background:
                linear-gradient(90deg, #111 12px, transparent 12px) 0 0/35px 35px,
                linear-gradient(#111 12px, transparent 12px) 0 0/35px 35px,
                white;
            border: 8px solid white;
            outline: 4px solid #111;
            flex-shrink: 0;
        }

        .qr-text {
            font-size: 25px;
            line-height: 1.15;
            font-weight: 600;
        }

        .emoji {
            font-size: 145px;
            line-height: 1;
            margin: 10px 0;
        }

        .survey-label {
            font-size: 32px;
            font-weight: 600;
        }

        /* =========================
           FOOTER
        ========================= */

        footer {
            margin-top: 35px;
            min-height: 110px;
            background: #2449ae;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 25px 8%;
            font-size: 14px;
        }

        .footer-title {
            font-weight: 700;
            font-size: 15px;
            margin-bottom: 5px;
        }

        .footer-right {
            text-align: right;
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 1000px) {

            .header {
                gap: 30px;
                padding: 20px;
            }

            .system-name {
                font-size: 32px;
            }

            .bps-text {
                font-size: 16px;
            }

            .queue-section {
                padding: 45px 25px 0;
            }

            .queue-content {
                gap: 30px;
            }

            .bubble {
                width: 280px;
                font-size: 22px;
            }

            .survey-section {
                gap: 30px;
                padding: 30px;
            }
        }

        @media (max-width: 700px) {

            .header {
                width: 94%;
                flex-direction: column;
                gap: 18px;
                min-height: auto;
            }

            .bps-symbol,
            .system-symbol {
                width: 58px;
                height: 58px;
            }

            .system-name {
                font-size: 30px;
            }

            .welcome {
                font-size: 25px;
                padding: 0 20px;
            }

            .queue-section {
                width: 94%;
                padding: 35px 15px 0;
            }

            .queue-title {
                font-size: 42px;
                margin-bottom: 45px;
            }

            .queue-content {
                grid-template-columns: 1fr;
            }

            .bubble {
                width: 280px;
                font-size: 21px;
            }

            .survey-section {
                width: 94%;
                grid-template-columns: 1fr;
                padding: 15px;
            }

            .survey-card {
                min-height: 500px;
            }

            .survey-title {
                font-size: 43px;
            }

            .qr-box {
                flex-direction: column;
                text-align: center;
            }

            footer {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }

            .footer-right {
                text-align: center;
            }
        }
    </style>
</head>

<body>

    <!-- ==========================================
         HEADER
    =========================================== -->

    <div class="top-wrapper">

        <header class="header">

            <!-- Klik logo → Login -->
            <a href="{{ url('/login') }}" class="bps-logo">

                <div class="bps-symbol">
                    BPS
                </div>

                <div class="bps-text">
                    <span>BADAN PUSAT STATISTIK</span>
                    <span>KABUPATEN KOLAKA UTARA</span>
                </div>

            </a>

            <!-- Klik sistem → Login -->
            <a href="{{ url('/login') }}" class="system-logo">

                <div class="system-symbol"></div>

                <div>
                    <div class="system-name">
                        star<span>la</span>
                    </div>

                    <div class="system-subtitle">
                        SISTEM ANTRIAN LAYANAN
                    </div>
                </div>

            </a>

        </header>

    </div>


    <!-- ==========================================
         WELCOME
    =========================================== -->

    <div class="welcome">
        Selamat Datang di PST BPS Kabupaten Kolaka Utara 👋
    </div>


    <!-- ==========================================
         AMBIL ANTRIAN
    =========================================== -->

    <a href="{{ url('/layanan') }}" class="queue-link">

        <section class="queue-section">

            <h1 class="queue-title">
                Ambil Antrean Dulu, yuk!
            </h1>

            <div class="queue-content">

                <!-- INSTRUKSI 1 -->
                <div class="instruction">

                    <div class="bubble">
                        Tekan layar
                        <span class="yellow-text">kuning</span>
                        untuk ambil antrean
                    </div>

                    <div class="illustration">

                        <div class="person left">
                            <div class="head">
                                <div class="hair"></div>
                            </div>
                            <div class="body"></div>
                        </div>

                        <div class="machine">
                            <div class="machine-screen">
                                Ambil<br>
                                Antrean<br>
                                Di sini
                            </div>
                        </div>

                        <div class="arrow">
                            ➜
                        </div>

                    </div>

                </div>


                <!-- INSTRUKSI 2 -->
                <div class="instruction">

                    <div class="bubble">

                        <span class="yellow-text">
                            Petugas PST
                        </span>

                        akan mengarahkan Anda ke

                        <span class="yellow-text">
                            meja pelayanan
                        </span>

                    </div>

                    <div class="illustration">

                        <div class="person right">
                            <div class="head">
                                <div class="hair"></div>
                            </div>
                            <div class="body"></div>
                        </div>

                        <div class="desk"></div>

                    </div>

                </div>

            </div>

        </section>

    </a>


    <!-- ==========================================
         SURVEY
    =========================================== -->

    <section class="survey-section">

        <!-- LEFT -->

        <div class="survey-left">

            <div class="survey-small">
                Sudah selesai menerima layanan?
            </div>

            <div class="survey-title">
                Bantu Kami Jadi<br>
                Lebih Baik! 😊 👉
            </div>

            <!-- Link PPID -->
            <a
                href="{{ route('ppid.form') }}"
                class="ppid">

                <span>P</span>
                <span>P</span>
                <span>ID</span>

            </a>
        </div>


        <!-- RIGHT -->

        <!-- Link Survey -->
        <a href="#" class="survey-card">

            <div class="qr-box">

                <div class="qr"></div>

                <div class="qr-text">
                    Klik atau<br>
                    <strong>scan QR</strong><br>
                    untuk beri<br>
                    penilaian!
                </div>

            </div>

            <div class="emoji">
                😊
            </div>

            <div class="survey-label">
                Survey Kepuasan
            </div>

        </a>

    </section>


    <!-- ==========================================
         FOOTER
    =========================================== -->

    <footer>

        <div>
            <div class="footer-title">
                📍 Badan Pusat Statistik Kabupaten Kolaka Utara
            </div>

            <div>
                [Alamat BPS Kolaka Utara]
            </div>
        </div>

        <div class="footer-right">

            <div>
                © 2026 BPS Kabupaten Kolaka Utara
            </div>

            <strong>
                Sistem Antrian Pelayanan
            </strong>

        </div>

    </footer>


    <!-- ==========================================
         JAVASCRIPT
    =========================================== -->

    <script>
        // Efek muncul ketika halaman dibuka
        document.addEventListener("DOMContentLoaded", function() {

            const sections = document.querySelectorAll(
                '.header, .welcome, .queue-section, .survey-section, footer'
            );

            sections.forEach((section, index) => {

                section.style.opacity = "0";
                section.style.transform = "translateY(20px)";
                section.style.transition =
                    "opacity .7s ease, transform .7s ease";

                setTimeout(() => {
                    section.style.opacity = "1";
                    section.style.transform = "translateY(0)";
                }, index * 120);

            });

        });
    </script>

</body>

</html>