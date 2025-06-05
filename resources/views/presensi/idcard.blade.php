@extends('layouts.presensi')
@section('header')
    <!-- App Header -->
    <div class="appHeader  text-light" style="background:#1e3c72 !important;">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">ID Card</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->
@endsection
@section('content')
    @php
        use SimpleSoftwareIO\QrCode\Facades\QrCode;
        function getInitials($name)
        {
            $words = explode(' ', $name);
            $initials = '';
            foreach ($words as $word) {
                $initials .= strtoupper(substr($word, 0, 1));
            }
            return $initials;
        }
    @endphp
    <style>
        .id-card {
            width: 350px;
            height: 550px;
            background: linear-gradient(135deg, #ffffff 0%, #f5f5f5 100%);
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1),
                      0 0 0 1px rgba(0, 0, 0, 0.05);
            margin: 20px auto;
            position: relative;
            overflow: hidden;
        }

        .logo-container {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            padding: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .id-card-header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            height: 137.5px;
            padding: 20px;
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header-pattern {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            opacity: 0.05;
            background: 
                radial-gradient(circle at 0% 0%, rgba(255,255,255,0.2) 0%, transparent 50%),
                radial-gradient(circle at 100% 0%, rgba(255,255,255,0.2) 0%, transparent 50%),
                url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            z-index: 1;
            animation: shimmer 10s infinite linear;
        }

        @keyframes shimmer {
            0% { background-position: 0 0; }
            100% { background-position: 60px 60px; }
        }

        .id-card-header::before,
        .id-card-header::after {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            animation: floatBubble 8s infinite ease-in-out;
        }

        .id-card-header::before {
            top: -100px;
            right: -50px;
            animation-delay: 0s;
        }

        .id-card-header::after {
            bottom: -100px;
            left: -50px;
            width: 150px;
            height: 150px;
            animation-delay: -4s;
        }

        @keyframes floatBubble {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(10px) scale(1.05); }
        }

        .id-card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 100%;
            background: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'%3E%3Cpath d='M0 40L40 0H20L0 20M40 40V20L20 40'/%3E%3C/g%3E%3C/svg%3E") repeat;
            z-index: 0;
        }

        .id-card-header h2 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            position: absolute;
            top: 20px;
            right: 20px;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            z-index: 2;
        }

        .id-card-body {
            padding: 20px;
            margin-top: -50px;
        }

        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin: -50px auto 20px;
            border: 5px solid white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            position: relative;
            z-index: 1;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .profile-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-image .initials {
            font-size: 48px;
            font-weight: bold;
            color: #1e3c72;
        }

        .employee-info {
            text-align: center;
            margin-top: 20px;
        }

        .employee-info h3 {
            margin: 0;
            color: #333;
            font-size: 20px;
            font-weight: bold;
        }

        .employee-info p {
            margin: 5px 0;
            color: #666;
        }

        .employee-info .jabatan {
            font-size: 16px;
            color: #444;
            margin-bottom: 3px;
        }

        .employee-info .pt {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }

        .id-card-footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 15px;
            background: #f8f9fa;
            text-align: center;
            border-top: 1px solid #eee;
        }

        .qr-code {
            width: 100px;
            height: 100px;
            margin: 10px auto;
        }
    </style>

    <div class="id-card" style="margin-top: 60px;">
        <div class="id-card-header">
            <div class="header-pattern"></div>
            <div class="logo-container">
                <div class="logo">
                    <img src="{{ asset('assets/img/aida.png') }}" alt="Logo 1">
                </div>
                <div class="logo">
                    <img src="{{ asset('assets/img/swan.png') }}" alt="Logo 2">
                </div>
            </div>
            <h2>ID Card</h2>
        </div>

        <div class="id-card-body">
            <div class="profile-image">
                @if (!empty(Auth::guard('karyawan')->user()->foto))
                    @php
                        $path = 'https://app.portalmp.com/storage/karyawan/' . Auth::guard('karyawan')->user()->foto;
                    @endphp
                    <img src="{{ $path }}" alt="Foto Karyawan">
                @else
                    <div class="initials">{{ getInitials(Auth::guard('karyawan')->user()->nama_karyawan) }}</div>
                @endif
            </div>

            <div class="employee-info">
                <h3>{{ Auth::guard('karyawan')->user()->nama_karyawan }}</h3>
                <p class="jabatan">{{ $karyawan->nama_jabatan }}</p>
                <p class="pt">{{ $karyawan->nama_cabang }}</p>
            </div>

            <div class="qr-code">
                @php
                    $datakaryawan = [
                        'nik' => Auth::guard('karyawan')->user()->nik,
                        'nama' => Auth::guard('karyawan')->user()->nama_karyawan,
                        'jabatan' => $karyawan->nama_jabatan,
                        'cabang' => $karyawan->nama_cabang,
                    ];
                @endphp
                {!! QrCode::format('svg')->size(100)->generate(json_encode($datakaryawan)) !!}
            </div>
        </div>

        <div class="id-card-footer">
            <p style="margin: 0; font-size: 12px; color: #666;">
                ID Card ini adalah bukti keanggotaan karyawan<br>
                Harap dibawa setiap saat
            </p>
        </div>
    </div>
@endsection
