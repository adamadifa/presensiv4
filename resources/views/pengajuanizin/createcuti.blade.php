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
    <div class="pageTitle">Cuti</div>
    <div class="right"></div>
</div>
<!-- * App Header -->
@endsection
@section('content')
<div class="row" style="margin-top:70px;">
    <div class="col">
        <form method="POST" action="/pengajuanizin/store" id="frmPengajuanizin" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="status" value="c">
            <div class="row" style="margin-bottom:0 !important">
                <div class="col-5">
                    <div class="input-icons">
                        <ion-icon name="calendar-outline" class="icon"></ion-icon>
                        <input type="text" id="dari" name="dari" class="form-control datepicker input-field" placeholder="Dari" autocomplete="off">
                    </div>
                </div>
                <div class="col-5" style="margin-left: 30px !important">
                    <div class="input-icons">
                        <ion-icon name="calendar-outline" class="icon"></ion-icon>
                        <input type="text" id="sampai" name="sampai" class="form-control datepicker input-field" placeholder="Sampai" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="row" style="margin-bottom: 0" id="jenis_cuti_form">
                <div class="col-12">
                    <div class="form-group">
                        <select name="jenis_cuti" id="jenis_cuti" class="selectmaterialize">
                            <option value="">Jenis Cuti</option>
                            @foreach ($mastercuti as $d)
                            <option value="{{ $d->kode_cuti }}">{{ $d->nama_cuti }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row" style="margin-bottom: 0" id="kategori_cuti_khusus_form">
                <div class="col-12">
                    <div class="form-group">
                        <select name="kat_cuti_khusus" id="kat_cuti_khusus" class="selectmaterialize">
                            <option value="">Kategori Cuti</option>
                            @foreach ($mastercutikhusus as $d)
                            <option value="{{ $d->kode_cuti_khusus }}">{{ $d->nama_cuti_khusus }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-bottom: 0" id="jml_hari_frm">
                <div class="col-12">
                    <div class="input-icons">
                        <ion-icon name="calendar-number-outline" class="icon"></ion-icon>
                        <input type="text" name="jmlhari" class="form-control input-field" id="jmlhari" placeholder="Jumlah Hari" readonly>
                    </div>
                </div>
            </div>
            <div class="custom-file-upload" id="fileUpload1" style="height: 100px !important">
                <input type="file" name="doccuti" id="fileuploadInput" accept=".png, .jpg, .jpeg">
                <label for="fileuploadInput">
                    <span>
                        <strong>
                            <ion-icon name="cloud-upload-outline" role="img" class="md hydrated" aria-label="cloud upload outline"></ion-icon>
                            <i>Tap to Upload Document</i>
                        </strong>
                    </span>
                </label>
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

        function loadjumlahhari() {
            var dari = $("#dari").val();
            var sampai = $("#sampai").val();
            var date1 = new Date(dari);
            var date2 = new Date(sampai);

            // To calculate the time difference of two dates
            var Difference_In_Time = date2.getTime() - date1.getTime();

            // To calculate the no. of days between two dates
            var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);

            //To display the final no. of days (result)
            $("#jmlhari").val(Difference_In_Days + 1);
        }

        $("#dari,#sampai").change(function(e) {
            loadjumlahhari();
        });

        function loadcutikhusus() {
            var jenis_cuti = $("#jenis_cuti").val();
            if (jenis_cuti == "C03") {
                $("#kategori_cuti_khusus_form").show();
            } else {
                $("#kategori_cuti_khusus_form").hide();
            }
        }

        loadcutikhusus();

        $("#jenis_cuti").click(function(e) {
            loadcutikhusus();
        });

        $("#frmPengajuanizin").submit(function() {
            var dari = $("#frmPengajuanizin").find("#dari").val();
            var sampai = $("#frmPengajuanizin").find("#sampai").val();
            var keterangan = $("#keterangan").val();
            var jenis_cuti = $("#jenis_cuti").val();
            var kat_cuti_khusus = $("#kat_cuti_khusus").val();
            if (jenis_cuti == "") {
                Swal.fire({
                    title: 'Warning !'
                    , text: 'Jenis Cuti Harus Dipilih'
                    , icon: 'warning'
                })
                return false;
            } else if (jenis_cuti == "C03" && kat_cuti_khusus == "") {
                Swal.fire({
                    title: 'Warning !'
                    , text: 'Kategori Cuti Harus Dipilih'
                    , icon: 'warning'
                })

                return false;
            } else if (dari == "" || sampai == "") {
                Swal.fire({
                    title: 'Warning !'
                    , text: 'Tanggal Cuti Harus Diisi'
                    , icon: 'warning'
                })
                return false;
            } else if (keterangan == "") {
                Swal.fire({
                    title: 'Warning !'
                    , text: 'Keterangan Cuti Harus DIisi'
                    , icon: 'warning'
                })
                return false;
            }
        });
    });

</script>
@endpush
