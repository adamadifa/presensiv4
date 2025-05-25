@extends('layouts.presensi')
@section('header')
    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
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
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            position: relative;
            overflow: hidden;
        }

        .id-card-header {
            background: #dc3545;
            height: 137.5px;
            /* 1/4 dari tinggi card (550px) */
            padding: 20px;
            color: white;
            text-align: center;
            position: relative;
        }

        .id-card-header h2 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: absolute;
            top: 20px;
            right: 20px;
            color: white;
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
            color: #dc3545;
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

    <div class="id-card" style="margin-top: 100px;">
        <div class="id-card-header">
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
                {!! QrCode::format('svg')->size(100)->generate(Auth::guard('karyawan')->user()->nik) !!}
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
