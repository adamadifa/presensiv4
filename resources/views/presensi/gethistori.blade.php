@if ($histori->isEmpty())
    <div class="alert  alert-outline-warning">
        <p>Data Belum Aada</p>
    </div>
@endif
@foreach ($histori as $d)
    @if ($d->status == 'h')
        <div class="row mb-1">
            <div class="col">
                <div class="card historicard {{ $d->jam_out != null ? 'historibordergreen' : 'historiborderred' }}">
                    <div class="card-body">
                        <div class="historicontent">
                            <div class="historidetail1">
                                <div class="iconpresence">
                                    <ion-icon name="finger-print-outline" class="text-success" style="font-size: 48px"></ion-icon>
                                </div>
                                <div class="datepresence">
                                    <h4>{{ DateToIndo2($d->tanggal) }}</h4>
                                    <span class="timepresence">
                                        {!! $d->jam_in != null ? date('H:i', strtotime($d->jam_in)) : '<span class="danger">Belum Scan</span>' !!}
                                        {!! $d->jam_out != null ? '- ' . date('H:i', strtotime($d->jam_out)) : '<span class="danger"> - Belum Scan</span>' !!}
                                        @php

                                            //Tanggal Selesai Jam Kerja Jika Lintas Hari Maka Tanggal Presensi + 1 Hari
                                            $tanggal_selesai =
                                                $d->lintashari == '1' ? date('Y-m-d', strtotime('+1 day', strtotime($d->tanggal))) : $d->tanggal;

                                            //Jam Absen Karyawan
                                            $jam_in = date('Y-m-d H:i', strtotime($d->jam_in));
                                            $jam_out = date('Y-m-d H:i', strtotime($d->jam_out));

                                            //Jadwal Jam Kerja
                                            $j_mulai = date('Y-m-d H:i', strtotime($d->tanggal . ' ' . $d->jam_mulai));
                                            $j_selesai = date('Y-m-d H:i', strtotime($tanggal_selesai . ' ' . $d->jam_selesai));

                                            //Jika SPG Jam Mulai Kerja nya adalah Saat Dia Absen  Jika Tidak Sesuai Jadwal
                                            $is_j31_j32_effective = in_array($d->kode_jabatan, ['J31', 'J32']) && $d->tanggal >= '2026-02-21';
                                            $is_spg = in_array($d->kode_jabatan, ['J22', 'J23']) || $is_j31_j32_effective;
                                            $jam_mulai = $is_spg ? $d->jam_in : $j_mulai;
                                            $jam_selesai = $is_spg ? $d->jam_out : $j_selesai;

                                            // Jam Istirahat
                                            if ($d->istirahat == '1') {
                                                if ($d->lintashari == '0') {
                                                    $jam_awal_istirahat = $d->tanggal . ' ' . $d->jam_awal_istirahat;
                                                    $jam_akhir_istirahat = $d->tanggal . ' ' . $d->jam_akhir_istirahat;
                                                } else {
                                                    $jam_awal_istirahat = $tanggal_selesai . ' ' . $d->jam_awal_istirahat;
                                                    $jam_akhir_istirahat = $tanggal_selesai . ' ' . $d->jam_akhir_istirahat;
                                                }
                                            } else {
                                                $jam_awal_istirahat = null;
                                                $jam_akhir_istirahat = null;
                                            }
                                            $terlambat = hitungjamterlambat($jam_in, $jam_mulai);
                                        @endphp
                                        <br>

                                        <!-- Cek Apakah Terlambat-->
                                        @if (!empty($terlambat))
                                            @php
                                                $denda = hitungdenda(
                                                    $terlambat['jamterlambat'],
                                                    $terlambat['menitterlambat'],
                                                    $d->kode_izin_terlambat,
                                                    $d->kode_dept,
                                                );

                                            @endphp
                                            {{-- {{ $denda['cek'] }} --}}
                                            <span style="color:red">{{ $terlambat['keterangan_terlambat'] }}
                                                - {{ !empty($denda['denda']) ? $denda['denda'] : $denda['keterangan'] }}
                                            </span>
                                        @else
                                            <span style="color:green">Tepat Waktu</span>
                                        @endif


                                        <!-- Cek Pulang Cepat -->
                                        @if (!empty($jam_out) && $jam_out < $jam_selesai)
                                            <div class="danger">Pulang Cepat</div>
                                        @endif
                                        {{-- {{ $d->total_jam }} --}}
                                        @if (!empty($d->kode_izin_keluar))
                                            @php
                                                $jam_keluar = date('Y-m-d H:i', strtotime($d->jam_keluar));
                                                $jam_kembali = !empty($d->jam_kembali) ? date('Y-m-d H:i', strtotime($d->jam_kembali)) : '';

                                                $keluarkantor = hitungjamkeluarkantor(
                                                    $jam_keluar,
                                                    $jam_kembali,
                                                    $jam_selesai,
                                                    $d->total_jam,
                                                    $d->istirahat,
                                                    $jam_awal_istirahat,
                                                    $jam_akhir_istirahat,
                                                );
                                            @endphp
                                            <div class="{{ $keluarkantor['color'] }}">
                                                {{-- {{ $jam_keluar }} --}}
                                                Izin Keluar : {{ $keluarkantor['totaljamkeluar'] }}
                                            </div>
                                        @endif
                                    </span>

                                    <!-- Jika Izin Pulang -->
                                    @if (!empty($d->kode_izin_pulang))
                                        <div class="danger">Izin Pulang</div>
                                    @endif
                                </div>
                            </div>
                            <div class="historidetail2">
                                <h4 style="font-size: 14px">{{ $d->nama_jadwal }} {{ $d->kode_cabang }}</h4>
                                <div class="primary" style="font-size: 12px">{{ date('H:i', strtotime($d->jam_mulai)) }} -
                                    {{ date('H:i', strtotime($d->jam_selesai)) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row mb-1">
            <div class="col">
                <div class="card historicard historiborderred">
                    <div class="card-body">
                        <div class="historicontent">
                            <div class="historidetail1">
                                <div class="iconpresence">
                                    <ion-icon name="finger-print-outline" class="text-danger" style="font-size: 48px"></ion-icon>
                                </div>
                                <div class="datepresence">
                                    <h4>{{ DateToIndo2($d->tanggal) }}</h4>
                                    @if ($d->status == 'i')
                                        @php
                                            $status = 'Izin';
                                        @endphp
                                    @elseif($d->status == 'c')
                                        @php
                                            $status = 'Cuti';
                                        @endphp
                                    @elseif($d->status == 's')
                                        @php
                                            $status = 'Sakit';
                                        @endphp
                                    @else
                                        @php
                                            $status = '';
                                        @endphp
                                    @endif
                                    <div class="timepresence">{{ $status }} -
                                        @if ($d->status == 'i')
                                            Tidak Masuk Kantor
                                        @elseif($d->status == 'c')
                                            {{ $d->nama_cuti }}<br>
                                            {{ !empty($d->nama_cuti_khusus) ? '(' . $d->nama_cuti_khusus . ')' : '' }}
                                        @elseif($d->status == 's')
                                            @if (empty($d->doc_sid))
                                                <span class="text-danger">
                                                    Tanpa SID
                                                </span>
                                            @else
                                                <span class="text-primary">
                                                    <ion-icon name="document-attach-outline"></ion-icon> SID
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach
