<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Disposisiizinabsen;
use App\Models\Disposisiizincuti;
use App\Models\Disposisiizinsakit;
use App\Models\Izinabsen;
use App\Models\Izincuti;
use App\Models\Izinsakit;
use App\Models\Karyawan;
use App\Models\User;
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
        $mastercuti = DB::table('hrd_jeniscuti')->get();
        $mastercutikhusus = DB::table('hrd_jeniscuti_khusus')->get();
        return view('pengajuanizin.createcuti', compact('mastercuti', 'mastercutikhusus'));
    }


    public function store(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $karyawan = Karyawan::where('nik', $nik)->first();
        $request->validate([
            'dari' => 'required',
            'sampai' => 'required',
            'keterangan' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $jmlhari = hitungHari($request->dari, $request->sampai);
            if ($jmlhari > 3) {
                return Redirect::back()->with(messageError('Tidak Boleh Lebih dari 3 Hari!'));
            }

            $lastizin = Izinabsen::select('kode_izin')
                ->whereRaw('YEAR(dari)="' . date('Y', strtotime($request->dari)) . '"')
                ->whereRaw('MONTH(dari)="' . date('m', strtotime($request->dari)) . '"')
                ->orderBy("kode_izin", "desc")
                ->first();
            $last_kode_izin = $lastizin != null ? $lastizin->kode_izin : '';
            $kode_izin  = buatkode($last_kode_izin, "IA"  . date('ym', strtotime($request->dari)), 4);
            $k = new Karyawan();
            $karyawan = $k->getKaryawan($nik);

            Izinabsen::create([
                'kode_izin' => $kode_izin,
                'nik' => $nik,
                'kode_jabatan' => $karyawan->kode_jabatan,
                'kode_dept' => $karyawan->kode_dept,
                'kode_cabang' => $karyawan->kode_cabang,
                'tanggal' => $request->dari,
                'dari' => $request->dari,
                'sampai' => $request->sampai,
                'keterangan' => $request->keterangan,
                'status' => 0,
                'direktur' => 0,
                'id_user' => 1,
            ]);

            $cekregional = Cabang::where('kode_cabang', $karyawan->kode_cabang)->first();

            $roles_approve = cekRoleapprovepresensi($karyawan->kode_dept, $karyawan->kode_cabang, $karyawan->kategori, $karyawan->kode_jabatan);

            //dd($roles_approve);
            // dd($karyawan->kategori);
            // dd($roles_approve);
            $index_role = 0;
            // Jika Tidak Ada di dalam array




            $tanggal_hariini = date('Y-m-d');
            $lastdisposisi = Disposisiizinabsen::whereRaw('date(created_at)="' . $tanggal_hariini . '"')
                ->orderBy('kode_disposisi', 'desc')
                ->first();
            $last_kodedisposisi = $lastdisposisi != null ? $lastdisposisi->kode_disposisi : '';
            $format = "DPIA" . date('Ymd');
            $kode_disposisi = buatkode($last_kodedisposisi, $format, 4);
            $cek_user_approve = User::join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('roles.name', $roles_approve[$index_role])->first()
                ->where('users.status', '1')->first();


            Disposisiizinabsen::create([
                'kode_disposisi' => $kode_disposisi,
                'kode_izin' => $kode_izin,
                'id_pengirim' => 1,
                'id_penerima' => $cek_user_approve->id,
                'status' => 0
            ]);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }



    public function storesakit(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $karyawan = Karyawan::where('nik', $nik)->first();
        $request->validate([
            'dari' => 'required',
            'sampai' => 'required',
            'keterangan' => 'required',
        ]);
        DB::beginTransaction();
        try {
            // $jmlhari = hitungHari($request->dari, $request->sampai);
            // if ($jmlhari > 3) {
            //     return Redirect::back()->with(messageError('Tidak Boleh Lebih dari 3 Hari!'));
            // }

            $lastizinsakit = Izinsakit::select('kode_izin_sakit')
                ->whereRaw('YEAR(tanggal)="' . date('Y', strtotime($request->dari)) . '"')
                ->whereRaw('MONTH(tanggal)="' . date('m', strtotime($request->dari)) . '"')
                ->orderBy("kode_izin_sakit", "desc")
                ->first();
            $last_kode_izin_sakit = $lastizinsakit != null ? $lastizinsakit->kode_izin_sakit : '';
            $kode_izin_sakit  = buatkode($last_kode_izin_sakit, "IS"  . date('ym', strtotime($request->dari)), 4);


            $k = new Karyawan();
            $karyawan = $k->getKaryawan($nik);

            $data_sid = [];
            if ($request->hasfile('sid')) {
                $sid_name =  $kode_izin_sakit . "." . $request->file('sid')->getClientOriginalExtension();
                $destination_sid_path = "/public/uploads/sid";
                $sid = $sid_name;
                $data_sid = [
                    'doc_sid' => $sid,
                ];
            }

            $dataizinsakit = [
                'kode_izin_sakit' => $kode_izin_sakit,
                'nik' => $nik,
                'kode_jabatan' => $karyawan->kode_jabatan,
                'kode_dept' => $karyawan->kode_dept,
                'kode_cabang' => $karyawan->kode_cabang,
                'tanggal' => $request->dari,
                'dari' => $request->dari,
                'sampai' => $request->sampai,
                'keterangan' => $request->keterangan,
                'status' => 0,
                'direktur' => 0,
                'id_user' => 1,
            ];
            $data = array_merge($dataizinsakit, $data_sid);
            $simpandatasakit = Izinsakit::create($data);
            if ($simpandatasakit) {
                if ($request->hasfile('sid')) {
                    $request->file('sid')->storeAs($destination_sid_path, $sid_name);
                }
            }


            // $cekregional = Cabang::where('kode_cabang', $karyawan->kode_cabang)->first();

            $roles_approve = cekRoleapprovepresensi($karyawan->kode_dept, $karyawan->kode_cabang, $karyawan->kategori, $karyawan->kode_jabatan);

            //dd($roles_approve);
            // dd($karyawan->kategori);
            // dd($roles_approve);
            $index_role = 0;
            // Jika Tidak Ada di dalam array




            $tanggal_hariini = date('Y-m-d');
            $lastdisposisi = Disposisiizinsakit::whereRaw('date(created_at)="' . $tanggal_hariini . '"')
                ->orderBy('kode_disposisi', 'desc')
                ->first();
            $last_kodedisposisi = $lastdisposisi != null ? $lastdisposisi->kode_disposisi : '';
            $format = "DPIS" . date('Ymd');
            $kode_disposisi = buatkode($last_kodedisposisi, $format, 4);
            $cek_user_approve = User::join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('roles.name', $roles_approve[$index_role])->first()
                ->where('users.status', '1')->first();


            Disposisiizinsakit::create([
                'kode_disposisi' => $kode_disposisi,
                'kode_izin_sakit' => $kode_izin_sakit,
                'id_pengirim' => 1,
                'id_penerima' => $cek_user_approve->id,
                'status' => 0
            ]);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function storecuti(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $karyawan = Karyawan::where('nik', $nik)->first();
        $request->validate([
            'dari' => 'required',
            'sampai' => 'required',
            'keterangan' => 'required',
            'kode_cuti' => 'required',
        ]);
        $format = "IC" . date('ym', strtotime($request->dari));
        DB::beginTransaction();
        try {
            // $jmlhari = hitungHari($request->dari, $request->sampai);
            // if ($jmlhari > 3) {
            //     return Redirect::back()->with(messageError('Tidak Boleh Lebih dari 3 Hari!'));
            // }

            $lastizincuti = Izincuti::select('kode_izin_cuti')
                // ->whereRaw('YEAR(tanggal)="' . date('Y', strtotime($request->dari)) . '"')
                // ->whereRaw('MONTH(tanggal)="' . date('m', strtotime($request->dari)) . '"')
                ->whereRaw('LEFT(kode_izin_cuti,6)="' . $format . '"')
                ->orderBy("kode_izin_cuti", "desc")
                ->first();
            $last_kode_izin_cuti = $lastizincuti != null ? $lastizincuti->kode_izin_cuti : '';
            $kode_izin_cuti  = buatkode($last_kode_izin_cuti, "IC"  . date('ym', strtotime($request->dari)), 4);


            $k = new Karyawan();
            $karyawan = $k->getKaryawan($nik);

            $data_cuti = [];
            if ($request->hasfile('doc_cuti')) {
                $cuti_name =  $kode_izin_cuti . "." . $request->file('doc_cuti')->getClientOriginalExtension();
                $destination_cuti_path = "/public/uploads/cuti";
                $cuti = $cuti_name;
                $data_cuti = [
                    'doc_cuti' => $cuti,
                ];
            }

            $dataizincuti = [
                'kode_izin_cuti' => $kode_izin_cuti,
                'nik' => $nik,
                'kode_jabatan' => $karyawan->kode_jabatan,
                'kode_dept' => $karyawan->kode_dept,
                'kode_cabang' => $karyawan->kode_cabang,
                'tanggal' => $request->dari,
                'dari' => $request->dari,
                'sampai' => $request->sampai,
                'kode_cuti' => $request->kode_cuti,
                'kode_cuti_khusus' => $request->kode_cuti == 'C03' ? $request->kode_cuti_khusus : null,
                'keterangan' => $request->keterangan,
                'status' => 0,
                'direktur' => 0,
                'id_user' => 1,
            ];

            $data = array_merge($dataizincuti, $data_cuti);
            $simpandatacuti = Izincuti::create($data);
            if ($simpandatacuti) {
                if ($request->hasfile('doc_cuti')) {
                    $request->file('doc_cuti')->storeAs($destination_cuti_path, $cuti_name);
                }
            }



            // $cekregional = Cabang::where('kode_cabang', $karyawan->kode_cabang)->first();

            $roles_approve = cekRoleapprovepresensi($karyawan->kode_dept, $karyawan->kode_cabang, $karyawan->kategori, $karyawan->kode_jabatan);

            //dd($roles_approve);
            // dd($karyawan->kategori);
            // dd($roles_approve);
            $index_role = 0;
            // Jika Tidak Ada di dalam array




            $tanggal_hariini = date('Y-m-d');
            $lastdisposisi = Disposisiizincuti::whereRaw('date(created_at)="' . $tanggal_hariini . '"')
                ->orderBy('kode_disposisi', 'desc')
                ->first();
            $last_kodedisposisi = $lastdisposisi != null ? $lastdisposisi->kode_disposisi : '';
            $format = "DPIC" . date('Ymd');
            $kode_disposisi = buatkode($last_kodedisposisi, $format, 4);
            $cek_user_approve = User::join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('roles.name', $roles_approve[$index_role])->first()
                ->where('users.status', '1')->first();


            Disposisiizincuti::create([
                'kode_disposisi' => $kode_disposisi,
                'kode_izin_cuti' => $kode_izin_cuti,
                'id_pengirim' => 1,
                'id_penerima' => $cek_user_approve->id,
                'status' => 0
            ]);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
