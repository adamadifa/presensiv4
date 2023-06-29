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
        <div class="alert alert-warning" role="alert">
            <h4 class="alert-title">Warning</h4>
            <p>Hari Ini, Anda Tidak Bisa Melakukan Presensi Karena Hari ini adalah Hari Libur Minggu</p>
        </div>
    </div>
</div>
@endsection
