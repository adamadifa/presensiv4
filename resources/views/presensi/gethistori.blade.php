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
                            <span class="timepresence">{{ date("H:i:s",strtotime($d->jam_in)) }} {!! $d->jam_out != null ? "- ".date("H:i:s",strtotime($d->jam_out)) : '<span class="danger"> - Belum Pulang</span>' !!}</span><br>
                            @if (date("H:i:s",strtotime($d->jam_in)) <= $d->jam_masuk)
                                <span style="color:green">Tepat Waktu</span>
                                @else
                                <span style="color:red">Terlambat ({{ selisih($d->jam_masuk,date("H:i:s",strtotime($d->jam_in))) }}) </span>
                                @endif
                        </div>
                    </div>
                    <div class="historidetail2">
                        <h4>{{ $d->nama_jadwal }}</h4>
                        <span class="timepresence">
                            @if (!empty($d->kode_izin))
                            @if ($d->jenis_izin=="PL")
                            <span class="text-danger">
                                Izin Pulang
                            </span>
                            @endif
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
