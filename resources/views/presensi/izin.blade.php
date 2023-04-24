@extends('layouts.presensi')
@section('header')
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
<div class="row" style="margin-top:70px">
    <div class="col">
        <?php
        function DateToIndo2($date2)
        { // fungsi atau method untuk mengubah tanggal ke format indonesia
        // variabel BulanIndo merupakan variabel array yang menyimpan nama-nama bulan
            $BulanIndo2 = array(
            "Januari", "Februari", "Maret",
            "April", "Mei", "Juni",
            "Juli", "Agustus", "September",
            "Oktober", "November", "Desember"
            );

            $tahun2 = substr($date2, 0, 4); // memisahkan format tahun menggunakan substring
            $bulan2 = substr($date2, 5, 2); // memisahkan format bulan menggunakan substring
            $tgl2 = substr($date2, 8, 2); // memisahkan format tanggal menggunakan substring

            $result = $tgl2 . " " . $BulanIndo2[(int)$bulan2 - 1] . " " . $tahun2;
            return ($result);
        }
        ?>
        @php
        $messagesuccess = Session::get('success');
        $messageerror = Session::get('error');
        @endphp
        @if (Session::get('success'))
        @push('myscript')
        <script>
            $(document).ready(function() {
                toastbox('toast-3');
            });

        </script>
        @endpush
        @endif
        @if (Session::get('error'))
        <div class="alert alert-warning">
            {{ $messageerror }}
        </div>
        @endif
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
<div class="row">
    <div class="col">
        @foreach ($dataizin as $d)
        <div class="row mb-1">
            <div class="col">
                <div class="card listizin" data-id="{{ $d->kode_izin }}" data-toggle="modal" data-target="#actionSheetIconed">
                    <div class="card-body">
                        <div class="historicontent">
                            <div class="historidetail1">
                                <div class="iconpresence">
                                    <ion-icon name="document-text-outline" style="font-size: 48px"></ion-icon>
                                </div>
                                <div class="datepresence">
                                    <h4>{{ DateToIndo2($d->dari) }}
                                        @if ($d->status=="i")
                                        (Izin)
                                        @elseif($d->status=="s")
                                        (Sakit)
                                        @elseif($d->status=="c")
                                        (Cuti)
                                        @endif
                                    </h4>
                                    <small class="text-muted">{{ DateToIndo2($d->dari) }} s/d {{ DateToIndo2($d->sampai) }}</small>
                                    <br>
                                    <small class="text-muted">{{ $d->keterangan }}</small>
                                    <br>
                                    @if (!empty($d->sid))
                                    <a href="">
                                        <ion-icon name="document-text-outline"></ion-icon> Lihat SID
                                    </a>
                                    @endif

                                </div>
                            </div>
                            <div class="historidetail2">
                                <h4>{{ $d->jmlhari }} Hari</h4>
                                @if ($d->status_approved == 0)
                                <span class="badge bg-warning">Waiting</span>
                                @elseif($d->status_approved==1)
                                <span class="badge bg-success">Approved</span>
                                @elseif($d->status_approved==2)
                                <span class="badge bg-danger">Decline</span>
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
<div class="fab-button bottom-right" style="margin-bottom:70px">
    <a href="/presensi/buatizin" class="fab bg-danger">
        <ion-icon name="add-outline"></ion-icon>
    </a>
</div>

<div class="modal fade action-sheet" id="actionSheetIconed" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Action Sheet Title</h5>
            </div>
            <div class="modal-body" id="showact">

            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        $(".listizin").click(function(e) {
            var id = $(this).attr("data-id");
            $("#showact").load('/izin/' + id + "/showact");
        });

        //toastbox('toast-3');
    });

</script>
@endpush
