<?php

use Illuminate\Support\Facades\DB;

function hari($hari)
{
    $hari = date("D", strtotime($hari));

    switch ($hari) {
        case 'Sun':
            $hari_ini = "Minggu";
            break;

        case 'Mon':
            $hari_ini = "Senin";
            break;

        case 'Tue':
            $hari_ini = "Selasa";
            break;

        case 'Wed':
            $hari_ini = "Rabu";
            break;

        case 'Thu':
            $hari_ini = "Kamis";
            break;

        case 'Fri':
            $hari_ini = "Jumat";
            break;

        case 'Sat':
            $hari_ini = "Sabtu";
            break;

        default:
            $hari_ini = "Tidak di ketahui";
            break;
    }

    return $hari_ini;
}


// function ceklibur($dari, $sampai)
// {
//     $no = 1;
//     $libur = [];
//     $ceklibur = DB::table('harilibur')
//         ->selectRaw('tanggal_libur,
//         id_kantor,
//         keterangan,
//         IFNULL(harilibur_karyawan.nik,"ALL") as nik')
//         ->leftJoin('harilibur_karyawan', 'harilibur.kode_libur', '=', 'harilibur_karyawan.kode_libur')
//         ->where('kategori', 1)
//         ->whereBetween('tanggal_libur', [$dari, $sampai])->get();

//     foreach ($ceklibur as $d) {
//         $libur[] = [
//             'nik' => $d->nik,
//             'id_kantor' => $d->id_kantor,
//             'tanggal_libur' => $d->tanggal_libur,
//             'keterangan' => $d->keterangan
//         ];
//     }

//     return $libur;
// }


// function cekliburpenggantiminggu($dari, $sampai)
// {
//     $no = 1;
//     $libur = [];
//     $ceklibur = DB::table('harilibur')
//         ->selectRaw('tanggal_libur,
//         id_kantor,
//         keterangan,
//         tanggal_diganti,
//         IFNULL(harilibur_karyawan.nik,"ALL") as nik')
//         ->leftJoin('harilibur_karyawan', 'harilibur.kode_libur', '=', 'harilibur_karyawan.kode_libur')
//         ->where('kategori', 2)
//         ->whereBetween('tanggal_libur', [$dari, $sampai])->get();

//     foreach ($ceklibur as $d) {
//         $libur[] = [
//             'nik' => $d->nik,
//             'id_kantor' => $d->id_kantor,
//             'tanggal_libur' => $d->tanggal_libur,
//             'keterangan' => $d->keterangan,
//             'tanggal_diganti' => $d->tanggal_diganti
//         ];
//     }

//     return $libur;
// }

// function cekminggumasuk($dari, $sampai)
// {
//     $no = 1;
//     $libur = [];
//     $ceklibur = DB::table('harilibur')
//         ->selectRaw('tanggal_libur,
//         id_kantor,
//         keterangan,
//         tanggal_diganti,
//         IFNULL(harilibur_karyawan.nik,"ALL") as nik')
//         ->leftJoin('harilibur_karyawan', 'harilibur.kode_libur', '=', 'harilibur_karyawan.kode_libur')
//         ->where('kategori', 2)
//         ->whereBetween('tanggal_diganti', [$dari, $sampai])->get();

//     foreach ($ceklibur as $d) {
//         $libur[] = [
//             'nik' => $d->nik,
//             'id_kantor' => $d->id_kantor,
//             'tanggal_libur' => $d->tanggal_libur,
//             'keterangan' => $d->keterangan,
//             'tanggal_diganti' => $d->tanggal_diganti
//         ];
//     }

//     return $libur;
// }


// function cekwfh($dari, $sampai)
// {
//     $no = 1;
//     $libur = [];
//     $ceklibur = DB::table('harilibur')
//         ->selectRaw('tanggal_libur,
//         id_kantor,
//         keterangan,
//         tanggal_diganti,
//         IFNULL(harilibur_karyawan.nik,"ALL") as nik')
//         ->leftJoin('harilibur_karyawan', 'harilibur.kode_libur', '=', 'harilibur_karyawan.kode_libur')
//         ->where('kategori', 3)
//         ->whereBetween('tanggal_libur', [$dari, $sampai])->get();

