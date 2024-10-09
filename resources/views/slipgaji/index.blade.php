@extends('layouts.presensi')
@section('header')
    <style>
        body {
            margin-bottom: 15% !important;
        }

        .card .card-body {
            padding: 15px 10px 10px 5px !important;
        }

        /* .historicontent {
                                                                                                                                                                            justify-content: left !important;
                                                                                                                                                                        } */
    </style>
    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Slip Gaji</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->
@endsection
@section('content')
    <div class="row" style="margin-top: 70px; overflow:scroll; height:100%; position:relative; bottom:10%">
        <div class="col">
            @foreach ($slipgaji as $d)
                <a href="/slipgaji/{{ $d->bulan }}/{{ $d->tahun }}/cetak">
                    <div class="row mb-1">
                        <div class="col">
                            <div class="card historiborderred">
                                <div class="card-body">
                                    <div class="historicontent">
                                        <div class="historidetail1">
                                            <div class="iconpresence">
                                                <ion-icon name="document-text-outline" style="font-size: 64px;" class="text-danger"></ion-icon>
                                            </div>
                                            <div class="datepresence">
                                                <h4>{{ $namabulan[$d->bulan] }} {{ $d->tahun }}</h4>
                                                @php
                                                    $bulan = $d->bulan;
                                                    $tahun = $d->tahun;
                                                    if ($bulan == 1) {
                                                        $lastbulan = 12;
                                                        $lasttahun = $tahun - 1; //2023
                                                    } else {
                                                        $lastbulan = $bulan - 1;
                                                        $lasttahun = $tahun;
                                                    }

                                                    $lastbulan = $lastbulan < 10 ? '0' . $lastbulan : $lastbulan;
                                                    $bulan = $bulan < 10 ? '0' . $bulan : $bulan;
                                                    $dari = $lasttahun . '-' . $lastbulan . '-21';
                                                    $sampai = $tahun . '-' . $bulan . '-20';
                                                @endphp
                                                <small class="text-muted">
                                                    Periode : {{ date('d-m-Y', strtotime($dari)) }} s/d
                                                    {{ date('d-m-Y', strtotime($sampai)) }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                @if ($d->bulan == '2' && $tahun == '2024')
                    <a href="/slipgaji/3/2024/cetakthr">
                        <div class="row mb-1">
                            <div class="col">
                                <div class="card historiborderred">
                                    <div class="card-body">
                                        <div class="historicontent">
                                            <div class="historidetail1">
                                                <div class="iconpresence">
                                                    <ion-icon name="document-text-outline" style="font-size: 64px;" class="text-danger"></ion-icon>
                                                </div>
                                                <div class="datepresence">
                                                    <h4>THR {{ $d->tahun }}</h4>

                                                    <small class="text-muted">
                                                        THR 2024
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endif
            @endforeach
        </div>
    </div>
    {{-- <div class="fab-button bottom-right" style="margin-bottom:70px">
    <a href="/presensi/buatizin" class="fab bg-danger">
        <ion-icon name="add-outline"></ion-icon>
    </a>
</div> --}}
@endsection
