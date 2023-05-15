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
    $tgl2   = substr($date2, 8, 2); // memisahkan format tanggal menggunakan substring

    $result = $tgl2 . " " . $BulanIndo2[(int)$bulan2 - 1] . " " . $tahun2;
    return ($result);
}

function desimal($nilai)
{

    return number_format($nilai, '2', ',', '.');
}
function desimal3($nilai)
{
    return number_format($nilai, '3', ',', '.');
}

function rupiah($nilai)
{

    return number_format($nilai, '0', ',', '.');
}

?>
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
        width: 90%;
        margin-right: 30px !important;
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
<style>
    body {
        margin-bottom: 15% !important;
    }

</style>
<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Simulasi Pinjaman</div>
    <div class="right"></div>
</div>
<!-- * App Header -->
@endsection
@section('content')
<div class="row" style="margin-top: 70px">
    <div class="col">
        <div class="alert alert-danger" role="alert">
            <h4 class="alert-title">Warning</h4>
            <p>Karyawan Tidak Dapat Mengajukan Pinjaman Karena Masih Memiliki Pinjaman Yang Belum Lunas, Untuk Melakukan Pinjaman Kembali Min. Sudah Membayar 75% dari Total Pinjaman Sebelumnya
                <br>
                <table class="table" style="color:white !important">
                    <tr>
                        <th>No. Pinjaman</th>
                        <td>{{ $cekpinjaman->no_pinjaman }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td>{{ $cekpinjaman->tgl_pinjaman }}</td>
                    </tr>
                    <tr>
                        <th>Jumlah Pinjaman</th>
                        <td style="text-align:right">{{ rupiah($cekpinjaman->jumlah_pinjaman) }}</td>
                    </tr>
                    <tr>
                        <th>Total Pembayaran</th>
                        <td style="text-align:right">{{ rupiah($cekpinjaman->totalpembayaran) }} ({{ $cekpinjaman->totalpembayaran / $cekpinjaman->jumlah_pinjaman * 100 }}%) </td>
                    </tr>
                </table>
            </p>
        </div>
    </div>
</div>
@endsection