//     foreach ($ceklibur as $d) {
//         $libur[] = [
//             'nik' => $d->nik,
//             'id_kantor' => $d->id_kantor,
//             'tanggal_libur' => $d->tanggal_libur,
//             'keterangan' => $d->keterangan,
//             'tanggal_diganti' => $d->tanggal_diganti
//         ];
//     }

//     return $libur;
// }


// function cekwfhfull($dari, $sampai)
// {
//     $no = 1;
//     $libur = [];
//     $ceklibur = DB::table('harilibur')
//         ->selectRaw('tanggal_libur,
//         id_kantor,
//         keterangan,
//         tanggal_diganti,
//         IFNULL(harilibur_karyawan.nik,"ALL") as nik')
//         ->leftJoin('harilibur_karyawan', 'harilibur.kode_libur', '=', 'harilibur_karyawan.kode_libur')
//         ->where('kategori', 4)
//         ->whereBetween('tanggal_libur', [$dari, $sampai])->get();

//     foreach ($ceklibur as $d) {
//         $libur[] = [
//             'nik' => $d->nik,
//             'id_kantor' => $d->id_kantor,
//             'tanggal_libur' => $d->tanggal_libur,
//             'keterangan' => $d->keterangan,
//             'tanggal_diganti' => $d->tanggal_diganti
//         ];
//     }

//     return $libur;
// }


// function ceklembur($dari, $sampai, $kategori)
// {
//     $no = 1;
//     $lembur = [];
//     $ceklembur = DB::table('lembur')
//         ->selectRaw('
//         tanggal,
//         tanggal_dari,
//         tanggal_sampai,
//         id_kantor,
//         kode_dept,
//         keterangan,
//         kategori,
//         istirahat,
//         IFNULL(lembur_karyawan.nik,"ALL") as nik')
//         ->leftJoin('lembur_karyawan', 'lembur.kode_lembur', '=', 'lembur_karyawan.kode_lembur')
//         ->whereBetween('tanggal', [$dari, $sampai])
//         ->where('kategori', $kategori)
//         ->get();

//     foreach ($ceklembur as $d) {
//         $lembur[] = [
//             'nik' => $d->nik,
//             'id_kantor' => $d->id_kantor,
//             'tanggal_lembur' => $d->tanggal,
//             'tanggal_dari' => $d->tanggal_dari,
//             'tanggal_sampai' => $d->tanggal_sampai,
//             'keterangan' => $d->keterangan,
//             'kategori' => $d->kategori,
//             'istirahat' => $d->istirahat
//         ];
//     }

//     return $lembur;
// }

// function cektgllibur($array, $search_list)
// {

//     // Create the result array
//     $result = array();

//     // Iterate over each array element
//     foreach ($array as $key => $value) {

//         // Iterate over each search condition
//         foreach ($search_list as $k => $v) {

//             // If the array element does not meet
//             // the search condition then continue
//             // to the next element
//             if (!isset($value[$k]) || $value[$k] != $v) {

//                 // Skip two loops
//                 continue 2;
//             }
//         }

//         // Append array element's key to the
//         //result array
//         $result[] = $value;
//     }

//     // Return result
//     return $result;
// }


// function hitungjam($jadwal_jam_masuk, $jam_presensi)
// {
//     $j1 = strtotime($jadwal_jam_masuk);
//     $j2 = strtotime($jam_presensi);

//     $diffterlambat = $j2 - $j1;

//     $jamterlambat = floor($diffterlambat / (60 * 60));
//     $menitterlambat = floor(($diffterlambat - ($jamterlambat * (60 * 60))) / 60);

//     $jterlambat = $jamterlambat <= 9 ? "0" . $jamterlambat : $jamterlambat;
//     $mterlambat = $menitterlambat <= 9 ? "0" . $menitterlambat : $menitterlambat;


//     $terlambat = $jterlambat . ":" . $mterlambat;
//     return $terlambat;
// }


// function hitungjamdesimal($jam1, $jam2)
// {
//     $j1 = strtotime($jam1);
//     $j2 = strtotime($jam2);

//     $diffterlambat = $j2 - $j1;

//     $jamterlambat = floor($diffterlambat / (60 * 60));
//     $menitterlambat = floor(($diffterlambat - ($jamterlambat * (60 * 60))) / 60);



