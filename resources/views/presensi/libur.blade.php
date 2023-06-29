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
        <div class="alert alert-danger" role="alert">
            <h4 class="alert-title">Warning</h4>
            <p>Hari Ini, Anda Tidak Bisa Melakukan Presensi Karena Hari ini Anda Sedang Libur</p>
            <p>
                <table class="table" style="color:white">
                    <tr>
                        <th>Kode Libur</th>
                        <td>{{ $cekliburhariini->kode_libur }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Libur</th>
                        <td>{{ date("d-m-Y",strtotime($cekliburhariini->tanggal_libur)) }}</td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td>
                            @if ($cekliburhariini->kategori==1)
                            <span class="badge bg-success">Libur Nasional</span>
                            @elseif($cekliburhariini->kategori==2)
                            <span class="badge bg-info">Libur Pengganti Minggu</span>
                            @elseif($cekliburhariini->kategori==3)
                            <span class="badge bg-warning">WFH</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Kode Libur</th>
                        <td>{{ $cekliburhariini->keterangan }}</td>
                    </tr>
                </table>
            </p>
        </div>
    </div>
</div>
@endsection
