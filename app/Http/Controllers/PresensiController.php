<?php

namespace App\Http\Controllers;

use App\Models\Izinabsen;
use App\Models\Izinkeluarkantor;
use App\Models\Izinpulang;
use App\Models\Izinterlambat;
use App\Models\Pengajuanizin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class PresensiController extends Controller
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

    public function hari_ini()
    {
        $hari = date("D");

        switch ($hari) {
            case 'Sun':
                $hari_ini = "Minggu";
                break;

            case 'Mon':
                $hari_ini = "Senin";
                break;

            case 'Tue':
                $hari_ini = "Selasa";
                break;

            case 'Wed':
                $hari_ini = "Rabu";
                break;

            case 'Thu':
                $hari_ini = "Kamis";
                break;

            case 'Fri':
                $hari_ini = "Jumat";
                break;

            case 'Sat':
                $hari_ini = "Sabtu";
                break;

            default:
                $hari_ini = "Tidak di ketahui";
                break;
        }

        return $hari_ini;
    }


    public function hari_tanggal($tgl)
    {
        $hari = date("D", strtotime($tgl));

        switch ($hari) {
            case 'Sun':
                $hari_ini = "Minggu";
                break;

            case 'Mon':
                $hari_ini = "Senin";
                break;

            case 'Tue':
                $hari_ini = "Selasa";
                break;

            case 'Wed':
                $hari_ini = "Rabu";
                break;

            case 'Thu':
                $hari_ini = "Kamis";
                break;

            case 'Fri':
                $hari_ini = "Jumat";
                break;

            case 'Sat':
                $hari_ini = "Sabtu";
                break;

            default:
                $hari_ini = "Tidak di ketahui";
                break;
        }

        return $hari_ini;
    }


    public function create()
    {
        $hariini = date("Y-m-d");
        $nik = Auth::guard('karyawan')->user()->nik;

        //Cek Apakah Sedang Perjalanan Dinas Ke Cabang lain
        $cekperjalanandinas = DB::table('hrd_izindinas')
            ->whereRaw('"' . $hariini . '" >= dari')
            ->whereRaw('"' . $hariini . '" <= sampai')
            ->where('nik', $nik)
            ->first();


        if ($cekperjalanandinas != null) {
            $kode_cabang = $cekperjalanandinas->kode_cabang;
        } else {
            $kode_cabang = Auth::guard('karyawan')->user()->kode_cabang;
        }

        //Cek Lokasi Cabang
        $lok_kantor = DB::table('cabang')->where('kode_cabang', $kode_cabang)->first();

        // Cek Apakah Sudah Absen
        $cek = DB::table('hrd_presensi')->where('tanggal', $hariini)->where('nik', $nik)->count();

        //Cek Apakah Memiliki Jadwal Shift
        $cekjadwalshift = DB::table('hrd_jadwalshift_detail')
            ->join('hrd_jadwalshift', 'hrd_jadwalshift_detail.kode_jadwalshift', '=', 'hrd_jadwalshift.kode_jadwalshift')
            ->whereRaw('"' . $hariini . '" >= dari')
            ->whereRaw('"' . $hariini . '" <= sampai')
            ->where('nik', $nik)
            ->first();

        //Cek Apakah Ada Pergantian Shift
        $cekgantishift = DB::table('hrd_gantishift')->where('tanggal', $hariini)->where('nik', $nik)->first();

        //Jika Ada Pergantian Shift
        if ($cekgantishift != null) {
            $kode_jadwal = $cekgantishift->kode_jadwal;
            //Jika Memiliki Jadwal Shift
        } else if ($cekjadwalshift != null) {
            $kode_jadwal = $cekjadwalshift->kode_jadwal;

            //Jika Sedang Perjalanan Dinas
        } else if ($cekperjalanandinas != null) {
            //Sesuaikan dengan Jadwal Cabang Tujuan
            $cekjadwaldinas = DB::table('hrd_jadwalkerja')
                ->where('nama_jadwal', 'NON SHIFT')
                ->where('kode_cabang', $cekperjalanandinas->kode_cabang)->first();
            $kode_jadwal = $cekjadwaldinas->kode_jadwal;
        } else {

            //Gunakan Jadwal Default
            $kode_jadwal = Auth::guard('karyawan')->user()->kode_jadwal;
        }

        //Tanggal 5 Jam Ketika Besok Libur
        $libur = DB::table('hrd_harilibur_detail')
            ->leftJoin('hrd_harilibur', 'hrd_harilibur_detail.kode_libur', '=', 'hrd_harilibur.kode_libur')
            ->where('nik', $nik)
            ->where('kode_cabang', $kode_cabang)
            ->where('tanggal_limajam', $hariini);

        $ceklibur = $libur->count();
        $datalibur = $libur->first();
        $tanggal_libur = $datalibur != null ? $datalibur->tanggal : '';

        //Cek Libur Hari ini
        $cekliburhariini = DB::table('hrd_harilibur_detail')
            ->leftJoin('hrd_harilibur', 'hrd_harilibur_detail.kode_libur', '=', 'hrd_harilibur.kode_libur')
            ->where('nik', $nik)
            ->where('kode_cabang', $kode_cabang)
            ->where('tanggal', $hariini)
            ->where('kategori', 1)
            ->first();

        // Cek Wfh Hari Ini
        $cekwfhhariini = DB::table('hrd_harilibur_detail')
            ->leftJoin('hrd_harilibur', 'hrd_harilibur_detail.kode_libur', '=', 'hrd_harilibur.kode_libur')
            ->where('nik', $nik)
            ->where('kode_cabang', $kode_cabang)
            ->where('tanggal', $hariini)
            ->where('kategori', 3)
            ->first();

        //Cek Libur Pengganti Hari Minggu
        $cekliburpenggantiminggu = DB::table('hrd_harilibur_detail')
            ->leftJoin('hrd_harilibur', 'hrd_harilibur_detail.kode_libur', '=', 'hrd_harilibur.kode_libur')
            ->where('nik', $nik)
            ->where('kode_cabang', $kode_cabang)
            ->where('tanggal', $hariini)
            ->where('kategori', 2)
            ->first();

        //Cek Hari Minggu Masuk
        $cekminggumasuk = DB::table('hrd_harilibur_detail') // Mengganti 'harilibur_karyawan' dengan 'hrd_harilibur_karyawan'
            ->leftJoin('hrd_harilibur', 'hrd_harilibur_detail.kode_libur', '=', 'hrd_harilibur.kode_libur')
            ->where('nik', $nik)
            ->where('kode_cabang', $kode_cabang)
            ->where('tanggal_diganti', $hariini)
            ->where('kategori', 2)
            ->first();

        //Cek Lembur
        $ceklembur = DB::table('hrd_lembur_detail')
            ->join('hrd_lembur', 'hrd_lembur_detail.kode_lembur', '=', 'hrd_lembur.kode_lembur')
            ->where('nik', $nik)
            ->where('tanggal', $hariini)->count();




        //dd($ceklibur);
        if ($ceklibur > 0 && $this->hari_tanggal($tanggal_libur) == "Sabtu") {
            $hariini = "Sabtu";
        } elseif ($cekminggumasuk != null) {
            $hariini = $this->hari_tanggal($cekminggumasuk->tanggal_libur);
        } else {
            $hariini = $this->hari_ini();
        }

        if ($hariini == "Sabtu" && $ceklembur > 0) {
            $hariini = "Jumat";
        }


        $kode_jabatan = Auth::user()->kode_jabatan;
        $jabatan = DB::table('hrd_jabatan')->where('kode_jabatan', $kode_jabatan)->first();

        //Jika Jabatan Security
        if ($jabatan->nama_jabatan == "SECURITY" && $hariini == "Sabtu") {
            $hariini = "Senin";
        }


        // $id_group = Auth::guard('karyawan')->user()->grup;
        // $group_saus =  [29, 26, 27];
        // if (date('Y-m-d') == '2024-02-10') {
        //     if (in_array($id_group, $group_saus)) {
        //         $hariini = "Senin";
        //     }
        // }

        $jadwal = DB::table('hrd_jadwalkerja_detail')
            ->join('hrd_jadwalkerja', 'hrd_jadwalkerja_detail.kode_jadwal', '=', 'hrd_jadwalkerja.kode_jadwal')
            ->where('hari', $hariini)->where('hrd_jadwalkerja_detail.kode_jadwal', $kode_jadwal)->first();


        //Jika Belum Memiliki Jadwal
        if ($jadwal == null && empty($cekminggumasuk)) {
            return view('presensi.notifjadwal');
        }

        $jam_kerja = DB::table('hrd_jamkerja')->where('kode_jam_kerja', $jadwal->kode_jam_kerja)->first();

        $kode_dept =  Auth::guard('karyawan')->user()->kode_dept;
        $kode_cabang =  Auth::guard('karyawan')->user()->kode_cabang;

        // if ($cekliburhariini != null && $jabatan->nama_jabatan != "SECURITY") {
        //     return view('presensi.libur', compact('cekliburhariini'));
        // } else if ($cekwfhhariini != null) {
        //     return view('presensi.wfh', compact('cekwfhhariini'));
        // } elseif ($cekliburpenggantiminggu != null) {
        //     return view('presensi.liburpenggantiminggu', compact('cekliburpenggantiminggu'));
        // } else {
        //     if ($kode_dept == "MKT" || $id_kantor != "PST" || $kode_dept == "ADT") {
        //         return view('presensi.create_with_camera', compact('cek', 'lok_kantor', 'jam_kerja', 'jadwal'));
        //     } else {
        //         return view('presensi.create', compact('cek', 'lok_kantor', 'jam_kerja', 'jadwal'));
        //     }
        // }

        if ($kode_dept == "MKT" || $kode_cabang != "PST" || $kode_dept == "ADT") {
            return view('presensi.create_with_camera', compact('cek', 'lok_kantor', 'jam_kerja', 'jadwal'));
        } else {
            return view('presensi.create', compact('cek', 'lok_kantor', 'jam_kerja', 'jadwal'));
        }
    }

    public function store(Request $request)
    {

        $nik = Auth::guard('karyawan')->user()->nik;
        $lock_location = Auth::guard('karyawan')->user()->lock_location;
        $tgl_presensi = date("Y-m-d");

        $cekperjalanandinas = DB::table('hrd_izindinas')
            ->whereRaw('"' . $tgl_presensi . '" >= dari')
            ->whereRaw('"' . $tgl_presensi . '" <= sampai')
            ->where('nik', $nik)
            ->first();
        if ($cekperjalanandinas != null) {
            $kode_cabang = $cekperjalanandinas->kode_cabang;
        } else {
            $kode_cabang = Auth::guard('karyawan')->user()->kode_cabang;
        }

        $lastday = date('Y-m-d', strtotime('-1 day', strtotime($tgl_presensi)));
        $jam = date("Y-m-d H:i:s");

        $lok_kantor = DB::table('cabang')->where('kode_cabang', $kode_cabang)->first();
        $lok = explode(",", $lok_kantor->lokasi_cabang);
        $latitudekantor = $lok[0];
        $longitudekantor = $lok[1];
        $lokasi = $request->lokasi;
        $lokasiuser = explode(",", $lokasi);
        $latitudeuser = $lokasiuser[0];
        $longitudeuser = $lokasiuser[1];
        $statuspresensi = $request->statuspresensi;

        if ($statuspresensi == "masuk") {
            $ket = "in";
        } else {
            $ket = "out";
        }

        if (isset($request->image)) {
            $image = $request->image;
            $folderPath = "public/uploads/absensi/";
            $formatName = $nik . "-" . $tgl_presensi . "-" . $ket;
            $image_parts = explode(";base64", $image);
            $image_base64 = base64_decode($image_parts[1]);
            $fileName = $formatName . ".png";
            $file = $folderPath . $fileName;
        } else {
            $fileName = null;
        }

        $cekjadwalshift = DB::table('hrd_jadwalshift_detail')
            ->join('hrd_jadwalshift', 'hrd_jadwalshift_detail.kode_jadwalshift', '=', 'hrd_jadwalshift.kode_jadwalshift')
            ->whereRaw('"' . $tgl_presensi . '" >= dari')
            ->whereRaw('"' . $tgl_presensi . '" <= sampai')
            ->where('nik', $nik)
            ->first();

        $cekgantishift = DB::table('hrd_gantishift')->where('tanggal', $tgl_presensi)->where('nik', $nik)->first();

        if ($cekgantishift != null) {
            $kode_jadwal = $cekgantishift->kode_jadwal;
        } else if ($cekjadwalshift != null) {
            $kode_jadwal = $cekjadwalshift->kode_jadwal;
        } else if ($cekperjalanandinas != null) {
            $cekjadwaldinas = DB::table('hrd_jadwalkerja')
                ->where('nama_jadwal', 'NON SHIFT')
                ->where('kode_cabang', $cekperjalanandinas->kode_cabang)->first();
            $kode_jadwal = $cekjadwaldinas->kode_jadwal;
        } else {
            $kode_jadwal = Auth::guard('karyawan')->user()->kode_jadwal;
        }


        $libur = DB::table('hrd_harilibur_detail')
            ->leftJoin('hrd_harilibur', 'hrd_harilibur_detail.kode_libur', '=', 'hrd_harilibur.kode_libur')
            ->where('nik', $nik)
            ->where('kode_cabang', $kode_cabang)
            ->where('tanggal_limajam', $tgl_presensi)
            ->where('kategori', 1);

        $ceklibur = $libur->count();
        $datalibur = $libur->first();
        $tanggal_libur = $datalibur != null ? $datalibur->tanggal : '';


        $ceklembur = DB::table('hrd_lembur_detail')
            ->join('hrd_lembur', 'hrd_lembur_detail.kode_lembur', '=', 'hrd_lembur.kode_lembur')
            ->where('nik', $nik)
            ->where('tanggal', $tgl_presensi)->count();

        if ($ceklibur > 0 && $this->hari_tanggal($tanggal_libur) == "Sabtu") {
            $hariini = "Sabtu";
        } else {
            $hariini = $this->hari_ini();
        }

        if ($ceklembur > 0 && $hariini == "Sabtu") {
            $hariini = "Jumat";
        }

        $kode_jabatan = Auth::user()->kode_jabatan;
        $jabatan = DB::table('hrd_jabatan')->where('kode_jabatan', $kode_jabatan)->first();


        if ($jabatan->nama_jabatan == "SECURITY" && $hariini == "Sabtu") {
            $hariini = "Senin";
        }

        // $id_group = Auth::guard('karyawan')->user()->grup;
        // $group_saus =  [29, 26, 27];
        // if (date('Y-m-d') == '2024-02-10') {
        //     if (in_array($id_group, $group_saus)) {
        //         $hariini = "Senin";
        //     }
        // }



        $jadwal = DB::table('hrd_jadwalkerja_detail')
            ->join('hrd_jadwalkerja', 'hrd_jadwalkerja_detail.kode_jadwal', '=', 'hrd_jadwalkerja.kode_jadwal')
            ->where('hari', $hariini)->where('hrd_jadwalkerja_detail.kode_jadwal', $kode_jadwal)
            ->first();
        $jam_kerja = DB::table('hrd_jamkerja')->where('kode_jam_kerja', $jadwal->kode_jam_kerja)->first();

        $lintashari  = $jam_kerja->lintashari;


        $jarak = $this->distance($latitudekantor, $longitudekantor, $latitudeuser, $longitudeuser);
        $radius = round($jarak["meters"]);

        $cek = DB::table('hrd_presensi')->where('tanggal', $tgl_presensi)->where('nik', $nik)->first();



        $jam_sekarang = date("H:i:s");


        //cek Izin Terlambat

        $cekizinterlambat = DB::table('hrd_presensi_izinterlambat')
            ->join('hrd_izinterlambat', 'hrd_presensi_izinterlambat.kode_izin_terlambat', '=', 'hrd_izinterlambat.kode_izin_terlambat')
            ->where('nik', $nik)->where('tanggal', $tgl_presensi)->first();

        $kode_izin = $cekizinterlambat != null  ? $cekizinterlambat->kode_izin : NULL;

        $kode_dept = Auth::guard('karyawan')->user()->kode_dept;


        if ($radius > $lok_kantor->radius_cabang && $lock_location == 0) {
            echo "error|Maaf Anda Berada Diluar Radius, Jarak Anda " . $radius . " meter dari Kantor|radius";
        } else {
            if ($statuspresensi == "masuk") {
                $jam_masuk = $tgl_presensi . " " . "10:00";
                $jamabsen = $jam;
                if ($kode_jadwal == "JD004" && $jamabsen <= $jam_masuk  || $kode_jadwal == "JD003" && $jamabsen <= $jam_masuk) {
                    echo "error|Maaf Belum Waktunya Absen Masuk|in";
                } else {
                    if ($cek != null && !empty($cek->jam_in)) {
                        echo "error|Maaf Gagal absen, Anda Sudah Melakukan Presensi Masuk|in";
                    } else if ($cek != null && empty($cek->jam_in)) {
                        $data_masuk = [
                            'jam_in' => $jam,
                            'foto_in' => $fileName,
                            'lokasi_in' => $lokasi
                        ];
                        $update = DB::table('hrd_presensi')->where('tanggal', $tgl_presensi)->where('nik', $nik)->update($data_masuk);
                        if ($update) {
                            echo "success|Terimkasih, Selamat Bekerja|in";
                            if (isset($request->image)) {
                                Storage::put($file, $image_base64);
                            }
                            // Storage::put($file, $image_base64);
                        } else {
                            echo "error|Maaf Gagal absen, Hubungi Tim It|in";
                        }
                    } else if ($cek == null) {
                        $data = [
                            'nik' => $nik,
                            'tanggal' => $tgl_presensi,
                            'jam_in' => $jam,
                            'foto_in' => $fileName,
                            'lokasi_in' => $lokasi,
                            'kode_jadwal' => $kode_jadwal,
                            'kode_jam_kerja' => $jadwal->kode_jam_kerja,
                            'status' => 'h',
                        ];

                        $simpan = DB::table('hrd_presensi')->insert($data);
                        if ($simpan) {
                            echo "success|Terimkasih, Selamat Bekerja|in";
                            if (isset($request->image)) {
                                Storage::put($file, $image_base64);
                            }
                            // Storage::put($file, $image_base64);
                        } else {
                            echo "error|Maaf Gagal absen, Hubungi Tim It|in";
                        }
                    }
                }
            } else if ($statuspresensi == "pulang") {


                $ceklastpresensi = DB::table('hrd_presensi')
                    ->join('hrd_jamkerja', 'hrd_presensi.kode_jam_kerja', '=', 'hrd_jamkerja.kode_jam_kerja')
                    ->where('nik', $nik)->where('tanggal', $lastday)->first();

                $last_lintashari = $ceklastpresensi != null  ? $ceklastpresensi->lintashari : "";
                $tgl_pulang_shift_3 = date("H:i", strtotime(($jam)));

                $cekjadwalshiftlast = DB::table('hrd_jadwalshift_detail')
                    ->join('hrd_jadwalshift', 'hrd_jadwalshift_detail.kode_jadwalshift', '=', 'hrd_jadwalshift.kode_jadwalshift')
                    ->whereRaw('"' . $lastday . '" >= dari')
                    ->whereRaw('"' . $lastday . '" <= sampai')
                    ->where('nik', $nik)
                    ->first();
                $kode_jadwal_last = $cekjadwalshiftlast != null ? $cekjadwalshiftlast->kode_jadwal : $kode_jadwal;
                //dd($cekjadwalshiftlast);
                // /echo $tgl_pulang_shift_3;
                $kode_jam_kerja = $jadwal->kode_jam_kerja;
                if (!empty($last_lintashari)) {
                    if ($jam_sekarang > "00:00" && $jam_sekarang <= "08:00") {
                        $tgl_presensi = $lastday;
                    }

                    if ($hariini != "Sabtu") {
                        $tgl_pulang = date('Y-m-d', strtotime('+1 day', strtotime($tgl_presensi)));
                        $jam_pulang = $tgl_pulang . " " . date("H:i", strtotime($ceklastpresensi->jam_pulang));
                    } else {
                        $tgl_pulang = $tgl_presensi;
                        $jam_pulang = $tgl_pulang . " " . date("H:i", strtotime($jam_kerja->jam_pulang));
                    }
                } else {
                    if ($tgl_pulang_shift_3 <= "08:00" && $kode_jadwal_last == "JD004") {
                        $tgl_presensi = $lastday;
                        $tgl_pulang = date('Y-m-d', strtotime('+1 day', strtotime($tgl_presensi)));
                        if ($hariini != "Sabtu") {
                            $jam_pulang = $tgl_pulang . " 07:00";
                            $kode_jam_kerja = "JK08";
                        } else {
                            $jam_pulang = $tgl_pulang . " 22:00";
                            $kode_jam_kerja = "JK15";
                        }

                        $kode_jadwal = "JD004";

                        //echo "A" . $jam_pulang;
                    } else {
                        if ($kode_jadwal == "JD004") {
                            if ($hariini != "Sabtu") {
                                if ($jam_sekarang > "00:00" && $jam_sekarang <= "08:00") {
                                    $tgl_pulang = $tgl_presensi;
                                } else {
                                    $tgl_pulang = date('Y-m-d', strtotime('+1 day', strtotime($tgl_presensi)));
                                }
                            } else {
                                $tgl_pulang = $tgl_presensi;
                            }
                        } else {
                            $tgl_pulang = $tgl_presensi;
                        }
                        $jam_pulang = $tgl_pulang . " " . date("H:i", strtotime($jam_kerja->jam_pulang));

                        // echo "B" . $jam_pulang;
                    }
                }

                //die;
                // echo $jam_pulang;
                // die;

                $date_jampulang = date("Y-m-d", strtotime($jam_pulang));
                $hour_jampulang = (date("H", strtotime($jam_pulang)) - 2);
                $h_jampulang = $hour_jampulang < 9 ? "0" . $hour_jampulang : $hour_jampulang;
                $jam_pulang = $date_jampulang . " " . $h_jampulang . ":00";

                // echo $tgl_presensi;
                // echo $jam_pulang;
                //die;
                $jamabsen = $jam;
                if ($jamabsen < $jam_pulang) {
                    echo "error|Maaf Belum Waktunya Absen Pulang, Absen Pulang di Mulai Pada Pukul " . $jam_pulang . " |out" . $kode_jadwal;
                } else {

                    $cek = DB::table('hrd_presensi')->where('tanggal', $tgl_presensi)->where('nik', $nik)->first();
                    if ($cek == null) {
                        $data = [
                            'nik' => $nik,
                            'tanggal' => $tgl_presensi,
                            'jam_out' => $jam,
                            'foto_out' => $fileName,
                            'lokasi_out' => $lokasi,
                            'kode_jadwal' => $kode_jadwal,
                            'kode_jam_kerja' => $kode_jam_kerja,
                            'status' => 'h',
                        ];

                        $simpan = DB::table('hrd_presensi')->insert($data);
                        if ($simpan) {
                            echo "success|Terimkasih, Hati Hati Di Jalan|out";
                            if (isset($request->image)) {
                                Storage::put($file, $image_base64);
                            }
                            // Storage::put($file, $image_base64);
                        } else {
                            echo "error|Maaf Gagal absen, Hubungi Tim It|out";
                        }
                    } else if ($cek != null && !empty($cek->jam_out)) {
                        echo "error|Maaf Gagal absen, Anda Sudah Melakukan Presensi Pulang|in";
                    } else if ($cek != null && empty($cek->jam_out)) {
                        $data_masuk = [
                            'jam_out' => $jam,
                            'foto_out' => $fileName,
                            'lokasi_out' => $lokasi
                        ];
                        $update = DB::table('hrd_presensi')->where('tanggal', $tgl_presensi)->where('nik', $nik)->update($data_masuk);
                        if ($update) {
                            if (isset($request->image)) {
                                Storage::put($file, $image_base64);
                            }
                            echo "success|Terimkasih, Hati Hati Di Jalan|out";
                            // Storage::put($file, $image_base64);
                        } else {
                            echo "error|Maaf Gagal absen, Hubungi Tim It|out";
                        }
                    }
                }
            }
        }
    }




    //Menghitung Jarak
    function distance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return compact('meters');
    }

    public function editprofile()
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $karyawan = DB::table('hrd_karyawan')->where('nik', $nik)->first();
        return view('presensi.editprofile', compact('karyawan'));
    }

    public function updateprofile(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $nama_lengkap = $request->nama_lengkap;
        $no_hp = $request->no_hp;
        $this->validate($request, [
            // check validtion for image or file
            'foto' => 'image|mimes:jpg,png,jpeg,gif,svg|max:1024',
        ]);
        $password = Hash::make($request->password);
        $karyawan = DB::table('hrd_karyawan')->where('nik', $nik)->first();
        if ($request->hasFile('foto')) {
            $foto = $nik . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = $karyawan->foto;
        }
        if (empty($request->password)) {
            $data = [
                'nama_karyawan' => $nama_lengkap,
                'no_hp' => $no_hp,
                'foto' => $foto
            ];
        } else {
            $data = [
                'nama_karyawan' => $nama_lengkap,
                'no_hp' => $no_hp,
                'password' => $password,
                'foto' => $foto
            ];
        }

        try {
            DB::table('hrd_karyawan')->where('nik', $nik)->update($data);
            if ($request->hasFile('foto')) {
                $folderPath = "public/uploads/karyawan/";
                $request->file('foto')->storeAs($folderPath, $foto);
            }
            return Redirect::back()->with(['success' => 'Data Berhasil Di Update']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['error' => 'Data gagal Di Update']);
        }
    }


    public function histori()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        return view('presensi.histori', compact('namabulan'));
    }

    public function gethistori(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $dari = $tahun . "-" . $bulan . "-01";
        $sampai = date("Y-m-t", strtotime($dari));
        $nik = Auth::guard('karyawan')->user()->nik;

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
            ->whereBetween('hrd_presensi.tanggal', [$dari, $sampai])
            ->orderBy('hrd_presensi.tanggal', 'desc')

            ->get();



        //dd($histori);

        return view('presensi.gethistori', $data);
    }

    public function izin(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;

        //Izin terlmabat
        $qizinterlambat = Izinterlambat::query();
        $qizinterlambat->where('nik', $nik);
        if (!empty($request->bulan)) {
            $qizinterlambat->whereRaw('MONTH(tanggal)="' . $request->bulan . '"');
        }

        if (!empty($request->tahun)) {
            $qizinterlambat->whereRaw('YEAR(tanggal)="' . $request->tahun . '"');
        }
        $qizinterlambat->orderBy('tanggal', 'desc');
        $qizinterlambat->limit(7);
        $data['izinterlambat'] = $qizinterlambat->get();


        $qizinabsen = Izinabsen::query();
        $qizinabsen->where('nik', $nik);
        if (!empty($request->bulan)) {
            $qizinabsen->whereRaw('MONTH(tanggal)="' . $request->bulan . '"');
        }

        if (!empty($request->tahun)) {
            $qizinabsen->whereRaw('YEAR(tanggal)="' . $request->tahun . '"');
        }
        $qizinabsen->orderBy('tanggal', 'desc');
        $qizinabsen->limit(7);
        $data['izinabsen'] = $qizinabsen->get();


        $qizinkeluar = Izinkeluarkantor::query();
        $qizinkeluar->where('nik', $nik);
        if (!empty($request->bulan)) {
            $qizinkeluar->whereRaw('MONTH(tanggal)="' . $request->bulan . '"');
        }

        if (!empty($request->tahun)) {
            $qizinkeluar->whereRaw('YEAR(tanggal)="' . $request->tahun . '"');
        }
        $qizinkeluar->orderBy('tanggal', 'desc');
        $qizinkeluar->limit(7);
        $data['izinkeluar'] = $qizinkeluar->get();


        $qizinpulang = Izinpulang::query();
        $qizinpulang->where('nik', $nik);
        if (!empty($request->bulan)) {
            $qizinpulang->whereRaw('MONTH(tanggal)="' . $request->bulan . '"');
        }

        if (!empty($request->tahun)) {
            $qizinpulang->whereRaw('YEAR(tanggal)="' . $request->tahun . '"');
        }
        $qizinpulang->orderBy('tanggal', 'desc');
        $qizinpulang->limit(7);
        $data['izinpulang'] = $qizinpulang->get();

        $data['namabulan'] = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        return view('presensi.izin', $data);
    }

    public function buatizin()
    {

        $mastercuti = DB::table('hrd_mastercuti')->get();
        return view('presensi.buatizin', compact('mastercuti'));
    }

    public function storeizin(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $dari = $request->jenis_izin == "PL" || $request->jenis_izin == "KL" ? date("Y-m-d") : $request->dari;
        $sampai =  $request->jenis_izin == "PL" || $request->jenis_izin == "KL" ? date("Y-m-d") : $request->sampai;
        $jmlhari = $request->jmlhari;
        $status = $request->status;
        $keterangan = $request->keterangan;
        $jenis_izin = $request->jenis_izin;
        $jam_pulang = $request->jam_pulang;
        $jam_keluar = $request->jam_keluar;
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
            'jenis_cuti' => $jenis_cuti,
            'created_by' => 'user'
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
            dd($e);
            return redirect('/presensi/izin')->with(['error' => 'Data Gagal Disimpan']);
        }
    }

    public function monitoring()
    {
        return view('presensi.monitoring');
    }

    public function getpresensi(Request $request)
    {
        $tanggal = $request->tanggal;
        $presensi = DB::table('presensi')
            ->select('presensi.*', 'nama_lengkap', 'nama_dept')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->where('tgl_presensi', $tanggal)
            ->get();

        return view('presensi.getpresensi', compact('presensi'));
    }

    public function tampilkanpeta(Request $request)
    {
        $id = $request->id;
        $presensi = DB::table('presensi')->where('id', $id)
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->first();
        return view('presensi.showmap', compact('presensi'));
    }


    public function laporan()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $karyawan = DB::table('karyawan')->orderBy('nama_lengkap')->get();
        return view('presensi.laporan', compact('namabulan', 'karyawan'));
    }

    public function cetaklaporan(Request $request)
    {
        $nik = $request->nik;
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $karyawan = DB::table('karyawan')->where('nik', $nik)
            ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->first();

        $presensi = DB::table('presensi')
            ->where('nik', $nik)
            ->whereRaw('MONTH(tgl_presensi)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahun . '"')
            ->orderBy('tgl_presensi')
            ->get();

        if (isset($_POST['exportexcel'])) {
            $time = date("d-M-Y H:i:s");
            // Fungsi header dengan mengirimkan raw data excel
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Laporan Presensi Karyawan $time.xls");
            return view('presensi.cetaklaporanexcel', compact('bulan', 'tahun', 'namabulan', 'karyawan', 'presensi'));
        }
        return view('presensi.cetaklaporan', compact('bulan', 'tahun', 'namabulan', 'karyawan', 'presensi'));
    }

    public function rekap()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        return view('presensi.rekap', compact('namabulan'));
    }

    public function cetakrekap(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $rekap = DB::table('presensi')
            ->selectRaw('presensi.nik,nama_lengkap,
                MAX(IF(DAY(tgl_presensi) = 1,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_1,
                MAX(IF(DAY(tgl_presensi) = 2,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_2,
                MAX(IF(DAY(tgl_presensi) = 3,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_3,
                MAX(IF(DAY(tgl_presensi) = 4,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_4,
                MAX(IF(DAY(tgl_presensi) = 5,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_5,
                MAX(IF(DAY(tgl_presensi) = 6,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_6,
                MAX(IF(DAY(tgl_presensi) = 7,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_7,
                MAX(IF(DAY(tgl_presensi) = 8,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_8,
                MAX(IF(DAY(tgl_presensi) = 9,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_9,
                MAX(IF(DAY(tgl_presensi) = 10,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_10,
                MAX(IF(DAY(tgl_presensi) = 11,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_11,
                MAX(IF(DAY(tgl_presensi) = 12,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_12,
                MAX(IF(DAY(tgl_presensi) = 13,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_13,
                MAX(IF(DAY(tgl_presensi) = 14,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_14,
                MAX(IF(DAY(tgl_presensi) = 15,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_15,
                MAX(IF(DAY(tgl_presensi) = 16,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_16,
                MAX(IF(DAY(tgl_presensi) = 17,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_17,
                MAX(IF(DAY(tgl_presensi) = 18,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_18,
                MAX(IF(DAY(tgl_presensi) = 19,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_19,
                MAX(IF(DAY(tgl_presensi) = 20,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_20,
                MAX(IF(DAY(tgl_presensi) = 21,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_21,
                MAX(IF(DAY(tgl_presensi) = 22,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_22,
                MAX(IF(DAY(tgl_presensi) = 23,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_23,
                MAX(IF(DAY(tgl_presensi) = 24,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_24,
                MAX(IF(DAY(tgl_presensi) = 25,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_25,
                MAX(IF(DAY(tgl_presensi) = 26,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_26,
                MAX(IF(DAY(tgl_presensi) = 27,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_27,
                MAX(IF(DAY(tgl_presensi) = 28,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_28,
                MAX(IF(DAY(tgl_presensi) = 29,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_29,
                MAX(IF(DAY(tgl_presensi) = 30,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_30,
                MAX(IF(DAY(tgl_presensi) = 31,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_31')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->whereRaw('MONTH(tgl_presensi)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahun . '"')
            ->groupByRaw('presensi.nik,nama_lengkap')
            ->get();

        if (isset($_POST['exportexcel'])) {
            $time = date("d-M-Y H:i:s");
            // Fungsi header dengan mengirimkan raw data excel
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Rekap Presensi Karyawan $time.xls");
        }
        return view('presensi.cetakrekap', compact('bulan', 'tahun', 'namabulan', 'rekap'));
    }

    public function izinsakit(Request $request)
    {

        $query = Pengajuanizin::query();
        $query->select('id', 'tgl_izin', 'pengajuan_izin.nik', 'nama_lengkap', 'jabatan', 'status', 'status_approved', 'keterangan');
        $query->join('karyawan', 'pengajuan_izin.nik', '=', 'karyawan.nik');
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('tgl_izin', [$request->dari, $request->sampai]);
        }

        if (!empty($request->nik)) {
            $query->where('pengajuan_izin.nik', $request->nik);
        }

        if (!empty($request->nama_lengkap)) {
            $query->where('nama_lengkap', 'like', '%' . $request->nama_lengkap . '%');
        }

        if ($request->status_approved === '0' || $request->status_approved === '1' || $request->status_approved === '2') {
            $query->where('status_approved', $request->status_approved);
        }
        $query->orderBy('tgl_izin', 'desc');
        $izinsakit = $query->paginate(2);
        $izinsakit->appends($request->all());
        return view('presensi.izinsakit', compact('izinsakit'));
    }

    public function approveizinsakit(Request $request)
    {
        $status_approved = $request->status_approved;
        $id_izinsakit_form = $request->id_izinsakit_form;
        $update = DB::table('pengajuan_izin')->where('id', $id_izinsakit_form)->update([
            'status_approved' => $status_approved
        ]);
        if ($update) {
            return Redirect::back()->with(['success' => 'Data Berhasil Di Update']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Di Update']);
        }
    }

    public function batalkanizinsakit($id)
    {
        $update = DB::table('pengajuan_izin')->where('id', $id)->update([
            'status_approved' => 0
        ]);
        if ($update) {
            return Redirect::back()->with(['success' => 'Data Berhasil Di Update']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Di Update']);
        }
    }

    public function cekpengajuanizin(Request $request)
    {
        $tgl_izin = $request->tgl_izin;
        $nik = Auth::guard('karyawan')->user()->nik;

        $cek = DB::table('pengajuan_izin')->where('nik', $nik)->where('tgl_izin', $tgl_izin)->count();
        return $cek;
    }

    public function showactizin($id)
    {
        $izin = DB::table('pengajuan_izin')->where('kode_izin', $id)->first();
        return view('presensi.showactizin', compact('id', 'izin'));
    }

    public function showsid($id)
    {
        $izin = DB::table('pengajuan_izin')->where('kode_izin', $id)->first();
        return view('presensi.showsid', compact('id', 'izin'));
    }

    public function editizin($id)
    {
        $izin = DB::table('pengajuan_izin')->where('kode_izin', $id)->first();
        return view('presensi.editizin', compact('izin'));
    }

    public function updateizin($id, Request $request)
    {

        $dari = $request->dari;
        $sampai = $request->sampai;
        $jmlhari = $request->jmlhari;
        $status = $request->status;
        $keterangan = $request->keterangan;



        $data = [
            'dari' => $dari,
            'sampai' => $sampai,
            'jmlhari' => $jmlhari,
            'status' => $status,
            'keterangan' => $keterangan
        ];

        try {
            DB::table('pengajuan_izin')->where('kode_izin', $id)->update($data);
            return redirect('/presensi/izin')->with(['success' => 'Data Berhasil Disimpan']);
        } catch (\Exception $e) {
            return redirect('/presensi/izin')->with(['error' => 'Data Gagal Disimpan']);
        }
    }

    public function deleteizin($id)
    {
        try {
            DB::table('pengajuan_izin')->where('kode_izin', $id)->delete();
            return redirect('/presensi/izin')->with(['success' => 'Data Berhasil Dihapus']);
        } catch (\Exception $e) {
            return redirect('/presensi/izin')->with(['error' => 'Data Gagal Dihapus']);
        }
    }

    public function storefrommachine()
    {


        $original_data  = file_get_contents('php://input');
        $decoded_data   = json_decode($original_data, true);
        $encoded_data   = json_encode($decoded_data);

        $data           = $decoded_data['data'];
        $pin            = $data['pin'];
        $status_scan    = $data['status_scan'];
        $scan           = $data['scan'];


        // $nik               = '21.02.232';


        $tgl_presensi   = date("Y-m-d", strtotime($scan));
        $karyawan       = DB::table('master_karyawan')->where('pin', $pin)->first();
        $jabatan        = DB::table('hrd_jabatan')->where('id', $karyawan->id_jabatan)->first();

        if ($karyawan == null) {
            echo "PIN Tidak Ditemukan";
            $nik = "";
        } else {
            $nik = $karyawan->nik;
        }
        $cekperjalanandinas = DB::table('pengajuan_izin')
            ->where('status', 'p')
            ->whereRaw('"' . $tgl_presensi . '" >= dari')
            ->whereRaw('"' . $tgl_presensi . '" <= sampai')
            ->where('nik', $nik)
            ->first();
        if ($cekperjalanandinas != null) {
            $kode_cabang = $cekperjalanandinas->kode_cabang;
        } else {
            $kode_cabang = $karyawan->id_kantor;
        }
        $lastday = date('Y-m-d', strtotime('-1 day', strtotime($tgl_presensi)));

        $jam = $scan;

        $cekjadwalshift = DB::table('konfigurasi_jadwalkerja_detail')
            ->join('konfigurasi_jadwalkerja', 'konfigurasi_jadwalkerja_detail.kode_setjadwal', '=', 'konfigurasi_jadwalkerja.kode_setjadwal')
            ->whereRaw('"' . $tgl_presensi . '" >= dari')
            ->whereRaw('"' . $tgl_presensi . '" <= sampai')
            ->where('nik', $nik)
            ->first();

        $cekgantishift = DB::table('hrd_gantishift')->where('tanggal', $tgl_presensi)->where('nik', $nik)->first();

        if ($cekgantishift != null) {
            $kode_jadwal = $cekgantishift->kode_jadwal;
        } else if ($cekjadwalshift != null) {
            $kode_jadwal = $cekjadwalshift->kode_jadwal;
        } else if ($cekperjalanandinas != null) {
            $cekjadwaldinas = DB::table('jadwal_kerja')
                ->where('nama_jadwal', 'NON SHIFT')
                ->where('kode_cabang', $cekperjalanandinas->kode_cabang)->first();
            $kode_jadwal = $cekjadwaldinas->kode_jadwal;
        } else {
            $kode_jadwal = $karyawan->kode_jadwal;
        }

        $libur = DB::table('harilibur_karyawan')
            ->leftJoin('harilibur', 'harilibur_karyawan.kode_libur', '=', 'harilibur.kode_libur')
            ->where('nik', $nik)
            ->where('id_kantor', $kode_cabang)
            ->where('tanggal_limajam', $tgl_presensi)
            ->where('kategori', 1);
        $ceklibur = $libur->count();
        $datalibur = $libur->first();
        $tanggal_libur = $datalibur != null ? $datalibur->tanggal_libur : '';

        $ceklembur = DB::table('lembur_karyawan')
            ->join('lembur', 'lembur_karyawan.kode_lembur', '=', 'lembur.kode_lembur')
            ->where('nik', $nik)
            ->where('tanggal', $tgl_presensi)->count();

        if ($ceklibur > 0 && $this->hari_tanggal($tanggal_libur) == "Sabtu") {
            $hariini = "Sabtu";
        } else {
            $hariini = $this->hari_ini();
        }


        if ($jabatan->nama_jabatan == "SECURITY" && $hariini == "Sabtu") {
            $hariini = "Senin";
        }

        if ($ceklembur > 0 && $hariini == "Sabtu") {
            $hariini = "Jumat";
        }

        $id_group = $karyawan->grup;
        $group_saus =  [29, 26, 27];
        if (date('Y-m-d') == '2024-02-10') {
            if (in_array($id_group, $group_saus)) {
                $hariini = "Senin";
            }
        }

        $jadwal = DB::table('jadwal_kerja_detail')
            ->join('jadwal_kerja', 'jadwal_kerja_detail.kode_jadwal', '=', 'jadwal_kerja.kode_jadwal')
            ->where('hari', $hariini)->where('jadwal_kerja_detail.kode_jadwal', $kode_jadwal)
            ->first();


        $jam_kerja = DB::table('jam_kerja')->where('kode_jam_kerja', $jadwal->kode_jam_kerja)->first();
        $lintashari  = $jam_kerja->lintashari;

        $cek = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->first();

        $jam_sekarang = date("H:i:s");


        if ($status_scan == 0) {
            $jam_masuk = $tgl_presensi . " " . "10:00";
            $jamabsen = $jam;
            if ($kode_jadwal == "JD004" && $jamabsen <= $jam_masuk  || $kode_jadwal == "JD003" && $jamabsen <= $jam_masuk) {
                echo "error|Maaf Belum Waktunya Absen Masuk|in";
            } else {
                if ($cek != null && !empty($cek->jam_in)) {
                    echo "error|Maaf Gagal absen, Anda Sudah Melakukan Presensi Masuk|in";
                } else if ($cek != null && empty($cek->jam_in)) {
                    $data_masuk = [
                        'jam_in' => $jam
                    ];
                    $update = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->update($data_masuk);
                    if ($update) {
                        echo "success|Terimkasih, Selamat Bekerja|in";
                    } else {
                        echo "error|Maaf Gagal absen, Hubungi Tim It|in";
                    }
                } else if ($cek == null) {
                    $data = [
                        'nik' => $nik,
                        'tgl_presensi' => $tgl_presensi,
                        'jam_in' => $jam,
                        'kode_jadwal' => $kode_jadwal,
                        'kode_jam_kerja' => $jadwal->kode_jam_kerja,
                        'status' => 'h',
                    ];

                    $simpan = DB::table('presensi')->insert($data);
                    if ($simpan) {
                        echo "success|Terimkasih, Selamat Bekerja|in";
                    } else {
                        echo "error|Maaf Gagal absen, Hubungi Tim It|in";
                    }
                }
            }
        } else if ($status_scan == 1) {

            $ceklastpresensi = DB::table('presensi')
                ->join('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
                ->where('nik', $nik)->where('tgl_presensi', $lastday)->first();
            // $last_kode_jadwal = $ceklastpresensi->kode_jadwal;
            // $last_kode_jam_kerja = $ceklastpresensi->kode_jam_kerja;

            $last_lintashari = $ceklastpresensi != null  ? $ceklastpresensi->lintashari : "";
            $tgl_pulang_shift_3 = date("H:i", strtotime(($jam)));

            $cekjadwalshiftlast = DB::table('konfigurasi_jadwalkerja_detail')
                ->join('konfigurasi_jadwalkerja', 'konfigurasi_jadwalkerja_detail.kode_setjadwal', '=', 'konfigurasi_jadwalkerja.kode_setjadwal')
                ->whereRaw('"' . $lastday . '" >= dari')
                ->whereRaw('"' . $lastday . '" <= sampai')
                ->where('nik', $nik)
                ->first();
            $kode_jadwal_last = $cekjadwalshiftlast != null ? $cekjadwalshiftlast->kode_jadwal : $kode_jadwal;


            $kode_jam_kerja = $jadwal->kode_jam_kerja;

            if (!empty($last_lintashari)) {
                if ($jam_sekarang > "00:00" && $jam_sekarang <= "08:00") {
                    $tgl_presensi = $lastday;
                }

                if ($hariini != "Sabtu") {
                    $tgl_pulang = date('Y-m-d', strtotime('+1 day', strtotime($tgl_presensi)));
                    $jam_pulang = $tgl_pulang . " " . date("H:i", strtotime($ceklastpresensi->jam_pulang));
                } else {
                    $tgl_pulang = $tgl_presensi;
                    $jam_pulang = $tgl_pulang . " " . date("H:i", strtotime($jam_kerja->jam_pulang));
                }

                //echo "A" . $jam_pulang;
            } else {
                if ($tgl_pulang_shift_3 <= "08:00" && $kode_jadwal_last == "JD004") {
                    $tgl_presensi = $lastday;
                    $tgl_pulang = date('Y-m-d', strtotime('+1 day', strtotime($tgl_presensi)));
                    $jam_pulang = $tgl_pulang . " 07:00";
                    $kode_jam_kerja = "JK08";
                    $kode_jadwal = "JD004";
                    //echo 'B';
                } else {

                    if ($kode_jadwal == "JD004") {
                        if ($hariini != "Sabtu") {
                            if ($jam_sekarang > "00:00" && $jam_sekarang <= "08:00") {
                                $tgl_pulang = $tgl_presensi;
                            } else {
                                $tgl_pulang = date('Y-m-d', strtotime('+1 day', strtotime($tgl_presensi)));
                            }
                        } else {
                            $tgl_pulang = $tgl_presensi;
                        }
                    } else {
                        $tgl_pulang = $tgl_presensi;
                    }

                    //echo 'C';
                    $jam_pulang = $tgl_pulang . " " . date("H:i", strtotime($jam_kerja->jam_pulang));
                }
                // $tgl_pulang = $tgl_presensi;
                // $jam_pulang = $tgl_pulang . " " . $jam_kerja->jam_pulang;

            }

            $date_jampulang = date("Y-m-d", strtotime($jam_pulang));
            $hour_jampulang = (date("H", strtotime($jam_pulang)) - 2);
            $h_jampulang = $hour_jampulang < 9 ? "0" . $hour_jampulang : $hour_jampulang;
            $jam_pulang = $date_jampulang . " " . $h_jampulang . ":00";

            $jamabsen = $jam;
            if ($jamabsen < $jam_pulang) {
                echo "error|Maaf Belum Waktunya Absen Pulang, Absen Pulang di Mulai Pada Pukul "  . " " . $jam_pulang . " |out";
            } else {
                $cek = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->first();
                if ($cek == null) {
                    $data = [
                        'nik' => $nik,
                        'tgl_presensi' => $tgl_presensi,
                        'jam_out' => $jam,
                        'kode_jadwal' => $kode_jadwal,
                        'kode_jam_kerja' => $kode_jam_kerja,
                        'status' => 'h',
                    ];

                    $simpan = DB::table('presensi')->insert($data);
                    if ($simpan) {
                        echo "success|Terimkasih, Hati Hati Di Jalan|out";
                    } else {
                        echo "error|Maaf Gagal absen, Hubungi Tim It|out";
                    }
                } else if ($cek != null && !empty($cek->jam_out)) {
                    echo "error|Maaf Gagal absen, Anda Sudah Melakukan Presensi Pulang|in";
                } else if ($cek != null && empty($cek->jam_out)) {
                    $data_masuk = [
                        'jam_out' => $jam
                    ];
                    $update = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->update($data_masuk);
                    if ($update) {
                        echo "success|Terimkasih, Hati Hati Di Jalan|out";
                    } else {
                        echo "error|Maaf Gagal absen, Hubungi Tim It|out";
                    }
                }
            }
        }
    }
}
