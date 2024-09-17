@extends('layouts.presensi')
@section('content')
    <style>
        .logout {
            position: absolute;
            color: white;
            font-size: 30px;
            text-decoration: none;
            right: 8px;
        }

        .logout:hover {
            color: white;

        }

        .image-listview>li .item {
            min-height: 80px !important;
            border-radius: 20px !important;
        }
    </style>
    <div class="section" id="user-section">

        <a href="/proseslogout" class="logout">
            <ion-icon name="exit-outline"></ion-icon>
        </a>
        <div id="user-detail">
            <div class="avatar">
                @if (!empty(Auth::guard('karyawan')->user()->foto))
                    @php
                        $path = Storage::url('uploads/karyawan/' . Auth::guard('karyawan')->user()->foto);
                    @endphp
                    <img src="{{ url($path) }}" alt="avatar" class="imaged w64" style="height:60px; object-fit:cover">
                @else
                    <img src="assets/img/sample/avatar/avatar1.jpg" alt="avatar" class="imaged w64 rounded">
                @endif
            </div>
            <div id="user-info">
                <h3 id="user-name">{{ Auth::guard('karyawan')->user()->nama_karyawan }}</h3>
                <span id="user-role">{{ $jabatan->nama_jabatan }}</span>
                <span id="user-role">({{ Auth::guard('karyawan')->user()->kode_cabang }})</span>
            </div>
        </div>
    </div>

    <div class="section" id="menu-section">
        <div class="card">
            <div class="card-body text-center">
                <div class="list-menu">
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="/editprofile" class="green" style="font-size: 40px;">
                                <ion-icon name="person-sharp"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            <span class="text-center">Profil</span>
                        </div>
                    </div>
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="/presensi/izin" class="danger" style="font-size: 40px;">
                                <ion-icon name="calendar-number"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            <span class="text-center">Cuti</span>
                        </div>
                    </div>
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="/pinjaman" class="warning" style="font-size: 40px;">
                                <ion-icon name="cash-outline"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            <span class="text-center">Pinjaman</span>
                        </div>
                    </div>
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="/slipgaji" class="orange" style="font-size: 40px;">
                                <ion-icon name="newspaper"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            Slip Gaji
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
                                    <h4 class="presencetitle">Masuk</h4>
                                    <span>{{ $presensi_hariini != null && $presensihariini->jam_in != null ? date('H:i:s', strtotime($presensihariini->jam_in)) : 'Belum Scan' }}</span>
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
                                    <h4 class="presencetitle">Pulang</h4>
                                    <span>{{ $presensi_hariini != null && $presensihariini->jam_out != null ? date('H:i:s', strtotime($presensihariini->jam_out)) : 'Belum Scan' }}</span>
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
                                                                //Jika SPG Jam Mulai Kerja nya adalah Saat Dia Absen  Jika Tidak Sesuai Jadwal

                                                                $j_mulai = $d->tanggal . ' ' . $d->jam_mulai;
                                                                $j_selesai = $tanggal_selesai . ' ' . $d->jam_selesai;
                                                                $jam_mulai = $d->kode_jabatan == 'J22' ? $d->jam_in : $j_mulai;
                                                                $jam_selesai = $d->kode_jabatan == 'J22' ? $d->jam_out : $j_selesai;

                                                                $terlambat = hitungjamterlambat($d->jam_in, $jam_mulai);
                                                            @endphp
                                                            <br>
                                                            @if (!empty($terlambat))
                                                                <span
                                                                    style="color:{{ $terlambat['color_terlambat'] }}">{{ $terlambat['keterangan_terlambat'] }}
                                                                    {{-- @if (!empty($denda) && $denda != 'pj' && $denda != 'si')
                                                                        - {{ rupiah($denda) }}
                                                                    @else
                                                                        @if ($denda == 'pj')
                                                                            - Potong JK
                                                                        @elseif($denda == 'si')
                                                                            - Sudah Izin
                                                                        @endif
                                                                    @endif --}}
                                                                </span>
                                                            @endif
                                                        </span>
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
