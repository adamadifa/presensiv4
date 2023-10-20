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
        $presensihariini = DB::table('presensi')->where('nik', $nik)->where('tgl_presensi', $hariini)->first();
        $historibulanini = DB::table('presensi')
            ->select(
                'presensi.*',
                'nama_jadwal',
                'jadwal.kode_cabang',
                'jam_kerja.jam_masuk',
                'jenis_izin',
                'nama_cuti',
                'sid',
                'kode_dept',
                'pengajuan_izin.jam_keluar',
                'pengajuan_izin.jam_masuk as jam_masuk_kk',
                'jam_kerja.jam_pulang',
                'jam_kerja.lintashari',
                'total_jam',
                'master_karyawan.kode_dept',
                'master_karyawan.id_jabatan'
            )
            ->join('master_karyawan', 'presensi.nik', '=', 'master_karyawan.nik')
            ->leftJoin(
                DB::raw("(
                SELECT
                    jadwal_kerja_detail.kode_jadwal,nama_jadwal,kode_jam_kerja,kode_cabang
                FROM
                    jadwal_kerja_detail
                INNER JOIN jadwal_kerja ON jadwal_kerja_detail.kode_jadwal = jadwal_kerja.kode_jadwal
                GROUP BY
                jadwal_kerja_detail.kode_jadwal,nama_jadwal,kode_jam_kerja,kode_cabang
                ) jadwal"),
                function ($join) {
                    $join->on('presensi.kode_jadwal', '=', 'jadwal.kode_jadwal');
                    $join->on('presensi.kode_jam_kerja', '=', 'jadwal.kode_jam_kerja');
                }
            )
            ->leftjoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->leftjoin('pengajuan_izin', 'presensi.kode_izin', '=', 'pengajuan_izin.kode_izin')
            ->leftjoin('hrd_mastercuti', 'pengajuan_izin.jenis_cuti', '=', 'hrd_mastercuti.kode_cuti')
            ->where('presensi.nik', $nik)
            ->where('tgl_presensi', '<=', $hariini)
            ->orderBy('tgl_presensi', 'desc')
            ->limit(7)
            ->get();



        $rekappresensi = DB::table('presensi')
            ->selectRaw('SUM(IF(status="h",1,0)) as jmlhadir,
            SUM(IF(status="i",1,0)) as jmlizin,
            SUM(IF(status="s",1,0)) as jmlsakit,
            SUM(IF( DATE_FORMAT(jam_in,"%H:%i") > DATE_FORMAT(jam_masuk,"%H:%i"),1,0)) as jmlterlambat')
            ->leftjoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->where('nik', $nik)
            ->whereRaw('MONTH(tgl_presensi)="' . $bulanini . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahunini . '"')
            ->first();


        $leaderboard = DB::table('presensi')
            ->leftjoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->join('master_karyawan', 'presensi.nik', '=', 'master_karyawan.nik')
            ->leftjoin('hrd_jabatan', 'master_karyawan.id_jabatan', '=', 'hrd_jabatan.id')
            ->where('tgl_presensi', $hariini)
            ->where('id_kantor', Auth::guard('karyawan')->user()->id_kantor)
            ->where('presensi.status', 'h')
            ->orderBy('jam_in')
            ->get();
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

        // $rekapizin = DB::table('pengajuan_izin')
        //     ->selectRaw('SUM(IF(status="i",1,0)) as jmlizin,SUM(IF(status="s",1,0)) as jmlsakit')
        //     ->where('nik', $nik)
        //     ->whereRaw('MONTH(tgl_izin)="' . $bulanini . '"')
        //     ->whereRaw('YEAR(tgl_izin)="' . $tahunini . '"')
        //     ->where('status_approved', 1)
        //     ->first();

        $jabatan = DB::table('hrd_jabatan')->where('id', Auth::guard('karyawan')->user()->id_jabatan)->first();
        $kode_dept = Auth::guard('karyawan')->user()->kode_dept;
        $id_kantor = Auth::guard('karyawan')->user()->id_kantor;
        if ($kode_dept == "MKT" || $id_kantor != "PST") {
            return view('dashboard.dashboardwithcamera', compact('presensihariini', 'historibulanini', 'namabulan', 'bulanini', 'tahunini', 'rekappresensi', 'leaderboard', 'jabatan'));
        } else {
            return view('dashboard.dashboard', compact('presensihariini', 'historibulanini', 'namabulan', 'bulanini', 'tahunini', 'rekappresensi', 'leaderboard', 'jabatan'));
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
