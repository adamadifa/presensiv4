@extends('layouts.presensi')
@section('header')
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">SID {{ $id }}</div>
    <div class="right"></div>
</div>
<!-- * App Header -->
@endsection
@section('content')
<div class="row" style="margin-top:70px">
    <div class="col">
        @php
        $path = Storage::url('uploads/sid/'.$izin->sid);
        @endphp
        <img src="{{ url($path) }}" alt="" style="width: 100%">
    </div>
</div>
@endsection
