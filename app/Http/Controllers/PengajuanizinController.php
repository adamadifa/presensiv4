<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class PengajuanizinController extends Controller
{

    public function buatkode($nomor_terakhir, $kunci, $jumlah_karakter = 0)
    {
        /* mencari nomor baru dengan memecah nomor terakhir dan menambahkan 1
        string nomor baru dibawah ini harus dengan format XXX000000
        untuk penggunaan dalam format lain anda harus menyesuaikan sendiri */
        $nomor_baru = intval(substr($nomor_terakhir, strlen($kunci))) + 1;
        //    menambahkan nol didepan nomor baru sesuai panjang jumlah karakter
        $nomor_baru_plus_nol = str_pad($nomor_baru, $jumlah_karakter, "0", STR_PAD_LEFT);
        //    menyusun kunci dan nomor baru
        $kode = $kunci . $nomor_baru_plus_nol;
        return $kode;
    }

    public function createizinterlambat()
    {
        return view('pengajuanizin.createizinterlambat');
    }

    public function createizinabsen()
    {
        return view('pengajuanizin.createizinabsen');
    }

    public function createizinkeluar()
    {
        return view('pengajuanizin.createizinkeluar');
    }

    public function createizinpulang()
    {
        return view('pengajuanizin.createizinpulang');
    }

    public function createsakit()
    {
        return view('pengajuanizin.createsakit');
    }

    public function createcuti()
    {
        $mastercuti = DB::table('hrd_mastercuti')->get();
        return view('pengajuanizin.createcuti', compact('mastercuti'));
    }


    public function store(Request $request)
    {
        $nik = Auth::user()->nik;
        // $dari = $request->jenis_izin == "PL" || $request->jenis_izin == "KL" || $request->jenis_izin == "TL" ? date("Y-m-d") : $request->dari;
        $dari = $request->dari;
        // $sampai =  $request->jenis_izin == "PL" || $request->jenis_izin == "KL" || $request->jenis_izin == "TL" ? date("Y-m-d") : $request->sampai;
        $sampai = $request->sampai;
        $jmlhari = $request->jmlhari;
        $status = $request->status;
        $keterangan = $request->keterangan;
        $jenis_izin = $request->jenis_izin;
        $jam_pulang = $request->jam_pulang;
        $jam_keluar = $request->jam_keluar;
        $jam_terlambat = $request->jam_terlambat;
        $jenis_cuti = $request->jenis_cuti;
        $tgl = explode("-", $dari);
        $tahun = substr($tgl[0], 2, 2);
        $izin = DB::table("pengajuan_izin")
            ->whereRaw('YEAR(dari)="' . $tgl[0] . '"')
            ->orderBy("kode_izin", "desc")
            ->first();

        $last_kodeizin = $izin != null ? $izin->kode_izin : '';
        $kode_izin  = $this->buatkode($last_kodeizin, "IZ" . $tahun, 3);
        if ($request->hasFile('sid')) {
            $sid = $kode_izin . "." . $request->file('sid')->getClientOriginalExtension();
        } else {
            $sid = null;
        }

        $kode_cabang = $request->kode_cabang;
        $data = [
            'kode_izin' => $kode_izin,
            'nik' => $nik,
            'dari' => $dari,
            'sampai' => $sampai,
            'jmlhari' => $jmlhari,
            'status' => $status,
            'keterangan' => $keterangan,
            'sid' => $sid,
            'jenis_izin' => $jenis_izin,
            'jam_pulang' => $jam_pulang,
            'jam_keluar' => $jam_keluar,
            'jam_terlambat' => $jam_terlambat,
            'jenis_cuti' => $jenis_cuti,
            'kode_cabang' => $kode_cabang
        ];

        try {
            $simpan = DB::table('pengajuan_izin')->insert($data);
            if ($simpan) {
                if ($request->hasFile('sid')) {
                    $folderPath = "public/uploads/sid/";
                    $request->file('sid')->storeAs($folderPath, $sid);
                }
            }
            return redirect('/presensi/izin')->with(['success' => 'Data Berhasil Disimpan']);
        } catch (\Exception $e) {
            //dd($e);
            return redirect('/presensi/izin')->with(['error' => 'Data Gagal Disimpan']);
        }
    }
}