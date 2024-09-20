<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Pinjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class PinjamanController extends Controller
{
    public function index(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $query = Pinjaman::query();
        $query->select('keuangan_pjp.*', 'nama_karyawan', 'nama_jabatan', 'nama_dept', 'totalpembayaran');
        $query->join('hrd_karyawan', 'keuangan_pjp.nik', '=', 'hrd_karyawan.nik');
        $query->join('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');
        $query->join('hrd_departemen', 'hrd_karyawan.kode_dept', '=', 'hrd_departemen.kode_dept');
        $query->leftJoin(
            DB::raw("(
            SELECT no_pinjaman,SUM(jumlah) as totalpembayaran FROM keuangan_pjp_historibayar GROUP BY no_pinjaman
        ) hb"),
            function ($join) {
                $join->on('keuangan_pjp.no_pinjaman', '=', 'hb.no_pinjaman');
            }
        );
        if (!empty($request->bulan)) {
            $query->whereRaw('MONTH(tanggal)="' . $request->bulan . '"');
        }

        if (!empty($request->tahun)) {
            $query->whereRaw('YEAR(tanggal)="' . $request->tahun . '"');
        }


        $query->where('keuangan_pjp.nik', $nik);
        $query->orderBy('no_pinjaman', 'desc');
        if (empty($request->tahun) && empty($request->bulan)) {
            $query->limit(7);
            $pinjaman = $query->get();
        } else {
            $pinjaman = $query->get();
        }


        return view('pinjaman.index', compact('namabulan', 'pinjaman'));
    }


    public function show($no_pinjaman)
    {
        $no_pinjaman = Crypt::decrypt($no_pinjaman);
        $query = Pinjaman::query();
        $query->select('keuangan_pjp.*', 'nama_karyawan', 'nama_jabatan', 'nama_dept', 'totalpembayaran');
        $query->join('hrd_karyawan', 'keuangan_pjp.nik', '=', 'hrd_karyawan.nik');
        $query->join('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');
        $query->join('hrd_departemen', 'hrd_karyawan.kode_dept', '=', 'hrd_departemen.kode_dept');
        $query->leftJoin(
            DB::raw("(
            SELECT no_pinjaman,SUM(jumlah) as totalpembayaran FROM keuangan_pjp_historibayar GROUP BY no_pinjaman
        ) hb"),
            function ($join) {
                $join->on('keuangan_pjp.no_pinjaman', '=', 'hb.no_pinjaman');
            }
        );
        $query->where('keuangan_pjp.no_pinjaman', $no_pinjaman);
        $pinjaman = $query->first();

        $historibayar = DB::table('keuangan_pjp_historibayar')->where('no_pinjaman', $no_pinjaman)->get();
        return view('pinjaman.show', compact('pinjaman', 'historibayar'));
    }

    function diffInMonths(\DateTime $date1, \DateTime $date2)
    {
        $diff = $date1->diff($date2);

        $months = $diff->y * 12 + $diff->m + $diff->d / 30;

        return (int) round($months);
    }

    public function simulasi()
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $query = Karyawan::query();
        $query->select(
            'nik',
            'nama_karyawan',
            'tanggal_masuk',
            'nama_dept',
            'jenis_kelamin',
            'nama_jabatan',
            'kode_perusahaan',
            'hrd_karyawan.kode_cabang',
            'kode_klasifikasi',
            'status_karyawan',
            'nama_cabang'
        );
        $query->join('hrd_departemen', 'hrd_karyawan.kode_dept', '=', 'hrd_departemen.kode_dept');
        $query->join('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');
        $query->leftjoin('cabang', 'hrd_karyawan.kode_cabang', '=', 'cabang.kode_cabang');
        $query->where('nik', $nik);
        $karyawan = $query->first();

        $gaji = DB::table('hrd_gaji')
            ->selectRaw('IFNULL(gaji_pokok,0)+IFNULL(t_jabatan,0)+IFNULL(t_masakerja,0)+IFNULL(t_tanggungjawab,0)+IFNULL(t_makan,0)+IFNULL(t_istri,0)+IFNULL(t_skill,0) as gajitunjangan,gaji_pokok')
            ->where('nik', $nik)->orderBy('tanggal_berlaku', 'desc')->first();

        $jmk = DB::table('hrd_jasamasakerja')
            ->selectRaw('SUM(jumlah) as jumlah')
            ->where('nik', $nik)
            ->groupBy('nik')
            ->first();

        $hariini = date("Y-m-d");
        $sp = DB::table('hrd_suratperingatan')->where('nik', $nik)->where('sampai', '>', $hariini)
            ->orderBy('dari', 'desc')
            ->first();



        $query = Pinjaman::query();
        $query->select('keuangan_pjp.*', 'nama_karyawan', 'nama_jabatan', 'nama_dept', 'totalpembayaran');
        $query->join('hrd_karyawan', 'keuangan_pjp.nik', '=', 'hrd_karyawan.nik');
        $query->join('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');
        $query->join('hrd_departemen', 'hrd_karyawan.kode_dept', '=', 'hrd_departemen.kode_dept');
        $query->leftJoin(
            DB::raw("(
            SELECT no_pinjaman,SUM(jumlah) as totalpembayaran FROM keuangan_pjp_historibayar GROUP BY no_pinjaman
        ) hb"),
            function ($join) {
                $join->on('keuangan_pjp.no_pinjaman', '=', 'hb.no_pinjaman');
            }
        );
        $query->where('keuangan_pjp.nik', $nik);
        $query->whereRaw('IFNULL(jumlah_pinjaman,0) - IFNULL(totalpembayaran,0) != 0');
        $cekpinjaman = $query->first();

        $kontrak = DB::table('hrd_kontrak')->where('nik', $nik)->orderBy('tanggal', 'desc')->first();

        $start = date_create($karyawan->tgl_masuk);
        $end = date_create($hariini);

        $cekmasakerja =  $this->diffInMonths($start, $end);

        $jenis_sp = $sp != null ? $sp->ket : '';
        $kode_cabang = $karyawan->kode_cabang;
        return view('pinjaman.simulasi', compact('karyawan', 'gaji', 'jmk', 'kontrak'));
        //echo $jenis_sp . "-" . $id_kantor;
        // if (
        //     $sp != null && $jenis_sp == "SP3" && $id_kantor == "PST"
        //     || $sp != null && $jenis_sp == "SP2" && $id_kantor == "PST"
        //     || $sp != null && $jenis_sp == "SP1" && $id_kantor != "PST"
        // ) {
        //     return view('pinjaman.notifsp', compact('sp'));
        // } else if ($karyawan->status_karyawan == "K" && $cekmasakerja < 15) {
        //     return view('pinjaman.notifmasakerjakurang', compact('cekmasakerja'));
        // } else {
        //     if ($cekpinjaman != null) {
        //         $jumlah_pinjaman = $cekpinjaman->jumlah_pinjaman;
        //         $minpembayar = (75 / 100) * $jumlah_pinjaman;
        //         if ($cekpinjaman->totalpembayaran >= $minpembayar) {
        //             return view('pinjaman.simulasi', compact('karyawan', 'gaji', 'jmk', 'kontrak'));
        //         } else {
        //             return view('pinjaman.notiftopup', compact('cekpinjaman'));
        //         }
        //     } else {
        //         return view('pinjaman.simulasi', compact('karyawan', 'gaji', 'jmk', 'kontrak'));
        //         // return view('pinjaman.create2', compact('karyawan', 'gaji', 'jmk', 'kontrak'));
        //     }
        // }
    }
}
