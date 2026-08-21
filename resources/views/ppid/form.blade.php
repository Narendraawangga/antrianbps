<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Form Tamu PPID</title>


    <link
        rel="preconnect"
        href="https://fonts.googleapis.com">

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Source+Serif+4:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">


    <style>
        :root {
            --ink: #12263a;
            --navy: #0e3a5f;
            --navy-deep: #082944;
            --gold: #b6862c;
            --gold-soft: #e7c675;
            --paper: #f6f3ec;
            --paper-card: #fffdf8;
            --line: #d8d2c2;
            --muted: #6b7280;
            --error: #b3392f;
            --success: #166534;
            --success-bg: #f0fdf4;
            --success-line: #bbf7d0;
        }


        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        body {
            min-height: 100vh;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 32px 20px;

            background:
                radial-gradient(circle at 15% 10%,
                    rgba(14, 58, 95, .05),
                    transparent 45%),

                radial-gradient(circle at 85% 90%,
                    rgba(182, 134, 44, .06),
                    transparent 45%),

                var(--paper);

            font-family:
                'Inter',
                Arial,
                Helvetica,
                sans-serif;

            color: var(--ink);
        }


        /* =====================================================
           CARD
        ===================================================== */

        .ppid-container {
            width: 100%;
            max-width: 480px;

            background: var(--paper-card);

            border-radius: 4px;

            border: 1px solid var(--line);

            box-shadow:
                0 18px 40px -14px rgba(8, 41, 68, .22);

            overflow: hidden;
        }


        /* =====================================================
           HEADER
        ===================================================== */

        .ppid-header {
            position: relative;

            padding: 26px 28px 22px;

            background:
                linear-gradient(160deg,
                    var(--navy) 0%,
                    var(--navy-deep) 100%);

            color: #fdf9ef;

            border-bottom: 3px solid var(--gold);
        }


        .back-button {
            position: absolute;

            left: 18px;
            top: 22px;

            width: 30px;
            height: 30px;

            display: flex;
            align-items: center;
            justify-content: center;

            border: 1px solid rgba(253, 249, 239, .35);

            border-radius: 50%;

            color: #fdf9ef;

            text-decoration: none;

            font-size: 16px;

            line-height: 1;

            transition:
                background .2s,
                border-color .2s;
        }


        .back-button:hover {
            background:
                rgba(253, 249, 239, .12);

            border-color:
                var(--gold-soft);
        }


        .ppid-eyebrow {
            display: block;

            text-align: center;

            font-size: 10px;

            letter-spacing: .16em;

            text-transform: uppercase;

            color: var(--gold-soft);

            margin-bottom: 6px;
        }


        .ppid-header h1 {
            font-family:
                'Source Serif 4',
                Georgia,
                serif;

            font-size: 21px;

            font-weight: 600;

            text-align: center;

            letter-spacing: .01em;
        }


        .ppid-header p {
            text-align: center;

            font-size: 11.5px;

            color:
                rgba(253, 249, 239, .72);

            margin-top: 4px;
        }


        /* =====================================================
           FORM
        ===================================================== */

        form {
            padding: 26px 28px 28px;
        }


        .form-group {
            position: relative;

            margin-bottom: 16px;

            padding-left: 30px;
        }


        .form-group::before {
            content: attr(data-index);

            position: absolute;

            left: 0;
            top: 1px;

            width: 20px;

            font-family:
                'Source Serif 4',
                Georgia,
                serif;

            font-size: 12px;

            font-weight: 600;

            color: var(--gold);
        }


        .form-group::after {
            content: '';

            position: absolute;

            left: 9px;
            top: 20px;
            bottom: -16px;

            width: 1px;

            background: var(--line);
        }


        .form-group:last-of-type::after {
            display: none;
        }


        .form-group label {
            display: block;

            margin-bottom: 6px;

            font-size: 12.5px;

            font-weight: 600;

            color: var(--ink);

            letter-spacing: .01em;
        }


        .form-group .hint {
            display: block;

            font-size: 10.5px;

            color: var(--muted);

            margin-top: 4px;
        }


        .form-control {
            width: 100%;

            height: 38px;

            padding: 0 12px;

            border: 1px solid var(--line);

            border-radius: 3px;

            background: #fffefb;

            color: var(--ink);

            font-family:
                'Inter',
                sans-serif;

            font-size: 13px;

            outline: none;

            transition:
                border-color .15s,
                box-shadow .15s;
        }


        .form-control:hover {
            border-color: #b9b198;
        }


        .form-control:focus {
            border-color: var(--navy);

            box-shadow:
                0 0 0 3px rgba(14, 58, 95, .12);
        }


        textarea.form-control {
            height: 62px;

            padding: 9px 12px;

            resize: vertical;

            font-family: inherit;

            line-height: 1.4;
        }


        .form-control::placeholder {
            color: #a3a396;
        }


        .form-control:invalid:not(:placeholder-shown) {
            border-color: var(--error);
        }


        /* =====================================================
           VALIDATION
        ===================================================== */

        .validation-error {
            margin-bottom: 18px;

            padding: 10px 12px;

            border: 1px solid #fecaca;

            border-radius: 4px;

            background: #fef2f2;

            color: #b91c1c;

            font-size: 12px;

            line-height: 1.5;
        }


        .validation-error strong {
            display: block;

            margin-bottom: 4px;
        }


        .validation-error ul {
            margin: 4px 0 0 18px;

            padding: 0;
        }


        .validation-error li {
            margin-bottom: 2px;
        }


        .success-message {
            margin-bottom: 18px;

            padding: 10px 12px;

            border: 1px solid var(--success-line);

            border-radius: 4px;

            background: var(--success-bg);

            color: var(--success);

            font-size: 12px;

            line-height: 1.5;
        }


        .field-error {
            display: block;

            margin-top: 4px;

            color: var(--error);

            font-size: 10.5px;

            line-height: 1.4;
        }


        .form-control.input-error {
            border-color: var(--error);
        }


        .form-control.input-error:focus {
            border-color: var(--error);

            box-shadow:
                0 0 0 3px rgba(179, 57, 47, .10);
        }


        /* =====================================================
           BUTTON
        ===================================================== */

        .submit-button {
            width: 100%;

            height: 42px;

            margin-top: 10px;

            border: none;

            border-radius: 3px;

            background:
                linear-gradient(160deg,
                    var(--navy) 0%,
                    var(--navy-deep) 100%);

            color: #fdf9ef;

            font-family:
                'Inter',
                sans-serif;

            font-size: 13.5px;

            font-weight: 600;

            letter-spacing: .02em;

            cursor: pointer;

            transition:
                transform .15s,
                box-shadow .15s,
                background .2s;

            box-shadow:
                0 8px 18px -8px rgba(8, 41, 68, .55);
        }


        .submit-button:hover {
            background:
                linear-gradient(160deg,
                    #114a79 0%,
                    #0a3355 100%);
        }


        .submit-button:active {
            transform: translateY(1px);

            box-shadow:
                0 4px 10px -6px rgba(8, 41, 68, .55);
        }


        .submit-button:focus-visible,
        .form-control:focus-visible,
        .back-button:focus-visible {
            outline: 2px solid var(--gold);

            outline-offset: 2px;
        }


        .ppid-footnote {
            text-align: center;

            font-size: 10.5px;

            color: var(--muted);

            margin-top: 16px;
        }


        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media (max-width: 500px) {

            body {
                padding: 0;

                align-items: flex-start;
            }


            .ppid-container {
                border-radius: 0;

                border-left: none;

                border-right: none;

                max-width: none;

                min-height: 100vh;
            }


            .ppid-header {
                padding: 22px 20px 18px;
            }


            form {
                padding: 22px 20px 24px;
            }
        }


        @media (prefers-reduced-motion: reduce) {

            * {
                transition: none !important;
            }
        }
    </style>

</head>


<body>


    <div class="ppid-container">


        <!-- HEADER -->

        <div class="ppid-header">

            <a
                href="{{ url('/') }}"
                class="back-button"
                title="Kembali"
                aria-label="Kembali">

                ←

            </a>


            <span class="ppid-eyebrow">

                Pejabat Pengelola Informasi &amp; Dokumentasi

            </span>


            <h1>
                Buku Tamu Digital
            </h1>


            <p>
                Silakan lengkapi data kunjungan Anda
            </p>

        </div>


        <!-- FORM -->

        <form
            id="ppid-form"
            action="{{ route('ppid.store') }}"
            method="POST"
            onsubmit="return confirm('Apakah Anda yakin ingin menyimpan data tamu PPID?');">

            @csrf


            <!-- PESAN BERHASIL -->

            @if (session('success'))

            <div class="success-message">

                ✓ {{ session('success') }}

            </div>

            @endif


            <!-- PESAN VALIDASI -->

            @if ($errors->any())

            <div class="validation-error">

                <strong>
                    Periksa kembali data yang diisi.
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


            <!-- TANGGAL -->

            <div
                class="form-group"
                data-index="01">

                <label for="tanggal">
                    Tanggal
                </label>


                <input
                    type="date"
                    id="tanggal"
                    name="tanggal"
                    class="form-control @error('tanggal') input-error @enderror"
                    value="{{ old('tanggal', date('Y-m-d')) }}"
                    required>


                @error('tanggal')

                <span class="field-error">
                    {{ $message }}
                </span>

                @enderror

            </div>


            <!-- NAMA -->

            <div
                class="form-group"
                data-index="02">

                <label for="nama">
                    Nama Lengkap
                </label>


                <input
                    type="text"
                    id="nama"
                    name="nama"
                    class="form-control @error('nama') input-error @enderror"
                    value="{{ old('nama') }}"
                    placeholder="Masukkan nama lengkap"
                    required>


                @error('nama')

                <span class="field-error">
                    {{ $message }}
                </span>

                @enderror

            </div>


            <!-- WHATSAPP -->

            <div
                class="form-group"
                data-index="03">

                <label for="whatsapp">
                    Nomor WhatsApp
                </label>


                <input
                    type="tel"
                    id="whatsapp"
                    name="whatsapp"
                    class="form-control @error('whatsapp') input-error @enderror"
                    value="{{ old('whatsapp') }}"
                    placeholder="08xxxxxxxxxx"
                    pattern="08[0-9]{8,18}"
                    maxlength="20"
                    inputmode="numeric"
                    required>


                @error('whatsapp')

                <span class="field-error">
                    {{ $message }}
                </span>

                @enderror

            </div>


            <!-- PEKERJAAN -->

            <div
                class="form-group"
                data-index="04">

                <label for="pekerjaan">
                    Pekerjaan
                </label>


                <input
                    type="text"
                    id="pekerjaan"
                    name="pekerjaan"
                    class="form-control @error('pekerjaan') input-error @enderror"
                    value="{{ old('pekerjaan') }}"
                    placeholder="Masukkan pekerjaan"
                    required>


                @error('pekerjaan')

                <span class="field-error">
                    {{ $message }}
                </span>

                @enderror

            </div>


            <!-- ALAMAT -->

            <div
                class="form-group"
                data-index="05">

                <label for="alamat">
                    Alamat
                </label>


                <textarea
                    id="alamat"
                    name="alamat"
                    class="form-control @error('alamat') input-error @enderror"
                    placeholder="Masukkan alamat lengkap"
                    required>{{ old('alamat') }}</textarea>


                @error('alamat')

                <span class="field-error">
                    {{ $message }}
                </span>

                @enderror

            </div>


            <!-- ASAL INSTANSI -->

            <div
                class="form-group"
                data-index="06">

                <label for="asal_instansi">
                    Asal Instansi
                </label>


                <textarea
                    id="asal_instansi"
                    name="asal_instansi"
                    class="form-control @error('asal_instansi') input-error @enderror"
                    placeholder="Tuliskan asal instansi"
                    required>{{ old('asal_instansi') }}</textarea>


                @error('asal_instansi')

                <span class="field-error">
                    {{ $message }}
                </span>

                @enderror

            </div>


            <!-- TUJUAN -->

            <div
                class="form-group"
                data-index="07">

                <label for="tujuan">
                    Tujuan Kunjungan
                </label>


                <textarea
                    id="tujuan"
                    name="tujuan"
                    class="form-control @error('tujuan') input-error @enderror"
                    placeholder="Tuliskan tujuan kedatangan"
                    required>{{ old('tujuan') }}</textarea>


                <span class="hint">
                    Contoh: permintaan informasi publik, konsultasi, audiensi
                </span>


                @error('tujuan')

                <span class="field-error">
                    {{ $message }}
                </span>

                @enderror

            </div>


            <!-- SIMPAN -->

            <button
                type="submit"
                class="submit-button">

                Simpan Data Kunjungan

            </button>


            <p class="ppid-footnote">

                Data Anda tercatat resmi dalam buku tamu PPID

            </p>


        </form>


    </div>


</body>

</html>