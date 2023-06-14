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
@endsection
@section('content')
<div class="row" style="margin-top: 70px">
    <div class="col">
        <div class="alert alert-danger" role="alert">
            <h4 class="alert-title">Warning</h4>
            <p>Maaf, Tidak Ada Jadwal Kerja Untuk Anda Saat Ini, Silahkan Hubungi PIC
            </p>
        </div>
    </div>
</div>
@endsection
