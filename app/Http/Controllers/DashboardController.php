<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {

        //dd(Auth::guard('karyawan')->user());
        $hariini = date("Y-m-d");
        // $hariini = "2023-05-05";
        $bulanini = date("m") * 1; //1 atau Januari
        $tahunini = date("Y"); // 2023
        $nik = Auth::guard('karyawan')->user()->nik;
        $data['presensi_hariini'] = DB::table('hrd_presensi')->where('nik', $nik)->where('tanggal', $hariini)->first();


        //Rekap Presensi
        $data['rekap_presensi'] = DB::table('hrd_presensi')
            ->selectRaw('SUM(IF(status="h",1,0)) as jmlhadir,
            SUM(IF(status="i",1,0)) as jmlizin,
            SUM(IF(status="s",1,0)) as jmlsakit,
            SUM(IF( DATE_FORMAT(jam_in,"%H:%i") > DATE_FORMAT(jam_masuk,"%H:%i"),1,0)) as jmlterlambat')
            ->leftjoin('hrd_jamkerja', 'hrd_presensi.kode_jam_kerja', '=', 'hrd_jamkerja.kode_jam_kerja')
            ->where('nik', $nik)
            ->whereRaw('MONTH(tanggal)="' . $bulanini . '"')
            ->whereRaw('YEAR(tanggal)="' . $tahunini . '"')
            ->first();

        //Histori 7 Hari Terakhir
        $data['histori'] = DB::table('hrd_presensi')
            ->select(
                'hrd_presensi.*',
                'hrd_jamkerja.jam_masuk as jam_mulai',
                'hrd_jamkerja.jam_pulang as jam_selesai',
                'hrd_jamkerja.lintashari',
                'hrd_karyawan.kode_jabatan',
            )
            ->join('hrd_karyawan', 'hrd_presensi.nik', '=', 'hrd_karyawan.nik')
            ->leftJoin('hrd_jamkerja', 'hrd_presensi.kode_jam_kerja', '=', 'hrd_jamkerja.kode_jam_kerja')
            ->leftJoin('hrd_jadwalkerja', 'hrd_presensi.kode_jadwal', '=', 'hrd_jadwalkerja.kode_jadwal')
            ->leftJoin('hrd_izinterlambat', 'hrd_presensi.kode_izin', '=', 'hrd_izinterlambat.kode_izin')
            ->where('hrd_presensi.nik', $nik)
            ->where('hrd_presensi.tanggal', '<=', $hariini)
            ->orderBy('hrd_presensi.tanggal', 'desc')
            ->limit(7)
            ->get();
        // $historibulanini = DB::table('hrd_presensi')
        //     ->select(
        //         'hrd_presensi.*',
        //         'nama_jadwal',
        //         'hrd_jadwalkerja.kode_cabang',
        //         'hrd_jam_kerja.jam_masuk',
        //         'nama_cuti',
        //         'doc_sid',
        //         'hrd_izinkeluar.jam_keluar',
        //         'hrd_izinkeluar.jam_kembali',
        //         'hrd_jam_kerja.jam_pulang',
        //         'hrd_jam_kerja.lintashari',
        //         'hrd_jam_kerja.total_jam',
        //         'hrd_karyawan.kode_dept',
        //         'hrd_karyawan.kode_jabatan',
        //     )
        //     ->join('hrd_karyawan', 'hrd_presensi.nik', '=', 'hrd_karyawan.nik')
        //     ->leftJoin(
        //         DB::raw("(
        //         SELECT
        //             hrd_jadwalkerja_detail.kode_jadwal,nama_jadwal,kode_jam_kerja,kode_cabang
        //         FROM
        //             hrd_jadwalkerja_detail
        //         INNER JOIN hrd_jadwalkerja ON hrd_jadwalkerja_detail.kode_jadwal = hrd_jadwalkerja.kode_jadwal
        //         GROUP BY
        //         hrd_jadwalkerja_detail.kode_jadwal,nama_jadwal,kode_jam_kerja,kode_cabang
        //         ) jadwal"),
        //         function ($join) {
        //             $join->on('hrd_presensi.kode_jadwal', '=', 'jadwal.kode_jadwal');
        //             $join->on('hrd_presensi.kode_jam_kerja', '=', 'jadwal.kode_jam_kerja');
        //         }
        //     )
        //     ->leftjoin('hrd_jam_kerja', 'hrd_presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
        //     ->leftjoin('pengajuan_izin', 'presensi.kode_izin', '=', 'pengajuan_izin.kode_izin')
        //     ->leftjoin('hrd_mastercuti', 'pengajuan_izin.jenis_cuti', '=', 'hrd_mastercuti.kode_cuti')
        //     ->where('presensi.nik', $nik)
        //     ->where('tgl_presensi', '<=', $hariini)
        //     ->orderBy('tgl_presensi', 'desc')
        //     ->limit(7)
        //     ->get();






        // $leaderboard = DB::table('presensi')
        //     ->leftjoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
        //     ->join('master_karyawan', 'presensi.nik', '=', 'master_karyawan.nik')
        //     ->leftjoin('hrd_jabatan', 'master_karyawan.id_jabatan', '=', 'hrd_jabatan.id')
        //     ->where('tgl_presensi', $hariini)
        //     ->where('id_kantor', Auth::guard('karyawan')->user()->id_kantor)
        //     ->where('presensi.status', 'h')
        //     ->orderBy('jam_in')
        //     ->get();
        $data['namabulan'] = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

        // $rekapizin = DB::table('pengajuan_izin')
        //     ->selectRaw('SUM(IF(status="i",1,0)) as jmlizin,SUM(IF(status="s",1,0)) as jmlsakit')
        //     ->where('nik', $nik)
        //     ->whereRaw('MONTH(tgl_izin)="' . $bulanini . '"')
        //     ->whereRaw('YEAR(tgl_izin)="' . $tahunini . '"')
        //     ->where('status_approved', 1)
        //     ->first();

        $jabatan = DB::table('hrd_jabatan')->where('kode_jabatan', Auth::guard('karyawan')->user()->kode_jabatan)->first();
        $kode_dept = Auth::guard('karyawan')->user()->kode_dept;
        $kode_cabang = Auth::guard('karyawan')->user()->kode_cabang;
        $data['jabatan'] = $jabatan;
        $data['kode_dept'] = $kode_dept;
        $data['kode_cabang'] = $kode_cabang;
        $data['bulanini'] = $bulanini;
        $data['tahunini'] = $tahunini;
        if ($kode_dept == "MKT" || $kode_cabang != "PST") {
            return view('dashboard.dashboardwithcamera', compact('presensihariini', 'historibulanini', 'namabulan', 'bulanini', 'tahunini', 'rekappresensi', 'leaderboard', 'jabatan'));
        } else {
            return view('dashboard.dashboard', $data);
        }
    }

    public function dashboardadmin()
    {
        $hariini = date("Y-m-d");
        $rekappresensi = DB::table('presensi')
            ->selectRaw('COUNT(nik) as jmlhadir, SUM(IF(jam_in > "07:00",1,0)) as jmlterlambat')
            ->where('tgl_presensi', $hariini)
            ->first();

        $rekapizin = DB::table('pengajuan_izin')
            ->selectRaw('SUM(IF(status="i",1,0)) as jmlizin,SUM(IF(status="s",1,0)) as jmlsakit')
            ->where('tgl_izin', $hariini)
            ->where('status_approved', 1)
            ->first();


        return view('dashboard.dashboardadmin', compact('rekappresensi', 'rekapizin'));
    }
}
