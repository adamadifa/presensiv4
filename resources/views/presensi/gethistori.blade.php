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
?>
@if ($histori->isEmpty())
<div class="alert  alert-outline-warning">
    <p>Data Belum Aada</p>
</div>
@endif
@foreach ($histori as $d)
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
