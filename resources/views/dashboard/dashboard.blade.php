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
            padding: 8px;
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
            width: 40px;
            height: 40px;
            line-height: 40px;
            border-radius: 12px;
            color: white;
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
            margin: 0;
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

        .presencecontent {
            display: flex;
            align-items: center;
            color: white;
        }

        .presencetitle {
            margin: 0;
            font-size: 16px;
            font-weight: 500;
        }

        #rekappresensi {
            padding: 10px 5px 5px 5px;
            margin-top: 0;
            margin-bottom: 10px;
        }

        #rekappresensi h3 {
            font-size: 15px;
            margin-bottom: 10px;
            color: #222;
            font-weight: 700;
        }

        .rekap-row {
            display: flex;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 0;
        }

        .rekap-card {
            flex: 1;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(30, 60, 114, 0.06);
            padding: 10px 0 8px 0;
            text-align: center;
            margin: 0 2px;
            min-width: 0;
        }

        .rekap-card .badge {
            position: absolute;
            top: 5px;
            right: 10px;
            font-size: 0.65rem;
            z-index: 999;
        }

        .rekap-card .rekap-icon {
            font-size: 1.4rem;
            margin-bottom: 2px;
            display: block;
        }

        .rekap-card .rekap-label {
            font-size: 0.78rem;
            font-weight: 500;
            margin-top: 2px;
            color: #333;
        }

        .badge {
            padding: 5px 8px;
            border-radius: 8px;
        }

        .historicard {
            border-radius: 12px;
            margin-bottom: 5px;
            padding: 4px 12px 4px 6px;
        }

        .historibordergreen {
            border-left: 4px solid #00b09b;
        }

        .historiborderred {
            border-left: 4px solid #ff416c;
        }

        .historicontent {
            display: flex;
            align-items: flex-start;
            padding: 0;
        }

        .historidetail1 {
            display: flex;
            align-items: flex-start;
            flex: 1;
        }

        .iconpresence {
            font-size: 22px;
            margin-right: 7px;
            margin-top: 2px;
        }

        .datepresence {
            margin-left: 8px;
            min-width: 120px;
        }

        .datepresence h4 {
            margin: 0 0 2px 0;
            font-size: 15px;
            color: #333;
            font-weight: 600;
            white-space: nowrap;
        }

        .jadwal-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
            margin-bottom: 2px;
        }

        .shift-jadwal {
            font-size: 13px;
            color: #222;
            font-weight: 500;
            white-space: nowrap;
            flex: 1;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .jam-jadwal {
            font-size: 12px;
            color: #1e3c72;
            white-space: nowrap;
            margin-left: 10px;
            flex-shrink: 0;
        }

        .timepresence {
            font-size: 12px;
            color: #666;
            white-space: nowrap;
        }

        .danger {
            color: #ff416c;
            font-size: 12px;
            white-space: nowrap;
        }

        .primary {
            color: #1e3c72;
            font-size: 12px;
            white-space: nowrap;
        }

        .historicard .rekap-label,
        .historicard .rekap-icon {
            font-size: 12px;
        }

        /* Hilangkan margin bawah berlebih pada section berikutnya */
        #presence-section {
            margin-bottom: 10px;
        }

        .bottom-nav {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 100;
            background: linear-gradient(120deg, #1e3c72, #2a5298, #2980b9, #6dd5fa);
            background-size: 300% 300%;
            animation: gradientMove 8s ease-in-out infinite;
            box-shadow: 0 -2px 16px 0 rgba(30, 60, 114, 0.12);
            border-radius: 18px 18px 0 0;
            padding: 6px 0 2px 0;
            display: flex;
            justify-content: space-around;
            align-items: center;
            height: 62px;
        }

        .bottom-nav .nav-item {
            flex: 1;
            text-align: center;
            color: #e0e6ed;
            font-size: 11px;
            font-weight: 500;
            transition: color 0.2s;
            cursor: pointer;
        }

        .bottom-nav .nav-item.active {
            color: #fff;
            font-weight: 700;
        }

        .bottom-nav .nav-item .nav-icon {
            font-size: 24px;
            display: block;
            margin-bottom: 2px;
            transition: color 0.2s;
        }

        .bottom-nav .nav-item.active .nav-icon {
            color: #6dd5fa;
        }

        .bottom-nav .nav-item:not(.active) .nav-icon {
            color: #e0e6ed;
    </style>
    <div class="section" id="user-section">
        <a href="/proseslogout" class="logout">
            <ion-icon name="exit-outline"></ion-icon>
        </a>
        <div id="user-detail">
            <div class="avatar">
                @if (!empty(Auth::guard('karyawan')->user()->foto))
                    @php
                        $path = 'https://app.portalmp.com/storage/karyawan/' . Auth::guard('karyawan')->user()->foto;
                    @endphp
                    <img src="{{ $path }}" alt="avatar" class="imaged w64" style="height:60px; object-fit:cover">
                @else
                    <img src="assets/img/sample/avatar/avatar1.jpg" alt="avatar" class="imaged w64 rounded">
                @endif
            </div>
            <div id="user-info">
                <h3 id="user-name">{{ Auth::guard('karyawan')->user()->nama_karyawan }}</h3>
                <span id="user-role">{{ $jabatan->nama_jabatan }} ({{ Auth::guard('karyawan')->user()->kode_cabang }})</span>
            </div>
        </div>
    </div>

    <div class="section" id="menu-section">
        <div class="card">
            <div class="card-body text-center" style="padding: 8px;">
                <div class="list-menu">
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="/editprofile" class="green" style="font-size: 30px;">
                                <ion-icon name="person-sharp"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            <span>Profil</span>
                        </div>
                    </div>
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="/presensi/idcard" class="danger" style="font-size: 30px;">
                                <ion-icon name="card-outline"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            <span>ID Card</span>
                        </div>
                    </div>
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="/pinjaman" class="warning" style="font-size: 30px;">
                                <ion-icon name="cash-outline"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            <span>Pinjaman</span>
                        </div>
                    </div>
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="/slipgaji" class="orange" style="font-size: 30px;">
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
                                    <ion-icon name="finger-print-outline"></ion-icon>
                                </div>
                                <div class="presencedetail">
                                    <span class="presencetitle">Masuk</span>
                                    <span class="presencescan">
                                        {{ $presensi_hariini != null && $presensi_hariini->jam_in != null ? date('H:i:s', strtotime($presensi_hariini->jam_in)) : 'Belum Scan' }}
                                    </span>
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
                                    <ion-icon name="finger-print-outline"></ion-icon>
                                </div>
                                <div class="presencedetail">
                                    <span class="presencetitle">Pulang</span>
                                    <span class="presencescan">
                                        {{ $presensi_hariini != null && $presensi_hariini->jam_out != null ? date('H:i:s', strtotime($presensi_hariini->jam_out)) : 'Belum Scan' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="rekappresensi">
            <h3>Rekap Presensi Bulan {{ $namabulan[$bulanini] }} Tahun {{ $tahunini }}</h3>
            <div class="rekap-row">
                <div class="rekap-card" style="position:relative;">
                    <span class="badge bg-danger">{{ $rekap_presensi->jmlhadir }}</span>
                    <span class="rekap-icon" style="color:#1e3c72"><ion-icon name="accessibility-outline"></ion-icon></span>
                    <span class="rekap-label">Hadir</span>
                </div>
                <div class="rekap-card" style="position:relative;">
                    <span class="badge bg-danger">{{ !empty($rekap_presensi->jmlizin) ? $rekap_presensi->jmlizin : '' }}</span>
                    <span class="rekap-icon" style="color:#00b09b"><ion-icon name="newspaper-outline"></ion-icon></span>
                    <span class="rekap-label">Izin</span>
                </div>
                <div class="rekap-card" style="position:relative;">
                    <span class="badge bg-danger">{{ !empty($rekap_presensi->jmlsakit) ? $rekap_presensi->jmlsakit : '' }}</span>
                    <span class="rekap-icon" style="color:#f7971e"><ion-icon name="medkit-outline"></ion-icon></span>
                    <span class="rekap-label">Sakit</span>
                </div>
                <div class="rekap-card" style="position:relative;">
                    @if (!empty($rekap_presensi->jmlterlambat))
                        <span class="badge bg-danger">{{ $rekap_presensi->jmlterlambat }}</span>
                    @endif
                    <span class="rekap-icon" style="color:#ff416c"><ion-icon name="alarm-outline"></ion-icon></span>
                    <span class="rekap-label">Telat</span>
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
                                                        <ion-icon name="finger-print-outline" class="text-success"></ion-icon>
                                                    </div>
                                                    <div class="datepresence">
                                                        <h4>{{ DateToIndo2($d->tanggal) }}</h4>
                                                        <div class="jadwal-row">
                                                            <span class="shift-jadwal">{{ $d->nama_jadwal }} {{ $d->kode_cabang }}</span>
                                                            <span class="jam-jadwal">{{ date('H:i', strtotime($d->jam_mulai)) }} -
                                                                {{ date('H:i', strtotime($d->jam_selesai)) }}</span>
                                                        </div>
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

                                                            @php
                                                                $denda = hitungdenda(
                                                                    $terlambat['jamterlambat'],
                                                                    $terlambat['menitterlambat'],
                                                                    $d->kode_izin_terlambat,
                                                                    $d->kode_dept,
                                                                    $d->kode_jabatan,
                                                                );
                                                            @endphp
                                                            <span style="color:{{ $terlambat['color'] }}">{{ $terlambat['keterangan'] }}
                                                                {{ !empty($denda['denda']) ? ' - ' . $denda['denda'] : $denda['keterangan'] }}</span>

                                                            @if (!empty($jam_out) && $jam_out < $jam_selesai)
                                                                <div class="danger">Pulang Cepat</div>
                                                            @endif

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
                                                                    Izin Keluar : {{ $keluarkantor['totaljamkeluar'] }}
                                                                </div>
                                                            @endif

                                                            @if (!empty($d->kode_izin_pulang))
                                                                <div class="danger">Izin Pulang</div>
                                                            @endif
                                                        </span>
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
