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
    <?php
    function DateToIndo2($date2)
    { // fungsi atau method untuk mengubah tanggal ke format indonesia
    // variabel BulanIndo merupakan variabel array yang menyimpan nama-nama bulan
        $BulanIndo2 = array(
        "Januari", "Februari", "Maret",
        "April", "Mei", "Juni",
        "Juli", "Agustus", "September",
        "Oktober", "November", "Desember"
        );

        $tahun2 = substr($date2, 0, 4); // memisahkan format tahun menggunakan substring
        $bulan2 = substr($date2, 5, 2); // memisahkan format bulan menggunakan substring
        $tgl2 = substr($date2, 8, 2); // memisahkan format tanggal menggunakan substring

        $result = $tgl2 . " " . $BulanIndo2[(int)$bulan2 - 1] . " " . $tahun2;
        return ($result);
    }

    function selisih($jam_masuk, $jam_keluar)
    {
        list($h, $m, $s) = explode(":", $jam_masuk);
        $dtAwal = mktime($h, $m, $s, "1", "1", "1");
        list($h, $m, $s) = explode(":", $jam_keluar);
        $dtAkhir = mktime($h, $m, $s, "1", "1", "1");
        $dtSelisih = $dtAkhir - $dtAwal;
        $totalmenit = $dtSelisih / 60;
        $jam = explode(".", $totalmenit / 60);
        $sisamenit = ($totalmenit / 60) - $jam[0];
        $sisamenit2 = $sisamenit * 60;
        $jml_jam = $jam[0];
        return $jml_jam . ":" . round($sisamenit2);
    }

    function rupiah($nilai)
    {

        return number_format($nilai, '0', ',', '.');
    }
    ?>
    <a href="/proseslogout" class="logout">
        <ion-icon name="exit-outline"></ion-icon>
    </a>
    <div id="user-detail">
        <div class="avatar">
            @if(!empty(Auth::guard('karyawan')->user()->foto))
            @php
            $path = Storage::url('uploads/karyawan/'.Auth::guard('karyawan')->user()->foto);
            @endphp
            <img src="{{ url($path) }}" alt="avatar" class="imaged w64" style="height:60px; object-fit:cover">
            @else
            <img src="assets/img/sample/avatar/avatar1.jpg" alt="avatar" class="imaged w64 rounded">
            @endif
        </div>
        <div id="user-info">
            @php
            $nk =Auth::guard('karyawan')->user()->nama_karyawan;
            $namakar = explode(" ",$nk);
            $lastname = count($namakar) > 1 ? $namakar[1] : '';
            $namakaryawan = $namakar[0]." ".$lastname;
            @endphp
            <h3 id="user-name">{{ $namakaryawan }}</h3>
            <span id="user-role">{{ $jabatan->nama_jabatan }}</span>
            <span id="user-role">({{ Auth::guard('karyawan')->user()->id_kantor }})</span>
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
                        <a href="" class="orange" style="font-size: 40px;">
                            <ion-icon name="location"></ion-icon>
                        </a>
                    </div>
                    <div class="menu-name">
                        Lokasi
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
                                @if ($presensihariini != null && $presensihariini->jam_in != null)
                                @php
                                $path = Storage::url('uploads/absensi/'.$presensihariini->foto_in);
                                $src = "uploads/absensi/".$presensihariini->foto_in;
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
                                <span>{{ $presensihariini != null && $presensihariini->jam_in != null ? date("H:i:s",strtotime($presensihariini->jam_in)) : 'Belum Scan' }}</span>
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
                                @if ($presensihariini != null && $presensihariini->jam_out != null)
                                @php
                                $path = Storage::url('uploads/absensi/'.$presensihariini->foto_out);
                                $src = "uploads/absensi/".$presensihariini->foto_out;
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
                                <span>{{ $presensihariini != null && $presensihariini->jam_out != null ? date("H:i:s",strtotime($presensihariini->jam_out)) : 'Belum Scan' }}</span>
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
                        <span class="badge bg-danger" style="position: absolute; top:3px; right:10px; font-size:0.6rem; z-index:999">{{ $rekappresensi->jmlhadir }}</span>
                        <ion-icon name="accessibility-outline" style="font-size: 1.6rem;" class="text-primary mb-1"></ion-icon>
                        <br>
                        <span style="font-size: 0.8rem; font-weight:500">Hadir</span>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-body text-center" style="padding: 12px 12px !important; line-height:0.8rem">
                        <span class="badge bg-danger" style="position: absolute; top:3px; right:10px; font-size:0.6rem; z-index:999">{{ !empty($rekappresensi->jmlizin) ? $rekappresensi->jmlizin : ''  }}</span>
                        <ion-icon name="newspaper-outline" style="font-size: 1.6rem;" class="text-success mb-1"></ion-icon>
                        <br>
                        <span style="font-size: 0.8rem; font-weight:500">Izin</span>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-body text-center" style="padding: 12px 12px !important; line-height:0.8rem">
                        <span class="badge bg-danger" style="position: absolute; top:3px; right:10px; font-size:0.6rem; z-index:999">{{!empty($rekappresensi->jmlsakit) ? $rekappresensi->jmlsakit : ''}}</span>
                        <ion-icon name="medkit-outline" style="font-size: 1.6rem;" class="text-warning mb-1"></ion-icon>
                        <br>
                        <span style="font-size: 0.8rem; font-weight:500">Sakit</span>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-body text-center" style="padding: 12px 12px !important; line-height:0.8rem">
                        @if (!empty($rekappresensi->jmlterlambat))
                        <span class="badge bg-danger" style="position: absolute; top:3px; right:10px; font-size:0.6rem; z-index:999">{{ $rekappresensi->jmlterlambat }}</span>
                        @endif
                        <ion-icon name="alarm-outline" style="font-size: 1.6rem;" class="text-danger mb-1"></ion-icon>
                        <br>
                        <span style="font-size: 0.8rem; font-weight:500">Telat</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                {{-- {{ dd($historibulanini) }} --}}
                @php
                $denda = 0;
                @endphp
                @foreach ($historibulanini as $d)
                {{-- @php
                    $path = Storage::url('uploads/absensi/'.$d->foto_in);
                    @endphp --}}
                @if ($d->status=="h")
                <div class="row mb-1">
                    <div class="col">
                        <div class="card historicard {{ $d->jam_out != null  ? 'historibordergreen' : 'historiborderred' }}">
                            <div class="card-body">
                                <div class="historicontent">
                                    <div class="historidetail1">
                                        <div class="iconpresence">
                                            <ion-icon name="finger-print-outline" class="text-success" style="font-size: 48px"></ion-icon>
                                        </div>
                                        <div class="datepresence">
                                            <h4>{{ DateToIndo2($d->tgl_presensi) }}</h4>
                                            <span class="timepresence">{!! $d->jam_in != null ? date("H:i",strtotime($d->jam_in)) : '<span class="danger">Belum Scan</span>' !!} {!! $d->jam_out != null ? "- ".date("H:i",strtotime($d->jam_out)) : '<span class="danger"> - Belum Scan</span>' !!}</span><br>
                                            {{-- @if (date("H:i:s",strtotime($d->jam_in)) <= $d->jam_masuk)
                                                <span style="color:green">Tepat Waktu</span>
                                                @else
                                                <span style="color:red">Terlambat ({{ selisih($d->jam_masuk,date("H:i:s",strtotime($d->jam_in))) }}) </span>
                                            @endif --}}

                                            <?php

                                                $jam_in = date("H:i", strtotime($d->jam_in));
                                                $jam_out = date("H:i", strtotime($d->jam_out));
                                                $jam_pulang = date("H:i", strtotime($d->jam_pulang));
                                                //$status = $d->status_presensi;
                                                if (!empty($d->jam_in)) {
                                                    if ($jam_in > $d->jam_masuk) {

                                                        $jam_masuk = $d->tgl_presensi . " " . $d->jam_masuk;

                                                        $j1 = strtotime($jam_masuk);
                                                        $j2 = strtotime($d->jam_in);

                                                        $diffterlambat = $j2 - $j1;

                                                        $jamterlambat = floor($diffterlambat / (60 * 60));
                                                        $menitterlambat = floor(($diffterlambat - ($jamterlambat * (60 * 60)))/60);

                                                        $jterlambat = $jamterlambat <= 9 ? "0" . $jamterlambat : $jamterlambat;
                                                        $mterlambat = $menitterlambat <= 9 ? "0" . $menitterlambat : $menitterlambat;


                                                        $terlambat = "Telat ".$jterlambat . ":" . $mterlambat;
                                                        $desimalterlambat = ROUND(($menitterlambat * 100) / 60);
                                                        $colorterlambat = "red";
                                                    } else {
                                                        $terlambat = "Tepat waktu";
                                                        $jamterlambat = 0;
                                                        $desimalterlambat = 0;
                                                        $colorterlambat = "green";
                                                    }
                                                } else {
                                                    $terlambat = "";
                                                    $jamterlambat = 0;
                                                    $desimalterlambat = 0;
                                                    $colorterlambat = "";
                                                }

                                                if(!empty($d->jam_keluar)){
                                                    $jamkeluar = $d->tgl_presensi." ".$d->jam_keluar;
                                                    if(!empty($d->jam_masuk_kk)){
                                                        $jam_masuk_kk = $d->tgl_presensi." ".$d->jam_masuk_kk;
                                                    }else{
                                                        $jam_masuk_kk = $d->tgl_presensi." ".$d->jam_pulang;
                                                    }

                                                    $jk1 = strtotime($jamkeluar);
                                                    $jk2 = strtotime($jam_masuk_kk);
                                                    $difkeluarkantor = $jk2 - $jk1;

                                                    $jamkeluarkantor = floor($difkeluarkantor / (60 * 60));
                                                    $menitkeluarkantor = floor(($difkeluarkantor - ($jamkeluarkantor * (60 * 60)))/60);

                                                    $jkeluarkantor = $jamkeluarkantor <= 9 ? "0" . $jamkeluarkantor : $jamkeluarkantor;
                                                    $mkeluarkantor = $menitkeluarkantor <= 9 ? "0" . $menitkeluarkantor : $menitkeluarkantor;

                                                    if(empty($d->jam_masuk_kk)){
                                                        if($d->total_jam == 7){
                                                            $totaljamkeluar = ($jkeluarkantor-1).":".$mkeluarkantor;
                                                        }else{
                                                            $totaljamkeluar = $jkeluarkantor.":".$mkeluarkantor;
                                                        }
                                                    }else{
                                                        $totaljamkeluar = $jkeluarkantor.":".$mkeluarkantor;
                                                    }
                                                    $desimaljamkeluar = ROUND(($menitkeluarkantor * 100) / 60);
                                                }else{
                                                    $totaljamkeluar = "";
                                                    $desimaljamkeluar = 0;
                                                    $jamkeluarkantor = 0;
                                                }


                                                if(!empty($d->jam_out) && $jam_out < $jam_pulang){
                                                    $pc = "Pulang Cepat";
                                                }else{
                                                    $pc = "";
                                                }
                                                if (!empty($d->jam_in) and $d->kode_dept != 'MKT') {
                                                    if ($jam_in > $d->jam_masuk and empty($d->kode_izin_terlambat)) {
                                                        if ($jamterlambat < 1) {
                                                            if($menitterlambat >= 5 AND $menitterlambat < 10){
                                                                $denda = 5000;
                                                            }else if($menitterlambat >= 10 AND $menitterlambat <15){
                                                                $denda = 10000;
                                                            }else if($menitterlambat >= 15 AND $menitterlambat <= 59){
                                                                $denda = 15000;
                                                            }
                                                        }else{
                                                            $denda = "pj";
                                                        }
                                                    } else {
                                                        if(!empty($d->kode_izin_terlambat)){
                                                            $denda = "si";
                                                        }else{
                                                            $denda = 0;
                                                        }
                                                    }
                                                } else {
                                                    if ($jamterlambat < 1) {
                                                        $denda = 0;
                                                    }else{
                                                        $denda="pj";
                                                    }
                                                }
                                            ?>

                                            @if (!empty($d->jam_in))
                                            <span style="color:{{ $colorterlambat }}">{{ $terlambat }}
                                                @if (!empty($denda) && $denda != "pj" && $denda != "si" )
                                                - {{ rupiah($denda) }}
                                                @else
                                                @if ($denda=="pj")
                                                - Potong JK
                                                @elseif($denda=="si")
                                                - Sudah Izin
                                                @endif
                                                @endif
                                            </span>

                                            <br>
                                            @endif

                                            @if (!empty($pc))
                                            <span class="danger">{{ $pc }}</span>
                                            <br>
                                            @endif

                                            @if (!empty($jamkeluarkantor))
                                            @if ($jamkeluarkantor > 0)
                                            <span class="danger">Izin Keluar : {{ $totaljamkeluar }}</span>
                                            @else
                                            <span>Izin Keluar : {{ $totaljamkeluar }}</span>
                                            @endif
                                            @endif


                                        </div>
                                    </div>
                                    <div class="historidetail2">
                                        <h4>{{ $d->nama_jadwal }} {{ $d->kode_cabang }}</h4>
                                        <span class="timepresence">
                                            @if (!empty($d->kode_izin_pulang))
                                            <span class="text-danger">
                                                Izin Pulang
                                            </span>
                                            @endif
                                        </span>
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
                                            <h4>{{ DateToIndo2($d->tgl_presensi) }}</h4>
                                            @if ($d->status=="i")
                                            @php
                                            $status = "Izin";
                                            @endphp
                                            @elseif($d->status=="c")
                                            @php
                                            $status = "Cuti";
                                            @endphp
                                            @elseif($d->status=="s")
                                            @php
                                            $status="Sakit";
                                            @endphp
                                            @else
                                            @php
                                            $status = "";
                                            @endphp
                                            @endif


                                            <span class="timepresence">{{ $status }} -
                                                @if ($d->status=="i")
                                                Tidak Masuk Kantor
                                                @elseif($d->status=="c")
                                                {{ $d->nama_cuti }}
                                                @elseif($d->status=="s")
                                                @if (empty($d->sid))
                                                <span class="text-danger">
                                                    Tanpa SID
                                                </span>
                                                @else
                                                <span class="text-primary">
                                                    <ion-icon name="document-attach-outline"></ion-icon> SID
                                                </span>
                                                @endif
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    <div class="historidetail2">
                                        <h4>{{ $d->nama_jadwal }}</h4>
                                        <span class="timepresence">

                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @endforeach

            </div>
            <div class="tab-pane fade" id="profile" role="tabpanel">
                {{-- <ul class="listview image-listview">
                    @foreach ($leaderboard as $d)
                    <li>
                        <div class="item">
                            <img src="assets/img/sample/avatar/avatar1.jpg" alt="image" class="image">
                            <div class="in">
                                <div>
                                    <b>{{ $d->nama_karyawan }}</b><br>
                <small class="text-muted">{{ $d->nama_jabatan }}</small>
            </div>
            <span class="badge {{ date("H:i:s",strtotime($d->jam_in)) < $d->jam_masuk ? "bg-success" : "bg-danger" }}">
                {{ date("H:i:s",strtotime($d->jam_in)) }}
            </span>
        </div>
    </div>
    </li>

    @endforeach

    </ul> --}}
</div>

</div>
</div>
</div>
@endsection
