@extends('layouts.presensi')
@section('header')
    <style>
        body {
            margin-bottom: 15% !important;
        }

        .card .card-body {
            padding: 15px 10px 10px 5px !important;
        }
    </style>
    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Slip Gaji {{ $namabulan[$bulan * 1] }} {{ $tahun }}</div>
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
                    <td>{{ $slip_gaji->nik }}</td>
                </tr>
                <tr>
                    <th>Nama Karyawan</th>
                    <td>{{ $slip_gaji->nama_karyawan }}</td>
                </tr>
                <tr>
                    <th>Departemen</th>
                    <td>{{ $slip_gaji->nama_dept }}</td>
                </tr>
                <tr>
                    <th>Kantor</th>
                    <td>{{ $slip_gaji->nama_cabang }}</td>
                </tr>
                <tr>
                    <th>Jabatan</th>
                    <td>{{ $slip_gaji->nama_jabatan }}</td>
                </tr>
            </table>
            <hr>
            <table class="table">
                <tr>
                    <th>Gaji Pokok</th>
                    <td class="text-right">{{ rupiah($slip_gaji->gaji_pokok) }}</td>
                </tr>
                <tr>
                    <th>Tunj. Jabatan</th>
                    <td class="text-right">{{ rupiah($slip_gaji->t_jabatan) }}</td>
                </tr>
                <tr>
                    <th>Tunj. Masa Kerja</th>
                    <td class="text-right">{{ rupiah($slip_gaji->t_masa_kerja) }}</td>
                </tr>
                <tr>
                    <th>Tunj. Tanggung Jawab</th>
                    <td class="text-right">{{ rupiah($slip_gaji->t_tanggung_jawab) }}</td>
                </tr>
                <tr>
                    <th>Tunj. Makan</th>
                    <td class="text-right">{{ rupiah($slip_gaji->t_makan) }}</td>
                </tr>

                @if ($slip_gaji->kategori == 'MJ')
                    <tr>
                        <th>Tunj. Istri</th>
                        <td class="text-right">{{ rupiah($slip_gaji->t_istri) }}</td>
                    </tr>
                @endif

                <tr>
                    <th>Tunj. Skill</th>
                    <td class="text-right">{{ rupiah($slip_gaji->t_skill) }}</td>
                </tr>

            </table>
            <hr>
            <table class="table">
                <tr>
                    <th>∑ JAM KERJA BULAN INI</th>
                    <td style="font-weight: bold">{{ desimal($slip_gaji->jml_jamkerja) }} JAM</td>
                </tr>
                <tr>
                    <th>UPAH / JAM</th>
                    <td class="text-right">{{ desimal($slip_gaji->upah_perjam) }}</td>
                </tr>
            </table>
            <hr>
            <table class="table">
                <tr>
                    <th>UPAH BULAN INI</th>
                    <td style="font-weight: bold; text-align:right">{{ rupiah($slip_gaji->upah) }}</td>
                </tr>
            </table>
            <hr>
            <table class="table">
                <tr>
                    <th>Overtime 1</th>
                    <td class="text-center">{{ desimal($slip_gaji->overtime_1_jam) }}</td>
                    <td>JAM</td>
                    <td class="text-right">{{ rupiah($slip_gaji->overtime_1_jumlah) }}</td>
                </tr>
                <tr>
                    <th>Overtime 2</th>
                    <td class="text-center">{{ desimal($slip_gaji->overtime_2_jam) }}</td>
                    <td>JAM</td>
                    <td class="text-right">{{ rupiah($slip_gaji->overtime_2_jumlah) }}</td>
                </tr>
                <tr>
                    <th>Lembur Hari Libur</th>
                    <td class="text-center">{{ desimal($slip_gaji->overtime_libur_jam) }}
                    </td>
                    <td>JAM</td>
                    <td class="text-right">{{ rupiah($slip_gaji->overtime_libur_jumlah) }}</td>
                </tr>
                <tr>
                    <th>Premi Shift 2</th>
                    <td class="text-center">{{ $slip_gaji->premi_2_hari }}</td>
                    <td>HARI</td>
                    <td class="text-right">{{ rupiah($slip_gaji->premi_2_jumlah) }}</td>
                </tr>
                <tr>
                    <th>Premi Shift 3</th>
                    <td class="text-center">{{ $slip_gaji->premi_3_hari }}</td>
                    <td>HARI</td>
                    <td class="text-right">{{ rupiah($slip_gaji->premi_3_jumlah) }}</td>
                </tr>
                <hr>
                <table class="table">
                    <tr>
                        <th>TOTAL PENERIMAAN</th>
                        <td style="font-weight: bold; text-align:right">{{ rupiah($slip_gaji->bruto) }}</td>
                    </tr>
                </table>
                <hr>
                <table class="table">
                    <tr>
                        <th colspan="2" class="text-center">POTONGAN</th>
                    </tr>
                    <tr>
                        <th>Absensi</th>
                        <td>{{ desimal($slip_gaji->potongan_jam) }} JAM</td>
                    </tr>
                    <tr>
                        <th>Denda Keterlambatan</th>
                        <td class="text-right">{{ rupiah($slip_gaji->denda_terlambat) }}</td>
                    </tr>
                    <tr>
                        <th>Softloan</th>
                        <td class="text-right">{{ rupiah($slip_gaji->cicilan_pjp) }}</td>
                    </tr>
                    <tr>
                        <th>Pinjaman Perusahaan</th>
                        <td class="text-right">{{ rupiah($slip_gaji->pinjaman_perusahaan) }}</td>
                    </tr>
                    <tr>
                        <th>Kasbon</th>
                        <td class="text-right">{{ rupiah($slip_gaji->cicilan_kasbon) }}</td>
                    </tr>
                    <tr>
                        <th>BPJS KES</th>
                        <td class="text-right">{{ rupiah($slip_gaji->bpjs_kesehatan) }}</td>
                    </tr>
                    <tr>
                        <th>BPJS TENAGA KERJA</th>
                        <th>BPJS TENAGA KERJA</th>
                        <td class="text-right">{{ rupiah($slip_gaji->bpjs_tenagakerja) }}</td>
                    </tr>
                    <tr>
                        <th>SPIP</th>
                        <td class="text-right">{{ rupiah($slip_gaji->spip) }}</td>
                    </tr>
                    <tr>
                        <th>Pengurang</th>
                        <td class="text-right">{{ rupiah($slip_gaji->pengurang) }}</td>
                    </tr>
                    <tr>
                        <th>Penambah</th>
                        <td class="text-right">{{ rupiah($slip_gaji->penambah) }}</td>
                    </tr>
                </table>
                <hr>
                <table class="table">
                    <tr>
                        <th>TOTAL POTONGAN</th>
                        <td style="font-weight: bold; text-align:right">{{ rupiah($slip_gaji->jumlah_potongan) }}</td>
                    </tr>
                </table>
                <table class="table">
                    <tr>
                        <th style="font-size:18px">GAJI BERSIH</th>
                        <td style="font-weight: bold;font-size:18px; text-align:right">{{ rupiah($slip_gaji->jumlah_bersih) }}</td>
                    </tr>
                </table>
            </table>
            <hr>
        </div>
    </div>
@endsection
