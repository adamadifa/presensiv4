@extends('layouts.presensi')
@section('header')
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
?>

<style>
    body {
        margin-bottom: 15% !important;
    }

    .card .card-body {
        padding: 15px 10px 10px 5px !important;
    }

    /* .historicontent {
        justify-content: left !important;
    } */

</style>
<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Data Izin / Sakit</div>
    <div class="right"></div>
</div>
<!-- * App Header -->
@endsection
@section('content')
<div class="row" style="top:56px; position:fixed; width:105%; z-index:999; background-color:#e9ecef; padding:5px">
    <div class="col">
        <form action="/presensi/izin" method="GET">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <select name="bulan" id="bulan" class="selectmaterialize">
                            <option value="">Bulan</option>
                            @for ($i=1; $i<=12; $i++) <option value="{{ $i }}" {{ Request('bulan') == $i ? 'selected' : '' }}>{{ $namabulan[$i] }}</option>
                                @endfor
                        </select>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <select name="tahun" id="tahun" class="selectmaterialize">
                            <option value="">Tahun</option>
                            @php
                            $tahunmulai = 2022;
                            $tahunskrg = date("Y");
                            @endphp
                            @for ($tahun=$tahunmulai; $tahun<= $tahunskrg; $tahun++) <option value="{{ $tahun }}" {{ Request("tahun") == $tahun ? 'selected' : ''  }}>{{ $tahun }}
                                </option>
                                @endfor
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <button class="btn btn-danger btn-block" id="getdata">
                            <ion-icon name="search-outline"></ion-icon> Search
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="row" style="margin-top: 200px; overflow:scroll; height:100%; position:relative; bottom:10%">
    <div class="col">
        @foreach ($dataizin as $d)
        <div class="row mb-1">
            <div class="col">
                <div class="card historiborderred listizin" data-id="{{ $d->kode_izin }}" data-toggle="modal" data-target="#actionSheetIconed">
                    <div class="card-body">
                        <div class="historicontent">
                            <div class="historidetail1">
                                <div class="iconpresence">
                                    <ion-icon name="document-text-outline" style="font-size: 64px;" class="text-danger"></ion-icon>
                                </div>
                                <div class="datepresence">
                                    <h4 class="">{{ DateToIndo2($d->dari) }}
                                        @if ($d->status=="i")
                                        (Izin)
                                        @elseif($d->status=="s")
                                        (Sakit)
                                        @elseif($d->status=="c")
                                        (Cuti)
                                        @endif
                                    </h4>


                                    <small class="text-muted">
                                        {{ date("d-m-Y",strtotime($d->dari)) }}
                                        @if ($d->jenis_izin != "PL" && $d->jenis_izin != "KL")
                                        s/d {{ date("d-m-Y",strtotime($d->sampai)) }}
                                        @else
                                        @if ($d->jenis_izin=="PL")
                                        {{ $d->jam_pulang }}
                                        @elseif($d->jenis_izin=="KL")
                                        {{ $d->jam_keluar }}
                                        @endif
                                        @endif
                                    </small>
                                    <br>
                                    @if ($d->status=="i")
                                    <small>
                                        <span class="badge bg-danger">
                                            @if ($d->jenis_izin=="PL")
                                            Pulang
                                            @elseif($d->jenis_izin=="KL")
                                            Keluar Kantor
                                            @elseif($d->jenis_izin=="TM")
                                            Tidak Masuk Kantor
                                            @endif
                                        </span><br>
                                    </small>
                                    @endif
                                    @if ($d->status=="c")
                                    <small>
                                        <span class="badge bg-danger">
                                            {{ $d->nama_cuti }}
                                        </span><br>
                                    </small>
                                    @endif
                                    {{-- <small class="text-muted">{{ $d->keterangan }}</small> --}}

                                    @if (!empty($d->sid))
                                    <a href="#">
                                        <ion-icon name="document-attach-outline"></ion-icon> SID
                                    </a>
                                    @endif

                                </div>
                            </div>
                            <div class="historidetail2" style="margin-left:20px">

                                @if ($d->status_approved == 0)
                                <span class="badge bg-warning">
                                    <ion-icon name="refresh-outline"></ion-icon>
                                </span>
                                @elseif($d->status_approved==1)
                                <span class="badge bg-success">
                                    Disetujui
                                </span>
                                @elseif($d->status_approved==2)
                                <span class="badge bg-danger">
                                    Ditolak
                                </span>
                                @endif
                                <span class="timepresence">

                                </span>
                                @if ($d->jenis_izin != "PL" && $d->jenis_izin != "KL")
                                <h4 class="mt-1">{{ $d->jmlhari }} Hari</h4>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
<div class="fab-button bottom-right" style="margin-bottom:70px">
    <a href="/presensi/buatizin" class="fab bg-danger">
        <ion-icon name="add-outline"></ion-icon>
    </a>
</div>

<div class="modal fade action-sheet" id="actionSheetIconed" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Aksi</h5>
            </div>
            <div class="modal-body" id="showact">

            </div>
        </div>
    </div>
</div>

<div id="toast-3" class="toast-box toast-bottom">
    <div class="in">
        <ion-icon name="checkmark-circle" class="text-success"></ion-icon>
        <div class="text">
            {{ Session::get('success') }}
        </div>
    </div>
    <button type="button" class="btn btn-sm btn-text-success close-button">CLOSE</button>
</div>
<div class="modal fade dialogbox" id="DialogBasic" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Yakin Dihapus ?</h5>
            </div>
            <div class="modal-body">
                Data Pengajuan Izin Akan dihapus
            </div>
            <div class="modal-footer">
                <div class="btn-inline">
                    <a href="#" class="btn btn-text-secondary" data-dismiss="modal">Batalkan</a>
                    <a href="" class="btn btn-text-primary" id="hapuspengajuan">Hapus</a>
                </div>
            </div>
        </div>
    </div>
</div>
@if (Session::get('success'))
@push('myscript')
<script>
    $(document).ready(function() {
        toastbox('toast-3');
    });

</script>
@endpush
@endif
@endsection
@push('myscript')
<script>
    $(function() {
        $(".listizin").click(function(e) {
            var id = $(this).attr("data-id");
            $("#showact").load('/izin/' + id + "/showact");
        });

        //toastbox('toast-3');
    });

</script>
@endpush
