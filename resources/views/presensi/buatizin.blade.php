@extends('layouts.presensi')
@section('header')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
<style>
    .datepicker-modal {
        max-height: 460px !important;
    }

    .datepicker-date-display {
        background-color: #0f3a7e !important;
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
<div class="row" style="margin-top:70px">
    <div class="col">
        <form method="POST" action="/presensi/storeizin" id="frmIzin" enctype="multipart/form-data">
            @csrf
            <div class="row" style="margin-bottom:0 !important">
                <div class="col-6">
                    <div class="form-group">
                        <input type="text" id="dari" name="dari" class="form-control datepicker" placeholder="Dari">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <input type="text" id="sampai" name="sampai" class="form-control datepicker" placeholder="Sampai">
                    </div>
                </div>
            </div>
            <div class="row" style="margin-bottom: 0">
                <div class="col-12">
                    <div class="form-group">
                        <select name="status" id="status" class="form-control">
                            <option value="">Permohonan</option>
                            <option value="i">Izin</option>
                            <option value="s">Sakit</option>
                            <option value="c">Cuti</option>
                        </select>
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
            <div class="row" style="margin-bottom: 0">
                <div class="form-group">
                    <div class="col-12">
                        <input type="text" name="jmlhari" class="form-control" id="jmlhari" placeholder="Jumlah Hari" readonly>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <textarea name="keterangan" id="keterangan" cols="30" rows="5" class="form-control" placeholder="Keterangan"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
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
            $("#sidupload").hide();
        }

        function showsid() {
            $("#sidupload").show();
        }

        hidesid();
        $(".datepicker").datepicker({
            format: "yyyy-mm-dd"
        });

        $("#status").change(function() {
            var status = $(this).val();
            if (status == "s") {
                showsid();
            } else {
                hidesid();
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



        $("#dari").change(function(e) {
            loadjumlahhari();
        });

        $("#sampai").change(function(e) {
            loadjumlahhari();
        });

        $("#frmIzin").submit(function() {
            var dari = $("#dari").val();
            var sampai = $("#sampai").val();
            var status = $("#status").val();
            var keterangan = $("#keterangan").val();
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
            } else if (keterangan == "") {
                Swal.fire({
                    title: 'Oops !'
                    , text: 'Ketereangan Harus Diisi'
                    , icon: 'warning'
                });
                return false;
            }
        });
    });

</script>
@endpush
