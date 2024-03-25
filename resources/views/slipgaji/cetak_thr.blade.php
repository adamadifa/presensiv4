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
    @php
        $upah =
            $karyawan->gaji_pokok +
            $karyawan->t_jabatan +
            $karyawan->t_masakerja +
            $karyawan->t_tanggungjawab +
            $karyawan->t_makan +
            $karyawan->t_istri +
            $karyawan->t_skill;
        //Masa Kerja THR
        $sampaithr = '2024-03-23';
        $awal = date_create($karyawan->tgl_masuk);
        $akhir = date_create($sampaithr); // waktu sekarang
        $diff = date_diff($awal, $akhir);
        // echo $diff->y . ' tahun, '.$diff->m.' bulan, '.$diff->d.' Hari'
        $tahunkerja = $diff->y;
        $bulankerja = $diff->m;
    @endphp
    @if ($tahunkerja >= 10 && $tahunkerja < 15)
        @php
            $thr2 = 0.25 * $karyawan->gaji_pokok;
        @endphp
    @else
        @php
            $thr2 = 0;
        @endphp
    @endif

    @if ($tahunkerja >= 15)
        @php
            $thr3 = 0.5 * $karyawan->gaji_pokok;
        @endphp
    @else
        @php
            $thr3 = 0;
        @endphp
    @endif

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
                <tr>
                    <th>Masa Kerja</th>
                    <td>
                        @php
                            echo $diff->y . ' tahun, ' . $diff->m . ' bulan';
                        @endphp
                    </td>
                </tr>


            </table>
            <hr>
            <table class="table">
                <tr>
                    <th>Gaji Pokok</th>
                    <td class="text-right">{{ rupiah($karyawan->gaji_pokok) }}</td>
                </tr>
                <tr>
                    <th>Tunj. Jabatan</th>
                    <td class="text-right">{{ rupiah($karyawan->t_jabatan) }}</td>
                </tr>
                <tr>
                    <th>Tunj. Masa Kerja</th>
                    <td class="text-right">{{ rupiah($karyawan->t_masakerja) }}</td>
                </tr>
                <tr>
                    <th>Tunj. Tanggung Jawab</th>
                    <td class="text-right">{{ rupiah($karyawan->t_tanggungjawab) }}</td>
                </tr>
                <tr>
                    <th>Tunj. Makan</th>
                    <td class="text-right">{{ rupiah($karyawan->t_makan) }}</td>
                </tr>

                @if (!empty($karyawan->t_istri))
                    <tr>
                        <th>Tunj. Istri</th>
                        <td class="text-right">{{ rupiah($karyawan->t_istri) }}</td>
                    </tr>
                @endif

                <tr>
                    <th>Tunj. Skill</th>
                    <td class="text-right">{{ rupiah($karyawan->t_skill) }}</td>
                </tr>
                <tr>
                    <th>THR</th>
                    <td align="right">
                        @if ($tahunkerja >= 1)
                            @php
                                $thr = $upah;
                            @endphp
                        @else
                            @php
                                $thr = ($bulankerja / 12) * $upah;
                            @endphp
                        @endif
                        {{ rupiah($thr) }}
                    </td>
                </tr>
                @if (!empty($thr2))
                    <tr>
                        <th>THR 1/4</th>
                        <td align="right">
                            {{ rupiah($thr2) }}
                        </td>
                    </tr>
                @endif

                @if (!empty($thr3))
                    <tr>
                        <th>THR 1/2</th>
                        <td align="right">
                            {{ rupiah($thr3) }}
                        </td>
                    </tr>
                @endif
                <tr>
                    <th>TOTAL THR</th>
                    <th style="text-align:right; font-size:16px">
                        {{ rupiah($thr + $thr2 + $thr3) }}
                    </th>
                </tr>
            </table>
        </div>
    </div>
@endsection
