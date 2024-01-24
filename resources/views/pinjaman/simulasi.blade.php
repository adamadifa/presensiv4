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
            <form method="POST" action="/pinjaman/store" id="frmPinjaman">
                @csrf
                <input type="hidden" id="cekpembayaran">
                <div class="row">
                    <div class="col-12">
                        <table class="table table-bordered">
                            <tr>
                                <th>NIK</th>
                                <td>
                                    <input type="hidden" name="nik" id="nik" value="{{ $karyawan->nik }}">
                                    {{ $karyawan->nik }}
                                </td>
                            </tr>
                            <tr>
                                <th>Nama Karyawan</th>
                                <td>{{ $karyawan->nama_karyawan }}</td>
                            </tr>
                            <tr>
                                <th>Departmen</th>
                                <td>{{ $karyawan->nama_dept }}</td>
                            </tr>

                            <tr>
                                <th>Masa Kerja</th>
                                <td>
                                    @php
                                        $awal = date_create($karyawan->tgl_masuk);
                                        $akhir = date_create(date('Y-m-d')); // waktu sekarang
                                        $diff = date_diff($awal, $akhir);
                                        echo $diff->y . ' tahun, ' . $diff->m . ' bulan, ' . $diff->d . ' Hari';
                                    @endphp
                                </td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <input type="hidden" name="status_karyawan" id="status_karyawan"
                                        value="{{ $karyawan->status_karyawan }}">
                                    {{ $karyawan->status_karyawan == 'T' ? 'Karyawan Tetap' : 'Karyawan Kontrak' }}
                                </td>
                            </tr>
                            @if ($karyawan->status_karyawan == 'K')
                                <tr>
                                    <th>Akhir Kontrak</th>
                                    <td>
                                        <input type="hidden" name="akhir_kontrak" id="akhir_kontrak"
                                            value="{{ $kontrak->sampai }}">
                                        {{ $kontrak != null ? DateToIndo2($kontrak->sampai) : '' }}
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <th>Gaji</th>
                                <td style="text-align: right">
                                    <input type="hidden" name="gapok_tunjangan" id="gapok_tunjangan"
                                        value="{{ $gaji->gajitunjangan }}">
                                    {{ rupiah($gaji->gajitunjangan) }}
                                </td>
                            </tr>

                            <tr>
                                <th>Tenor Maksimal</th>
                                <td>
                                    <?php
                                    if ($karyawan->status_karyawan == 'T') {
                                        $tenormax = 20;
                                    } else {
                                        $tglpinjaman = date_create(date('Y-m-d')); // waktu sekarang
                                        $akhirkontrak = date_create($kontrak != null ? $kontrak->sampai : date('Y-m-d'));
                                        $sisakontrak = date_diff($tglpinjaman, $akhirkontrak);
                                        $tenormax = $sisakontrak->m;
                                    }
                                    ?>
                                    {{ $tenormax }} Bulan
                                    <input type="hidden" name="tenor_max" id="tenor_max" value="{{ $tenormax }}">
                                </td>
                            </tr>
                            <tr>
                                <th style="width:40%">Angsuran Maksimal</th>
                                <td style="text-align:right">
                                    @php
                                        $angsuranmax = (40 / 100) * $gaji->gajitunjangan;
                                    @endphp
                                    {{ rupiah($angsuranmax) }}
                                    <input type="hidden" name="angsuran_max" id="angsuran_max"
                                        value="{{ $angsuranmax }}">
                                </td>
                            </tr>
                            <tr>
                                <th>Plafon</th>
                                <td style="text-align: right">
                                    @php
                                        $plafon = $angsuranmax * $tenormax;
                                    @endphp
                                    <input type="hidden" name="plafon" id="plafon"
                                        value="{{ $angsuranmax * $tenormax }}">
                                    {{ rupiah($angsuranmax * $tenormax) }}
                                </td>
                            </tr>
                            <tr>
                                <th>JMK</th>
                                <td style="text-align: right">
                                    <?php
                                    $masakerja = $diff->y;
                                    if ($masakerja >= 3 && $masakerja < 6) {
                                        $jmlkali = 2;
                                    } elseif ($masakerja >= 6 && $masakerja < 9) {
                                        $jmlkali = 3;
                                    } elseif ($masakerja >= 9 && $masakerja < 12) {
                                        $jmlkali = 4;
                                    } elseif ($masakerja >= 12 && $masakerja < 15) {
                                        $jmlkali = 5;
                                    } elseif ($masakerja >= 15 && $masakerja < 18) {
                                        $jmlkali = 6;
                                    } elseif ($masakerja >= 18 && $masakerja < 21) {
                                        $jmlkali = 7;
                                    } elseif ($masakerja >= 21 && $masakerja < 24) {
                                        $jmlkali = 8;
                                    } elseif ($masakerja >= 24) {
                                        $jmlkali = 10;
                                    } else {
                                        $jmlkali = 0.5;
                                    }
                                    
                                    if ($masakerja <= 2) {
                                        $totaljmk = $jmlkali * $gaji->gaji_pokok;
                                    } else {
                                        $totaljmk = $gaji->gajitunjangan * $jmlkali;
                                    }
                                    ?>

                                    {{ rupiah($totaljmk) }}
                                    <input type="hidden" name="jmk" id="jmk" value="{{ $totaljmk }}">
                                </td>
                            </tr>
                            <tr>
                                <th>JMK Sudah Dibayar</th>
                                <td style="text-align: right">
                                    {{ rupiah($jmk != null ? $jmk->jml_jmk : 0) }}
                                    <input type="hidden" name="jmk_sudahbayar" id="jmk_sudahbayar"
                                        value="{{ $jmk != null ? $jmk->jml_jmk : 0 }}">
                                </td>
                            </tr>
                            <tr>
                                <th style="width:40%">Plafon Maksimal</th>
                                <td style="text-align:right">
                                    @php
                                        // $plafonmax = ((40/100) * $gaji->gajitunjangan )* 20;
                                        $jmksudahdibayar = $jmk != null ? $jmk->jml_jmk : 0;
                                        $plafonjmk = $totaljmk - $jmksudahdibayar;
                                    $plafonmax = $plafonjmk < $plafon ? $plafonjmk : $plafon; @endphp {{ rupiah($plafonmax) }} <input type="hidden" name="plafon_max"
                                        id="plafon_max" value="{{ $plafonmax }}">
                                </td>
                            </tr>

                        </table>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 0">
                    <div class="col">
                        <div class="input-icons">
                            <ion-icon name="calendar-outline" class="icon"></ion-icon>
                            <input type="text" id="tgl_pinjaman" name="tgl_pinjaman"
                                class="form-control datepicker input-field" placeholder="Tanggal Pinjaman"
                                autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 0">
                    <div class="col">
                        <div class="input-icons">
                            <ion-icon name="document-outline" class="icon"></ion-icon>
                            <input type="text" id="jml_pinjaman" style="text-align: right" name="jml_pinjaman"
                                class="form-control  input-field" placeholder="Jumlah Pinjaman" autocomplete="off">
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-bottom: 0">
                    <div class="col">
                        <div class="input-icons">
                            <ion-icon name="document-outline" class="icon"></ion-icon>
                            <input type="text" id="angsuran" name="angsuran" class="form-control  input-field"
                                placeholder="Angsuran" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 0">
                    <div class="col">
                        <div class="input-icons">
                            <ion-icon name="document-outline" class="icon"></ion-icon>
                            <input type="text" id="jml_angsuran" name="jml_angsuran"
                                class="form-control  input-field" placeholder="Jumlah Angsuran / Bulan"
                                autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 0">
                    <div class="col">
                        <div class="input-icons">
                            <ion-icon name="calendar-outline" class="icon"></ion-icon>
                            <input type="text" id="mulai_cicilan" style="text-align: right" name="mulai_cicilan"
                                class="form-control  input-field" placeholder="Mulai Cicilan" autocomplete="off">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('myscript')
    <script>
        $(function() {
            $(".datepicker").datepicker({
                format: "yyyy-mm-dd",
                minDate: null
            });

            $("#jml_pinjaman,#jml_angsuran").maskMoney();

            function convertToRupiah(number) {
                if (number) {
                    var rupiah = "";
                    var numberrev = number
                        .toString()
                        .split("")
                        .reverse()
                        .join("");
                    for (var i = 0; i < numberrev.length; i++)
                        if (i % 3 == 0) rupiah += numberrev.substr(i, 3) + ".";
                    return (
                        rupiah
                        .split("", rupiah.length - 1)
                        .reverse()
                        .join("")
                    );
                } else {
                    return number;
                }
            }


            $("#tgl_pinjaman").change(function(e) {
                var tgl_pinjaman = $(this).val();
                var tanggal = tgl_pinjaman.split("-");
                var tgl = tanggal[2];
                var bulan = tanggal[1];
                var tahun = tanggal[0];
                if (tgl == 19 || tgl == 20) {
                    Swal.fire({
                        title: 'Oops',
                        text: 'Tidak Bisa Melakukan Pinjaman Pada Tanggal 19 dan 20 !',
                        icon: 'warning',
                        showConfirmButton: false
                    }).then(function() {
                        $("#mulai_cicilan").val("");
                        $("#tgl_pinjaman").val("");
                    });
                    $("#mulai_cicilan").val("");
                    $("#tgl_pinjaman").val("");
                } else {
                    if (tgl <= 18 && bulan <= 10) {
                        var nextbulan = parseInt(bulan) + 1;
                        var nexttahun = parseInt(tahun);
                    } else if (tgl <= 18 && bulan == 12) {
                        var nextbulan = 1;
                        var nexttahun = parseInt(tahun) + 1;
                    } else if (parseInt(tgl) >= 21 && parseInt(bulan) <= 10) {
                        var nextbulan = parseInt(bulan) + 2;
                        var nexttahun = parseInt(tahun);
                    } else if (parseInt(tgl) <= 18 && parseInt(bulan) > 10) {
                        var nextbulan = parseInt(bulan) + 1;
                        var nexttahun = parseInt(tahun);
                    } else if (parseInt(tgl) >= 21 && parseInt(bulan) == 12) {
                        var nextbulan = 2;
                        var nexttahun = parseInt(tahun) + 1;
                    } else if (parseInt(tgl) >= 21 && parseInt(bulan) < 10) {
                        var nextbulan = 1;
                        var nexttahun = parseInt(tahun) + 1;
                    }

                    if (nextbulan <= 9) {
                        var nextbulan = "0" + nextbulan;
                    }
                    var mulai_cicilan = nexttahun + "-" + nextbulan + "-01";
                    $("#mulai_cicilan").val(mulai_cicilan);
                }

            });


            function hitungpinjaman() {
                var jmlpinjaman = $("#jml_pinjaman").val();
                var jmlangsuran = $("#jml_angsuran").val();
                var angsuran = $("#angsuran").val();
                var plafonmax = "{{ $plafonmax }}";
                var angsuranmax = "{{ $angsuranmax }}";
                var tenormax = "{{ $tenormax }}";

                if (jmlpinjaman.length === 0) {
                    var jmlpinjaman = 0;
                } else {
                    var jmlpinjaman = parseInt(jmlpinjaman.replace(/\./g, ''));
                }

                if (angsuran.length === 0) {
                    var angsuran = 0;
                } else {
                    var angsuran = parseInt(angsuran.replace(/\./g, ''));
                }

                if (jmlangsuran.length === 0) {
                    var jmlangsuran = 0;
                } else {
                    var jmlangsuran = parseInt(jmlangsuran.replace(/\./g, ''));
                }

                if (parseInt(jmlpinjaman) > parseInt(plafonmax)) {
                    Swal.fire({
                        title: 'Oops',
                        text: 'Jumlah Pinjaman Melebihi Plafon !',
                        icon: 'warning',
                        showConfirmButton: false
                    }).then(function() {
                        $("#jml_pinjaman").val(0);
                        $("#jml_angsuran").val(0);
                    });

                } else {

                    var angsuranperbulan = parseInt(angsuran) != 0 ? parseInt(jmlpinjaman) / parseInt(angsuran) : 0;
                    var cekangsuran = Number.isInteger(angsuranperbulan);
                    if (!cekangsuran) {
                        angsuranperbulan = Math.floor(angsuranperbulan / 1000) * 1000;
                    }
                    $("#jml_angsuran").val(convertToRupiah(angsuranperbulan));
                }

                if (parseInt(angsuran) > parseInt(tenormax)) {
                    Swal.fire({
                        title: 'Oops',
                        text: 'Angsuran Tidak Boleh Lebih dari !' + tenormax,
                        icon: 'warning',
                        showConfirmButton: false
                    }).then(function() {
                        $("#angsuran").val(0);
                        $("#jml_angsuran").val(0);
                    });

                }

            }

            $("#jml_pinjaman,#angsuran").on('keyup', function() {
                hitungpinjaman();
            });
        });
    </script>
@endpush
