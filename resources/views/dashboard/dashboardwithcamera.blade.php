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
                @php
                    $nk = Auth::guard('karyawan')->user()->nama_karyawan;
                    $namakar = explode(' ', $nk);
                    $lastname = count($namakar) > 1 ? $namakar[1] : '';
                    $namakaryawan = $namakar[0] . ' ' . $lastname;
                @endphp
                <h3 id="user-name">{{ $namakaryawan }}</h3>
                <span id="user-role">{{ $jabatan->nama_jabatan }}</span>
                <span id="user-role">({{ Auth::guard('karyawan')->user()->kode_cabang }})</span>
                <h3 id="user-name" style="margin-top:10px !important">{{ Auth::guard('karyawan')->user()->nik }}</h3>
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
                                    <span>{{ $presensi_hariini != null && $presensi_hariini->jam_in != null ? date('H:i:s', strtotime($presensi_hariini->jam_in)) : 'Belum Scan' }}</span>
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
                                    <span>{{ $presensi_hariini != null && $presensi_hariini->jam_out != null ? date('H:i:s', strtotime($presensi_hariini->jam_out)) : 'Belum Scan' }}</span>
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
                                                            @if (!empty($terlambat))
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
                                                                <span style="color:red">{{ $terlambat['keterangan'] }}
                                                                    - {{ !empty($denda['denda']) ? $denda['denda'] : $denda['keterangan'] }}
                                                                </span>
                                                            @else
                                                                <span style="color:green">Tepat Waktu</span>
                                                            @endif

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
