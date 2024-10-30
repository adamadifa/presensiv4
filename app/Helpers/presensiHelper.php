<?php
function presensiHitungJamTerlambat($jam_in, $jam_mulai)
{

    // $jam_in = date('Y-m-d H:i', strtotime($jam_in));
    // $jam_mulai = date('Y-m-d H:i', strtotime($jam_mulai));
    if (!empty($jam_in)) {
        if ($jam_in > $jam_mulai) {
            $j1 = strtotime($jam_mulai);
            $j2 = strtotime($jam_in);

            $diffterlambat = $j2 - $j1;

            $jamterlambat = floor($diffterlambat / (60 * 60));
            $menitterlambat = floor(($diffterlambat - $jamterlambat * (60 * 60)) / 60);

            $jterlambat = $jamterlambat <= 9 ? '0' . $jamterlambat : $jamterlambat;
            $mterlambat = $menitterlambat <= 9 ? '0' . $menitterlambat : $menitterlambat;

            $keterangan_terlambat =  $jterlambat . ':' . $mterlambat;
            $desimal_terlambat = $jamterlambat +   ROUND(($menitterlambat / 60), 2);


            if ($jamterlambat < 1 && $menitterlambat <= 5) {
                $desimal_terlambat = 0;
            } elseif ($jamterlambat < 1 && $menitterlambat > 5) {
                $desimal_terlambat = 0;
            } else {
                $desimal_terlambat = $desimal_terlambat;
            }

            return [
                'status' => true,
                'keterangan' => 'Telat :' . $keterangan_terlambat . ' (' . $desimal_terlambat . ')',
                'jamterlambat' => $jamterlambat,
                'menitterlambat' => $menitterlambat,
                'desimal' => $desimal_terlambat,
                'color' => 'red'
            ];
        } else {
            return [
                'status' => false,
                'keterangan' => 'Tepat Waktu',
                'jamterlambat' => 0,
                'menitterlambat' => 0,
                'desimal' => 0,
                'color' => 'green',
            ];
        }
    } else {
        return [
            'status' => false,
            'keterangan' => '',
            'jamterlambat' => 0,
            'menitterlambat' => 0,
            'desimal' => 0,
            'color' => '',
        ];
    }
}



function presensiHitungPulangCepat($jam_out, $jam_selesai, $jam_awal_istirahat, $jam_akhir_istirahat)
{

    if ($jam_out > $jam_awal_istirahat && $jam_out < $jam_akhir_istirahat) {
        $jam_out = $jam_akhir_istirahat;
    }
    if (!empty($jam_out)) {
        if ($jam_out < $jam_selesai) {
            $j1 = strtotime($jam_out);
            $j2 = strtotime($jam_selesai);

            $diffpulangcepat = $j2 - $j1;

            $jampulangcepat = floor($diffpulangcepat / (60 * 60));
            $menitpulangcepat = floor(($diffpulangcepat - $jampulangcepat * (60 * 60)) / 60);

            $jpulangcepat = $jampulangcepat <= 9 ? '0' . $jampulangcepat : $jampulangcepat;
            $mpulangcepat = $menitpulangcepat <= 9 ? '0' . $menitpulangcepat : $menitpulangcepat;

            $keterangan_pulangcepat =  $jpulangcepat . ':' . $mpulangcepat;

            if ($jam_out < $jam_awal_istirahat) {
                $pengurang_jam = 1;
            } else {
                $pengurang_jam = 0;
            }
            $desimal_pulangcepat = ($jampulangcepat - $pengurang_jam) +   ROUND(($menitpulangcepat / 60), 2);

            return [
                'status' => true,
                'keterangan' => 'PC :' . $keterangan_pulangcepat . ' (' . $desimal_pulangcepat . ')',
                'desimal' => $desimal_pulangcepat,
                'color' => 'red'
            ];
        } else {
            return [
                'status' => false,
                'keterangan' => '',
                'desimal' => 0,
                'color' => 'green'
            ];
        }
    } else {
        return [
            'status' => false,
            'keterangan' => '',
            'desimal' => 0,
            'color' => ''
        ];
    }
}


