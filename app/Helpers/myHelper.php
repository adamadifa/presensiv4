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


function ceklibur($dari, $sampai)
{
    $no = 1;
    $libur = [];
    $ceklibur = DB::table('harilibur')
        ->selectRaw('tanggal_libur,
        id_kantor,
        keterangan,
        IFNULL(harilibur_karyawan.nik,"ALL") as nik')
        ->leftJoin('harilibur_karyawan', 'harilibur.kode_libur', '=', 'harilibur_karyawan.kode_libur')
        ->where('kategori', 1)
        ->whereBetween('tanggal_libur', [$dari, $sampai])->get();

    foreach ($ceklibur as $d) {
        $libur[] = [
            'nik' => $d->nik,
            'id_kantor' => $d->id_kantor,
            'tanggal_libur' => $d->tanggal_libur,
            'keterangan' => $d->keterangan
        ];
    }

    return $libur;
}


function cekliburpenggantiminggu($dari, $sampai)
{
    $no = 1;
    $libur = [];
    $ceklibur = DB::table('harilibur')
        ->selectRaw('tanggal_libur,
        id_kantor,
        keterangan,
        tanggal_diganti,
        IFNULL(harilibur_karyawan.nik,"ALL") as nik')
        ->leftJoin('harilibur_karyawan', 'harilibur.kode_libur', '=', 'harilibur_karyawan.kode_libur')
        ->where('kategori', 2)
        ->whereBetween('tanggal_libur', [$dari, $sampai])->get();

    foreach ($ceklibur as $d) {
        $libur[] = [
            'nik' => $d->nik,
            'id_kantor' => $d->id_kantor,
            'tanggal_libur' => $d->tanggal_libur,
            'keterangan' => $d->keterangan,
            'tanggal_diganti' => $d->tanggal_diganti
        ];
    }

    return $libur;
}

function cekminggumasuk($dari, $sampai)
{
    $no = 1;
    $libur = [];
    $ceklibur = DB::table('harilibur')
        ->selectRaw('tanggal_libur,
        id_kantor,
        keterangan,
        tanggal_diganti,
        IFNULL(harilibur_karyawan.nik,"ALL") as nik')
        ->leftJoin('harilibur_karyawan', 'harilibur.kode_libur', '=', 'harilibur_karyawan.kode_libur')
        ->where('kategori', 2)
        ->whereBetween('tanggal_diganti', [$dari, $sampai])->get();

    foreach ($ceklibur as $d) {
        $libur[] = [
            'nik' => $d->nik,
            'id_kantor' => $d->id_kantor,
            'tanggal_libur' => $d->tanggal_libur,
            'keterangan' => $d->keterangan,
            'tanggal_diganti' => $d->tanggal_diganti
        ];
    }

    return $libur;
}


function cekwfh($dari, $sampai)
{
    $no = 1;
    $libur = [];
    $ceklibur = DB::table('harilibur')
        ->selectRaw('tanggal_libur,
        id_kantor,
        keterangan,
        tanggal_diganti,
        IFNULL(harilibur_karyawan.nik,"ALL") as nik')
        ->leftJoin('harilibur_karyawan', 'harilibur.kode_libur', '=', 'harilibur_karyawan.kode_libur')
        ->where('kategori', 3)
        ->whereBetween('tanggal_libur', [$dari, $sampai])->get();

    foreach ($ceklibur as $d) {
        $libur[] = [
            'nik' => $d->nik,
            'id_kantor' => $d->id_kantor,
            'tanggal_libur' => $d->tanggal_libur,
            'keterangan' => $d->keterangan,
            'tanggal_diganti' => $d->tanggal_diganti
        ];
    }

    return $libur;
}


function cekwfhfull($dari, $sampai)
{
    $no = 1;
    $libur = [];
    $ceklibur = DB::table('harilibur')
        ->selectRaw('tanggal_libur,
        id_kantor,
        keterangan,
        tanggal_diganti,
        IFNULL(harilibur_karyawan.nik,"ALL") as nik')
        ->leftJoin('harilibur_karyawan', 'harilibur.kode_libur', '=', 'harilibur_karyawan.kode_libur')
        ->where('kategori', 4)
        ->whereBetween('tanggal_libur', [$dari, $sampai])->get();

    foreach ($ceklibur as $d) {
        $libur[] = [
            'nik' => $d->nik,
            'id_kantor' => $d->id_kantor,
            'tanggal_libur' => $d->tanggal_libur,
            'keterangan' => $d->keterangan,
            'tanggal_diganti' => $d->tanggal_diganti
        ];
    }

    return $libur;
}


function ceklembur($dari, $sampai, $kategori)
{
    $no = 1;
    $lembur = [];
    $ceklembur = DB::table('lembur')
        ->selectRaw('
        tanggal,
        tanggal_dari,
        tanggal_sampai,
        id_kantor,
        kode_dept,
        keterangan,
        kategori,
        istirahat,
        IFNULL(lembur_karyawan.nik,"ALL") as nik')
        ->leftJoin('lembur_karyawan', 'lembur.kode_lembur', '=', 'lembur_karyawan.kode_lembur')
        ->whereBetween('tanggal', [$dari, $sampai])
        ->where('kategori', $kategori)
        ->get();

    foreach ($ceklembur as $d) {
        $lembur[] = [
            'nik' => $d->nik,
            'id_kantor' => $d->id_kantor,
            'tanggal_lembur' => $d->tanggal,
            'tanggal_dari' => $d->tanggal_dari,
            'tanggal_sampai' => $d->tanggal_sampai,
            'keterangan' => $d->keterangan,
            'kategori' => $d->kategori,
            'istirahat' => $d->istirahat
        ];
    }

    return $lembur;
}

function cektgllibur($array, $search_list)
{

    // Create the result array
    $result = array();

    // Iterate over each array element
    foreach ($array as $key => $value) {

        // Iterate over each search condition
        foreach ($search_list as $k => $v) {

            // If the array element does not meet
            // the search condition then continue
            // to the next element
            if (!isset($value[$k]) || $value[$k] != $v) {

                // Skip two loops
                continue 2;
            }
        }

        // Append array element's key to the
        //result array
        $result[] = $value;
    }

    // Return result
    return $result;
}


function hitungjam($jadwal_jam_masuk, $jam_presensi)
{
    $j1 = strtotime($jadwal_jam_masuk);
    $j2 = strtotime($jam_presensi);

    $diffterlambat = $j2 - $j1;

    $jamterlambat = floor($diffterlambat / (60 * 60));
    $menitterlambat = floor(($diffterlambat - ($jamterlambat * (60 * 60))) / 60);

    $jterlambat = $jamterlambat <= 9 ? "0" . $jamterlambat : $jamterlambat;
    $mterlambat = $menitterlambat <= 9 ? "0" . $menitterlambat : $menitterlambat;


    $terlambat = $jterlambat . ":" . $mterlambat;
    return $terlambat;
}


function hitungjamdesimal($jam1, $jam2)
{
    $j1 = strtotime($jam1);
    $j2 = strtotime($jam2);

    $diffterlambat = $j2 - $j1;

    $jamterlambat = floor($diffterlambat / (60 * 60));
    $menitterlambat = floor(($diffterlambat - ($jamterlambat * (60 * 60))) / 60);



    $desimalterlambat = $jamterlambat + ROUND(($menitterlambat / 60), 2);

    return $desimalterlambat;
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
