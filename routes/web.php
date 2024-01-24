<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KonfigurasiController;
use App\Http\Controllers\PengajuanizinController;
use App\Http\Controllers\PinjamanController;
use App\Http\Controllers\PinjamnaController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\SlipgajiController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Psy\VarDumper\Presenter;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/




Route::middleware(['guest:karyawan'])->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/proseslogin', [AuthController::class, 'proseslogin']);
});


Route::middleware(['guest:user'])->group(function () {
    Route::get('/panel', function () {
        return view('auth.loginadmin');
    })->name('loginadmin');

    Route::post('/prosesloginadmin', [AuthController::class, 'prosesloginadmin']);
});
Route::get('/dashboard', [DashboardController::class, 'index']);
Route::middleware(['auth:karyawan'])->group(function () {

    Route::get('/proseslogout', [AuthController::class, 'proseslogout']);

    //Presensi
    Route::get('/presensi/create', [PresensiController::class, 'create']);
    Route::post('/presensi/store', [PresensiController::class, 'store']);
    Route::post('/presensi/storewithcamera', [PresensiController::class, 'storewithcamera']);

    //Edit Profile
    Route::get('/editprofile', [PresensiController::class, 'editprofile']);
    Route::post('/presensi/{nik}/updateprofile', [PresensiController::class, 'updateprofile']);

    //Histori
    Route::get('/presensi/histori', [PresensiController::class, 'histori']);
    Route::post('/gethistori', [PresensiController::class, 'gethistori']);

    //Izin
    Route::get('/presensi/izin', [PresensiController::class, 'izin']);
    Route::get('/presensi/buatizin', [PresensiController::class, 'buatizin']);
    Route::post('/presensi/storeizin', [PresensiController::class, 'storeizin']);
    Route::post('/presensi/cekpengajuanizin', [PresensiController::class, 'cekpengajuanizin']);
    Route::get('/izin/{id}/showact', [PresensiController::class, 'showactizin']);
    Route::get('/izin/{id}/editizin', [PresensiController::class, 'editizin']);
    Route::post('/izin/{id}/update', [PresensiController::class, 'updateizin']);
    Route::get('/izin/{id}/delete', [PresensiController::class, 'deleteizin']);
    Route::get('/izin/{id}/showsid', [PresensiController::class, 'showsid']);


    //Pinjaman

    Route::get('/pinjaman', [PinjamanController::class, 'index']);
    Route::get('/pinjaman/{no_pinjaman}/show', [PinjamanController::class, 'show']);
    Route::get('/pinjaman/simulasi', [PinjamanController::class, 'simulasi']);

    //Slip Gaji
    Route::get('/slipgaji',[SlipgajiController::class,'index']);
    Route::get('/slipgaji/{bulan}/{tahun}/cetak',[SlipgajiController::class,'cetak']);

    Route::get('/pengajuanizin/createizinterlambat', [PengajuanizinController::class, 'createizinterlambat']);
    Route::get('/pengajuanizin/createizinabsen', [PengajuanizinController::class, 'createizinabsen']);
    Route::get('/pengajuanizin/createizinkeluar', [PengajuanizinController::class, 'createizinkeluar']);
    Route::get('/pengajuanizin/createizinpulang', [PengajuanizinController::class, 'createizinpulang']);
    Route::get('/pengajuanizin/createsakit', [PengajuanizinController::class, 'createsakit']);
    Route::get('/pengajuanizin/createcuti', [PengajuanizinController::class, 'createcuti']);
    Route::post('/pengajuanizin/store', [PengajuanizinController::class, 'store']);
});


Route::middleware(['auth:user'])->group(function () {
    Route::get('/proseslogoutadmin', [AuthController::class, 'proseslogoutadmin']);
    Route::get('/panel/dashboardadmin', [DashboardController::class, 'dashboardadmin']);

    //Karyawan
    Route::get('/karyawan', [KaryawanController::class, 'index']);
    Route::post('/karyawan/store', [KaryawanController::class, 'store']);
    Route::post('/karyawan/edit', [KaryawanController::class, 'edit']);
    Route::post('/karyawan/{nik}/update', [KaryawanController::class, 'update']);
    Route::post('/karyawan/{nik}/delete', [KaryawanController::class, 'delete']);

    //Departemen
    Route::get('/departemen', [DepartemenController::class, 'index']);
    Route::post('/departemen/store', [DepartemenController::class, 'store']);
    Route::post('/departemen/edit', [DepartemenController::class, 'edit']);
    Route::post('/departemen/{kode_dept}/update', [DepartemenController::class, 'update']);
    Route::post('/departemen/{kode_dept}/delete', [DepartemenController::class, 'delete']);

    //Presensi
    Route::get('/presensi/monitoring', [PresensiController::class, 'monitoring']);
    Route::post('/getpresensi', [PresensiController::class, 'getpresensi']);
    Route::post('/tampilkanpeta', [PresensiController::class, 'tampilkanpeta']);
    Route::get('/presensi/laporan', [PresensiController::class, 'laporan']);
    Route::post('/presensi/cetaklaporan', [PresensiController::class, 'cetaklaporan']);
    Route::get('/presensi/rekap', [PresensiController::class, 'rekap']);
    Route::post('/presensi/cetakrekap', [PresensiController::class, 'cetakrekap']);
    Route::get('/presensi/izinsakit', [PresensiController::class, 'izinsakit']);
    Route::post('/presensi/approveizinsakit', [PresensiController::class, 'approveizinsakit']);
    Route::get('/presensi/{id}/batalkanizinsakit', [PresensiController::class, 'batalkanizinsakit']);


    //Cabang
    Route::get('/cabang', [CabangController::class, 'index']);
    Route::post('/cabang/store', [CabangController::class, 'store']);
    Route::post('/cabang/edit', [CabangController::class, 'edit']);
    Route::post('/cabang/update', [CabangController::class, 'update']);
    Route::post('/cabang/{kode_cabang}/delete', [CabangController::class, 'delete']);

   

    //Konfigurasi

    Route::get('/konfigurasi/lokasikantor', [KonfigurasiController::class, 'lokasikantor']);
    Route::post('/konfigurasi/updatelokasikantor', [KonfigurasiController::class, 'updatelokasikantor']);

    Route::get('/konfigurasi/jamkerja', [KonfigurasiController::class, 'jamkerja']);
    Route::post('/konfigurasi/storejamkerja', [KonfigurasiController::class, 'storejamkerja']);
    Route::post('/konfigurasi/editjamkerja', [KonfigurasiController::class, 'editjamkerja']);
    Route::post('/konfigurasi/updatejamkerja', [KonfigurasiController::class, 'updatejamkerja']);
    Route::post('/konfigurasi/{kode_jam_kerja}/delete', [KonfigurasiController::class, 'deletejamkerja']);
});

Route::get('/storefrommachine', [PresensiController::class, 'storefrommachine']);
