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
        <div class="pageTitle">Slip Gaji {{ $namabulan[$bulan * 1] }} {{ $tahun }}</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->
@endsection
@section('content')
    <div class="row" style="margin-top: 70px; overflow:scroll; height:100%; position:relative; bottom:10%">
        <div class="col">
            @php
                $no = 1;
                $totaljam1bulan = 173;

                $total_gajipokok = 0; // Total All Gaji Pokok
                // Total Tunjangan
                $total_tunjangan_jabatan = 0; //Total All Tunjangan Jabatan
                $total_tunjangan_masakerja = 0; // Total All Tunjangan Masa Kerja
                $total_tunjangan_tanggungjawab = 0; // Total All Tanggung Jawab
                $total_tunjangan_makan = 0; // Total All Tunjangan Makan
                $total_tunjangan_istri = 0; // Total All Tunjangan Istri
                $total_tunjangan_skillkhusus = 0; // Total All Tunjangan Skill Khusus

                // Total Insentif
                $total_insentif_masakerja = 0; // Total All Insentif Masa Kerja
                $total_insentif_lembur = 0; // Total All Insentif Lembur
                $total_insentif_penempatan = 0; // Total All Insentif Penempatan
                $total_insentif_kpi = 0; // Total All Insentif KPI

                // Total Insentif Manager
                $total_im_ruanglingkup = 0; // Total All IM Ruang Lingkup
                $total_im_penempatan = 0; // Total All IM Penempatan
                $total_im_kinerja = 0; // Total All IM Kinerja

                $total_upah = 0; // Total All Upah
                $total_insentif = 0; // Total All Insentif

                $total_all_jamkerja = 0; // Total All Jam Kerja
                $total_all_upahperjam = 0; // Total All Upah / Jam

                // Total Overtime 1
                $total_all_overtime_1 = 0; // Total All OVertime 1
                $total_all_upah_ot_1 = 0; // Total  All  Upah OVertime 1
                // Total Overtime 2
                $total_all_overtime_2 = 0; // Total All Overtime 2
                $total_all_upah_ot_2 = 0; // Total All Upah Overtime 2

                // Total Overtim Libur
                $total_all_overtime_libur = 0; // Total All Overtime Libur
                $total_overtime_libur_nasional = 0; // Total Overtime Libur Nasional
                $total_all_upah_overtime_libur = 0; // Total All Upah Overtime Libur

                // Total All Upah Overtime
                $total_all_upah_overtime = 0;

                $total_all_hari_shift_2 = 0; // Total All Hari Shift 2
                $total_all_premi_shift_2 = 0; // Total All Premi Shift 2

                $total_all_hari_shift_3 = 0; // Total All Hari Shift 3
                $total_all_premi_shift_3 = 0; // Total All Premi Shift 3

                $total_all_bruto = 0; // Total All Bruto Gaji

                // Potongan
                $total_all_potongan_jam = 0; // Total ALl Potongan Jam

                $total_all_bpjskesehatan = 0; // Total All BPJS Kesehatan
                $total_all_bpjstk = 0; // Total All BPJS Tenaga Kerja
                $total_all_denda = 0; // Total All Denda

                $total_all_pjp = 0; // Total All PJP
                $total_all_kasbon = 0; // Total ALl Kasbon
                $total_all_nonpjp = 0; // Total All Non PJP
                $total_all_spip = 0; // Total ALl SPIP
                $total_all_pengurang = 0; // Total ALl SPIP
                $total_all_penambah = 0; // Total ALl SPIP

                $total_all_potongan = 0; // Total All Potongan

                // Total Gaji Bersih
                $total_all_bersih = 0;
                $show_for_hrd = ['14', '4', '5', '2', '9', '20', '9', '11'];
            @endphp
            @foreach ($presensi as $d)
                @php
                    $kode_dept = $d->kode_dept;
                    $totalterlambat = 0; // Total Jam Terlambatn
                    $totalkeluar = 0; // Total Jam Keluar
                    $totaldenda = 0; // Total Denda
                    $totalpremi = 0; // Total Premi
                    $totaldirumahkan = 0; // Total Jam DIrumahkan
                    $jmldirumahkan = 0; // Jmlah Hari Dirumahkan
                    $totaltidakhadir = 0; // Total Tidak Hadir
                    $totalpulangcepat = 0; // Total Jam Pulang Cepat
                    $totalizinabsen = 0; // Total Izin Absen
                    $total_overtime_1 = 0; // Total OVertime 1
                    $total_overtime_2 = 0; // Total Overtime 2
                    $total_overtime_libur_1 = 0; // Total Overtime Libur
                    $total_overtime_libur_2 = 0;
                    $izinsakit = 0; // Total Izin Sakit
                    $jmlsid = 0; // Jumlah SID
                    $totalizinsakit = 0; // Total Izin Sakit
                    // $jmlharipremi1 = 0;
                    // $jmlharipremi2 = 0;
                    // $jmlpremi1 = 0;
                    // $jmlpremi2 = 0;
                    $totalpremi_shift_2 = 0; // Total Premi Shift 2
                    $totalpremilembur_shift_2 = 0; // Total Premi Lembur SHift 2
                    $totalpremilembur_harilibur_shift_2 = 0; // Total Premi Lembur SHift 2

                    $totalpremi_shift_3 = 0; // Total Premi Shift 3
                    $totalpremilembur_shift_3 = 0; // Total Premi Lembur Shift 3
                    $totalpremilembur_harilibur_shift_3 = 0; // Total Premi Lembur Shift 3

                    $totalhari_shift_2 = 0; // Total Hari Shift 2
                    $totalharilembur_shift_2 = 0; // Total Hari Lembur Shift 2
                    $totalharilembur_harilibur_shift_2 = 0; // Total Hari Lembur Shift 2

                    $totalhari_shift_3 = 0; // Total Hari Shift 3
                    $totalharilembur_shift_3 = 0; // Total Hari Lembur Shift 3
                    $totalharilembur_harilibur_shift_3 = 0; // Total Hari Lembur Shift 3
                @endphp
                <!-- Looping Periode Tanggal -->
                @for ($i = 0; $i < count($rangetanggal); $i++)
                    @php
                        $hari_ke = 'hari_' . $i + 1; // Mengambil Field Hari
                        $tgl_presensi = $rangetanggal[$i]; // Mengambil Value Tanggal

                        //Proses Perhitungan Masa Kerja
                        $start_kerja = date_create($d->tgl_masuk); //Tanggal Masuk Kerja
                        $end_kerja = date_create($tgl_presensi); // Tanggal Presensi
                        $diff = date_diff($start_kerja, $end_kerja); //Hitung Masa Kerja
                        $cekmasakerja = $diff->y * 12 + $diff->m; // Value Masa Kerja

                        $tgllibur = "'" . $tgl_presensi . "'"; // Tanggal Libur

                        //Parameter Pencarian Data Libur
                        $search_items = [
                            'nik' => $d->nik,

                            'tanggal_libur' => $tgl_presensi,
                        ];

                        //Parameter Pencarian Data Lembur
                        $search_items_lembur = [
                            'nik' => $d->nik,

                            'tanggal_lembur' => $tgl_presensi,
                        ];

                        //Parameter Penggantian Libur Minggu
                        $search_items_minggumasuk = [
                            'nik' => $d->nik,

                            'tanggal_diganti' => $tgl_presensi,
                        ];

                        //Parameter Pencarian Data Libur
                        $search_items_all = [
                            'nik' => 'ALL',

                            'tanggal_libur' => $tgl_presensi,
                        ];

                        //Cek Libur
                        $ceklibur = cektgllibur($datalibur, $search_items); // Cek Libur Nasional
                        $cekliburpenggantiminggu = cektgllibur($dataliburpenggantiminggu, $search_items); // Cek Libur Pengganti Minggu
                        $cekminggumasuk = cektgllibur($dataminggumasuk, $search_items_minggumasuk); // Cek Minggu Masuk
                        $cekwfh = cektgllibur($datawfh, $search_items); // Cek Dirumahkan
                        $cekwfhfull = cektgllibur($datawfhfull, $search_items); // Cek WFH
                        $ceklembur = cektgllibur($datalembur, $search_items_lembur); // Cek Lembur
                        $ceklemburharilibur = cektgllibur($datalemburharilibur, $search_items_lembur); // Cek Lembur

                        //Menghitung Jumlah Jam Dirumahkan
                        $namahari = hari($tgl_presensi); // Cek Nama Hari
                        if ($namahari == 'Sabtu') {
                            $jamdirumahkan = 5;
                        } else {
                            $jamdirumahkan = 7;
                        }

                        //Pewarnaan Kolom
                        if ($namahari == 'Minggu') {
                            // Jika Hari Minggu
                            if (!empty($cekminggumasuk)) {
                                // Cek Jika Minggu Harus Masuk
                                if ($d->$hari_ke != null) {
                                    // Cek Jika Melakukan Presensi
                                    $colorcolumn = ''; // Warna Kolom Putih
                                    $colortext = '';
                                } else {
                                    $colorcolumn = 'red'; // Warna Kolom Merah Jika TIdak Absen
                                    $colortext = '';
                                }
                            } else {
                                // Jika Minggu Libur atau Tidak Ada Jadwal Masuk
                                $colorcolumn = '#ffaf03'; // Warna Kolom Orange
                                $colortext = 'white';
                            }
                        } else {
                            // Jika Hari Bukan Hari Minggu
                            if ($d->$hari_ke != null) {
                                // Jika Karyawa Melakukan Presensi
                                if (!empty($ceklibur)) {
                                    // Jika Pada Hari Itu ada Libur Nasional
                                    $colorcolumn = 'rgb(4, 163, 65)';
                                    $colortext = 'white';
                                } else {
                                    $colorcolumn = '';
                                    $colortext = '';
                                }

                                if (!empty($cekliburpenggantiminggu)) {
                                    // Jika Pada Hari Itu adalah Libur Pengganti Minggu
                                    $colorcolumn = '#ffaf03';
                                    $colortext = 'white';
                                } else {
                                    $colorcolumn = $colorcolumn;
                                    $colortext = $colortext;
                                }

                                if (!empty($cekwfh)) {
                                    // Jika pada Hari Itu Sedang Dirumahkan
                                    $colorcolumn = '#fc0380';
                                    $colortext = 'black';
                                } else {
                                    $colorcolumn = $colorcolumn;
                                    $colortext = $colortext;
                                }

                                if (!empty($cekwfhfull)) {
                                    // Jika pada Hari Itu Sedang WFH
                                    $colorcolumn = '#9f0ecf';
                                    $colortext = 'black';
                                } else {
                                    $colorcolumn = $colorcolumn;
                                    $colortext = $colortext;
                                }
                            } else {
                                // Jika Karyawan Tidak Melakukan Presensi

                                if (!empty($ceklibur)) {
                                    // Jika Pada Hari Itu ada Libur Nasional
                                    $colorcolumn = 'rgb(4, 163, 65)';
                                    $colortext = 'white';
                                } else {
                                    $colorcolumn = 'red';
                                    $colortext = 'white';
                                }

                                if (!empty($cekliburpenggantiminggu)) {
                                    // Jika Pada Hari Itu adalah Libur Pengganti Minggu
                                    $colorcolumn = '#ffaf03';
                                    $colortext = 'white';
                                } else {
                                    if (empty($ceklibur)) {
                                        $colorcolumn = $colorcolumn;
                                        $colortext = $colortext;
                                    }
                                }

                                if (!empty($cekwfh)) {
                                    // Jika pada Hari Itu Sedang Dirumahkan
                                    $colorcolumn = '#fc0380';
                                    $colortext = 'black';
                                } else {
                                    $colorcolumn = $colorcolumn;
                                    $colortext = $colortext;
                                }

                                if (!empty($cekwfhfull)) {
                                    // Jika pada Hari Itu Sedang WFH
                                    $colorcolumn = '#9f0ecf';
                                    $colortext = 'black';
                                } else {
                                    $colorcolumn = $colorcolumn;
                                    $colortext = $colortext;
                                }
                            }
                        }
                    @endphp
                    <!-- Jika Ada Data Presensi-->
                    @if ($d->$hari_ke != null)
                        @php
                            $datapresensi = explode('|', $d->$hari_ke); // Split Data Presensi Per Tanggal
                            $status = $datapresensi[5] != 'NA' ? $datapresensi[5] : ''; // Status Presensi, H,I,S,C
                            $nama_jadwal = $datapresensi[2]; // Jadwal Presensi

                            $lintashari = $datapresensi[16] != 'NA' ? $datapresensi[16] : ''; // Lintas Hari
                            $izinpulangdirut = $datapresensi[17] != 'NA' ? $datapresensi[17] : ''; //Izin Pulang Persetujuan Dirut
                            $keperluankeluar = $datapresensi[19] != 'NA' ? $datapresensi[19] : ''; //Izin Pulang Persetujuan Dirut
                            $izinabsendirut = $datapresensi[18] != 'NA' ? $datapresensi[18] : ''; // Izin Absen Persetujuan Dirut
                            $izinterlambatdirut = $datapresensi[20] != 'NA' ? $datapresensi[20] : ''; // Izin Absen Persetujuan Dirut

                            // Jika Jadwal Presesni Lintas Hari
                            if (!empty($lintashari)) {
                                $tgl_pulang = date('Y-m-d', strtotime('+1 day', strtotime($tgl_presensi))); // Tanggal Pulang adalah Tanggal Berikutnya
                            } else {
                                $tgl_pulang = $tgl_presensi; // Tanggal Pulang adalah Tanggal Presensi
                            }

                            //Jam Masuk Presensi
                            $jam_in = $datapresensi[0]; // Y-m-d H:i:s Jam dan Tanggal Karyawan Melakukan Presensi Masuk
                            $jam_in_presensi = $jam_in != 'NA' ? date('H:i', strtotime($jam_in)) : ''; // Jam Presensi Masuk
                            $jam_in_tanggal = $jam_in != 'NA' ? date('Y-m-d H:i', strtotime($jam_in)) : ''; // Jam Tgl Masuk Presensi

                            //Jam Pulang Presensi
                            $jam_out = $datapresensi[1]; // Y-m-d H:i:s Jam dan Tangal Karyawan Melakukan Presensi Pulang
                            $jam_out_presensi = $jam_out != 'NA' ? date('H:i', strtotime($jam_out)) : ''; //Jam Presensi Pulang
                            $jam_out_tanggal = $jam_out != 'NA' ? date('Y-m-d H:i', strtotime($jam_out)) : ''; //Jam Tgl Presensi Pulang

                            // Menentukan Jam Masuk & Jam Pulang
                            // Cek Jika Absen Di Hari Minggu
                            if ($namahari == 'Minggu') {
                                if (!empty($cekminggumasuk)) {
                                    if ($d->nama_jabatan == 'SPG' || $d->nama_jabatan == 'SPB') {
                                        $jam_masuk = $jam_in_presensi;
                                        $jam_masuk_tanggal = $tgl_presensi . ' ' . $jam_masuk;
                                    } else {
                                        $jam_masuk = date('H:i', strtotime($datapresensi[3]));
                                        $jam_masuk_tanggal = $tgl_presensi . ' ' . $jam_masuk;
                                    }

                                    if ($d->nama_jabatan == 'SPG' || $d->nama_jabatan == 'SPB') {
                                        $jam_pulang = !empty($jam_out_presensi) ? $jam_out_presensi : '';
                                        $jam_pulang_tanggal = !empty($jam_out_presensi)
                                            ? $tgl_pulang . ' ' . $jam_pulang
                                            : '';
                                    } else {
                                        $jam_pulang =
                                            $datapresensi[4] != 'NA' ? date('H:i', strtotime($datapresensi[4])) : '';
                                        $jam_pulang_tanggal =
                                            $datapresensi[4] != 'NA' ? $tgl_pulang . ' ' . $jam_pulang : '';
                                    }
                                } else {
                                    $jam_masuk = $jam_in_presensi;
                                    $jam_masuk_tanggal = $tgl_presensi . ' ' . $jam_masuk;
                                    $jam_pulang = !empty($jam_out_presensi) ? $jam_out_presensi : '';
                                    $jam_pulang_tanggal = !empty($jam_out_presensi)
                                        ? $tgl_pulang . ' ' . $jam_pulang
                                        : '';
                                }
                            } else {
                                if (!empty($ceklibur) || !empty($cekliburpenggantiminggu)) {
                                    $jam_masuk = $jam_in_presensi;
                                    $jam_masuk_tanggal = $tgl_presensi . ' ' . $jam_masuk;
                                    $jam_pulang = !empty($jam_out_presensi) ? $jam_out_presensi : '';
                                    $jam_pulang_tanggal = !empty($jam_out_presensi)
                                        ? $tgl_pulang . ' ' . $jam_pulang
                                        : '';
                                } else {
                                    if ($d->nama_jabatan == 'SPG' || $d->nama_jabatan == 'SPB' || !empty($cekwfh)) {
                                        $jam_masuk = $jam_in_presensi;
                                        $jam_masuk_tanggal = $tgl_presensi . ' ' . $jam_masuk;
                                    } else {
                                        $jam_masuk = date('H:i', strtotime($datapresensi[3]));
                                        $jam_masuk_tanggal = $tgl_presensi . ' ' . $jam_masuk;
                                    }

                                    if ($d->nama_jabatan == 'SPG' || $d->nama_jabatan == 'SPB' || !empty($cekwfh)) {
                                        $jam_pulang = !empty($jam_out_presensi) ? $jam_out_presensi : '';
                                        $jam_pulang_tanggal = !empty($jam_out_presensi)
                                            ? $tgl_pulang . ' ' . $jam_pulang
                                            : '';
                                    } else {
                                        $jam_pulang =
                                            $datapresensi[4] != 'NA' ? date('H:i', strtotime($datapresensi[4])) : '';
                                        $jam_pulang_tanggal =
                                            $datapresensi[4] != 'NA' ? $tgl_pulang . ' ' . $jam_pulang : '';
                                    }
                                }
                            }

                            //Keluar Kantor
                            $jam_keluar = $datapresensi[9] != 'NA' ? date('H:i', strtotime($datapresensi[9])) : ''; // Jam Keluar Kantor
                            $jam_masuk_kk = $datapresensi[10] != 'NA' ? date('H:i', strtotime($datapresensi[10])) : ''; //Jam masuk Keluar Kantor

                            $total_jam = $namahari != 'Sabtu' ? ($datapresensi[11] != 'NA' ? $datapresensi[11] : 0) : 5; // Total Jam Kerja Dalam 1 Hari

                            //Pengajuan Izin
                            $sid = $datapresensi[12] != 'NA' ? $datapresensi[12] : ''; //SID
                            $kode_izin_terlambat = $datapresensi[7] != 'NA' ? $datapresensi[7] : ''; // Izin Terlambat
                            $kode_izin_pulang = $datapresensi[8] != 'NA' ? $datapresensi[8] : ''; // Iizn Pulang

                            //Jam Istirahat
                            $jam_istirahat = $datapresensi[14];
                            $jam_istirahat_presensi =
                                $datapresensi[14] != 'NA' ? date('H:i', strtotime($datapresensi[14])) : '';
                            $jam_istirahat_presensi_tanggal =
                                $datapresensi[14] != 'NA' ? $tgl_pulang . ' ' . $jam_istirahat_presensi : '';

                            $jam_awal_istirahat =
                                $datapresensi[13] != 'NA' ? date('H:i', strtotime($datapresensi[13])) : '';
                            $jam_awal_istirahat_tanggal =
                                $datapresensi[14] != 'NA' ? $tgl_pulang . ' ' . $jam_awal_istirahat : '';

                            $jam_akhir_istirahat =
                                $datapresensi[14] != 'NA' ? date('H:i', strtotime($datapresensi[14])) : '';
                            $jam_akhir_istirahat_tanggal =
                                $datapresensi[14] != 'NA' ? $tgl_pulang . ' ' . $jam_akhir_istirahat : '';

                            //Menghitung Jam Keterlambatan
                            if (!empty($jam_in)) {
                                if ($jam_in_tanggal > $jam_masuk_tanggal) {
                                    //Hitung Jam Keterlambatan
                                    $j1 = strtotime($jam_masuk_tanggal);
                                    $j2 = strtotime($jam_in_tanggal);

                                    $diffterlambat = $j2 - $j1;
                                    //Jam Terlambat
                                    $jamterlambat = floor($diffterlambat / (60 * 60));
                                    //Menit Terlambat
                                    $menitterlambat = floor(($diffterlambat - $jamterlambat * (60 * 60)) / 60);
                                    //Tambah 0 Jika Jam < dari 10
                                    $jterlambat = $jamterlambat <= 9 ? '0' . $jamterlambat : $jamterlambat;
                                    //Tambah 0 Jika Menit Kurang Dari 10
                                    $mterlambat = $menitterlambat <= 9 ? '0' . $menitterlambat : $menitterlambat;

                                    //Total Keterlambatan Dalam Jam dan Menit
                                    $terlambat = $jterlambat . ':' . $mterlambat;
                                    //Keterlambatan Menit Dalam Desimal
                                    $desimalterlambat = ROUND($menitterlambat / 60, 2);
                                    $colorterlambat = 'red';
                                } else {
                                    $terlambat = 'Tepat waktu';
                                    $jamterlambat = 0;
                                    $desimalterlambat = 0;
                                    $colorterlambat = 'green';
                                }
                            } else {
                                $terlambat = '';
                                $jamterlambat = 0;
                                $desimalterlambat = 0;
                                $colorterlambat = '';
                            }
                            //Tambah 0 Didepan Jika < 10 pada Jam Terlambat
                            $jamterlambat = $jamterlambat < 0 && !empty($kode_izin_terlambat) ? 0 : $jamterlambat;

                            //Jam terlambat dalam Desimal
                            if (!empty($izinterlambatdirut) && $izinterlambatdirut == 1) {
                                $jt = 0;
                            } else {
                                $jt = round($jamterlambat + $desimalterlambat, 2, PHP_ROUND_HALF_DOWN);
                            }

                            $jt = !empty($jt) ? $jt : 0;

                            if (!empty($jam_keluar)) {
                                $jam_keluar_tanggal = $datapresensi[9] != 'NA' ? $tgl_pulang . ' ' . $jam_keluar : '';
                                if (!empty($jam_masuk_kk)) {
                                    $jam_masuk_kk_tanggal = $tgl_pulang . ' ' . $jam_masuk_kk;
                                } else {
                                    $jam_masuk_kk_tanggal = $tgl_pulang . ' ' . $jam_pulang;
                                }

                                $jk1 = strtotime($jam_keluar_tanggal);
                                $jk2 = strtotime($jam_masuk_kk_tanggal);
                                $difkeluarkantor = $jk2 - $jk1;

                                //Total Jam Keluar Kantor
                                $jamkeluarkantor = floor($difkeluarkantor / (60 * 60));
                                //Total Menit Keluar Kantor
                                $menitkeluarkantor = floor(($difkeluarkantor - $jamkeluarkantor * (60 * 60)) / 60);

                                //Tambah 0 di Depan Jika < 10
                                $jkeluarkantor = $jamkeluarkantor <= 9 ? '0' . $jamkeluarkantor : $jamkeluarkantor;
                                $mkeluarkantor =
                                    $menitkeluarkantor <= 9 ? '0' . $menitkeluarkantor : $menitkeluarkantor;

                                if (empty($jam_masuk_kk)) {
                                    if ($total_jam == 7) {
                                        $totaljamkeluar = $jkeluarkantor - 1 . ':' . $mkeluarkantor;
                                        $desimaljamkeluar = ROUND($menitkeluarkantor / 60, 2) - 1;
                                    } else {
                                        $totaljamkeluar = $jkeluarkantor . ':' . $mkeluarkantor;
                                        $desimaljamkeluar = ROUND($menitkeluarkantor / 60, 2);
                                    }
                                } else {
                                    $totaljamkeluar = $jkeluarkantor . ':' . $mkeluarkantor;
                                    $desimaljamkeluar = ROUND($menitkeluarkantor / 60, 2);
                                }
                            } else {
                                $totaljamkeluar = '';
                                $desimaljamkeluar = 0;
                                $jamkeluarkantor = 0;
                            }

                            if ($jamkeluarkantor > 0) {
                                if ($keperluankeluar == 'K') {
                                    $jk = 0;
                                } else {
                                    $jk = $jamkeluarkantor + $desimaljamkeluar;
                                }
                            } else {
                                $jk = 0;
                            }

                            // Menghitung Denda
                            if (!empty($jam_in) and $kode_dept != 'MKT') {
                                if ($jam_in_presensi > $jam_masuk and empty($kode_izin_terlambat)) {
                                    if ($jamterlambat < 1) {
                                        if ($menitterlambat >= 5 and $menitterlambat < 10) {
                                            $denda = 5000;
                                            //echo "test5000|";
                                        } elseif ($menitterlambat >= 10 and $menitterlambat < 15) {
                                            $denda = 10000;
                                            //echo "test10ribu|";
                                        } elseif ($menitterlambat >= 15 and $menitterlambat <= 59) {
                                            $denda = 15000;
                                            //echo "Test15ribu|";
                                        } else {
                                            $denda = 0;
                                        }
                                    } else {
                                        $denda = 0;
                                        //echo "testlebihdari1jam";
                                    }
                                } else {
                                    $denda = 0;
                                }
                            } else {
                                $denda = 0;
                            }

                            //Jika Terlambat dan Mengajukan Izin
                            if ($denda == 0 and empty($kode_izin_terlambat)) {
                                if ($kode_dept != 'MKT') {
                                    if ($jamterlambat < 1) {
                                        $jt = 0;
                                    } else {
                                        $jt = $jt;
                                    }
                                } else {
                                    if ($jamterlambat < 1) {
                                        $jt = 0;
                                    } else {
                                        $jt = $jt;
                                    }
                                }
                            } else {
                                if ($jamterlambat < 1) {
                                    $jt = 0;
                                } else {
                                    $jt = $jt;
                                }
                            }

                            //Menghitung total Jam
                            //Menentukan Jam Absen Pulang
                            if (
                                $jam_out_tanggal > $jam_awal_istirahat_tanggal &&
                                $jam_out_tanggal <= $jam_akhir_istirahat_tanggal
                            ) {
                                $jout = $jam_awal_istirahat_tanggal; //Jika Pulang Lebih dari Jam Awal Istirhat dan Kurang dari Jam AKhir Istirahat
                            } else {
                                $jout = $jam_out_tanggal; // Jam Absen Pulang Sesuai Dengan Jam Absen Pulang
                            }

                            $awal = strtotime($jam_masuk_tanggal);
                            $akhir = strtotime($jout);
                            $diff = $akhir - $awal;
                            if (empty($jout)) {
                                $jam = 0;
                                $menit = 0;
                            } else {
                                $jam = floor($diff / (60 * 60));
                                $m = $diff - $jam * (60 * 60);
                                $menit = floor($m / 60);
                            }

                            //Cek Dirumahkan / Tidak
                            if (!empty($cekwfh)) {
                                $totaljam = $jam - $jt - $jk;
                            } else {
                                $totaljam = $total_jam - $jt - $jk;
                            }

                            if ($jam_out != 'NA') {
                                if ($jam_out_tanggal < $jam_pulang_tanggal) {
                                    //Shift 3 Belum Di Set | Coba
                                    if ($jam_out_tanggal > $jam_akhir_istirahat_tanggal && $jam_istirahat != 'NA') {
                                        $desimalmenit = ROUND($menit / 60, 2);
                                        //$desimalmenit = ROUND(($menit * 100) / 60);
                                        $grandtotaljam = $jam - 1 + $desimalmenit;
                                    } else {
                                        $desimalmenit = ROUND($menit / 60, 2);
                                        $grandtotaljam = $jam + $desimalmenit;
                                    }

                                    $grandtotaljam = $grandtotaljam - $jt - $jk;
                                } else {
                                    $desimalmenit = 0;
                                    $grandtotaljam = $totaljam;
                                }
                            } else {
                                $desimalmenit = 0;
                                $grandtotaljam = 0;
                            }

                            if ($jam_in == 'NA') {
                                if ($status == 'i') {
                                    $grandtotaljam = 0;
                                } elseif ($status == 's') {
                                    if (!empty($sid)) {
                                        $jmlsid += 1;
                                        $grandtotaljam = $jamdirumahkan;
                                        $ceksid = 1;
                                        if (!empty($cekwfh)) {
                                            $ceksid = 2;
                                            $grandtotaljam = $grandtotaljam / 2;
                                        }

                                        if ($jmlsid > 5 && $d->nik == '21.10.460' && $bulan == 9 && ($tahun = 2023)) {
                                            if ($namahari != 'Minggu') {
                                                if ($namahari == 'Sabtu') {
                                                    $grandtotaljam = $grandtotaljam - 1.25;
                                                } else {
                                                    $grandtotaljam = $grandtotaljam - 1.75;
                                                }
                                            }

                                            $ceksid = 3;
                                        }
                                    } else {
                                        $grandtotaljam = 0;
                                    }
                                } elseif ($status == 'c') {
                                    $grandtotaljam = $jamdirumahkan;
                                    // if(!empty($cekwfh)){
                                    //     $grandtotaljam = $grandtotaljam / 2 ;
                                    // }
                                } else {
                                    $grandtotaljam = 0;
                                }
                            }

                            //Menghitung Premi
                            if ($nama_jadwal == 'SHIFT 2' && $grandtotaljam >= 5) {
                                $premi = 5000;
                                $premi_shift_2 = 5000;
                                $totalpremi_shift_2 += $premi_shift_2;
                                $totalhari_shift_2 += 1;
                            } elseif ($nama_jadwal == 'SHIFT 3' && $grandtotaljam >= 5) {
                                $premi = 6000;
                                $premi_shift_3 = 6000;
                                $totalpremi_shift_3 += $premi_shift_3;
                                $totalhari_shift_3 += 1;
                            } else {
                                $premi = 0;
                            }

                            //Menghitung Total Jam Pulang Cepat

                            if ($jam_out != 'NA' && $jam_out_tanggal < $jam_pulang_tanggal) {
                                $pc = 'Pulang Cepat';
                                if (!empty($izinpulangdirut) && $izinpulangdirut == 1) {
                                    $totalpc = 0;
                                } else {
                                    $totalpc = $total_jam + $jk - $grandtotaljam;
                                    // if($totalpc <= 0.02){
                                    //     $totalpc = 0;
                                    // }
                                }
                            } else {
                                $pc = '';
                                $totalpc = 0;
                            }

                            //Menghitung Jumlah Tidak Hadir
                            if ($status == 'a') {
                                if ($namahari == 'Sabtu') {
                                    $tidakhadir = 5;
                                } else {
                                    $tidakhadir = 7;
                                }
                            } else {
                                $tidakhadir = 0; // Jika Karyawan Absen Maka $tidakhadir dihitung 0
                            }

                        @endphp
                        @if ($status == 'h')
                            @php
                                $izinabsen = 0;
                                $izinsakit = 0;
                            @endphp
                            <!-- Menghitung Lembur Reguler-->
                            @if (!empty($ceklembur))
                                @php
                                    $tgl_lembur_dari = $ceklembur[0]['tanggal_dari'];
                                    $tgl_lembur_sampai = $ceklembur[0]['tanggal_sampai'];
                                    $jamlembur_dari = date('H:i', strtotime($tgl_lembur_dari));
                                    $jmljam_lbr = hitungjamdesimal($tgl_lembur_dari, $tgl_lembur_sampai);
                                    $istirahatlbr = $ceklembur[0]['istirahat'] == 1 ? 1 : 0;
                                    $jmljam_lembur = $jmljam_lbr > 7 ? 7 : $jmljam_lbr - $istirahatlbr;
                                    $jmljam_lembur = !empty($ceklibur) ? $jmljam_lembur * 2 : $jmljam_lembur;
                                    $kategori_lembur = $ceklembur[0]['kategori'];
                                @endphp
                                @if (empty($ceklibur) && empty($cekliburpenggantiminggu) && empty($cekwfhfull) && $namahari != 'Minggu')
                                    @if ($jamlembur_dari >= '22:00' && $jmljam_lbr >= 5)
                                        @php
                                            $premilembur = 6000;
                                            $premilembur_shift_3 = 6000;
                                            $totalpremilembur_shift_3 += $premilembur_shift_3;
                                            $totalharilembur_shift_3 += 1;
                                        @endphp
                                    @elseif($jamlembur_dari >= '15:00' && $jmljam_lbr >= 5)
                                        @php
                                            $premilembur = 5000;
                                            $premilembur_shift_2 = 5000;
                                            $totalpremilembur_shift_2 += $premilembur_shift_2;
                                            $totalharilembur_shift_2 += 1;
                                        @endphp
                                    @endif
                                @endif

                                <!--Kategori Lembur 1-->
                                @php
                                    $overtime_1 = $jmljam_lembur > 1 ? 1 : $jmljam_lembur;
                                    $overtime_1 = round($overtime_1, 2, PHP_ROUND_HALF_DOWN);
                                    $overtime_2 = $jmljam_lembur > 1 ? $jmljam_lembur - 1 : 0;
                                    $overtime_2 = round($overtime_2, 2, PHP_ROUND_HALF_DOWN);
                                    $total_overtime_1 += $overtime_1;
                                    $total_overtime_2 += $overtime_2;
                                @endphp
                            @else
                                @php
                                    $premilembur = 0;
                                @endphp
                            @endif


                            <!-- Menghitung Lembur Hari Libur -->
                            @if (!empty($ceklemburharilibur))
                                @php
                                    $tgl_lembur_dari = $ceklemburharilibur[0]['tanggal_dari'];
                                    $tgl_lembur_sampai = $ceklemburharilibur[0]['tanggal_sampai'];
                                    $jamlembur_dari = date('H:i', strtotime($tgl_lembur_dari));
                                    $jmljam_lbr = hitungjamdesimal($tgl_lembur_dari, $tgl_lembur_sampai);
                                    $istirahatlbr = $ceklemburharilibur[0]['istirahat'] == 1 ? 1 : 0;
                                    $jmljam_lembur = $jmljam_lbr > 7 ? 7 : $jmljam_lbr - $istirahatlbr;
                                    if (!empty($ceklibur)) {
                                        $jmljam_lembur_liburnasional = $jmljam_lembur * 2;
                                        $jmljam_lembur_reguler = 0;
                                    } else {
                                        $jmljam_lembur_liburnasional = 0;
                                        $jmljam_lembur_reguler = $jmljam_lembur;
                                    }
                                    // $jmljam_lembur = !empty($ceklibur) ? $jmljam_lembur * 2 : $jmljam_lembur;
                                    $kategori_lembur = $ceklemburharilibur[0]['kategori'];
                                @endphp
                                @if (empty($ceklibur) && empty($cekliburpenggantiminggu) && empty($cekwfhfull))
                                    @if ($jamlembur_dari >= '22:00' && $jmljam_lbr >= 5)
                                        @php
                                            $premilembur_harilibur = 6000;
                                            $premilembur_harilibur_shift_3 = 6000;
                                            $totalpremilembur_harilibur_shift_3 += $premilembur_harilibur_shift_3;
                                            $totalharilembur_harilibur_shift_3 += 1;
                                        @endphp
                                    @elseif($jamlembur_dari >= '15:00' && $jmljam_lbr >= 5)
                                        @php
                                            $premilembur_harilibur = 5000;
                                            $premilembur_harilibur_shift_2 = 5000;
                                            $totalpremilembur_harilibur_shift_2 += $premilembur_harilibur_shift_2;
                                            $totalharilembur_harilibur_shift_2 += 1;
                                        @endphp
                                    @endif
                                @endif
                                @php
                                    $overtime_libur_1 = $jmljam_lembur_reguler;
                                    $overtime_libur_nasional = $jmljam_lembur_liburnasional;
                                    $overtime_libur_2 = 0;
                                    $total_overtime_libur_1 += $overtime_libur_1;
                                    $total_overtime_libur_nasional += $overtime_libur_nasional;
                                    $total_overtime_libur_2 += $overtime_libur_2;
                                @endphp
                            @else
                                @php
                                    $premilembur_harilibur = 0;
                                @endphp
                            @endif

                            {{-- @php
                            echo $premilembur_harilibur;
                        @endphp --}}
                        @elseif($status == 's')
                            @if ($namahari != 'Minggu')
                                @if (!empty($sid))
                                    @php
                                        $izinsakit = 0;
                                    @endphp
                                @else
                                    @if (empty($izinabsendirut) || $izinabsendirut == 2)
                                        @if ($namahari == 'Sabtu')
                                            @php
                                                $izinsakit = 5;
                                            @endphp
                                        @elseif($namahari == 'Minggu')
                                            @if (!empty($cekminggumasuk))
                                                @php
                                                    $izinsakit = 7;
                                                @endphp
                                            @else
                                                @php
                                                    $izinsakit = 0;
                                                @endphp
                                            @endif
                                        @else
                                            @php
                                                $izinsakit = 7;
                                            @endphp
                                        @endif
                                    @else
                                        @php
                                            $izinsakit = 0;
                                        @endphp
                                    @endif
                                @endif
                            @endif
                            @php
                                $izinabsen = 0;
                            @endphp
                        @elseif($status == 'i')
                            @if (empty($izinabsendirut) || $izinabsendirut == 2)
                                <!-- Jika Tidak Disetujui Oleh Direktur-->
                                @if ($namahari == 'Sabtu')
                                    @php
                                        $izinabsen = 5;
                                    @endphp
                                @elseif ($namahari == 'Minggu')
                                    @if (!empty($cekminggumasuk))
                                        @php
                                            $izinabsen = 7;
                                        @endphp
                                    @else
                                        @php
                                            $izinabsen = 0;
                                        @endphp
                                    @endif
                                @else
                                    @php
                                        $izinabsen = 7;
                                    @endphp
                                @endif
                            @else
                                @php
                                    $izinabsen = 0;
                                @endphp
                            @endif
                            @php
                                $izinsakit = 0;
                            @endphp
                        @elseif($status == 'c')
                            @php
                                $izinabsen = 0;
                                $izinsakit = 0;
                            @endphp
                        @endif
                    @else
                        @php
                            $jt = 0;
                            $jk = 0;
                            $denda = 0;
                            $premi = 0;
                            $premilembur = 0;
                            $totalpc = 0;
                            $izinabsen = 0;
                            $izinsakit = 0;

                        @endphp
                        @if (
                            !empty($ceklibur) ||
                                !empty($cekliburpenggantiminggu) ||
                                !empty($cekwfh) ||
                                (!empty($cekwfhfull) && $cekmasakerja >= 3))
                            @php
                                $tidakhadir = 0; // Dihitung Hadir dan Full Jam Kerja
                            @endphp
                        @else
                            <!-- Jika Hari Sabtu-->
                            @if ($namahari == 'Sabtu')
                                @php
                                    $tidakhadir = 5; // Dihitung Tidak Hadir 5 Jam
                                @endphp
                                <!-- Jika Hari Minggu-->
                            @elseif($namahari == 'Minggu')
                                <!-- Jika Minggu Masuk-->
                                @if (!empty($cekminggumasuk))
                                    @php
                                        $tidakhadir = 7; // Dihitung Tidak Hadir 7 Jam
                                    @endphp
                                @else
                                    @php
                                        $tidakhadir = 0;
                                    @endphp
                                @endif
                            @else
                                @php
                                    $tidakhadir = 7;
                                @endphp
                            @endif
                            <!-- Jika Tidak Ada Presensi dan Dirumahkan-->
                        @endif
                        @if (!empty($cekwfh))
                            @php
                                //Cek Jika Besok Libur
                                $search_items_next = [
                                    'nik' => $d->nik,
                                    'tanggal_libur' => date('Y-m-d', strtotime('+1 day', strtotime($tgl_presensi))),
                                ];

                                $cekliburnext = cektgllibur($datalibur, $search_items_next);
                                if ($namahari == 'Jumat' && !empty($cekliburnext)) {
                                    $jamdirumahkan = 5;
                                    $tambahjamdirumahkan = 2;
                                } else {
                                    $tambahjamdirumahkan = 0;
                                }

                                $jmldirumahkan += 1;
                                $totaljamdirumahkan =
                                    $jamdirumahkan +
                                    $tambahjamdirumahkan -
                                    (ROUND((50 / 100) * $jamdirumahkan, 2) + $tambahjamdirumahkan);
                                $totaldirumahkan += $totaljamdirumahkan;
                            @endphp
                        @endif
                    @endif
                    @php

                        $totalterlambat += $jt;
                        $totalkeluar += $jk;
                        $totaldenda += $denda;
                        $totalpremi += $premi;
                        $totaltidakhadir += $tidakhadir;
                        $totalpulangcepat += $totalpc;
                        $totalizinabsen += $izinabsen;
                        $totalizinsakit += $izinsakit;
                    @endphp
                    <!-- Total Jam Kerja 1 Bulan -->
                @endfor
                @if ($d->nama_jabatan == 'DIREKTUR')
                    @php
                        $totaljamkerja = 173;
                        $totalpotonganjam = 0;
                    @endphp
                @else
                    @php
                        $totaljamkerja =
                            $totaljam1bulan -
                            $totalterlambat -
                            $totalkeluar -
                            $totaldirumahkan -
                            $totaltidakhadir -
                            $totalpulangcepat -
                            $totalizinabsen -
                            $totalizinsakit;
                        $totalpotonganjam =
                            $totalterlambat +
                            $totalkeluar +
                            $totaldirumahkan +
                            $totaltidakhadir +
                            $totalpulangcepat +
                            $totalizinabsen +
                            $totalizinsakit;
                    @endphp
                @endif

                @php
                    //Total Shift 2
                    $totalhariall_shift_2 = $totalhari_shift_2 + $totalharilembur_harilibur_shift_2;
                    $totalpremiall_shift_2 = $totalpremi_shift_2 + $totalpremilembur_harilibur_shift_2;
                    //Total Shift 3
                    $totalhariall_shift_3 = $totalhari_shift_3 + $totalharilembur_harilibur_shift_3;
                    $totalpremiall_shift_3 = $totalpremi_shift_3 + $totalpremilembur_harilibur_shift_3;

                    //UPAH
                    $upah =
                        $d->gaji_pokok +
                        $d->t_jabatan +
                        $d->t_masakerja +
                        $d->t_tanggungjawab +
                        $d->t_makan +
                        $d->t_istri +
                        $d->t_skill;

                    //Upah Per Jam
                    $upah_perjam = $upah / 173;

                    //Insentif
                    $jmlinsentif =
                        $d->iu_masakerja +
                        $d->iu_lembur +
                        $d->iu_penempatan +
                        $d->iu_kpi +
                        $d->im_ruanglingkup +
                        $d->im_penempatan +
                        $d->im_kinerja;
                @endphp

                @if ($d->nama_jabatan == 'SECURITY')
                    @php
                        $tgl_berlaku = '2024-02-01';
                        $tgl_gaji = $tahun . '-' . $bulan . '-01';

                        if ($tgl_gaji >= $tgl_berlaku) {
                            $upah_ot_1 = 1.5 * 6597 * $total_overtime_1;
                            $upah_ot_2 = 1.5 * 6597 * $total_overtime_2;
                            $upah_otl_1 = 13194 * $total_overtime_libur_1;
                            $upah_otl_libur_nasional = 13143 * $total_overtime_libur_nasional;
                            $upah_otl_2 = 0;
                        } else {
                            $upah_ot_1 = 8000 * $total_overtime_1;
                            $upah_ot_2 = 8000 * $total_overtime_2;
                            $upah_otl_1 = 13143 * $total_overtime_libur_1;
                            $upah_otl_libur_nasional = 13143 * $total_overtime_libur_nasional;
                            $upah_otl_2 = 0;
                        }
                    @endphp
                @else
                    @php
                        $upah_ot_1 = $upah_perjam * 1.5 * $total_overtime_1;
                        $upah_ot_2 = $upah_perjam * 2 * $total_overtime_2;
                        $upah_otl_1 = floor(
                            $upah_perjam * 2 * ($total_overtime_libur_1 + $total_overtime_libur_nasional),
                        );
                        $upah_otl_libur_nasional = 0;
                        $upah_otl_2 = $upah_perjam * 2 * $total_overtime_libur_2;
                    @endphp
                @endif

                @php
                    $total_upah_overtime =
                        $upah_ot_1 + $upah_ot_2 + $upah_otl_1 + $upah_otl_libur_nasional + $upah_otl_2; // Total Upah Overtime
                    $bruto =
                        $upah_perjam * $totaljamkerja +
                        $total_upah_overtime +
                        $totalpremiall_shift_2 +
                        $totalpremiall_shift_3; // Total Upah Bruto
                    $bpjskesehatan = $d->iuran_kes; // BPJS Kesehatan
                    $bpjstenagakerja = $d->iuran_tk; // BPJS Tenaga Kerja
                @endphp
                <!-- Perhitungan SPIP-->
                @if (($d->id_kantor == 'PST' && $cekmasakerja >= 3) || ($d->id_kantor == 'TSM' && $cekmasakerja >= 3) || $d->spip == 1)
                    @php
                        $spip = 5000;
                    @endphp
                @else
                    @php
                        $spip = 0;
                    @endphp
                @endif
                @php
                    $potongan = ROUND(
                        $bpjskesehatan +
                            $bpjstenagakerja +
                            $totaldenda +
                            $d->cicilan_pjp +
                            $d->jml_kasbon +
                            $d->jml_nonpjp +
                            $d->jml_pengurang +
                            $spip,
                        0,
                    ); // Potongan Upah
                    $penambah = $d->jml_penambah;
                    $jmlbersih = $bruto - $potongan + $penambah; // Jumlah Upah Bersih

                    //Total Gaji Pokok
                    $total_gajipokok += $d->gaji_pokok;
                    $total_tunjangan_jabatan += $d->t_jabatan;
                    $total_tunjangan_masakerja += $d->t_masakerja;
                    $total_tunjangan_tanggungjawab += $d->t_tanggungjawab;
                    $total_tunjangan_makan += $d->t_makan;
                    $total_tunjangan_istri += $d->t_istri;
                    $total_tunjangan_skillkhusus += $d->t_skill;

                    $total_insentif_masakerja += $d->iu_masakerja;
                    $total_insentif_lembur += $d->iu_lembur;
                    $total_insentif_penempatan += $d->iu_penempatan;
                    $total_insentif_kpi += $d->iu_kpi;

                    $total_im_ruanglingkup += $d->im_ruanglingkup;
                    $total_im_penempatan += $d->im_penempatan;
                    $total_im_kinerja += $d->im_kinerja;

                    $total_upah += $upah;
                    $total_insentif += $jmlinsentif;

                    $total_all_jamkerja += $totaljamkerja;
                    $total_all_upahperjam += $upah_perjam;

                    $total_all_overtime_1 += $total_overtime_1;
                    $total_all_upah_ot_1 += $upah_ot_1;

                    $total_all_overtime_2 += $total_overtime_2;
                    $total_all_upah_ot_2 += $upah_ot_2;

                    $total_all_overtime_libur += $total_overtime_libur_1 + $total_overtime_libur_nasional;
                    $total_all_upah_overtime_libur += $upah_otl_1 + $upah_otl_libur_nasional;

                    $total_all_upah_overtime += $total_upah_overtime;

                    $total_all_hari_shift_2 += $totalhariall_shift_2;
                    $total_all_premi_shift_2 += $totalpremiall_shift_2;

                    $total_all_hari_shift_3 += $totalhariall_shift_3;
                    $total_all_premi_shift_3 += $totalpremiall_shift_3;

                    $total_all_bruto += $bruto;

                    $total_all_potongan_jam += $totalpotonganjam;
                    $total_all_bpjskesehatan += $bpjskesehatan;
                    $total_all_bpjstk += $bpjstenagakerja;

                    $total_all_denda += $totaldenda;

                    $total_all_pjp += $d->cicilan_pjp;
                    $total_all_kasbon += $d->jml_kasbon;
                    $total_all_pengurang += $d->jml_pengurang;
                    $total_all_penambah += $d->jml_penambah;
                    $total_all_nonpjp += $d->jml_nonpjp;
                    $total_all_spip += $spip;

                    $total_all_potongan += $potongan;
                    $total_all_bersih += $jmlbersih;

                @endphp
                <table class="table">
                    <tr>
                        <th>NIK</th>
                        <td>{{ $d->nik }}</td>
                    </tr>
                    <tr>
                        <th>Nama Karyawan</th>
                        <td>{{ $d->nama_karyawan }}</td>
                    </tr>
                    <tr>
                        <th>Departemen</th>
                        <td>{{ $d->nama_dept }}</td>
                    </tr>
                    <tr>
                        <th>Kantor</th>
                        <td>{{ $d->nama_cabang }}</td>
                    </tr>
                    <tr>
                        <th>Jabatan</th>
                        <td>{{ $d->nama_jabatan }}</td>
                    </tr>
                </table>
                <hr>
                <table class="table">
                    <tr>
                        <th>Gaji Pokok</th>
                        <td class="text-right">{{ rupiah($d->gaji_pokok) }}</td>
                    </tr>
                    <tr>
                        <th>Tunj. Jabatan</th>
                        <td class="text-right">{{ rupiah($d->t_jabatan) }}</td>
                    </tr>
                    <tr>
                        <th>Tunj. Masa Kerja</th>
                        <td class="text-right">{{ rupiah($d->t_masakerja) }}</td>
                    </tr>
                    <tr>
                        <th>Tunj. Tanggung Jawab</th>
                        <td class="text-right">{{ rupiah($d->t_tanggungjawab) }}</td>
                    </tr>
                    <tr>
                        <th>Tunj. Makan</th>
                        <td class="text-right">{{ rupiah($d->t_makan) }}</td>
                    </tr>

                    @if (in_array($d->id_jabatan, $show_for_hrd))
                        <tr>
                            <th>Tunj. Istri</th>
                            <td class="text-right">{{ rupiah($d->t_istri) }}</td>
                        </tr>
                    @endif

                    <tr>
                        <th>Tunj. Skill</th>
                        <td class="text-right">{{ rupiah($d->t_skill) }}</td>
                    </tr>

                </table>
                <hr>
                <table class="table">
                    <tr>
                        <th>∑ JAM KERJA BULAN INI</th>
                        <td style="font-weight: bold">{{ desimal($totaljamkerja) }} JAM</td>
                    </tr>
                    <tr>
                        <th>UPAH / JAM</th>
                        <td class="text-right">{{ desimal($upah_perjam) }}</td>
                    </tr>
                </table>
                <hr>
                <table class="table">
                    @php
                        $upah_bulanini = $upah_perjam * $totaljamkerja;
                    @endphp
                    <tr>
                        <th>UPAH BULAN INI</th>
                        <td style="font-weight: bold; text-align:right">{{ rupiah($upah_bulanini) }}</td>
                    </tr>
                </table>
                <hr>
                <table class="table">
                    <tr>
                        <th>Overtime 1</th>
                        <td class="text-center">{{ desimal($total_overtime_1) }}</td>
                        <td>JAM</td>
                        <td class="text-right">{{ rupiah($upah_ot_1) }}</td>
                    </tr>
                    <tr>
                        <th>Overtime 2</th>
                        <td class="text-center">{{ desimal($total_overtime_2) }}</td>
                        <td>JAM</td>
                        <td class="text-right">{{ rupiah($upah_ot_2) }}</td>
                    </tr>
                    <tr>
                        <th>Lembur Hari Libur</th>
                        <td class="text-center">{{ desimal($total_overtime_libur_1 + $total_overtime_libur_nasional) }}
                        </td>
                        <td>JAM</td>
                        <td class="text-right">{{ rupiah($upah_otl_1 + $upah_otl_libur_nasional) }}</td>
                    </tr>
                    <tr>
                        <th>Premi Shift 2</th>
                        <td class="text-center">{{ $totalhariall_shift_2 }}</td>
                        <td>HARI</td>
                        <td class="text-right">{{ rupiah($totalpremiall_shift_2) }}</td>
                    </tr>
                    <tr>
                        <th>Premi Shift 3</th>
                        <td class="text-center">{{ $totalhariall_shift_3 }}</td>
                        <td>HARI</td>
                        <td class="text-right">{{ rupiah($totalpremiall_shift_3) }}</td>
                    </tr>
                    <hr>
                    <table class="table">
                        <tr>
                            <th>TOTAL PENERIMAAN</th>
                            <td style="font-weight: bold; text-align:right">{{ rupiah($bruto) }}</td>
                        </tr>
                    </table>
                    <hr>
                    <table class="table">
                        <tr>
                            <th colspan="2" class="text-center">POTONGAN</th>
                        </tr>
                        <tr>
                            <th>Absensi</th>
                            <td>{{ desimal($totalpotonganjam) }} JAM</td>
                        </tr>
                        <tr>
                            <th>Denda Keterlambatan</th>
                            <td class="text-right">{{ rupiah($totaldenda) }}</td>
                        </tr>
                        <tr>
                            <th>Softloan</th>
                            <td class="text-right">{{ rupiah($d->cicilan_pjp) }}</td>
                        </tr>
                        <tr>
                            <th>Pinjaman Perusahaan</th>
                            <td class="text-right">{{ rupiah($d->jml_nonpjp) }}</td>
                        </tr>
                        <tr>
                            <th>Kasbon</th>
                            <td class="text-right">{{ rupiah($d->jml_kasbon) }}</td>
                        </tr>
                        <tr>
                            <th>BPJS KES</th>
                            <td class="text-right">{{ rupiah($bpjskesehatan) }}</td>
                        </tr>
                        <tr>
                            <th>BPJS TENAGA KERJA</th>
                            <td class="text-right">{{ rupiah($bpjstenagakerja) }}</td>
                        </tr>
                        <tr>
                            <th>SPIP</th>
                            <td class="text-right">{{ rupiah($spip) }}</td>
                        </tr>
                        <tr>
                            <th>Pengurang</th>
                            <td class="text-right">{{ rupiah($d->jml_pengurang) }}</td>
                        </tr>
                        <tr>
                            <th>Penambah</th>
                            <td class="text-right">{{ rupiah($d->jml_penambah) }}</td>
                        </tr>
                    </table>
                    <hr>
                    <table class="table">
                        <tr>
                            <th>TOTAL POTONGAN</th>
                            <td style="font-weight: bold; text-align:right">{{ rupiah($potongan) }}</td>
                        </tr>
                    </table>
                    <table class="table">
                        <tr>
                            <th style="font-size:18px">GAJI BERSIH</th>
                            <td style="font-weight: bold;font-size:18px; text-align:right">{{ rupiah($jmlbersih) }}</td>
                        </tr>
                    </table>
                </table>
                <hr>
                <table class="table">
                    <tr>
                        <th colspan="2" class="text-center">INSENTIF</th>
                    </tr>
                    @if (in_array($d->id_jabatan, $show_for_hrd))
                        <tr>
                            <th>RUANG LINGKUP</th>
                            <td class="text-right">{{ rupiah($d->im_ruanglingkup) }}</td>
                        </tr>
                        <tr>
                            <th>PENEMPATAN</th>
                            <td class="text-right">{{ rupiah($d->im_penempatan) }}</td>
                        </tr>
                        <tr>
                            <th>KINERJA</th>
                            <td class="text-right">{{ rupiah($d->im_kinerja) }}</td>
                        </tr>
                    @else
                        <tr>
                            <th>MASA KERJA</th>
                            <td class="text-right">{{ rupiah($d->iu_masakerja) }}</td>
                        </tr>
                        <tr>
                            <th>LEMBUR</th>
                            <td class="text-right">{{ rupiah($d->iu_lembur) }}</td>
                        </tr>
                        <tr>
                            <th>PENEMPATAN</th>
                            <td class="text-right">{{ rupiah($d->iu_penempatan) }}</td>
                        </tr>
                        <tr>
                            <th>KPI</th>
                            <td class="text-right">{{ rupiah($d->iu_kpi) }}</td>
                        </tr>
                    @endif

                </table>
            @endforeach
        </div>
    </div>
    {{-- <div class="fab-button bottom-right" style="margin-bottom:70px">
    <a href="/presensi/buatizin" class="fab bg-danger">
        <ion-icon name="add-outline"></ion-icon>
    </a>
</div> --}}
@endsection
