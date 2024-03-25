@extends('layouts.presensi')
@section('header')
    <style>
        body {
            margin-bottom: 15% !important;
        }

        .card .card-body {
            padding: 15px 10px 10px 5px !important;
        }

        /* .historicontent {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            justify-content: left !important;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        } */
    </style>
    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">THR TAHUN {{ $tahun }}</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->
@endsection
@section('content')
    <div class="row" style="margin-top: 70px; overflow:scroll; height:100%; position:relative; bottom:10%">
        <div class="col">
            <table class="table">
                <tr>
                    <th>NIK</th>
                    <td>{{ $karyawan->nik }}</td>
                </tr>
                <tr>
                    <th>Nama Karyawan</th>
                    <td>{{ $karyawan->nama_karyawan }}</td>
                </tr>
                <tr>
                    <th>Departemen</th>
                    <td>{{ $karyawan->nama_dept }}</td>
                </tr>
                <tr>
                    <th>Kantor</th>
                    <td>{{ $karyawan->nama_cabang }}</td>
                </tr>
                <tr>
                    <th>Jabatan</th>
                    <td>{{ $karyawan->nama_jabatan }}</td>
                </tr>
            </table>
        </div>
    </div>
@endsection
