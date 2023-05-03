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
    <div class="pageTitle">Form Izin</div>
    <div class="right"></div>
</div>
<!-- * App Header -->
@endsection
@section('content')
<div class="row" style="margin-top:70px;">
    <div class="col">
        <form method="POST" action="/presensi/storeizin" id="frmIzin" enctype="multipart/form-data">
            @csrf
            <div class="row" style="margin-bottom: 0">
                <div class="col-12">
                    <div class="form-group">
                        <select name="status" id="status" class="selectmaterialize">
                            <option value="">Permohonan</option>
                            <option value="i">Izin</option>
                            <option value="s">Sakit</option>
                            <option value="c">Cuti</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-bottom: 0" id="jenis_izin_form">
                <div class="col-12">
                    <div class="form-group">
                        <select name="jenis_izin" id="jenis_izin" class="selectmaterialize">
                            <option value="">Jenis Izin</option>
                            <option value="TM">Tidak Masuk Kantor</option>
                            <option value="PL">Pulang</option>
                            <option value="KL">Keluar Kantor</option>
                        </select>
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
            <div class="row" id="jam_pulang_form">
                <div class="col-12">
                    <div class="input-icons">
                        <ion-icon name="alarm-outline" class="icon"></ion-icon>
                        <input type="text" id="jam_pulang" name="jam_pulang" class="form-control input-field timepicker" placeholder="Jam Pulang" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="row" id="jam_keluar_form">
                <div class="col-12">
                    <div class="input-icons">
                        <ion-icon name="alarm-outline" class="icon"></ion-icon>
                        <input type="text" id="jam_keluar" name="jam_keluar" class="form-control input-field timepicker" placeholder="Jam Keluar" autocomplete="off">
                    </div>
                </div>
            </div>
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

            <div class="custom-file-upload" id="fileUpload1" style="height: 100px !important">
                <input type="file" name="sid" id="fileuploadInput" accept=".png, .jpg, .jpeg">
                <label for="fileuploadInput">
                    <span>
                        <strong>
                            <ion-icon name="cloud-upload-outline" role="img" class="md hydrated" aria-label="cloud upload outline"></ion-icon>
                            <i>Tap to Upload SID</i>
                        </strong>
                    </span>
                </label>
            </div>
            <div class="row" style="margin-bottom: 0" id="jml_hari_frm">
                <div class="col-12">
                    <div class="input-icons">
                        <ion-icon name="calendar-number-outline" class="icon"></ion-icon>
                        <input type="text" name="jmlhari" class="form-control input-field" id="jmlhari" placeholder="Jumlah Hari" readonly>
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

        function hidesid() {
            $("#fileUpload1").hide();
        }

        function showsid() {
            $("#fileUpload1").show();
        }


        function hidejenisizin() {
            $("#jenis_izin_form").hide();
        }

        function showjenisizin() {
            $("#jenis_izin_form").show();
        }

        function hidejeniscuti() {
            $("#jenis_cuti_form").hide();
        }

        function showjeniscuti() {
            $("#jenis_cuti_form").show();
        }

        function hidejampulang() {
            $("#jam_pulang_form").hide();
        }

        function showjampulang() {
            $("#jam_pulang_form").show();

        }

        function hidejamkeluar() {
            $("#jam_keluar_form").hide();
        }

        function showjamkeluar() {
            $("#jam_keluar_form").show();

        }
        hidesid();
        hidejenisizin();
        hidejeniscuti();
        hidejampulang();
        hidejamkeluar();

        $(".datepicker").datepicker({
            format: "yyyy-mm-dd"
            , minDate: null
        });
        $('.timepicker').timepicker({

            twelveHour: false
            , fromNow: 0
        });

        $("#status").change(function() {
            var status = $(this).val();
            if (status == "s") {
                showsid();
            } else {
                hidesid();
            }

            if (status == "i") {
                showjenisizin();
            } else {
                hidejenisizin();
            }

            if (status == "c") {
                showjeniscuti();
            } else {
                hidejeniscuti();
            }
        });

        $("#jenis_izin").change(function() {
            var jenis_izin = $(this).val();
            if (jenis_izin == "PL") {
                showjampulang();
            } else {
                hidejampulang();

            }

            if (jenis_izin == "KL") {
                showjamkeluar();
            } else {
                hidejamkeluar();
            }

            if (jenis_izin == "PL" || jenis_izin == "KL") {
                $("#jml_hari_frm").hide();
                $("#dari").val("{{ date('Y-m-d') }}");
                $("#sampai").val("{{ date('Y-m-d') }}");
                $("#dari").prop('disabled', true);
                $("#sampai").prop('disabled', true);
            } else {
                $("#jml_hari_frm").show();
                $("#dari").val("");
                $("#sampai").val("");
                $("#dari").prop('disabled', false);
                $("#sampai").prop('disabled', false);
            }
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



        // $("#dari").change(function(e) {
        //     loadjumlahhari();
        // });

        $("#sampai").change(function(e) {
            // var sampai = $(this).val();
            // var jenis_cuti = $("#jenis_cuti").val();
            // if (jenis_cuti == "C02") {
            //     Swal.fire({
            //         title: 'Oops !'
            //         , text: 'Tidak Dapat Merubah Tanggal Akhir Untuk Cuti Melahirkan'
            //         , icon: 'warning'
            //     });

            //     $("#sampai").val(sampai);
            // }
            loadjumlahhari();
        });

        $("#frmIzin").submit(function() {
            var dari = $("#dari").val();
            var sampai = $("#sampai").val();
            var status = $("#status").val();
            var keterangan = $("#keterangan").val();
            var jenis_izin = $("#jenis_izin").val();
            if (dari == "") {
                Swal.fire({
                    title: 'Oops !'
                    , text: 'Tanggal Harus Diisi'
                    , icon: 'warning'
                });
                return false;
            } else if (sampai == "") {
                Swal.fire({
                    title: 'Oops !'
                    , text: 'Sampai Harus Diisi'
                    , icon: 'warning'
                });
                return false;
            } else if (status == "") {
                Swal.fire({
                    title: 'Oops !'
                    , text: 'Status Harus Diisi'
                    , icon: 'warning'
                });
                return false;
            } else if (status == "i" && jenis_izin == "") {
                Swal.fire({
                    title: 'Oops !'
                    , text: 'Jenis Izin Harus Diisi'
                    , icon: 'warning'
                });
                return false;
            } else if (keterangan == "") {
                Swal.fire({
                    title: 'Oops !'
                    , text: 'Ketereangan Harus Diisi'
                    , icon: 'warning'
                });
                return false;
            }
        });

        function gettanggal() {
            var tanggal = $("#dari").val();
            var someDate = new Date(tanggal);
            var numberOfDaysToAdd = 89;
            var result = someDate.setDate(someDate.getDate() + numberOfDaysToAdd);
            var str = (new Date(result)).toLocaleDateString('en-CA');
            $("#sampai").val(str);
            console.log(str)
        }

        $("#dari").change(function(e) {
            var jenis_cuti = $("#jenis_cuti").val();
            if (jenis_cuti == "C02") {
                gettanggal();
            }
            loadjumlahhari();
        });

        $("#jenis_cuti").change(function() {
            var jenis_cuti = $("#jenis_cuti").val();
            if (jenis_cuti == "C02") {
                gettanggal();
                loadjumlahhari();
            } else {
                $("#sampai").val("");
            }
        });



    });

</script>
@endpush