//     $desimalterlambat = $jamterlambat + ROUND(($menitterlambat / 60), 2);

//     return $desimalterlambat;
// }

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

function DateToIndo2($date2)
{
    // fungsi atau method untuk mengubah tanggal ke format indonesia
    // variabel BulanIndo merupakan variabel array yang menyimpan nama-nama bulan
    $BulanIndo2 = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    $tahun2 = substr($date2, 0, 4); // memisahkan format tahun menggunakan substring
    $bulan2 = substr($date2, 5, 2); // memisahkan format bulan menggunakan substring
    $tgl2 = substr($date2, 8, 2); // memisahkan format tanggal menggunakan substring

    $result = $tgl2 . ' ' . $BulanIndo2[(int) $bulan2 - 1] . ' ' . $tahun2;
    return $result;
}

function selisih($jam_masuk, $jam_keluar)
{
    [$h, $m, $s] = explode(':', $jam_masuk);
    $dtAwal = mktime($h, $m, $s, '1', '1', '1');
    [$h, $m, $s] = explode(':', $jam_keluar);
    $dtAkhir = mktime($h, $m, $s, '1', '1', '1');
    $dtSelisih = $dtAkhir - $dtAwal;
    $totalmenit = $dtSelisih / 60;
    $jam = explode('.', $totalmenit / 60);
    $sisamenit = $totalmenit / 60 - $jam[0];
    $sisamenit2 = $sisamenit * 60;
    $jml_jam = $jam[0];
    return $jml_jam . ':' . round($sisamenit2);
}


// function hitungjamterlambat($jam_in, $jam_mulai)
// {

//     // $jam_in = date('Y-m-d H:i', strtotime($jam_in));
//     // $jam_mulai = date('Y-m-d H:i', strtotime($jam_mulai));
//     if (!empty($jam_in)) {
//         if ($jam_in > $jam_mulai) {
//             $j1 = strtotime($jam_mulai);
//             $j2 = strtotime($jam_in);

//             $diffterlambat = $j2 - $j1;

//             $jamterlambat = floor($diffterlambat / (60 * 60));
//             $menitterlambat = floor(($diffterlambat - $jamterlambat * (60 * 60)) / 60);

//             $jterlambat = $jamterlambat <= 9 ? '0' . $jamterlambat : $jamterlambat;
//             $mterlambat = $menitterlambat <= 9 ? '0' . $menitterlambat : $menitterlambat;

//             $keterangan_terlambat = 'Telat ' . $jterlambat . ':' . $mterlambat;
//             $desimal_terlambat = ROUND(($menitterlambat * 100) / 60);
//             $color_terlambat = 'red';
//             return [
//                 'keterangan_terlambat' => $keterangan_terlambat,
//                 'jamterlambat' => $jamterlambat,
//                 'menitterlambat' => $menitterlambat,
//                 'desimal_terlambat' => $desimal_terlambat,
//                 'color_terlambat' => $color_terlambat
//             ];
//         } else {
//             return [];
//         }
//     } else {
//         return [];
//     }
// }


// function hitungdenda($jamterlambat, $menitterlambat, $kode_izin_terlambat, $kode_dept)
// {

//     //
//     //Jika Terlambat
//     if (!empty($jamterlambat) || !empty($menitterlambat)) {

//         //Jika Terlambat Kurang Dari 1 Jam
//         if ($jamterlambat < 1 || $jamterlambat == 1 && $menitterlambat == 0) {
//             //Jika Departemen Marketing
//             if ($kode_dept == "MKT") {
//                 $denda = 0;
//                 $keterangan = "";
//                 $cek = 1;
//             } else {
//                 //JIka Sudah Izin
//                 if (!empty($kode_izin_terlambat)) {
//                     $denda = 0;
//                     $keterangan = "Sudah Izin";
//                     $cek = 2;
//                 } else {
//                     if ($menitterlambat >= 5 and $menitterlambat < 10) {
//                         $denda = 5000;
//                         $keterangan = "";
//                         $cek = 3;
//                     } elseif ($menitterlambat >= 10 and $menitterlambat < 15) {
//                         $denda = 10000;
//                         $keterangan = "";
//                         $cek = 4;
//                     } elseif ($menitterlambat >= 15 and $menitterlambat <= 59) {
//                         $denda = 15000;
//                         $keterangan = "";
//                         $cek = 5;
//                     } else {
//                         $denda = 0;
//                         $keterangan = "";
//                         $cek = 6;
//                     }
//                 }
//             }
//         } else {
//             $denda = 0;
//             $keterangan = "Potong JK";
//             $cek = 7;
//         }
//     } else {
//         $denda = 0;
//         $keterangan = "";
//         $cek = 8;
//     }