function presensiHitungJamKeluarKantor($jam_keluar, $j_kembali, $jam_selesai, $jam_out, $total_jam, $istirahat, $jam_awal_istirahat, $jam_akhir_istirahat, $keperluan)
{
    if (!empty($jam_keluar)) {
        if (empty($j_kembali)) {
            if (empty($jam_out)) {
                $jam_kembali = $jam_selesai;
            } else {
                $jam_kembali = $jam_out;
            }
        } else {
            $jam_kembali = $j_kembali;
        }
        $jk1 = strtotime($jam_keluar);
        $jk2 = strtotime($jam_kembali);
        $difkeluarkantor = $jk2 - $jk1;

        $jamkeluarkantor = floor($difkeluarkantor / (60 * 60));
        $menitkeluarkantor = floor(($difkeluarkantor - $jamkeluarkantor * (60 * 60)) / 60);

        $jkeluarkantor = $jamkeluarkantor <= 9 ? '0' . $jamkeluarkantor : $jamkeluarkantor;
        $mkeluarkantor = $menitkeluarkantor <= 9 ? '0' . $menitkeluarkantor : $menitkeluarkantor;

        if (empty($j_kembali)) {
            if ($total_jam == 7) {
                $totaljamkeluar = $jkeluarkantor - 1 . ':' . $mkeluarkantor;
                $jkeluar = $jkeluarkantor - 1;
            } else {
                $totaljamkeluar = $jkeluarkantor . ':' . $mkeluarkantor;
                $jkeluar = $jkeluarkantor;
            }
        } else {
            //Jika Ada istirahat
            if ($istirahat == '1') {
                //Jika Jam Keluar Kantor Sebelum Jam Istirahat
                if ($jam_keluar < $jam_awal_istirahat && $jam_kembali > $jam_akhir_istirahat) {
                    $totaljamkeluar = $jkeluarkantor - 1 . ':' . $mkeluarkantor;
                    $jkeluar = $jkeluarkantor - 1;
                } else {
                    $totaljamkeluar = $jkeluarkantor . ':' . $mkeluarkantor;
                    $jkeluar = $jkeluarkantor;
                }
            } else {
                $totaljamkeluar = $jkeluarkantor . ':' . $mkeluarkantor;
                $jkeluar = $jkeluarkantor;
            }
        }

        $desimaljamkeluar = $jkeluar +   ROUND(($menitkeluarkantor / 60), 2);

        if ($keperluan == 'K') {
            $desimaljamkeluar = 0;
        }

        return [
            'status' => true,
            'keterangan' => 'Keluar:' . $totaljamkeluar . ' (' . $desimaljamkeluar . ')',
            'color' => $desimaljamkeluar > 1 ? 'red' : 'blue',
            'desimal' => $desimaljamkeluar
        ];
    } else {
        return [
            'status' => false,
            'keterangan' => '',
            'color' => '',
            'desimal' => 0,
        ];
    }
}

function presensiHitungDenda($jamterlambat, $menitterlambat, $kode_izin_terlambat, $kode_dept, $kode_jabatan)
{

    //
    //Jika Terlambat
    if (!empty($jamterlambat) || !empty($menitterlambat)) {

        //Jika Terlambat Kurang Dari 1 Jam
        if ($jamterlambat < 1 || $jamterlambat == 1 && $menitterlambat == 0) {
            //Jika Departemen Marketing
            // if ($kode_dept == "MKT") {
            //     $denda = 0;
            //     $keterangan = "";
            //     $cek = 1;
            // } else {
            //     //JIka Sudah Izin
            //     if (!empty($kode_izin_terlambat)) {
            //         $denda = 0;
            //         $keterangan = "Sudah Izin";
            //         $cek = 2;
            //     } else {
            //         if ($menitterlambat >= 5 and $menitterlambat < 10) {
            //             $denda = 5000;
            //             $keterangan = "";
            //             $cek = 3;
            //         } elseif ($menitterlambat >= 10 and $menitterlambat < 15) {
            //             $denda = 10000;
            //             $keterangan = "";
            //             $cek = 4;
            //         } elseif ($menitterlambat >= 15 and $menitterlambat <= 59) {
            //             $denda = 15000;
            //             $keterangan = "";
            //             $cek = 5;
            //         } else {
            //             $denda = 0;
            //             $keterangan = "";
            //             $cek = 6;
            //         }
            //     }
            // }

            if (!empty($kode_izin_terlambat)) {
                $denda = 0;
                $keterangan = "Sudah Izin";
                $cek = 2;
            } else {

                if ($kode_jabatan == 'J19') {
                    if ($menitterlambat >= 10 and $menitterlambat < 15) {
                        $denda = 5000;
                        $keterangan = "";
                        $cek = 3;
                    } elseif ($menitterlambat >= 15 and $menitterlambat < 20) {
                        $denda = 10000;
                        $keterangan = "";
                        $cek = 4;
                    } elseif ($menitterlambat >= 20 and $menitterlambat <= 59) {
                        $denda = 15000;
                        $keterangan = "";
                        $cek = 5;
                    } else {
                        $denda = 0;
                        $keterangan = "";
                        $cek = 6;
                    }
                } else {
                    if ($menitterlambat >= 5 and $menitterlambat < 10) {
                        $denda = 5000;
                        $keterangan = "";
                        $cek = 3;
                    } elseif ($menitterlambat >= 10 and $menitterlambat < 15) {
                        $denda = 10000;
                        $keterangan = "";
                        $cek = 4;
                    } elseif ($menitterlambat >= 15 and $menitterlambat <= 59) {
                        $denda = 15000;
                        $keterangan = "";
                        $cek = 5;
                    } else {
                        $denda = 0;
                        $keterangan = "";
                        $cek = 6;
                    }
                }
            }
        } else {
            $denda = 0;
            $keterangan = "";
            $cek = 7;
        }
    } else {
        $denda = 0;
        $keterangan = "";
        $cek = 8;
    }

    return [
        'denda' => $denda,
        'keterangan' => $keterangan,
        'cek' => $cek
    ];
}

