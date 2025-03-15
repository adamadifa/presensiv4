<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class SlipgajiController extends Controller
{
    public function index()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $slipgaji = DB::table('slip_gaji')->where('nik', Auth::guard('karyawan')->user()->nik)->get();
        $slip_gaji_auto = DB::table('hrd_slipgaji')
            ->orderBy('tahun', 'desc')
            ->get();
        return view('slipgaji.index', compact('slipgaji', 'slip_gaji_auto', 'namabulan'));
    }



    public function cetak($bulan, $tahun)
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $slip_gaji = DB::table('slip_gaji')
            ->select('slip_gaji.*', 'hrd_karyawan.nama_karyawan', 'hrd_departemen.nama_dept', 'cabang.nama_cabang', 'hrd_jabatan.nama_jabatan', 'hrd_jabatan.kategori')
            ->join('hrd_karyawan', 'slip_gaji.nik', '=', 'hrd_karyawan.nik')
            ->join('hrd_departemen', 'hrd_karyawan.kode_dept', '=', 'hrd_departemen.kode_dept')
            ->join('cabang', 'hrd_karyawan.kode_cabang', '=', 'cabang.kode_cabang')
            ->join('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan')
            ->where('slip_gaji.nik', Auth::guard('karyawan')->user()->nik)->where('bulan', $bulan)->where('tahun', $tahun)->first();


        return view('slipgaji.cetak', compact('slip_gaji', 'bulan', 'tahun', 'namabulan'));
    }

    public function cetakslipgaji($bulan, $tahun)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $response = Http::get('https://app.portalmp.com/api/slipgaji/' . $bulan . '/' . $tahun . '/' . $nik);
        $data = $response->json(); // Mengubah response ke array
        $data['start_date'] = $data['start_date'];
        $data['end_date'] = $data['end_date'];

        $data['dataliburnasional'] = $data['dataliburnasional'];
        $data['datadirumahkan'] = $data['datadirumahkan'];
        $data['dataliburpengganti'] = $data['dataliburpengganti'];
        $data['dataminggumasuk'] = $data['dataminggumasuk'];
        $data['datatanggallimajam'] = $data['datatanggallimajam'];
        $data['datalembur'] = $data['datalembur'];
        $data['datalemburharilibur'] = $data['datalemburharilibur'];
        $data['jmlhari'] = $data['jmlhari'] + 1;
        $privillage_karyawan = [
            '16.11.266',
            '22.08.339',
            '19.10.142',
            '17.03.025',
            '00.12.062',
            '08.07.092',
            '16.05.259',
            '17.08.023',
            '15.10.043',
            '17.07.302',
            '15.10.143',
            '03.03.065',
            '23.12.337',
        ];
        $data['privillage_karyawan'] = $privillage_karyawan;

        return view('slipgaji.cetakslipgaji', $data);
    }





    public function cetakthr($bulan, $tahun)
    {

        $bl = $bulan;
        $kode_potongan = "GJ" . $bulan . $tahun;
        if ($bulan == 1) {
            $lastbulan = 12;
            $lasttahun = $tahun - 1; //2023
        } else {
            $lastbulan = $bulan - 1;
            $lasttahun = $tahun;
        }

        if ($bulan == 12) {
            $nextbulan = 1;
            $nexttahun = $tahun + 1;
        } else {
            $nextbulan = $bulan + 1;
            $nexttahun = $tahun;
        }
        $lastbulan = $lastbulan < 10 ?  "0" . $lastbulan : $lastbulan;
        $bulan = $bulan < 10 ?  "0" . $bulan : $bulan;

        $dari = $lasttahun . "-" . $lastbulan . "-21";
        $sampai = $tahun . "-" . $bulan . "-20";



        $daribulangaji = $dari;
        $berlakugaji = $sampai;
        $akhir_periode = $tahun . "-" . $bulan . "-01";
        $karyawan = DB::table('master_karyawan')
            ->selectRaw('master_karyawan.*,gaji_pokok,t_jabatan,t_masakerja,t_tanggungjawab,
             t_makan,t_istri,t_skill,nama_dept,nama_jabatan,nama_cabang')
            ->join('cabang', 'master_karyawan.id_kantor', '=', 'cabang.kode_cabang')
            ->join('hrd_departemen', 'master_karyawan.kode_dept', '=', 'hrd_departemen.kode_dept')
            ->join('hrd_jabatan', 'master_karyawan.id_jabatan', '=', 'hrd_jabatan.id')
            ->leftJoin(
                DB::raw("(
                        SELECT nik,gaji_pokok,t_jabatan,t_masakerja,t_tanggungjawab,
                        t_makan,t_istri,t_skill
                        FROM hrd_mastergaji
                        WHERE kode_gaji IN (SELECT MAX(kode_gaji) as kode_gaji FROM hrd_mastergaji
                        WHERE tgl_berlaku <= '$berlakugaji'  GROUP BY nik)
                    ) hrdgaji"),
                function ($join) {
                    $join->on('master_karyawan.nik', '=', 'hrdgaji.nik');
                }
            )
            ->where('master_karyawan.nik', Auth::guard('karyawan')->user()->nik)->first();
        return view('slipgaji.cetak_thr', compact('tahun', 'karyawan'));
    }
}