//     return [
//         'denda' => $denda,
//         'keterangan' => $keterangan,
//         'cek' => $cek
//     ];
// }



// function hitungjamkeluarkantor($jam_keluar, $j_kembali, $jam_selesai, $total_jam, $istirahat, $jam_awal_istirahat, $jam_akhir_istirahat)
// {

//     $jam_kembali = !empty($j_kembali) ? $j_kembali : $jam_selesai;
//     $jk1 = strtotime($jam_keluar);
//     $jk2 = strtotime($jam_kembali);
//     $difkeluarkantor = $jk2 - $jk1;

//     $jamkeluarkantor = floor($difkeluarkantor / (60 * 60));
//     $menitkeluarkantor = floor(($difkeluarkantor - $jamkeluarkantor * (60 * 60)) / 60);

//     $jkeluarkantor = $jamkeluarkantor <= 9 ? '0' . $jamkeluarkantor : $jamkeluarkantor;
//     $mkeluarkantor = $menitkeluarkantor <= 9 ? '0' . $menitkeluarkantor : $menitkeluarkantor;

//     if (empty($j_kembali)) {
//         if ($total_jam == 7) {
//             $totaljamkeluar = $jkeluarkantor - 1 . ':' . $mkeluarkantor;
//         } else {
//             $totaljamkeluar = $jkeluarkantor . ':' . $mkeluarkantor;
//         }
//     } else {
//         //Jika Ada istirahat
//         if ($istirahat == '1') {
//             //Jika Jam Keluar Kantor Sebelum Jam Istirahat
//             if ($jam_keluar < $jam_awal_istirahat && $jam_kembali > $jam_akhir_istirahat) {
//                 $totaljamkeluar = $jkeluarkantor - 1 . ':' . $mkeluarkantor;
//             } else {
//                 $totaljamkeluar = $jkeluarkantor . ':' . $mkeluarkantor;
//             }
//         } else {
//             $totaljamkeluar = $jkeluarkantor . ':' . $mkeluarkantor;
//         }
//     }
//     // $desimaljamkeluar = ROUND(($menitkeluarkantor * 100) / 60);

//     return [
//         'totaljamkeluar' => $totaljamkeluar,
//         'jamkeluarkantor' => $jamkeluarkantor,
//         'color' => $jamkeluarkantor > 0 ? 'danger' : 'primary'
//     ];
// }


function textUpperCase($value)
{
    return strtoupper(strtolower($value));
}
// Mengubah ke CamelCase
function textCamelCase($value)
{
    return ucwords(strtolower($value));
}

function singkatString($string)
{
    $words = explode(' ', $string);

    // Jika string terdiri dari tepat 3 kata, buat singkatan huruf besar
    if (count($words) === 3) {
        $abbreviation = '';

        foreach ($words as $word) {
            if (strlen($word) >= 3) {
                $abbreviation .= strtoupper($word[0]);
            }
        }

        return $abbreviation;
    }

    // Jika tidak, buat camelCase
    return ucwords(strtolower($string));
}


function formatRupiah($nilai)
{
    return number_format($nilai, '0', ',', '.');
}

function formatAngka($nilai)
{
    if (!empty($nilai)) {
        return number_format($nilai, '0', ',', '.');
    }
}


function formatAngkaDesimal($nilai)
{
    if (!empty($nilai)) {
        return number_format($nilai, '2', ',', '.');
    }
}

function formatAngkaDesimal3($nilai)
{
    if (!empty($nilai)) {
        return number_format($nilai, '3', ',', '.');
    }
}

function formatAngkaDesimal5($nilai)
{
    if (!empty($nilai)) {
        return number_format($nilai, '5', ',', '.');
    }
}
