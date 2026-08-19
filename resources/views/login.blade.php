<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Login - Sistem Antrian BPS Kolaka Utara
    </title>


    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        html,
        body {
            width: 100%;
            min-height: 100%;
        }


        body {

            font-family:
                Arial,
                Helvetica,
                sans-serif;

            background:
                radial-gradient(circle at 50% 20%,
                    #27285d 0%,
                    #11134e 35%,
                    #02043b 75%,
                    #000239 100%);

            display: flex;

            justify-content: center;

            align-items: flex-start;

            min-height: 100vh;

            padding:
                103px 15px 40px;

        }


        /* =====================================
           LOGIN CARD
        ===================================== */

        .login-card {

            width: 357px;

            min-height: 356px;

            background: #ffffff;

            border-radius: 5px;

            padding:
                28px 17px 24px;

            box-shadow:
                0 5px 18px rgba(0, 0, 0, .28);

            animation:
                fadeIn .45s ease;

        }


        /* =====================================
           ICON
        ===================================== */

        .lock-container {

            width: 43px;

            height: 43px;

            margin:
                0 auto 32px;

            border-radius: 50%;

            background: #c7c7c7;

            display: flex;

            align-items: center;

            justify-content: center;

        }


        .lock {

            width: 15px;

            height: 15px;

            background: white;

            border-radius: 2px;

            position: relative;

            margin-top: 5px;

        }


        .lock::before {

            content: "";

            position: absolute;

            width: 11px;

            height: 12px;

            border:
                3px solid white;

            border-bottom: 0;

            border-radius:
                8px 8px 0 0;

            left: 50%;

            top: -10px;

            transform:
                translateX(-50%);

        }


        .lock::after {

            content: "";

            position: absolute;

            width: 3px;

            height: 6px;

            background: #c7c7c7;

            border-radius: 2px;

            left: 50%;

            top: 5px;

            transform:
                translateX(-50%);

        }


        /* =====================================
           TITLE
        ===================================== */

        .login-title {

            text-align: center;

            margin-bottom: 25px;

        }


        .login-title h1 {

            color: #333;

            font-size: 20px;

            font-weight: 600;

        }


        .login-title p {

            margin-top: 5px;

            color: #999;

            font-size: 11px;

        }


        /* =====================================
           ERROR
        ===================================== */

        .login-error {

            margin-bottom: 18px;

            padding: 10px 12px;

            background: #fff1f2;

            border:
                1px solid #fecdd3;

            border-radius: 5px;

            color: #be123c;

            font-size: 11px;

            line-height: 1.5;

        }


        /* =====================================
           FORM
        ===================================== */

        .form-group {

            margin-bottom: 18px;

        }


        .form-label {

            display: block;

            margin-bottom: 6px;

            color: #555;

            font-size: 11px;

            font-weight: 600;

        }


        .input-box {

            width: 100%;

            height: 48px;

            background: #f5f5f5;

            border-bottom:
                1px solid #a9a9a9;

            display: flex;

            align-items: center;

            padding: 0 13px;

            transition:
                border .2s ease,
                background .2s ease;

        }


        .input-box:focus-within {

            background: #f8fbff;

            border-bottom:
                2px solid #2184d1;

        }


        .input-box input {

            width: 100%;

            border: none;

            outline: none;

            background: transparent;

            font-size: 15px;

            color: #555;

            font-family:
                Arial,
                Helvetica,
                sans-serif;

        }


        .input-box input::placeholder {

            color: #888;

            opacity: 1;

        }


        /* =====================================
           BUTTON
        ===================================== */

        .btn-login {

            width: 100%;

            height: 42px;

            margin-top: 8px;

            border: none;

            border-radius: 4px;

            background: #2184d1;

            color: white;

            font-size: 15px;

            font-weight: 600;

            letter-spacing: .3px;

            cursor: pointer;

            box-shadow:
                0 2px 4px rgba(0, 0, 0, .2);

            transition:
                background .2s ease,
                transform .1s ease;

        }


        .btn-login:hover {

            background: #1976c2;

        }


        .btn-login:active {

            transform:
                translateY(1px);

        }


        .btn-login:disabled {

            opacity: .7;

            cursor: wait;

        }


        /* =====================================
           INFO
        ===================================== */

        .login-info {

            margin-top: 20px;

            text-align: center;

            color: #aaa;

            font-size: 10px;

            line-height: 1.5;

        }


        /* =====================================
           RESPONSIVE
        ===================================== */

        @media (max-width: 500px) {

            body {

                padding-top: 60px;

            }


            .login-card {

                width: 100%;

                max-width: 357px;

            }

        }


        /* =====================================
           ANIMATION
        ===================================== */

        @keyframes fadeIn {

            from {

                opacity: 0;

                transform:
                    translateY(-10px);

            }

            to {

                opacity: 1;

                transform:
                    translateY(0);

            }

        }
    </style>

</head>


<body>


    <div class="login-card">


        <!-- ICON -->

        <div class="lock-container">

            <div class="lock"></div>

        </div>


        <!-- TITLE -->

        <div class="login-title">

            <h1>
                Sistem Antrian BPS
            </h1>

            <p>
                BPS Kabupaten Kolaka Utara
            </p>

        </div>


        <!-- ERROR -->

        @if ($errors->any())

        <div class="login-error">

            {{ $errors->first() }}

        </div>

        @endif


        <!-- LOGIN FORM -->

        <form
            method="POST"
            action="{{ route('login.process') }}">

            @csrf


            <!-- USERNAME -->

            <div class="form-group">

                <label
                    class="form-label"
                    for="username">
                    Username
                </label>


                <div class="input-box">

                    <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="Masukkan username"
                        value="{{ old('username') }}"
                        autocomplete="username"
                        required
                        autofocus>

                </div>

            </div>


            <!-- PASSWORD -->

            <div class="form-group">

                <label
                    class="form-label"
                    for="password">
                    Password
                </label>


                <div class="input-box">

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        autocomplete="current-password"
                        required>

                </div>

            </div>


            <!-- BUTTON -->

            <button
                type="submit"
                class="btn-login"
                id="loginButton">

                Sign In

            </button>


        </form>


        <div class="login-info">

            Gunakan akun yang telah terdaftar
            dalam sistem.

        </div>


    </div>


    <script>
        const loginForm =
            document.querySelector('form');

        const loginButton =
            document.getElementById('loginButton');


        loginForm.addEventListener(
            'submit',
            function() {

                loginButton.disabled = true;

                loginButton.textContent =
                    'Memproses...';

            }
        );
    </script>


</body>

</html>