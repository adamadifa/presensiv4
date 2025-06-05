@extends('layouts.presensi')
@section('content')
    <style>
        @keyframes gradientMove {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .logout {
            position: absolute;
            color: white;
            font-size: 28px;
            text-decoration: none;
            right: 18px;
            top: 22px;
        }

        .logout:hover {
            color: white;
        }

        .image-listview>li .item {
            min-height: 80px !important;
            border-radius: 20px !important;
        }

        #user-section {
            background: linear-gradient(120deg, #1e3c72, #2a5298, #2980b9, #6dd5fa);
            background-size: 300% 300%;
            animation: gradientMove 8s ease-in-out infinite;
            padding: 24px 20px 20px 20px;
            border-radius: 0 0 20px 20px;
            margin-bottom: 20px;
        }

        #user-detail {
            display: flex;
            align-items: center;
            color: white;
            padding-right: 40px;
            padding-left: 8px;
        }

        .avatar {
            margin-right: 18px;
            margin-left: 2px;
        }

        .avatar img {
            border: 3px solid rgba(255, 255, 255, 0.3);
        }

        #user-info {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        #user-info h3 {
            margin: 0 0 2px 0;
            font-size: 18px;
            font-weight: 700;
            line-height: 1.2;
        }

        #user-info span {
            font-size: 13px;
            opacity: 0.92;
            line-height: 1.1;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .list-menu {
            display: flex;
            justify-content: space-between;
            padding: 4px;
            flex-wrap: nowrap;
        }

        .item-menu {
            text-align: center;
            padding: 5px;
            border-radius: 12px;
            transition: all 0.3s ease;
            flex: 1;
            margin: 0 3px;
        }

        .item-menu:hover {
            transform: translateY(-3px);
        }

        .menu-icon {
            margin-bottom: 5px;
        }

        .menu-icon a {
            display: inline-block;
            width: 36px;
            height: 36px;
            line-height: 36px;
            border-radius: 10px;
            color: white;
            font-size: 20px !important;
        }

        .menu-icon a.green {
            background: linear-gradient(135deg, #00b09b, #96c93d);
        }

        .menu-icon a.danger {
            background: linear-gradient(135deg, #ff416c, #ff4b2b);
        }

        .menu-icon a.warning {
            background: linear-gradient(135deg, #f7971e, #ffd200);
        }

        .menu-icon a.orange {
            background: linear-gradient(135deg, #ff9966, #ff5e62);
        }

        .menu-name span {
            font-size: 11px;
            font-weight: 500;
            color: #333;
            display: block;
            margin-top: 3px;
        }

        .todaypresence {
            margin-bottom: 8px;
        }

        .todaypresence .card {
            border-radius: 12px;
            overflow: hidden;
            padding: 0 10px;
        }

        .presencecontent {
            display: flex;
            align-items: center;
            color: white;
            padding: 8px 0 8px 0;
            min-height: 60px;
        }

        .iconpresence {
            font-size: 22px;
            margin-right: 7px;
        }

        .presencedetail {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            min-width: 90px;
        }

        .presencetitle {
            margin: 0 0 4px 0;
            font-size: 15px;
            font-weight: 600;
            line-height: 1.1;
        }

        .presencescan {
            font-size: 13px;
            font-weight: 400;
            margin-top: 2px;
            line-height: 1.1;
            white-space: nowrap;
            min-width: 80px;
        }

        .gradasigreen {
            background: linear-gradient(135deg, #00b09b, #96c93d);
        }

        .gradasired {
            background: linear-gradient(135deg, #ff416c, #ff4b2b);
        }

        #rekappresensi h3 {
            font-size: 15px;
            margin-bottom: 10px;
            color: #222;
            font-weight: 700;
        }

        .historicard {
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .historiborderred {
            border-left: 3px solid #dc3545;
        }

        .historibordergreen {
            border-left: 3px solid #198754;
        }

        .historicontent {
            display: flex;
            align-items: flex-start;
        }

        .historidetail1 {
            display: flex;
            gap: 10px;
            width: 100%;
        }

        .historidetail2 {
            text-align: right;
            white-space: nowrap;
        }

        .datepresence {
            flex-grow: 1;
        }

        .datepresence h4 {
            font-size: 14px;
            margin-bottom: 5px;
        }

        .timepresence {
            font-size: 12px;
            color: #666;
        }

        .shift-jadwal {
            font-weight: 600;
            color: #333;
            margin-right: 8px;
        }

        .jam-jadwal {
            color: #666;
        }

        .jadwal-row {
            margin-bottom: 4px;
            font-size: 12px;
        }

        .danger {
            color: #dc3545;
        }

        .primary {
            color: #0d6efd;
        }
    </style>
    <div class="section" id="user-section">

        <a href="/proseslogout" class="logout">
            <ion-icon name="exit-outline"></ion-icon>
        </a>
        <div id="user-detail">
            <div class="avatar">
                @php
                    $nk = Auth::guard('karyawan')->user()->nama_karyawan;
                    $namakar = explode(' ', $nk);
                    $inisial = strtoupper(substr($namakar[0], 0, 1));
                    if(count($namakar) > 1) {
                        $inisial .= strtoupper(substr($namakar[1], 0, 1));
                    }
                    
                    $showInitials = true;
                    if (!empty(Auth::guard('karyawan')->user()->foto)) {
                        $src = 'uploads/karyawan/' . Auth::guard('karyawan')->user()->foto;
                        if (Storage::disk('public')->exists($src)) {
                            $path = Storage::url('uploads/karyawan/' . Auth::guard('karyawan')->user()->foto);
                            $showInitials = false;
                        }
                    }
                @endphp
                
                @if (!$showInitials)
                    <img src="{{ url($path) }}" alt="avatar" class="imaged w64" style="height:60px; object-fit:cover">
                @else
                    <div class="imaged w64 rounded" style="background: linear-gradient(120deg, #1e3c72, #2a5298); color: white; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: bold; height: 60px;">
                        {{ $inisial }}
                    </div>
                @endif
            </div>
            <div id="user-info">
                @php
                    $nk = Auth::guard('karyawan')->user()->nama_karyawan;
                    $namakar = explode(' ', $nk);
                    $lastname = count($namakar) > 1 ? $namakar[1] : '';
                    $namakaryawan = $namakar[0] . ' ' . $lastname;
                @endphp
                <h3 id="user-name">{{ $namakaryawan }}</h3>
                <span id="user-role">{{ $jabatan->nama_jabatan }}</span>
                <span id="user-role">({{ Auth::guard('karyawan')->user()->kode_cabang }})</span>
            </div>
        </div>
    </div>

    <div class="section" id="menu-section">
        <div class="card">
            <div class="card-body text-center" style="padding: 8px;">
                <div class="list-menu">
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="/editprofile" class="green">
                                <ion-icon name="person-sharp"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            <span>Profil</span>
                        </div>
                    </div>
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="/presensi/idcard" class="danger">
                                <ion-icon name="card-outline"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            <span>ID Card</span>
                        </div>
                    </div>
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="/pinjaman" class="warning">
                                <ion-icon name="cash-outline"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            <span>Pinjaman</span>
                        </div>
                    </div>
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="/slipgaji" class="orange">
                                <ion-icon name="newspaper"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            <span>Slip Gaji</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section mt-2" id="presence-section">
        <div class="todaypresence">
            <div class="row">

                <div class="col-6">
                    <div class="card gradasigreen">
                        <div class="card-body">
                            <div class="presencecontent">
                                <div class="iconpresence">
                                    @if ($presensi_hariini != null && $presensi_hariini->jam_in != null)
                                        @php
                                            $path = Storage::url('uploads/absensi/' . $presensi_hariini->foto_in);
                                            $src = 'uploads/absensi/' . $presensi_hariini->foto_in;
                                            $cekimage = Storage::disk('public')->exists($src);
                                        @endphp
                                        @if ($cekimage)
                                            <img src="{{ url($path) }}" alt="" class="imaged w48">
                                        @else
                                            <ion-icon name="camera"></ion-icon>
                                        @endif
                                    @else
                                        <ion-icon name="camera"></ion-icon>
                                    @endif
                                    {{-- <ion-icon name="finger-print-outline"></ion-icon> --}}
                                </div>
                                <div class="presencedetail">
                                    <h4 class="presencetitle">Masuk</h4>
                                    <span style="display: block;">{{ $presensi_hariini != null && $presensi_hariini->jam_in != null ? date('H:i:s', strtotime($presensi_hariini->jam_in)) : 'Belum Scan' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card gradasired">
                        <div class="card-body">
                            <div class="presencecontent">
                                <div class="iconpresence">
                                    @if ($presensi_hariini != null && $presensi_hariini->jam_out != null)
                                        @php
                                            $path = Storage::url('uploads/absensi/' . $presensi_hariini->foto_out);
                                            $src = 'uploads/absensi/' . $presensi_hariini->foto_out;
                                            $cekimage = Storage::disk('public')->exists($src);
                                        @endphp
                                        @if ($cekimage)
                                            <img src="{{ url($path) }}" alt="" class="imaged w48">
                                        @else
                                            <ion-icon name="camera"></ion-icon>
                                        @endif
                                    @else
                                        <ion-icon name="camera"></ion-icon>
                                    @endif
                                    {{-- <ion-icon name="finger-print-outline"></ion-icon> --}}
                                </div>
                                <div class="presencedetail">
                                    <h4 class="presencetitle">Pulang</h4>
                                    <span style="display: block;">{{ $presensi_hariini != null && $presensi_hariini->jam_out != null ? date('H:i:s', strtotime($presensi_hariini->jam_out)) : 'Belum Scan' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="rekappresensi">
            <h3>Rekap Presensi Bulan {{ $namabulan[$bulanini] }} Tahun {{ $tahunini }}</h3>
            <div class="row">
                <div class="col-3">
                    <div class="card">
                        <div class="card-body text-center" style="padding: 12px 12px !important; line-height:0.8rem">
                            <span class="badge bg-danger"
                                style="position: absolute; top:3px; right:10px; font-size:0.6rem; z-index:999">{{ $rekap_presensi->jmlhadir }}</span>
                            <ion-icon name="accessibility-outline" style="font-size: 1.6rem;" class="text-primary mb-1"></ion-icon>
                            <br>
                            <span style="font-size: 0.8rem; font-weight:500">Hadir</span>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card">
                        <div class="card-body text-center" style="padding: 12px 12px !important; line-height:0.8rem">
                            <span class="badge bg-danger"
                                style="position: absolute; top:3px; right:10px; font-size:0.6rem; z-index:999">{{ !empty($rekap_presensi->jmlizin) ? $rekap_presensi->jmlizin : '' }}</span>
                            <ion-icon name="newspaper-outline" style="font-size: 1.6rem;" class="text-success mb-1"></ion-icon>
                            <br>
                            <span style="font-size: 0.8rem; font-weight:500">Izin</span>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card">
                        <div class="card-body text-center" style="padding: 12px 12px !important; line-height:0.8rem">
                            <span class="badge bg-danger"
                                style="position: absolute; top:3px; right:10px; font-size:0.6rem; z-index:999">{{ !empty($rekap_presensi->jmlsakit) ? $rekap_presensi->jmlsakit : '' }}</span>
                            <ion-icon name="medkit-outline" style="font-size: 1.6rem;" class="text-warning mb-1"></ion-icon>
                            <br>
                            <span style="font-size: 0.8rem; font-weight:500">Sakit</span>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card">
                        <div class="card-body text-center" style="padding: 12px 12px !important; line-height:0.8rem">
                            @if (!empty($rekap_presensi->jmlterlambat))
                                <span class="badge bg-danger"
                                    style="position: absolute; top:3px; right:10px; font-size:0.6rem; z-index:999">{{ $rekap_presensi->jmlterlambat }}</span>
                            @endif
                            <ion-icon name="alarm-outline" style="font-size: 1.6rem;" class="text-danger mb-1"></ion-icon>
                            <br>
                            <span style="font-size: 0.8rem; font-weight:500">Telat</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Histori Presensi -->
        <div class="presencetab mt-2">
            <div class="tab-pane fade show active" id="pilled" role="tabpanel">
                <ul class="nav nav-tabs style1" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#home" role="tab">
                            7 Hari terakhir
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#profile" role="tab">
                            Lembur / Overtime
                        </a>
                    </li>
                </ul>
            </div>
            <div class="tab-content mt-2" style="margin-bottom:100px;">
                <div class="tab-pane fade show active" id="home" role="tabpanel">
                    @foreach ($histori as $d)
                        @if ($d->status == 'h')
                            <div class="row mb-1">
                                <div class="col">
                                    <div class="card historicard {{ $d->jam_out != null ? 'historibordergreen' : 'historiborderred' }}">
                                        <div class="card-body">
                                            <div class="historicontent">
                                                <div class="historidetail1">
                                                    <div class="iconpresence">
                                                        <ion-icon name="finger-print-outline" class="text-success" style="font-size: 48px"></ion-icon>
                                                    </div>
                                                    <div class="datepresence">
                                                        <h4>{{ DateToIndo2($d->tanggal) }}</h4>
                                                        <span class="timepresence">
                                                            {!! $d->jam_in != null ? date('H:i', strtotime($d->jam_in)) : '<span class="danger">Belum Scan</span>' !!}
                                                            {!! $d->jam_out != null ? '- ' . date('H:i', strtotime($d->jam_out)) : '<span class="danger"> - Belum Scan</span>' !!}
                                                            @php

                                                                //Tanggal Selesai Jam Kerja Jika Lintas Hari Maka Tanggal Presensi + 1 Hari
                                                                $tanggal_selesai =
                                                                    $d->lintashari == '1'
                                                                        ? date('Y-m-d', strtotime('+1 day', strtotime($d->tanggal)))
                                                                        : $d->tanggal;

                                                                //Jam Absen Karyawan
                                                                $jam_in = $d->jam_in != null ? date('Y-m-d H:i', strtotime($d->jam_in)) : null;
                                                                $jam_out = $d->jam_out != null ? date('Y-m-d H:i', strtotime($d->jam_out)) : null;

                                                                //Jadwal Jam Kerja
                                                                $j_mulai = date('Y-m-d H:i', strtotime($d->tanggal . ' ' . $d->jam_mulai));
                                                                $j_selesai = date('Y-m-d H:i', strtotime($tanggal_selesai . ' ' . $d->jam_selesai));

                                                                //Jika SPG Jam Mulai Kerja nya adalah Saat Dia Absen  Jika Tidak Sesuai Jadwal
                                                                $jam_mulai = $d->kode_jabatan == 'J22' ? $d->jam_in : $j_mulai;
                                                                $jam_selesai = $d->kode_jabatan == 'J22' ? $d->jam_out : $j_selesai;

                                                                // Jam Istirahat
                                                                if ($d->istirahat == '1') {
                                                                    if ($d->lintashari == '0') {
                                                                        $jam_awal_istirahat = $d->tanggal . ' ' . $d->jam_awal_istirahat;
                                                                        $jam_akhir_istirahat = $d->tanggal . ' ' . $d->jam_akhir_istirahat;
                                                                    } else {
                                                                        $jam_awal_istirahat = $tanggal_selesai . ' ' . $d->jam_awal_istirahat;
                                                                        $jam_akhir_istirahat = $tanggal_selesai . ' ' . $d->jam_akhir_istirahat;
                                                                    }
                                                                } else {
                                                                    $jam_awal_istirahat = null;
                                                                    $jam_akhir_istirahat = null;
                                                                }
                                                                $terlambat = hitungjamterlambat($jam_in, $jam_mulai, $d->kode_izin_terlambat);
                                                            @endphp
                                                            <br>

                                                            <!-- Cek Apakah Terlambat-->
                                                            {{-- @if (!empty($terlambat))

                                                            @else
                                                                <span style="color:green">Tepat Waktu</span>
                                                            @endif --}}

                                                            @php
                                                                $denda = hitungdenda(
                                                                    $terlambat['jamterlambat'],
                                                                    $terlambat['menitterlambat'],
                                                                    $d->kode_izin_terlambat,
                                                                    $d->kode_dept,
                                                                    $d->kode_jabatan,
                                                                );

                                                            @endphp
                                                            {{-- {{ $denda['cek'] }} --}}
                                                            <span style="color:{{ $terlambat['color'] }}">{{ $terlambat['keterangan'] }}
                                                                - {{ !empty($denda['denda']) ? $denda['denda'] : $denda['keterangan'] }}
                                                            </span>

                                                            {{-- {{ $jam_out }} {{ $jam_selesai }} --}}
                                                            <!-- Cek Pulang Cepat -->
                                                            @if (!empty($jam_out) && $jam_out < $jam_selesai)
                                                                <div class="danger">Pulang Cepat</div>
                                                            @endif
                                                            {{-- {{ $d->total_jam }} --}}
                                                            @if (!empty($d->kode_izin_keluar))
                                                                @php
                                                                    $jam_keluar = date('Y-m-d H:i', strtotime($d->jam_keluar));
                                                                    $jam_kembali = !empty($d->jam_kembali)
                                                                        ? date('Y-m-d H:i', strtotime($d->jam_kembali))
                                                                        : '';

                                                                    $keluarkantor = hitungjamkeluarkantor(
                                                                        $jam_keluar,
                                                                        $jam_kembali,
                                                                        $jam_selesai,
                                                                        $jam_out,
                                                                        $d->total_jam,
                                                                        $d->istirahat,
                                                                        $jam_awal_istirahat,
                                                                        $jam_akhir_istirahat,
                                                                    );
                                                                @endphp
                                                                <div class="{{ $keluarkantor['color'] }}">
                                                                    {{-- {{ $jam_keluar }} --}}
                                                                    Izin Keluar : {{ $keluarkantor['totaljamkeluar'] }}
                                                                </div>
                                                            @endif
                                                        </span>

                                                        <!-- Jika Izin Pulang -->
                                                        @if (!empty($d->kode_izin_pulang))
                                                            <div class="danger">Izin Pulang</div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="historidetail2">
                                                    <h4 style="font-size: 14px">{{ $d->nama_jadwal }} {{ $d->kode_cabang }}</h4>
                                                    <div class="primary" style="font-size: 12px">{{ date('H:i', strtotime($d->jam_mulai)) }} -
                                                        {{ date('H:i', strtotime($d->jam_selesai)) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="row mb-1">
                                <div class="col">
                                    <div class="card historicard historiborderred">
                                        <div class="card-body">
                                            <div class="historicontent">
                                                <div class="historidetail1">
                                                    <div class="iconpresence">
                                                        <ion-icon name="finger-print-outline" class="text-danger" style="font-size: 48px"></ion-icon>
                                                    </div>
                                                    <div class="datepresence">
                                                        <h4>{{ DateToIndo2($d->tanggal) }}</h4>
                                                        @if ($d->status == 'i')
                                                            @php
                                                                $status = 'Izin';
                                                            @endphp
                                                        @elseif($d->status == 'c')
                                                            @php
                                                                $status = 'Cuti';
                                                            @endphp
                                                        @elseif($d->status == 's')
                                                            @php
                                                                $status = 'Sakit';
                                                            @endphp
                                                        @else
                                                            @php
                                                                $status = '';
                                                            @endphp
                                                        @endif
                                                        <div class="timepresence">{{ $status }} -
                                                            @if ($d->status == 'i')
                                                                Tidak Masuk Kantor
                                                            @elseif($d->status == 'c')
                                                                {{ $d->nama_cuti }}<br>
                                                                {{ !empty($d->nama_cuti_khusus) ? '(' . $d->nama_cuti_khusus . ')' : '' }}
                                                            @elseif($d->status == 's')
                                                                @if (empty($d->doc_sid))
                                                                    <span class="text-danger">
                                                                        Tanpa SID
                                                                    </span>
                                                                @else
                                                                    <span class="text-primary">
                                                                        <ion-icon name="document-attach-outline"></ion-icon> SID
                                                                    </span>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
