@extends('layouts.presensi')
@section('content')
    <style>
        /* Import Font */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
        }

        .dashboard-container {
            padding: 20px;
            padding-bottom: 80px; /* Space for bottom nav */
        }

        /* Header Section */
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            margin-top: 10px;
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .avatar-wrapper {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 15px;
            border: 2px solid #fff;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .avatar-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-text h4 {
            font-size: 14px;
            color: #888;
            margin: 0;
            font-weight: 400;
        }

        .user-text h2 {
            font-size: 16px;
            color: #333;
            margin: 0;
            font-weight: 600;
        }

        .notification-icon {
            font-size: 24px;
            color: #333;
            position: relative;
        }
        
        .notification-icon .badge-dot {
            position: absolute;
            top: 2px;
            right: 2px;
            width: 8px;
            height: 8px;
            background-color: #ff416c;
            border-radius: 50%;
        }

        /* Blue Card Section */
        .blue-card {
            background: linear-gradient(135deg, #304ffe 0%, #0026ca 100%);
            border-radius: 20px;
            padding: 25px;
            color: white;
            box-shadow: 0 10px 25px rgba(48, 79, 254, 0.3);
            margin-bottom: 25px;
            position: relative;
            overflow: hidden;
        }

        /* Decorative circles */
        .blue-card::before {
            content: '';
            position: absolute;
            top: -20px;
            right: -20px;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .blue-card::after {
            content: '';
            position: absolute;
            bottom: -30px;
            left: -20px;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
        }

        .card-info {
            position: relative;
            z-index: 1;
            margin-bottom: 20px;
        }

        .card-info .role {
            font-size: 14px;
            color: white;
            opacity: 0.9;
            margin-bottom: 5px;
            display: block;
        }

        .card-info .nik {
            font-size: 28px;
            color: white;
            font-weight: 700;
            letter-spacing: 1px;
            margin: 0;
        }

        .presence-times-box {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 15px;
            display: flex;
            position: relative;
            z-index: 1;
            backdrop-filter: blur(5px);
        }

        .presence-time-col {
            flex: 1;
            text-align: center;
        }

        .presence-time-col:first-child {
            border-right: 1px solid rgba(255, 255, 255, 0.3);
        }

        .presence-time-col .label {
            font-size: 12px;
            opacity: 0.8;
            margin-bottom: 5px;
            display: block;
        }

        .presence-time-col .time {
            font-size: 20px;
            font-weight: 600;
            display: block;
        }

        /* Menu Section (Replaces Map/Distance) */
        .menu-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 25px;
        }

        .menu-item {
            background: white;
            padding: 15px;
            border-radius: 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: none; /* No border for cleaner look */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            text-decoration: none;
            color: #333;
            transition: transform 0.2s;
        }

        .menu-item:hover {
            transform: translateY(-5px);
            color: #304ffe;
        }

        .menu-item ion-icon {
            font-size: 28px;
            margin-bottom: 10px;
            color: #304ffe;
        }

        .menu-item span {
            font-size: 13px;
            font-weight: 500;
        }

        /* History Section */
        .section-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .section-title h3 {
            font-size: 18px;
            font-weight: 700;
            color: #333;
            margin: 0;
        }

        .section-title a {
            font-size: 13px;
            color: #304ffe;
            text-decoration: none;
            font-weight: 500;
        }

        .history-card {
            background: white;
            border-radius: 15px;
            padding: 15px 20px;
            margin-bottom: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .history-info {
            flex: 1;
        }

        .history-label {
            font-size: 12px;
            color: #888;
            margin-bottom: 2px;
        }
        
        .history-label.check-in { color: #00b09b; font-weight: 500;}
        .history-label.check-out { color: #333; font-weight: 500;}

        .history-time {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        .history-date {
            text-align: right;
            font-size: 12px;
            color: #999;
        }
        
        .history-status {
             margin-top: 5px;
             font-size: 11px;
             font-weight: 500;
        }
        
        /* Recap Badge Row */
        .rekap-wrapper {
             overflow-x: auto; 
             white-space: nowrap; 
             padding-bottom: 5px; 
             margin-bottom: 20px;
        }
        
        .rekap-badge {
            display: inline-flex;
            align-items: center;
            padding: 8px 15px;
            background: white;
            border-radius: 50px;
            margin-right: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            font-size: 13px;
            color: #555;
        }
        
        .rekap-badge ion-icon {
            margin-right: 5px;
            font-size: 16px;
        }
        
        .rekap-badge strong {
            margin-left: 5px;
            color: #333;
        }

    </style>

    <div class="dashboard-container">
        <!-- 1. Header Section -->
        <div class="header-section">
            <div class="user-info">
                <div class="avatar-wrapper">
                    @if (!empty(Auth::guard('karyawan')->user()->foto))
                        @php
                            $path = 'https://app.portalmp.com/storage/karyawan/' . Auth::guard('karyawan')->user()->foto;
                        @endphp
                        <img src="{{ $path }}" alt="avatar">
                    @else
                        <img src="assets/img/sample/avatar/avatar1.jpg" alt="avatar">
                    @endif
                </div>
                <div class="user-text">
                    <h4>Welcome back</h4>
                    <h2>{{ Auth::guard('karyawan')->user()->nama_karyawan }}</h2>
                </div>
            </div>
            <a href="/proseslogout" class="notification-icon">
                <ion-icon name="log-out-outline"></ion-icon>
            </a>
        </div>

        <!-- 2. Blue Main Card -->
        <div class="blue-card">
            <div class="card-info">
                <span class="role">{{ $jabatan->nama_jabatan }} ({{ Auth::guard('karyawan')->user()->kode_cabang }})</span>
                <h3 class="nik">{{ Auth::guard('karyawan')->user()->nik }}</h3>
            </div>
            <div class="presence-times-box">
                <div class="presence-time-col">
                    <span class="label">Check In</span>
                    <span class="time">
                        {{ $presensi_hariini != null && $presensi_hariini->jam_in != null ? date('H:i', strtotime($presensi_hariini->jam_in)) : '--:--' }}
                    </span>
                </div>
                <div class="presence-time-col">
                    <span class="label">Check Out</span>
                    <span class="time">
                        {{ $presensi_hariini != null && $presensi_hariini->jam_out != null ? date('H:i', strtotime($presensi_hariini->jam_out)) : '--:--' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- 3. Recap Section (Horizontal) -->
         <div class="rekap-wrapper">
             <div class="rekap-badge">
                 <ion-icon name="accessibility-outline" style="color:#304ffe;"></ion-icon>
                 Hadir <strong>{{ $rekap_presensi->jmlhadir }}</strong>
             </div>
             <div class="rekap-badge">
                 <ion-icon name="document-text-outline" style="color:#00b09b;"></ion-icon>
                 Izin <strong>{{ !empty($rekap_presensi->jmlizin) ? $rekap_presensi->jmlizin : '0' }}</strong>
             </div>
             <div class="rekap-badge">
                 <ion-icon name="medkit-outline" style="color:#f7971e;"></ion-icon>
                 Sakit <strong>{{ !empty($rekap_presensi->jmlsakit) ? $rekap_presensi->jmlsakit : '0' }}</strong>
             </div>
             <div class="rekap-badge">
                 <ion-icon name="alarm-outline" style="color:#ff416c;"></ion-icon>
                 Telat <strong>{{ !empty($rekap_presensi->jmlterlambat) ? $rekap_presensi->jmlterlambat : '0' }}</strong>
             </div>
         </div>

        <!-- 4. Menu Grid (Replaces Distance/Maps) -->
        <div class="menu-grid">
            <a href="/editprofile" class="menu-item">
                <ion-icon name="person-circle-outline"></ion-icon>
                <span>Profil</span>
            </a>
            <a href="/presensi/idcard" class="menu-item">
                <ion-icon name="card-outline"></ion-icon>
                <span>ID Card</span>
            </a>
            <a href="/pinjaman" class="menu-item">
                <ion-icon name="cash-outline"></ion-icon>
                <span>Pinjaman</span>
            </a>
            <a href="/slipgaji" class="menu-item">
                <ion-icon name="newspaper-outline"></ion-icon>
                <span>Slip Gaji</span>
            </a>
        </div>

        <!-- 5. Presence History -->
        <div class="section-title">
            <h3>Presence History</h3>
            <a href="/presensi/histori">Show all</a>
        </div>

        <div class="history-list">
             @foreach ($histori as $d)
                 <div class="history-card">
                     <div class="history-info">
                         <div class="row">
                            <div class="col-6">
                                <span class="history-label check-in">Check In</span>
                                <span class="history-time">
                                    {{ $d->jam_in != null ? date('H:i', strtotime($d->jam_in)) : '--:--' }}
                                </span>
                            </div>
                            <div class="col-6">
                                <span class="history-label check-out">Check Out</span>
                                <span class="history-time">
                                    {{ $d->jam_out != null ? date('H:i', strtotime($d->jam_out)) : '--:--' }}
                                </span>
                            </div>
                         </div>
                         
                         <div class="history-details mt-2">
                             @if ($d->status == 'h')
                                 @php
                                     // Logic Calculations
                                     $jam_in = $d->jam_in != null ? date('Y-m-d H:i', strtotime($d->jam_in)) : null;
                                     $jam_out = $d->jam_out != null ? date('Y-m-d H:i', strtotime($d->jam_out)) : null;
                                     
                                     // Jadwal Jam Kerja
                                     $tanggal_selesai = $d->lintashari == '1' ? date('Y-m-d', strtotime('+1 day', strtotime($d->tanggal))) : $d->tanggal;
                                     $j_mulai = date('Y-m-d H:i', strtotime($d->tanggal . ' ' . $d->jam_mulai));
                                     $j_selesai = date('Y-m-d H:i', strtotime($tanggal_selesai . ' ' . $d->jam_selesai));

                                     // SPG Logic
                                     $is_j31_j32_effective = in_array($d->kode_jabatan, ['J31', 'J32']) && $d->tanggal >= '2026-02-21';
                                     $is_spg = in_array($d->kode_jabatan, ['J22', 'J23']) || $is_j31_j32_effective;
                                     $jam_mulai = $is_spg ? $d->jam_in : $j_mulai;
                                     $jam_selesai = $is_spg ? $d->jam_out : $j_selesai;

                                     // Terlambat
                                     $terlambat = hitungjamterlambat($jam_in, $jam_mulai, $d->kode_izin_terlambat);
                                     
                                     // Denda
                                     $denda = hitungdenda($terlambat['jamterlambat'], $terlambat['menitterlambat'], $d->kode_izin_terlambat, $d->kode_dept, $d->kode_jabatan);

                                     // Izin Keluar Logic
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
                                 @endphp

                                 {{-- Display Terlambat & Denda --}}
                                 <div class="detail-item" style="color:{{ $terlambat['color'] }}; font-size: 11px;">
                                     {{ $terlambat['keterangan'] }} 
                                     {{ !empty($denda['denda']) ? ' - ' . $denda['denda'] : '' }}
                                 </div>

                                 {{-- Pulang Cepat --}}
                                 @if (!empty($jam_out) && $jam_out < $jam_selesai)
                                     <div class="detail-item text-danger" style="font-size: 11px;">Pulang Cepat</div>
                                 @endif

                                 {{-- Izin Keluar --}}
                                 @if (!empty($d->kode_izin_keluar))
                                     @php
                                         $jam_keluar = date('Y-m-d H:i', strtotime($d->jam_keluar));
                                         $jam_kembali = !empty($d->jam_kembali) ? date('Y-m-d H:i', strtotime($d->jam_kembali)) : '';
                                         $keluarkantor = hitungjamkeluarkantor($jam_keluar, $jam_kembali, $jam_selesai, $jam_out, $d->total_jam, $d->istirahat, $jam_awal_istirahat, $jam_akhir_istirahat);
                                     @endphp
                                     <div class="detail-item {{ $keluarkantor['color'] }}" style="font-size: 11px;">
                                         Izin Keluar : {{ $keluarkantor['totaljamkeluar'] }}
                                     </div>
                                 @endif
                                 
                                 {{-- Izin Pulang --}}
                                 @if (!empty($d->kode_izin_pulang))
                                     <div class="detail-item text-danger" style="font-size: 11px;">Izin Pulang</div>
                                 @endif

                             @elseif($d->status != 'h')
                                 <div class="history-status" style="color:orange; font-size: 12px;">
                                     @if($d->status == 'i') Izin : {{ $d->keterangan }}
                                     @elseif($d->status == 's') Sakit : {{ $d->keterangan }}
                                     @elseif($d->status == 'c') Cuti : {{ $d->nama_cuti }}
                                     @endif
                                 </div>
                             @endif
                         </div>
                     </div>
                     <div class="history-date">
                         <span>{{ date('l', strtotime($d->tanggal)) }}</span><br>
                         <span style="font-weight:600; color:#333;">{{ date('d M Y', strtotime($d->tanggal)) }}</span>
                     </div>
                 </div>
             @endforeach
        </div>

    </div>
    <!-- End Dashboard Container -->
@endsection

