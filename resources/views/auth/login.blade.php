<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#1e3c72">
    <title>E-Presensi Geolocation</title>
    <meta name="description" content="Mobilekit HTML Mobile UI Kit">
    <meta name="keywords" content="bootstrap 4, mobile template, cordova, phonegap, mobile, html" />
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('logo.png') }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #1e3c72;
            margin: 0;
            padding: 0;
            overflow: hidden; /* Prevent scrolling on the main body usually */
        }

        .login-container {
            width: 100%;
            height: 100vh;
            display: flex;
            flex-direction: column;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            position: relative;
        }

        .header-section {
            height: 35vh; /* Upper 35% */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            padding-bottom: 20px;
        }

        .header-section h1 {
            font-size: 32px;
            font-weight: 700;
            margin: 0;
            letter-spacing: 0.5px;
        }
        
        .header-section p {
            font-size: 14px;
            opacity: 0.8;
            margin-top: 5px;
        }

        .bottom-sheet {
            height: 65vh; /* Lower 65% */
            background-color: #ffffff;
            border-top-left-radius: 30px;
            border-top-right-radius: 30px;
            padding: 40px 30px;
            overflow-y: auto;
            position: relative;
            box-shadow: 0 -10px 20px rgba(0,0,0,0.1);
        }

        .form-header {
            margin-bottom: 30px;
            text-align: center; /* Centered as per reference might look better, or left */
        }
        
        .text-left {
            text-align: left;
        }

        .form-header h2 {
            font-size: 24px;
            font-weight: 700;
            color: #333;
            margin-bottom: 8px;
        }

        .form-header p {
            font-size: 14px;
            color: #888;
            margin: 0;
        }

        .form-group-custom {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 500;
            color: #666;
        }

        .form-control-custom {
            width: 100%;
            height: 55px;
            padding: 0 20px;
            border: 1px solid #eee;
            background-color: #f9f9f9;
            border-radius: 15px;
            font-size: 15px;
            color: #333;
            transition: all 0.3s;
            outline: none;
        }

        .form-control-custom:focus {
            background-color: #fff;
            border-color: #1e3c72;
            box-shadow: 0 0 0 3px rgba(30, 60, 114, 0.1);
        }

        .password-group {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
            cursor: pointer;
            font-size: 20px;
        }

        .btn-primary-custom {
            width: 100%;
            height: 55px;
            background: linear-gradient(90deg, #1e3c72 0%, #2a5298 100%);
            border: none;
            border-radius: 15px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
            box-shadow: 0 10px 20px rgba(30, 60, 114, 0.3);
            transition: transform 0.2s;
        }

        .btn-primary-custom:active {
            transform: scale(0.98);
        }

        .forgot-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #888;
            font-size: 14px;
            text-decoration: none;
            font-weight: 500;
        }
        
        .alert-custom {
            background: #fff3cd;
            color: #856404;
            padding: 15px;
            border-radius: 12px;
            font-size: 13px;
            margin-bottom: 20px;
            border: 1px solid #ffeeba;
        }
    </style>
</head>

<body>

    <!-- loader -->
    <div id="loader">
        <div class="spinner-border text-light" role="status"></div>
    </div>
    <!-- * loader -->

    <div class="login-container">
        <!-- Top Section -->
        <div class="header-section">
            <ion-icon name="finger-print-outline" style="font-size: 80px; color: white; margin-bottom: 15px;"></ion-icon>
            <h1 style="color: white;">E-Presensi</h1>
            <p>Geolocation Presence System</p>
        </div>

        <!-- Bottom Sheet -->
        <div class="bottom-sheet">
            <div class="form-header text-left">
                <h2>Welcome Back</h2>
                <p>Silahkan masukkan data anda untuk masuk</p>
            </div>

            @php
                $messagewarning = Session::get('warning');
            @endphp
            @if (Session::get('warning'))
                <div class="alert-custom">
                    {{ $messagewarning }}
                </div>
            @endif

            <form action="/proseslogin" method="POST">
                @csrf
                <div class="form-group-custom">
                    <label class="form-label">NIK (Nomor Induk Karyawan)</label>
                    <input type="text" name="nik" class="form-control-custom" placeholder="Contoh: 123456" autocomplete="off" required>
                </div>

                <div class="form-group-custom">
                    <label class="form-label">Password</label>
                    <div class="password-group">
                        <input type="password" name="password" id="password" class="form-control-custom" placeholder="••••••••" autocomplete="off" required>
                        <ion-icon name="eye-outline" class="toggle-password" id="togglePassword"></ion-icon>
                    </div>
                </div>

                <button type="submit" class="btn-primary-custom">Sign in</button>

                <a href="page-forgot-password.html" class="forgot-link">Forgot your password?</a>
            </form>
        </div>
    </div>

    <script>
        var BASE_URL = "{{ url('/') }}/";
        document.addEventListener('DOMContentLoaded', init, false);

        function init() {
            if ('serviceWorker' in navigator && navigator.onLine) {
                navigator.serviceWorker.register(BASE_URL + 'service-worker.js')
                    .then((reg) => {
                        console.log('Registrasi service worker Berhasil', reg);
                    }, (err) => {
                        console.error('Registrasi service worker Gagal', err);
                    });
            }
            
            // Password Toggle
            const togglePassword = document.querySelector('#togglePassword');
            const password = document.querySelector('#password');
            if(togglePassword) {
                togglePassword.addEventListener('click', function (e) {
                    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                    password.setAttribute('type', type);
                    this.setAttribute('name', type === 'password' ? 'eye-outline' : 'eye-off-outline');
                });
            }
        }
    </script>

    <!-- ///////////// Js Files ////////////////////  -->
    <!-- Jquery -->
    <script src="{{ asset('assets/js/lib/jquery-3.4.1.min.js') }}"></script>
    <!-- Bootstrap-->
    <script src="{{ asset('assets/js/lib/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/bootstrap.min.js') }}"></script>
     <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <!-- Base Js File -->
    <script src="{{ asset('assets/js/base.js') }}"></script>

</body>

</html>
