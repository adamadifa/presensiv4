@extends('layouts.presensi')
@section('header')
    <style>
        body {
            margin-bottom: 15% !important;
        }

        .card .card-body {
            padding: 15px 10px 10px 5px !important;
        }
    </style>
    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Data Izin / Sakit</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->
@endsection
@section('content')
    <div class="row" style="margin-top: 50px;">
        <div class="col-12" style="background-color: #cb1f0e; padding: 0px; margin: 0px">
            <div class="horizontal-scroll" style="padding: 0 !important">
                <nav class="navbar navbar-expand">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->is('pengajuanizin/createizinterlambat') ? 'active' : '' }}"
                                href="javascript:void(0);" onclick="showContent('izin_terlambat')">Izin
                                Terlambat</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->is('pengajuanizin/createizinabsen') ? 'active' : '' }}" href="javascript:void(0);"
                                onclick="showContent('izin_absen')">Izin
                                Absen</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->is('pengajuanizin/createizinkeluar') ? 'active' : '' }}"
                                href="javascript:void(0);" onclick="showContent('izin_keluar')">Izin
                                Keluar Kantor</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->is('pengajuanizin/createizinpulang') ? 'active' : '' }}"
                                href="javascript:void(0);" onclick="showContent('izin_pulang')">Izin
                                Pulang</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->is('pengajuanizin/createsakit') ? 'active' : '' }}" href="javascript:void(0);"
                                onclick="showContent('sakit')">Sakit</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->is('pengajuanizin/createcuti') ? 'active' : '' }}" href="javascript:void(0);"
                                onclick="showContent('cuti')">Cuti</a>
                        </li>
                    </ul>
                </nav>
            </div>
            <style>
                .horizontal-scroll {
                    overflow-x: auto;
                    white-space: nowrap;
                }

                .navbar-nav {
                    display: inline-flex;
                }

                .nav-item {
                    margin-right: 10px;
                    margin-bottom: 3px;
                }

                .nav-link.active {
                    font-weight: bold;
                    background-color: #c74d41;
                    border-radius: 30px;
                    padding: 3px;
                    margin-top: 8px;
                }
            </style>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div id="content-area" style="margin-top: 20px;">
                <div id="izin_terlambat" class="content" style="display: none;">
                    @foreach ($izinterlambat as $d)
                        <div class="row mb-1">
                            <div class="col">
                                <div class="card historiborderred listizin" data-id="{{ $d->kode_izin_terlambat }}" data-toggle="modal"
                                    data-target="#actionSheetIconed">
                                    <div class="card-body">
                                        <div class="historicontent">
                                            <div class="historidetail1">
                                                <div class="iconpresence">
                                                    <ion-icon name="document-text-outline" style="font-size: 64px;" class="text-danger"></ion-icon>
                                                </div>
                                                <div class="datepresence">
                                                    <h4 class="">{{ DateToIndo2($d->tanggal) }} <br> Izin Terlambat</h4>
                                                    <span>Terlambat Pukul : {{ date('H:i', strtotime($d->jam_terlambat)) }}</span>
                                                </div>
                                            </div>
                                            <div class="historidetail2" style="margin-left:20px">

                                                @if ($d->status == 0)
                                                    <span class="badge bg-warning">
                                                        <ion-icon name="refresh-outline"></ion-icon>
                                                    </span>
                                                @elseif($d->status == 1)
                                                    <span class="badge bg-success">
                                                        Disetujui
                                                    </span>
                                                @elseif($d->status == 2)
                                                    <span class="badge bg-danger">
                                                        Ditolak
                                                    </span>
                                                @endif
                                                <span class="timepresence">

                                                </span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div id="izin_absen" class="content" style="display: none;">
                    @foreach ($izinabsen as $d)
                        <div class="row mb-1">
                            <div class="col">
                                <div class="card historiborderred listizin" data-id="{{ $d->kode_izin }}" data-toggle="modal"
                                    data-target="#actionSheetIconed">
                                    <div class="card-body">
                                        <div class="historicontent">
                                            <div class="historidetail1">
                                                <div class="iconpresence">
                                                    <ion-icon name="document-text-outline" style="font-size: 64px;" class="text-danger"></ion-icon>
                                                </div>
                                                <div class="datepresence">
                                                    <h4 class="">{{ DateToIndo2($d->tanggal) }}
                                                        <br>
                                                        Izin Tidak Masuk Kantor
                                                    </h4>
                                                    <span>{{ $d->keterangan }}</span><br>
                                                    <small class="text-muted">
                                                        {{ date('d-m-Y', strtotime($d->dari)) }} s/d {{ date('d-m-Y', strtotime($d->sampai)) }}
                                                    </small>

                                                </div>
                                            </div>
                                            <div class="historidetail2" style="margin-left:20px">

                                                @if ($d->status == 0)
                                                    <span class="badge bg-warning">
                                                        <ion-icon name="refresh-outline"></ion-icon>
                                                    </span>
                                                @elseif($d->status == 1)
                                                    <span class="badge bg-success">
                                                        Disetujui
                                                    </span>
                                                @elseif($d->status == 2)
                                                    <span class="badge bg-danger">
                                                        Ditolak
                                                    </span>
                                                @endif
                                                <span class="timepresence">

                                                </span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div id="izin_keluar" class="content" style="display: none;">
                    @foreach ($izinkeluar as $d)
                        <div class="row mb-1">
                            <div class="col">
                                <div class="card historiborderred listizin" data-id="{{ $d->kode_izin_keluar }}" data-toggle="modal"
                                    data-target="#actionSheetIconed">
                                    <div class="card-body">
                                        <div class="historicontent">
                                            <div class="historidetail1">
                                                <div class="iconpresence">
                                                    <ion-icon name="document-text-outline" style="font-size: 64px;" class="text-danger"></ion-icon>
                                                </div>
                                                <div class="datepresence">
                                                    <h4 class="">{{ DateToIndo2($d->tanggal) }}
                                                        <br>
                                                        Izin Keluar Kantor
                                                    </h4>
                                                    <span>Keluar Pukul : {{ date('H:i', strtotime($d->jam_keluar)) }}</span>
                                                </div>
                                            </div>
                                            <div class="historidetail2" style="margin-left:20px">

                                                @if ($d->status == 0)
                                                    <span class="badge bg-warning">
                                                        <ion-icon name="refresh-outline"></ion-icon>
                                                    </span>
                                                @elseif($d->status == 1)
                                                    <span class="badge bg-success">
                                                        Disetujui
                                                    </span>
                                                @elseif($d->status == 2)
                                                    <span class="badge bg-danger">
                                                        Ditolak
                                                    </span>
                                                @endif
                                                <span class="timepresence">

                                                </span>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div id="izin_pulang" class="content" style="display: none;">
                    @foreach ($izinpulang as $d)
                        <div class="row mb-1">
                            <div class="col">
                                <div class="card historiborderred listizin" data-id="{{ $d->kode_izin_pulang }}" data-toggle="modal"
                                    data-target="#actionSheetIconed">
                                    <div class="card-body">
                                        <div class="historicontent">
                                            <div class="historidetail1">
                                                <div class="iconpresence">
                                                    <ion-icon name="document-text-outline" style="font-size: 64px;" class="text-danger"></ion-icon>
                                                </div>
                                                <div class="datepresence">
                                                    <h4 class="">{{ DateToIndo2($d->tanggal) }}
                                                        <br>
                                                        Izin Pulang
                                                    </h4>
                                                    <span>Pulang Pukul : {{ date('H:i', strtotime($d->jam_pulang)) }}</span>




                                                </div>
                                            </div>
                                            <div class="historidetail2" style="margin-left:20px">

                                                @if ($d->status_approved == 0)
                                                    <span class="badge bg-warning">
                                                        <ion-icon name="refresh-outline"></ion-icon>
                                                    </span>
                                                @elseif($d->status_approved == 1)
                                                    <span class="badge bg-success">
                                                        Disetujui
                                                    </span>
                                                @elseif($d->status_approved == 2)
                                                    <span class="badge bg-danger">
                                                        Ditolak
                                                    </span>
                                                @endif
                                                <span class="timepresence">

                                                </span>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div id="sakit" class="content" style="display: none;">
                    @foreach ($izinsakit as $d)
                        <div class="row mb-1">
                            <div class="col">
                                <div class="card historiborderred listizin" data-id="{{ $d->kode_izin_sakit }}" data-toggle="modal"
                                    data-target="#actionSheetIconed">
                                    <div class="card-body">
                                        <div class="historicontent">
                                            <div class="historidetail1">
                                                <div class="iconpresence">
                                                    <ion-icon name="document-text-outline" style="font-size: 64px;" class="text-danger"></ion-icon>
                                                </div>
                                                <div class="datepresence">
                                                    <h4 class="">{{ DateToIndo2($d->tanggal) }}
                                                        <br>
                                                        Izin Sakit {{ !empty($d->doc_sid) ? '(SID)' : '' }}
                                                    </h4>
                                                    <span>{{ $d->keterangan }}</span><br>
                                                    <small class="text-muted">
                                                        {{ date('d-m-Y', strtotime($d->dari)) }} s/d {{ date('d-m-Y', strtotime($d->sampai)) }}
                                                    </small>

                                                </div>
                                            </div>
                                            <div class="historidetail2" style="margin-left:20px">

                                                @if ($d->status == 0)
                                                    <span class="badge bg-warning">
                                                        <ion-icon name="refresh-outline"></ion-icon>
                                                    </span>
                                                @elseif($d->status == 1)
                                                    <span class="badge bg-success">
                                                        Disetujui
                                                    </span>
                                                @elseif($d->status == 2)
                                                    <span class="badge bg-danger">
                                                        Ditolak
                                                    </span>
                                                @endif
                                                <span class="timepresence">

                                                </span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div id="cuti" class="content" style="display: none;">
                    @foreach ($izincuti as $d)
                        <div class="row mb-1">
                            <div class="col">
                                <div class="card historiborderred listizin" data-id="{{ $d->kode_izin_cuti }}" data-toggle="modal"
                                    data-target="#actionSheetIconed">
                                    <div class="card-body">
                                        <div class="historicontent">
                                            <div class="historidetail1">
                                                <div class="iconpresence">
                                                    <ion-icon name="document-text-outline" style="font-size: 64px;" class="text-danger"></ion-icon>
                                                </div>
                                                <div class="datepresence">
                                                    <h4 class="">{{ DateToIndo2($d->tanggal) }}
                                                        <br>
                                                        Izin Cuti
                                                    </h4>
                                                    <span>{{ $d->keterangan }}</span><br>
                                                    <small class="text-muted">
                                                        {{ date('d-m-Y', strtotime($d->dari)) }} s/d {{ date('d-m-Y', strtotime($d->sampai)) }}
                                                    </small>

                                                </div>
                                            </div>
                                            <div class="historidetail2" style="margin-left:20px">

                                                @if ($d->status == 0)
                                                    <span class="badge bg-warning">
                                                        <ion-icon name="refresh-outline"></ion-icon>
                                                    </span>
                                                @elseif($d->status == 1)
                                                    <span class="badge bg-success">
                                                        Disetujui
                                                    </span>
                                                @elseif($d->status == 2)
                                                    <span class="badge bg-danger">
                                                        Ditolak
                                                    </span>
                                                @endif
                                                <span class="timepresence">

                                                </span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="fab-button animate bottom-right dropdown" style="margin-bottom:70px">
        <a href="#" class="fab bg-danger" data-toggle="dropdown">
            <ion-icon name="add-outline" role="img" class="md hydrated" aria-label="add outline"></ion-icon>
        </a>
        <div class="dropdown-menu">
            <a class="dropdown-item bg-danger" href="/pengajuanizin/createizinterlambat">
                <ion-icon name="document-outline" role="img" class="md hydrated" aria-label="musical notes outline"></ion-icon>
                <p>Izin Terlambat</p>
            </a>
            <a class="dropdown-item bg-danger" href="/pengajuanizin/createizinabsen">
                <ion-icon name="document-outline" role="img" class="md hydrated" aria-label="image outline"></ion-icon>
                <p>Izin Absen</p>
            </a>
            <a class="dropdown-item bg-danger" href="/pengajuanizin/createizinkeluar">
                <ion-icon name="document-outline" role="img" class="md hydrated" aria-label="videocam outline"></ion-icon>
                <p>Izin Keluar Kantor</p>
            </a>
            <a class="dropdown-item bg-danger" href="/pengajuanizin/createizinpulang">
                <ion-icon name="document-outline" role="img" class="md hydrated" aria-label="videocam outline"></ion-icon>
                <p>Izin Pulang</p>
            </a>
            <a class="dropdown-item bg-danger" href="/pengajuanizin/createsakit">
                <ion-icon name="document-outline" role="img" class="md hydrated" aria-label="videocam outline"></ion-icon>
                <p>Sakit</p>
            </a>
            <a class="dropdown-item bg-danger" href="/pengajuanizin/createcuti">
                <ion-icon name="document-outline" role="img" class="md hydrated" aria-label="videocam outline"></ion-icon>
                <p>Cuti</p>
            </a>
        </div>
    </div>

    <div class="modal fade action-sheet" id="actionSheetIconed" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Aksi</h5>
                </div>
                <div class="modal-body" id="showact"></div>
            </div>
        </div>
    </div>

    <div id="toast-3" class="toast-box toast-bottom">
        <div class="in">
            <ion-icon name="checkmark-circle" class="text-success"></ion-icon>
            <div class="text">
                {{ Session::get('success') }}
            </div>
        </div>
        <button type="button" class="btn btn-sm btn-text-success close-button">CLOSE</button>
    </div>
    <div class="modal fade dialogbox" id="DialogBasic" data-backdrop="static" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Yakin Dihapus ?</h5>
                </div>
                <div class="modal-body">
                    Data Pengajuan Izin Akan dihapus
                </div>
                <div class="modal-footer">
                    <div class="btn-inline">
                        <a href="#" class="btn btn-text-secondary" data-dismiss="modal">Batalkan</a>
                        <a href="" class="btn btn-text-primary" id="hapuspengajuan">Hapus</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (Session::get('success'))
        @push('myscript')
            <script>
                $(document).ready(function() {
                    toastbox('toast-3');
                });
            </script>
        @endpush
    @endif
@endsection
@push('myscript')
    <script>
        function showContent(content) {
            // Sembunyikan semua konten
            const contents = document.querySelectorAll('.content');
            contents.forEach((item) => {
                item.style.display = 'none';
            });
            // Tampilkan konten yang dipilih
            document.getElementById(content).style.display = 'block';

            // Update active state pada navbar
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach((link) => {
                link.classList.remove('active');
            });

            // Tambahkan class active pada link yang diklik
            const activeLink = document.querySelector(`[onclick="showContent('${content}')"]`);
            if (activeLink) {
                activeLink.classList.add('active');
            }
        }

        // Set active state awal berdasarkan URL
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            let defaultContent = 'izin_terlambat';

            if (currentPath.includes('createizinabsen')) {
                defaultContent = 'izin_absen';
            } else if (currentPath.includes('createizinkeluar')) {
                defaultContent = 'izin_keluar';
            } else if (currentPath.includes('createizinpulang')) {
                defaultContent = 'izin_pulang';
            } else if (currentPath.includes('createsakit')) {
                defaultContent = 'sakit';
            } else if (currentPath.includes('createcuti')) {
                defaultContent = 'cuti';
            }

            showContent(defaultContent);
        });
    </script>
@endpush
