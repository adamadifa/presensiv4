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
                'hrd_karyawan.kode_dept',
                'hrd_presensi_izinterlambat.kode_izin_terlambat',
                'hrd_presensi_izinkeluar.kode_izin_keluar',
                'hrd_izinkeluar.jam_keluar',
                'hrd_izinkeluar.jam_kembali',
                'hrd_jamkerja.total_jam',
                'hrd_jamkerja.istirahat',
                'hrd_jamkerja.jam_awal_istirahat',
                'hrd_jamkerja.jam_akhir_istirahat',
                'hrd_presensi_izinpulang.kode_izin_pulang',
                'hrd_jadwalkerja.nama_jadwal',
                'hrd_karyawan.kode_cabang',
                'hrd_presensi.status',
                'nama_cuti',
                'nama_cuti_khusus',
                'doc_sid'
            )
            ->join('hrd_karyawan', 'hrd_presensi.nik', '=', 'hrd_karyawan.nik')
            ->leftJoin('hrd_jamkerja', 'hrd_presensi.kode_jam_kerja', '=', 'hrd_jamkerja.kode_jam_kerja')
            ->leftJoin('hrd_jadwalkerja', 'hrd_presensi.kode_jadwal', '=', 'hrd_jadwalkerja.kode_jadwal')

            ->leftJoin('hrd_presensi_izinterlambat', 'hrd_presensi.id', '=', 'hrd_presensi_izinterlambat.id_presensi')
            ->leftJoin('hrd_izinterlambat', 'hrd_presensi_izinterlambat.kode_izin_terlambat', '=', 'hrd_izinterlambat.kode_izin_terlambat')

            ->leftJoin('hrd_presensi_izinkeluar', 'hrd_presensi.id', '=', 'hrd_presensi_izinkeluar.id_presensi')
            ->leftJoin('hrd_izinkeluar', 'hrd_presensi_izinkeluar.kode_izin_keluar', '=', 'hrd_izinkeluar.kode_izin_keluar')

            ->leftJoin('hrd_presensi_izinpulang', 'hrd_presensi.id', '=', 'hrd_presensi_izinpulang.id_presensi')
            ->leftJoin('hrd_izinpulang', 'hrd_presensi_izinpulang.kode_izin_pulang', '=', 'hrd_izinpulang.kode_izin_pulang')

            ->leftJoin('hrd_presensi_izincuti', 'hrd_presensi.id', '=', 'hrd_presensi_izincuti.id_presensi')
            ->leftJoin('hrd_izincuti', 'hrd_presensi_izincuti.kode_izin_cuti', '=', 'hrd_izincuti.kode_izin_cuti')
            ->leftJoin('hrd_jeniscuti', 'hrd_izincuti.kode_cuti', '=', 'hrd_jeniscuti.kode_cuti')
            ->leftJoin('hrd_jeniscuti_khusus', 'hrd_izincuti.kode_cuti_khusus', '=', 'hrd_jeniscuti_khusus.kode_cuti_khusus')

            ->leftJoin('hrd_presensi_izinsakit', 'hrd_presensi.id', '=', 'hrd_presensi_izinsakit.id_presensi')
            ->leftJoin('hrd_izinsakit', 'hrd_presensi_izinsakit.kode_izin_sakit', '=', 'hrd_izinsakit.kode_izin_sakit')

            ->where('hrd_presensi.nik', $nik)
            ->where('hrd_presensi.tanggal', '<=', $hariini)
            ->orderBy('hrd_presensi.tanggal', 'desc')
            ->limit(7)
            ->get();

        $data['namabulan'] = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];


        $jabatan = DB::table('hrd_jabatan')->where('kode_jabatan', Auth::guard('karyawan')->user()->kode_jabatan)->first();
        $kode_dept = Auth::guard('karyawan')->user()->kode_dept;
        $kode_cabang = Auth::guard('karyawan')->user()->kode_cabang;
        $data['jabatan'] = $jabatan;
        $data['kode_dept'] = $kode_dept;
        $data['kode_cabang'] = $kode_cabang;
        $data['bulanini'] = $bulanini;
        $data['tahunini'] = $tahunini;
        if ($kode_dept == "MKT" || $kode_cabang != "PST") {
            return view('dashboard.dashboardwithcamera', $data);
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
