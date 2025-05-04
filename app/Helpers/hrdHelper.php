<?php

// Cek Role Approve Penilaian

use App\Models\Detailharilibur;
use App\Models\Detaillembur;
use App\Models\Harilibur;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

function cekRoleapprove($kode_dept, $kode_cabang, $kategori_jabatan, $kode_jabatan = "")
{
    // Cek Role Name
    $role = Auth::user()->roles->pluck('name')[0];

    if ($kode_dept == 'AKT' && $kode_cabang != 'PST' && $kategori_jabatan == 'NM') {
        //Akunting Cabang Non Manajemen
        $roles_approve =  ['operation manager', 'regional operation manager', 'gm administrasi', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == 'AKT' && $kode_cabang == 'PST' && $kategori_jabatan == 'NM') {
        //Akunting Pusat Non Manajemen
        $roles_approve =  ['manager keuangan', 'gm administrasi', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == 'KEU' && $kode_cabang == 'PST' && $kategori_jabatan == 'NM') {
        //Akunting Pusat Non Manajemen
        $roles_approve =  ['manager keuangan', 'gm administrasi', 'asst. manager hrd', 'direktur'];
    } else if ($kode_jabatan == 'J08') {
        //Operation Manager
        $roles_approve =  ['regional operation manager', 'gm administrasi', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == 'MKT' && $kode_cabang != 'PST' && $kategori_jabatan == 'NM') {
        //Marketing Cabang Non Manajemen
        $roles_approve =  ['sales marketing manager', 'regional sales manager', 'gm marketing', 'asst. manager hrd', 'direktur'];
    } else if ($kode_jabatan == 'J07') {
        //Sales Marketing Manager
        $roles_approve =  ['regional sales manager', 'gm marketing', 'asst. manager hrd', 'direktur'];
    } else if ($kode_jabatan == 'J03') {
        //Regional Sales Marketing Manager
        $roles_approve =  ['gm marketing', 'asst. manager hrd', 'direktur'];
    } else if (in_array($kode_dept, ['GAF', 'PMB', 'GDG', 'MTC', 'PRD', 'PDQ']) && in_array($kode_jabatan, ['J05', 'J06'])) {
        $roles_approve =  ['gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == 'GAF'  && $kategori_jabatan == 'NM') {
        $roles_approve =  ['manager general affair', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == 'PDQ') {
        $roles_approve =  ['gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == 'GDG' && $kategori_jabatan == "NM") {
        $roles_approve =  ['manager gudang', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == 'HRD' && $kategori_jabatan == "NM") {
        $roles_approve =  ['asst. manager hrd', 'gm operasional', 'direktur'];
    } else if ($kode_dept == 'MTC' && $kategori_jabatan == "NM") {
        $roles_approve =  ['manager maintenance', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == 'PMB' && $kategori_jabatan == "NM") {
        $roles_approve =  ['manager pembelian', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == 'PRD' && $kategori_jabatan == "NM") {
        $roles_approve =  ['manager produksi', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else {
        $roles_approve =  ['regional operation manager', 'gm administrasi', 'asst. manager hrd', 'direktur'];
    }



    return $roles_approve;
}



function listApprovepenilaian($kode_dept, $level = "")
{
    $list_approve = [];
    if ($kode_dept == "AKT") {
        $list_approve =  ['operation manager', 'regional operation manager', 'manager keuangan', 'gm administrasi', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == "MKT") {
        $list_approve =  ['sales marketing manager', 'regional sales manager', 'gm marketing', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == "GAF") {
        $list_approve =  ['manager general affair', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == "MTC") {
        $list_approve =  ['manager maintenance', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == "PMB") {
        $list_approve =  ['manager pembelian', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == "PRD") {
        $list_approve =  ['manager produksi', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == "GDG") {
        $list_approve =  ['manager gudang', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == "PDQ") {
        $list_approve =  ['gm operasional', 'asst. manager hrd', 'direktur'];
    }

    if ($level == "manager keuangan") {
        $list_approve =  ['manager keuangan', 'gm administrasi', 'asst. manager hrd', 'direktur'];
    } else if ($level == "regional operation manager") {
        $list_approve =  ['regional operation manager', 'gm administrasi', 'asst. manager hrd', 'direktur'];
    } else if ($level == "manager gudang") {
        $list_approve =  ['manager gudang', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($level == "manager maintenance") {
        $list_approve =  ['manager maintenance', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($level == "manager pembelian") {
        $list_approve =  ['manager pembelian', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($level == "manager produksi") {
        $list_approve =  ['manager produksi', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($level == "manager general affair") {
        $list_approve =  ['manager general affair', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($level == "regional sales manager") {
        $list_approve =  ['regional sales manager', 'asst. manager hrd', 'direktur'];
    } else if ($level == "gm administrasi") {
        $list_approve =  ['gm administrasi', 'asst. manager hrd', 'direktur'];
    } else if ($level == "gm marketing") {
        $list_approve =  ['gm marketing', 'asst. manager hrd', 'direktur'];
    } else if ($level == "gm operasional") {
        $list_approve =  ['gm operasional', 'asst. manager hrd', 'direktur'];
    } else if (in_array($level, ['super admin', 'asst. manager hrd'])) {
        $list_approve =  [
            'operation manager',
            'sales marketing manager',
            'regional sales manager',
            'regional operation manager',
            'manager keuangan',
            'manager gudang',
            'manager maintenance',
            'manager pembelian',
            'manager produksi',
            'manager general affair',
            'gm administrasi',
            'gm marketing',
            'gm operasional',
            'asst. manager hrd',
            'direktur'
        ];
    }
    return $list_approve;
}


function listApprovepresensi($kode_dept = "", $kode_cabang = "", $level = "")
{
    $user = User::find(auth()->user()->id);
    //Jika user Memiliki Permission izinabsen.create

    $list_approve = [];
    if ($kode_cabang != "PST" && $user->hasPermissionTo('izinabsen.create')) {
        $list_approve =  [
            'operation manager',
            'sales marketing manager',
            'regional sales manager',
            'manager keuangan',
            'gm administrasi',
            'gm marketing',
            'asst. manager hrd',
            'direktur'
        ];
    } else if ($kode_cabang != "PST" && $kode_dept == "AKT") {
        $list_approve =  [
            'operation manager',
            'manager keuangan',
            'gm administrasi',
            'asst. manager hrd',
            'direktur'
        ];
    } else if ($kode_cabang != "PST" && $kode_dept == "MKT") {
        $list_approve =  [
            'sales marketing manager',
            'regional sales manager',
            'gm marketing',
            'asst. manager hrd',
            'direktur'
        ];
    } else if ($kode_dept == "GAF") {
        $list_approve =  ['manager general affair', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == "MTC") {
        $list_approve =  ['manager maintenance', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == "PMB") {
        $list_approve =  ['manager pembelian', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == "PRD") {
        $list_approve =  ['manager produksi', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == "GDG") {
        $list_approve =  ['manager gudang', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == "PDQ") {
        $list_approve =  ['gm operasional', 'asst. manager hrd', 'direktur'];
    }

    if ($level == "manager keuangan") {
        $list_approve =  ['manager keuangan', 'gm administrasi', 'asst. manager hrd', 'direktur'];
    } else if ($level == "manager gudang") {
        $list_approve =  ['manager gudang', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($level == "manager maintenance") {
        $list_approve =  ['manager maintenance', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($level == "manager pembelian") {
        $list_approve =  ['manager pembelian', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($level == "manager produksi") {
        $list_approve =  ['manager produksi', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($level == "manager general affair") {
        $list_approve =  ['manager general affair', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($level == "regional sales manager") {
        $list_approve =  ['regional sales manager', 'asst. manager hrd', 'direktur'];
    } else if ($level == "gm administrasi") {
        $list_approve =  ['gm administrasi', 'asst. manager hrd', 'direktur'];
    } else if ($level == "gm marketing") {
        $list_approve =  ['gm marketing', 'asst. manager hrd', 'direktur'];
    } else if ($level == "gm operasional") {
        $list_approve =  ['gm operasional', 'asst. manager hrd', 'direktur'];
    } else if (in_array($level, ['super admin', 'asst. manager hrd'])) {
        $list_approve =  [
            'operation manager',
            'sales marketing manager',
            'regional sales manager',
            'manager keuangan',
            'manager gudang',
            'manager maintenance',
            'manager pembelian',
            'manager produksi',
            'manager general affair',
            'gm administrasi',
            'gm marketing',
            'gm operasional',
            'asst. manager hrd',
            'direktur'
        ];
    }
    return $list_approve;
}



function cekRoleapprovelembur($kode_dept)
{
    if ($kode_dept == "GAF") {
        $roles_approve =  ['manager general affair', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == "MTC") {
        $roles_approve =  ['manager maintenance', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == "PRD") {
        $roles_approve =  ['manager produksi', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == "PDQ") {
        $roles_approve =  ['gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == "GDG") {
        $roles_approve =  ['manager gudang', 'gm operasional', 'asst. manager hrd', 'direktur'];
    }

    return $roles_approve;
}



function cekRoleapprovepresensi($kode_dept, $kode_cabang, $kategori_jabatan, $kode_jabatan = "")
{
    // Cek Role Name
    // $role = Auth::user()->roles->pluck('name')[0];

    if ($kode_dept == 'AKT' && $kode_cabang != 'PST' && $kategori_jabatan == 'NM') {  //Akunting Cabang Non Manajemen
        $roles_approve =  ['operation manager', 'asst. manager hrd'];
    } else if (in_array($kode_dept, ['AKT', 'KEU']) && $kode_cabang == 'PST' && $kategori_jabatan == 'NM') { //Akunting dan  Keuangan Pusat Non Manajemen
        if (in_array($kode_jabatan, ['J25', 'J26', 'J27'])) {
            $roles_approve = ['gm administrasi', 'asst. manager hrd'];
        } else {
            $roles_approve =  ['manager keuangan', 'asst. manager hrd'];
        }
    } else if (in_array($kode_dept, ['AKT', 'KEU']) && $kode_cabang == 'PST' && in_array($kode_jabatan, ['J28'])) { //Akunting dan  Keuangan Pusat  Manajemen
        $roles_approve =  ['manager keuangan', 'asst. manager hrd'];
    } else if (in_array($kode_dept, ['AKT', 'KEU']) && $kode_cabang == 'PST' && in_array($kode_jabatan, ['J04', 'J05', 'J06'])) { //Akunting dan  Keuangan Pusat  Manajemen
        $roles_approve =  ['gm administrasi', 'asst. manager hrd'];
    } else if ($kode_jabatan == 'J08') { //Operation Manager
        $roles_approve =  ['regional operation manager', 'asst. manager hrd'];
    } else if ($kode_dept == 'MKT' && $kode_cabang != 'PST' && $kategori_jabatan == 'NM') { //Marketing Cabang Non Manajemen
        $roles_approve =  ['sales marketing manager',  'asst. manager hrd'];
    } else if ($kode_jabatan == 'J03') { //Sales Marketing Manager
        $roles_approve =  ['gm marketing', 'asst. manager hrd'];
    } else if ($kode_jabatan == 'J07') { //Sales Marketing Manager
        $roles_approve =  ['regional sales manager', 'asst. manager hrd'];
    } else if (in_array($kode_dept, ['GAF', 'PMB', 'GDG', 'MTC', 'PRD', 'PDQ']) && in_array($kode_jabatan, ['J05', 'J06'])) { //GAF, PMB, GDG, MTC, PRD, PDQ MANAGER / ASST. MANAGER HRD
        $roles_approve =  ['gm operasional', 'asst. manager hrd'];
    } else if ($kode_dept == 'GAF'  && $kategori_jabatan == 'NM') { //GAF Non Manajemen
        $roles_approve =  ['manager general affair', 'asst. manager hrd'];
    } else if ($kode_dept == 'PDQ') {
        $roles_approve =  ['gm operasional', 'asst. manager hrd'];
    } else if ($kode_dept == 'GDG' && $kategori_jabatan == "NM") { // Gudang Non Manajemen
        $roles_approve =  ['manager gudang', 'asst. manager hrd'];
    } else if ($kode_dept == 'HRD' && $kategori_jabatan == "NM") { // HRD Non Manajemen
        $roles_approve =  ['asst. manager hrd'];
    } else if ($kode_dept == 'HRD' && $kategori_jabatan != "NM") { // HRD Non Manajemen
        $roles_approve =  ['gm operasional', 'asst. manager hrd'];
    } else if ($kode_dept == 'MTC' && $kategori_jabatan == "NM") { // Maintenance Non Manajemen
        $roles_approve =  ['manager maintenance', 'asst. manager hrd'];
    } else if ($kode_dept == 'PMB' && $kategori_jabatan == "NM") { //Pembelian Non Manajemen
        $roles_approve =  ['manager pembelian',  'asst. manager hrd'];
    } else if ($kode_dept == 'PRD' && $kategori_jabatan == "NM") { //Produksi Non Manajemen
        $roles_approve =  ['manager produksi', 'asst. manager hrd'];
    } else if ($kode_dept == 'ADT' && $kategori_jabatan == "NM") { //Produksi Non Manajemen
        $roles_approve =  ['manager audit', 'asst. manager hrd'];
    } else {
        $roles_approve =  ['asst. manager hrd'];
    }

    return $roles_approve;
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


function hitungHari($startDate, $endDate)
{
    if ($startDate && $endDate) {
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);

        // Tambahkan 1 hari agar penghitungan inklusif
        $interval = $start->diff($end);
        $dayDifference = $interval->days + 1;

        return  $dayDifference;
    } else {
        return 0;
    }
}


function hitungSisaHari($endDate)
{
    $today = new DateTime();
    $end = new DateTime($endDate);

    $interval = $today->diff($end);
    $daysRemaining = $interval->days;

    if ($today > $end) {
        $daysRemaining = -$daysRemaining;
    }

    return $daysRemaining;
}


function getNamahari($date)
{
    $days = array(
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu'
    );
    $dayName = date('l', strtotime($date));
    return $days[$dayName];
}


function getSid($file)
{
    $url = url('/storage/uploads/sid/' . $file);
    return $url;
}



function getfileCuti($file)
{
    $url = url('/storage/uploads/cuti/' . $file);
    return $url;
}


function hitungjamkeluarkantor($jam_keluar, $j_kembali, $jam_selesai, $jam_out, $total_jam, $istirahat, $jam_awal_istirahat, $jam_akhir_istirahat)
{

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

    return [
        'totaljamkeluar' => $totaljamkeluar,
        'jamkeluarkantor' => $jamkeluarkantor,
        'color' => $jamkeluarkantor > 0 ? 'text-danger' : 'text-success',
        'desimaljamkeluar' => $desimaljamkeluar
    ];
}


function hitungjamterlambat($jam_in, $jam_mulai, $kode_izin_terlambat)
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


function hitungdenda($jamterlambat, $menitterlambat, $kode_izin_terlambat, $kode_dept, $kode_jabatan)
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


            //JIka Sudah Izin
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
            $keterangan = "Potong JK";
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


function hitungpulangcepat($jam_out, $jam_selesai, $jam_awal_istirahat, $jam_akhir_istirahat)
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
                'keterangan_pulangcepat' => $keterangan_pulangcepat,
                'desimal_pulangcepat' => $desimal_pulangcepat
            ];
        } else {
            return [
                'keterangan_pulangcepat' => "",
                'desimal_pulangcepat' => 0
            ];
        }
    } else {
        return [
            'keterangan_pulangcepat' => "",
            'desimal_pulangcepat' => 0
        ];
    }
}



function getdataliburnasional($dari, $sampai)
{
    $no = 1;
    $libur = [];
    $ceklibur = Detailharilibur::select(
        'nik',
        'tanggal',
        'kode_cabang',
        'keterangan',
        'tanggal_limajam'
    )
        ->leftJoin('hrd_harilibur', 'hrd_harilibur_detail.kode_libur', '=', 'hrd_harilibur.kode_libur')
        ->where('kategori', 1)
        ->whereBetween('tanggal', [$dari, $sampai])->get();

    foreach ($ceklibur as $d) {
        $libur[] = [
            'nik' => $d->nik,
            'kode_cabang' => $d->kode_cabang,
            'tanggal' => $d->tanggal,
            'tanggal_limajam' => $d->tanggal_limajam,
            'keterangan' => $d->keterangan
        ];
    }

    return $libur;
}


function gettanggallimajam($dari, $sampai)
{
    $no = 1;
    $libur = [];
    $ceklibur = Detailharilibur::select(
        'nik',
        'tanggal',
        'kode_cabang',
        'keterangan',
        'tanggal_limajam'
    )
        ->leftJoin('hrd_harilibur', 'hrd_harilibur_detail.kode_libur', '=', 'hrd_harilibur.kode_libur')
        ->where('kategori', 1)
        ->whereBetween('tanggal_limajam', [$dari, $sampai])->get();

    foreach ($ceklibur as $d) {
        $libur[] = [
            'nik' => $d->nik,
            'kode_cabang' => $d->kode_cabang,
            'tanggal' => $d->tanggal,
            'tanggal_limajam' => $d->tanggal_limajam,
            'keterangan' => $d->keterangan
        ];
    }

    return $libur;
}

function getdirumahkan($dari, $sampai)
{
    $no = 1;
    $libur = [];
    $ceklibur = Detailharilibur::select(
        'nik',
        'tanggal',
        'kode_cabang',
        'keterangan',
    )
        ->leftJoin('hrd_harilibur', 'hrd_harilibur_detail.kode_libur', '=', 'hrd_harilibur.kode_libur')
        ->where('kategori', 3)
        ->whereBetween('tanggal', [$dari, $sampai])->get();

    foreach ($ceklibur as $d) {
        $libur[] = [
            'nik' => $d->nik,
            'kode_cabang' => $d->kode_cabang,
            'tanggal' => $d->tanggal,
            'keterangan' => $d->keterangan
        ];
    }

    return $libur;
}

function getliburpengganti($dari, $sampai)
{
    $no = 1;
    $libur = [];
    $ceklibur = Detailharilibur::select(
        'nik',
        'tanggal',
        'tanggal_diganti',
        'kode_cabang',
        'keterangan',
    )
        ->leftJoin('hrd_harilibur', 'hrd_harilibur_detail.kode_libur', '=', 'hrd_harilibur.kode_libur')
        ->where('kategori', 2)
        ->whereBetween('tanggal', [$dari, $sampai])->get();

    foreach ($ceklibur as $d) {
        $libur[] = [
            'nik' => $d->nik,
            'kode_cabang' => $d->kode_cabang,
            'tanggal' => $d->tanggal,
            'tanggal_diganti' => $d->tanggal_diganti,
            'keterangan' => $d->keterangan
        ];
    }

    return $libur;
}



function getminggumasuk($dari, $sampai)
{
    $no = 1;
    $libur = [];
    $ceklibur = Detailharilibur::select(
        'nik',
        'tanggal',
        'tanggal_diganti',
        'kode_cabang',
        'keterangan',
    )
        ->leftJoin('hrd_harilibur', 'hrd_harilibur_detail.kode_libur', '=', 'hrd_harilibur.kode_libur')
        ->where('kategori', 2)
        ->whereBetween('tanggal_diganti', [$dari, $sampai])->get();

    foreach ($ceklibur as $d) {
        $libur[] = [
            'nik' => $d->nik,
            'kode_cabang' => $d->kode_cabang,
            'tanggal' => $d->tanggal,
            'tanggal_diganti' => $d->tanggal_diganti,
            'keterangan' => $d->keterangan
        ];
    }

    return $libur;
}

function getlembur($dari, $sampai, $kategori)
{
    $no = 1;
    $lembur = [];
    $ceklembur = Detaillembur::select(
        'nik',
        'tanggal',
        'tanggal_dari',
        'tanggal_sampai',
        'kode_cabang',
        'kode_dept',
        'keterangan',
        'kategori',
        'istirahat',
    )
        ->join('hrd_lembur', 'hrd_lembur_detail.kode_lembur', '=', 'hrd_lembur.kode_lembur')
        ->whereBetween('tanggal', [$dari, $sampai])
        ->where('kategori', $kategori)
        ->get();


    foreach ($ceklembur as $d) {
        $lembur[] = [
            'nik' => $d->nik,
            'id_kantor' => $d->id_kantor,
            'tanggal' => $d->tanggal,
            'tanggal_dari' => $d->tanggal_dari,
            'tanggal_sampai' => $d->tanggal_sampai,
            'keterangan' => $d->keterangan,
            'kategori' => $d->kategori,
            'istirahat' => $d->istirahat
        ];
    }

    return $lembur;
}

function ceklibur($array, $search_list)
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



function ceklembur($array, $search_list)
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


function DateToIndo($date2)
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

function hitungMasakerja($tanggal_masuk, $tanggal_sampai)
{
    $joinDate = Carbon::parse($tanggal_masuk);
    $currentDate = Carbon::parse($tanggal_sampai);

    $diffYears = $joinDate->diffInYears($currentDate);
    $diffMonths = $joinDate->copy()->addYears($diffYears)->diffInMonths($currentDate);
    $diffDays = $joinDate->copy()->addYears($diffYears)->addMonths($diffMonths)->diffInDays($currentDate);

    return [
        'tahun' => $diffYears,
        'bulan' => $diffMonths,
        'hari' => $diffDays
    ];
}
