@extends('layouts.presensi')
@section('header')
<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">E-Presensi</div>
    <div class="right"></div>
</div>
<!-- * App Header -->
@endsection
@section('content')
<div class="row" style="margin-top: 70px">
    <div class="col">
        <div class="alert alert-info" role="alert">
            <h4 class="alert-title">Warning</h4>
            <p>Hari Ini, Anda Tidak Bisa Melakukan Presensi Karena Hari ini Anda Sedang WFH</p>
            <p>
                <table class="table" style="color:white">
                    <tr>
                        <th>Kode Libur</th>
                        <td>{{ $cekwfhhariini->kode_libur }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal WFH</th>
                        <td>{{ date("d-m-Y",strtotime($cekwfhhariini->tanggal_libur)) }}</td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td>
                            @if ($cekwfhhariini->kategori==1)
                            <span class="badge bg-success">Libur Nasional</span>
                            @elseif($cekwfhhariini->kategori==2)
                            <span class="badge bg-info">Libur Pengganti Minggu</span>
                            @elseif($cekwfhhariini->kategori==3)
                            <span class="badge bg-warning">WFH</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Kode Libur</th>
                        <td>{{ $cekwfhhariini->keterangan }}</td>
                    </tr>
                </table>
            </p>
        </div>
    </div>
</div>
@endsection
