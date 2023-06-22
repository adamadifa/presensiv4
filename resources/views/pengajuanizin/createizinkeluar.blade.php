@extends('layouts.presensi')
@section('header')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
<style>
    .modal {
        height: auto !important;
    }

    .datepicker-modal {
        max-height: auto !important;
    }

    .datepicker-date-display {
        background-color: #9c0000 !important;
    }



    .timepicker-digital-display {
        height: 100px !important;
        background-color: #9c0000 !important;
        color: white !important;
    }

    .timepicker-text-container {
        margin-top: 30px !important;
    }

    .timepicker-span-hours,
    .timepicker-span-minutes {
        color: white !important;
    }

    .timepicker-close {
        color: #9c0000 !important;
    }

    .datepicker-cancel,
    .datepicker-clear,
    .datepicker-today,
    .datepicker-done {
        color: #9c0000 !important;
        padding: 0 1rem;
    }

</style>
<style>
    .input-icons ion-icon {
        position: absolute;
    }

    .input-icons {
        width: 100%;
    }

    .icon {
        padding: 5px;
        min-width: 10px;
        margin-top: 8px;
        font-size: 18px;
    }

    .input-field {
        width: 100%;
        padding-left: 30px !important;
    }

</style>
<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Izin Keluar</div>
    <div class="right"></div>
</div>
<!-- * App Header -->
@endsection
@section('content')
<div class="row" style="margin-top:70px;">
    <div class="col">
        <form method="POST" action="/pengajuanizin/store" id="frmPengajuanizin" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="status" value="i">
            <input type="hidden" name="jenis_izin" value="KL">

            <div class="row" style="margin-bottom:0 !important">
                <div class="col-12">
                    <div class="input-icons">
                        <ion-icon name="calendar-outline" class="icon"></ion-icon>
                        <input type="text" id="dari" name="dari" class="form-control datepicker input-field" placeholder="Tanggal" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="input-icons">
                        <ion-icon name="alarm-outline" class="icon"></ion-icon>
                        <input type="text" id="jam_keluar" name="jam_keluar" class="form-control input-field timepicker" placeholder="Jam Keluar (HH:MM)" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <textarea autocomplete="off" name="keterangan" id="keterangan" cols="30" rows="4" placeholder="Keterangan" placeholder="Keterangan"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="form-group" style="margin-top: 50px !important">
                        <button class="btn btn-danger w-100">Kirim</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@push('myscript')
<script>
    var currYear = (new Date()).getFullYear();
    $(document).ready(function() {
        $(".datepicker").datepicker({
            format: "yyyy-mm-dd"
            , minDate: null
        });
        $('.timepicker').timepicker({

            twelveHour: false
            , fromNow: 0
        });
    });

</script>
@endpush
