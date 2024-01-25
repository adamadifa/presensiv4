<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SlipgajiController extends Controller
{
    public function index()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $slipgaji = DB::table('slip_gaji')->limit(5)->orderBy('tanggal', 'desc')->get();
        return view('slipgaji.index', compact('slipgaji', 'namabulan'));
    }


    public function cetak($bulan, $tahun)
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
        //dd($berlakugaji);

        $datalibur = ceklibur($dari, $sampai);
        $dataliburpenggantiminggu = cekliburpenggantiminggu($dari, $sampai);
        $dataminggumasuk = cekminggumasuk($dari, $sampai);
        $datawfh = cekwfh($dari, $sampai);
        $datawfhfull = cekwfhfull($dari, $sampai);
        $datalembur = ceklembur($dari, $sampai, 1);
        $datalemburharilibur = ceklembur($dari, $sampai, 2);

        // echo json_encode($datalembur);
        // die;



        // Define search list with multiple key=>value pair
        //$search_items = array('id_kantor' => "TSM", 'tanggal_libur' => "2023-06-17");

        // Call search and pass the array and
        // the search list
        //$res = cektgllibur($ceklibur, $search_items);
        //dd(empty($res));

        //dd($sampai);
        $select_date = "";
        $field_date = "";
        $i = 1;
        while (strtotime($dari) <= strtotime($sampai)) {
            $rangetanggal[] = $dari;
            $select_date .= "MAX(IF(tgl_presensi = '$dari', CONCAT(
                IFNULL(jam_in,'NA'),
                '|',IFNULL(jam_out,'NA'),
                '|',IFNULL(nama_jadwal,'NA'),
                '|',IFNULL(jam_kerja.jam_masuk,'NA'),
                '|',IFNULL(jam_kerja.jam_pulang,'NA'),
                '|',IFNULL(presensi.status,'NA'),
                '|',IFNULL(presensi.kode_izin,'NA'),
                '|',IFNULL(presensi.kode_izin_terlambat,'NA'),
                '|',IFNULL(presensi.kode_izin_pulang,'NA'),
                '|',IFNULL(pengajuan_izin.jam_keluar,'NA'),
                '|',IFNULL(pengajuan_izin.jam_masuk,'NA'),
                '|',IFNULL(jam_kerja.total_jam,'NA'),
                '|',IFNULL(pengajuan_izin.sid,'NA'),
                '|',IFNULL(jam_kerja.jam_awal_istirahat,'NA'),
                '|',IFNULL(jam_kerja.jam_istirahat,'NA'),
                '|',IFNULL(jam_kerja.total_jam,'NA'),
                '|',IFNULL(jam_kerja.lintashari,'NA'),
                '|',IFNULL(izinpulang.direktur,'NA'),
                '|',IFNULL(pengajuan_izin.direktur,'NA'),
                '|',IFNULL(pengajuan_izin.keperluan,'NA'),
                '|',IFNULL(izinterlambat.direktur,'NA')
                ),NULL)) as hari_" . $i . ",";

            $field_date .= "hari_" . $i . ",";
            $i++;
            $dari = date("Y-m-d", strtotime("+1 day", strtotime($dari)));
        }


        // dd($bulan);
        //dd($rangetanggal);
        $jmlrange = count($rangetanggal);
        $lastrange = $jmlrange - 1;
        $namabulan = array("", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

        // if ($jmlrange == 30) {
        //     array_push($rangetanggal, $rangetanggal[$lastrange]);
        // } else if ($jmlrange == 29) {
        //     array_push($rangetanggal, $rangetanggal[$lastrange], $rangetanggal[$lastrange]);
        // }
        $jmlrange = count($rangetanggal);
        $lastrange = $jmlrange - 1;

        //dd($jmlrange);
        $query = Karyawan::query();

        //dd($jmlrange);
        $query->selectRaw("
                $field_date
                master_karyawan.*,nama_group,nama_dept,nama_jabatan,nama_cabang,klasifikasi,no_rekening,
                iu_masakerja,iu_lembur,iu_penempatan,iu_kpi,
                im_ruanglingkup, im_penempatan,im_kinerja,
                gaji_pokok,
                t_jabatan,t_masakerja,t_tanggungjawab,t_makan,t_istri,t_skill,
                cicilan_pjp,jml_kasbon,jml_nonpjp,jml_pengurang,
                bpjs_kesehatan.perusahaan,bpjs_kesehatan.pekerja,bpjs_kesehatan.keluarga,iuran_kes,
                bpjs_tenagakerja.k_jht,bpjs_tenagakerja.k_jp,iuran_tk
            ");
        $query->leftJoin(
            DB::raw("(
            SELECT
                $select_date
                presensi.nik
            FROM
                presensi
            LEFT JOIN pengajuan_izin ON presensi.kode_izin = pengajuan_izin.kode_izin
            LEFT JOIN pengajuan_izin as izinpulang ON presensi.kode_izin_pulang = izinpulang.kode_izin
            LEFT JOIN pengajuan_izin as izinterlambat ON presensi.kode_izin_terlambat = izinterlambat.kode_izin
            LEFT JOIN jadwal_kerja ON presensi.kode_jadwal = jadwal_kerja.kode_jadwal
            LEFT JOIN jam_kerja ON presensi.kode_jam_kerja = jam_kerja.kode_jam_kerja
            WHERE tgl_presensi BETWEEN '$rangetanggal[0]' AND  '$sampai'
            GROUP BY
                presensi.nik
            ) presensi"),
            function ($join) {
                $join->on('presensi.nik', '=', 'master_karyawan.nik');
            }
        );
        $query->leftJoin('hrd_group', 'master_karyawan.grup', '=', 'hrd_group.id');
        $query->leftJoin('hrd_departemen', 'master_karyawan.kode_dept', '=', 'hrd_departemen.kode_dept');
        $query->leftJoin('hrd_jabatan', 'master_karyawan.id_jabatan', '=', 'hrd_jabatan.id');
        $query->leftJoin('cabang', 'master_karyawan.id_kantor', '=', 'cabang.kode_cabang');
        $query->leftJoin(
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
        );

        $query->leftJoin(
            DB::raw("(
                    SELECT nik,iu_masakerja,iu_lembur,iu_penempatan,iu_kpi,
                    im_ruanglingkup,im_penempatan,im_kinerja
                    FROM hrd_masterinsentif WHERE kode_insentif IN (SELECT MAX(kode_insentif) as kode_insentif FROM hrd_masterinsentif
                    WHERE tgl_berlaku <= '$berlakugaji'  GROUP BY nik)
                ) hrdinsentif"),
            function ($join) {
                $join->on('master_karyawan.nik', '=', 'hrdinsentif.nik');
            }
        );

        $query->leftJoin(
            DB::raw("(
                    SELECT nik,perusahaan,pekerja,keluarga,iuran as iuran_kes
                    FROM bpjs_kesehatan WHERE kode_bpjs_kes IN (SELECT MAX(kode_bpjs_kes) as kode_bpjs_kes FROM bpjs_kesehatan
                    WHERE tgl_berlaku <= '$berlakugaji'  GROUP BY nik)
                ) bpjs_kesehatan"),
            function ($join) {
                $join->on('master_karyawan.nik', '=', 'bpjs_kesehatan.nik');
            }
        );

        $query->leftJoin(
            DB::raw("(
                    SELECT nik,k_jht,k_jp,iuran as iuran_tk
                    FROM bpjs_tenagakerja WHERE kode_bpjs_tk IN (SELECT MAX(kode_bpjs_tk) as kode_bpjs_tk FROM bpjs_tenagakerja
                    WHERE tgl_berlaku <= '$berlakugaji'  GROUP BY nik)
                ) bpjs_tenagakerja"),
            function ($join) {
                $join->on('master_karyawan.nik', '=', 'bpjs_tenagakerja.nik');
            }
        );

        $query->leftJoin(
            DB::raw("(
                   SELECT nik, SUM(jumlah) as cicilan_pjp
                   FROM pinjaman_historibayar
                   INNER JOIN pinjaman ON pinjaman_historibayar.no_pinjaman = pinjaman.no_pinjaman
                   WHERE kode_potongan = '$kode_potongan'
                   GROUP BY nik
                ) pjp"),
            function ($join) {
                $join->on('master_karyawan.nik', '=', 'pjp.nik');
            }
        );

        $query->leftJoin(
            DB::raw("(
                   SELECT nik, SUM(jumlah) as jml_kasbon
                   FROM kasbon_historibayar
                   INNER JOIN kasbon ON kasbon_historibayar.no_kasbon = kasbon.no_kasbon
                   WHERE kode_potongan = '$kode_potongan'
                   GROUP BY nik
                ) kasbon"),
            function ($join) {
                $join->on('master_karyawan.nik', '=', 'kasbon.nik');
            }
        );

        $query->leftJoin(
            DB::raw("(
                   SELECT nik, SUM(jumlah) as jml_pengurang
                   FROM pengurang_gaji
                   WHERE kode_gaji = '$kode_potongan'
                   GROUP BY nik
                ) penguranggaji"),
            function ($join) {
                $join->on('master_karyawan.nik', '=', 'penguranggaji.nik');
            }
        );

        $query->leftJoin(
            DB::raw("(
                   SELECT nik, SUM(jumlah) as jml_nonpjp
                   FROM pinjaman_nonpjp_historibayar
                   INNER JOIN pinjaman_nonpjp ON pinjaman_nonpjp_historibayar.no_pinjaman_nonpjp = pinjaman_nonpjp.no_pinjaman_nonpjp
                   WHERE kode_potongan = '$kode_potongan'
                   GROUP BY nik
                ) pinjamannonpjp"),
            function ($join) {
                $join->on('master_karyawan.nik', '=', 'pinjamannonpjp.nik');
            }
        );


        $query->where('master_karyawan.nik', Auth::guard('karyawan')->user()->nik);
        $query->where('status_aktif', 1);
        $query->where('tgl_masuk', '<=', $sampai);
        $query->orWhere('status_aktif', 0);
        $query->where('tgl_off_gaji', '>=', $daribulangaji);
        $query->where('tgl_masuk', '<=', $sampai);
        $query->where('master_karyawan.nik', Auth::guard('karyawan')->user()->nik);

        $presensi = $query->get();



        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        return view('slipgaji.cetak', compact(
            'bulan',
            'tahun',
            'namabulan',
            'jmlrange',
            'rangetanggal',
            'presensi',
            'datalibur',
            'dataliburpenggantiminggu',
            'dataminggumasuk',
            'datawfh',
            'datawfhfull',
            'datalembur',
            'datalemburharilibur',
            'sampai'
        ));
    }
}