function hitungjamlembur($jam1, $jam2)
{
    $j1 = strtotime($jam1);
    $j2 = strtotime($jam2);

    $diffterlambat = $j2 - $j1;

    $jamterlambat = floor($diffterlambat / (60 * 60));
    $menitterlambat = floor(($diffterlambat - ($jamterlambat * (60 * 60))) / 60);

    $desimalterlambat = $jamterlambat + ROUND(($menitterlambat / 60), 2);

    return $desimalterlambat;
}
function presensiHitunglembur($datalembur)
{
    if (!empty($datalembur)) {
        $tgl_lembur_dari = $datalembur[0]['tanggal_dari'];
        $tgl_lembur_sampai = $datalembur[0]['tanggal_sampai'];
        $jamlembur_dari = date('H:i', strtotime($tgl_lembur_dari));
        $jmljam_lbr = hitungjamlembur($tgl_lembur_dari, $tgl_lembur_sampai);
        $istirahatlbr = $datalembur[0]['istirahat'] == 1 ? 1 : 0;
        $jmljam_lembur = $jmljam_lbr > 7 ? 7 : $jmljam_lbr - $istirahatlbr;
        $kategori_lembur = $datalembur[0]['kategori'];

        if ($jamlembur_dari >= '22:00' && $jmljam_lbr >= 5) {
            // $premilembur = 6000;
            // $premilembur_shift_3 = 6000;
            $nama_jadwal = "SHIFT 3";
            $kode_shift = "M";
            $jmlharilembur_shift_3 = 1;
            $jmlharilembur_shift_2 = 0;
        } else if ($jamlembur_dari >= '15:00' && $jmljam_lbr >= 5) {
            // $premilembur = 5000;
            // $premilembur_shift_2 = 5000;
            $nama_jadwal = "SHIFT 2";
            $kode_shift = "S";
            $jmlharilembur_shift_2 = 1;
            $jmlharilembur_shift_3 = 0;
        } else {
            // $premilembur = 0;
            // $premilembur_shift_2 = 0;
            // $premilembur_shift_3 = 0;
            $nama_jadwal = "SHIFT 1";
            $kode_shift = "P";
            $jmlharilembur_shift_2 = 0;
            $jmlharilembur_shift_3 = 0;
        }

        if ($kategori_lembur == 1) {
            $overtime_1 = $jmljam_lembur > 1 ? 1 : $jmljam_lembur;
            $overtime_1 = round($overtime_1, 2, PHP_ROUND_HALF_DOWN);
            $overtime_2 = $jmljam_lembur > 1 ? $jmljam_lembur - 1 : 0;
            $overtime_2 = round($overtime_2, 2, PHP_ROUND_HALF_DOWN);
            $overtime_libur = 0;
        } else {
            $overtime_1 = 0;
            $overtime_2 = 0;
            $overtime_libur = round($jmljam_lembur, 2, PHP_ROUND_HALF_DOWN);
        }


        return [
            'overtime_1' => $overtime_1,
            'overtime_2' => $overtime_2,
            'overtime_libur' => $overtime_libur,
            'jmlharilembur_shift_2' => $jmlharilembur_shift_2,
            'jmlharilembur_shift_3' => $jmlharilembur_shift_3,
            'nama_jadwal' => $nama_jadwal,
            'kode_shift' => $kode_shift,
        ];
    } else {
        return [
            'overtime_1' => 0,
            'overtime_2' => 0,
            'overtime_libur' => 0,
            'jmlharilembur_shift_2' => 0,
            'jmlharilembur_shift_3' => 0,
            'nama_jadwal' => '',
            'kode_shift' => '',
        ];
    }
}
