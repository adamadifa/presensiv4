<?php

namespace App\Http\Controllers;

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
        $query->select('pinjaman.*', 'nama_karyawan', 'nama_jabatan', 'nama_dept', 'totalpembayaran');
        $query->join('master_karyawan', 'pinjaman.nik', '=', 'master_karyawan.nik');
        $query->join('hrd_jabatan', 'master_karyawan.id_jabatan', '=', 'hrd_jabatan.id');
        $query->join('departemen', 'master_karyawan.kode_dept', '=', 'departemen.kode_dept');
        $query->leftJoin(
            DB::raw("(
            SELECT no_pinjaman,SUM(jumlah) as totalpembayaran FROM pinjaman_historibayar GROUP BY no_pinjaman
        ) hb"),
            function ($join) {
                $join->on('pinjaman.no_pinjaman', '=', 'hb.no_pinjaman');
            }
        );
        if (!empty($request->bulan)) {
            $query->whereRaw('MONTH(tgl_pinjaman)="' . $request->bulan . '"');
        }

        if (!empty($request->tahun)) {
            $query->whereRaw('YEAR(tgl_pinjaman)="' . $request->tahun . '"');
        }


        $query->where('pinjaman.nik', $nik);
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
        $query->select('pinjaman.*', 'nama_karyawan', 'nama_jabatan', 'nama_dept', 'totalpembayaran');
        $query->join('master_karyawan', 'pinjaman.nik', '=', 'master_karyawan.nik');
        $query->join('hrd_jabatan', 'master_karyawan.id_jabatan', '=', 'hrd_jabatan.id');
        $query->join('departemen', 'master_karyawan.kode_dept', '=', 'departemen.kode_dept');
        $query->leftJoin(
            DB::raw("(
            SELECT no_pinjaman,SUM(jumlah) as totalpembayaran FROM pinjaman_historibayar GROUP BY no_pinjaman
        ) hb"),
            function ($join) {
                $join->on('pinjaman.no_pinjaman', '=', 'hb.no_pinjaman');
            }
        );
        $query->where('pinjaman.no_pinjaman', $no_pinjaman);
        $pinjaman = $query->first();

        $historibayar = DB::table('pinjaman_historibayar')->where('no_pinjaman', $no_pinjaman)->get();
        return view('pinjaman.show', compact('pinjaman', 'historibayar'));
    }
}
