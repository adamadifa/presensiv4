<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#000000">
    <title>Dashboard</title>
    <meta name="description" content="Mobilekit HTML Mobile UI Kit">
    <meta name="keywords" content="bootstrap 4, mobile template, cordova, phonegap, mobile, html" />
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('logo.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <style>
        .historicontent {
            display: flex;
            justify-content: space-between;
        }

        .historibordergreen {
            border: 1px solid rgb(2, 176, 69);
        }

        .historiborderred {
            border: 1px solid rgb(171, 18, 18);
        }

        .historidetail1 {
            display: flex;
        }

        .historidetail2 h4 {
            margin-bottom: 0;
        }



        .datepresence {
            margin-left: 10px;
        }

        .datepresence h4 {
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 0;
        }

        .timepresence {
            font-size: 14px;
        }
    </style>
    <style>
        .selectmaterialize,
        textarea {
            display: block;
            background-color: transparent !important;
            border: 0px !important;
            border-bottom: 1px solid #9e9e9e !important;
            border-radius: 0 !important;
            outline: none !important;
            height: 3rem !important;
            width: 100% !important;
            font-size: 16px !important;
            margin: 0 0 8px 0 !important;
            padding: 0 !important;
            color: #495057;

        }

        textarea {
            height: 80px !important;
            color: #495057 !important;
            padding: 20px !important;
        }
    </style>
</head>

<body style="background-color:#e9ecef;">

    <!-- loader -->
    <div id="loader">
        <div class="spinner-border text-primary" role="status"></div>
    </div>
    <!-- * loader -->

    @yield('header')

    <!-- App Capsule -->
    <div id="appCapsule">
        @yield('content')
    </div>
    <!-- * App Capsule -->


    @include('layouts.bottomNav')


    @include('layouts.script')




</body>

</html>
