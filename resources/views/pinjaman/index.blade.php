@extends('layouts.presensi')
@section('header')
    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Data Pinjaman</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->
@endsection
@section('content')
    <div class="row" style="top:56px; position:fixed; width:105%; z-index:999; background-color:#e9ecef; padding:5px">
        <div class="col">
            <form action="/pinjaman" method="GET">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <select name="bulan" id="bulan" class="selectmaterialize">
                                <option value="">Bulan</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ Request('bulan') == $i ? 'selected' : '' }}>
                                        {{ $namabulan[$i] }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <select name="tahun" id="tahun" class="selectmaterialize">
                                <option value="">Tahun</option>
                                @php
                                    $tahunmulai = 2022;
                                    $tahunskrg = date('Y');
                                @endphp
                                @for ($tahun = $tahunmulai; $tahun <= $tahunskrg; $tahun++)
                                    <option value="{{ $tahun }}" {{ Request('tahun') == $tahun ? 'selected' : '' }}>
                                        {{ $tahun }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <button class="btn btn-danger btn-block" id="getdata">
                                <ion-icon name="search-outline"></ion-icon> Search
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row" style="margin-top: 200px; overflow:scroll; height:100%; position:relative; bottom:10%">
        <div class="col">
            @foreach ($pinjaman as $d)
                <div class="row mb-1">
                    <div class="col">
                        <a href="/pinjaman/{{ Crypt::encrypt($d->no_pinjaman) }}/show">
                            <div class="card historiborderred listizin" data-toggle="modal" data-target="#actionSheetIconed">
                                <div class="card-body">
                                    <div class="historicontent">
                                        <div class="historidetail1">
                                            <div class="iconpresence">
                                                <ion-icon name="cash-outline" style="font-size: 64px;" class="text-warning"></ion-icon>
                                            </div>
                                            <div class="datepresence">
                                                <h4 class="">
                                                    {{ $d->no_pinjaman }}
                                                </h4>
                                                <small class="text-muted">
                                                    {{ DateToIndo2($d->tanggal) }}<br>
                                                    {{ $d->angsuran }} x Angsuran
                                                </small>

                                            </div>
                                        </div>
                                        <div class="historidetail2" style="margin-left:20px">
                                            <h3 class="" style="margin-bottom: 0">
                                                {{ number_format($d->jumlah_pinjaman, '0', '', '.') }}
                                            </h3>
                                            @if ($d->totalpembayaran != $d->jumlah_pinjaman)
                                                <span class="badge bg-danger"> Belum Lunas</span>
                                            @else
                                                <span class="badge bg-success"> Lunas</span>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="fab-button bottom-right" style="margin-bottom:70px">
        <a href="/pinjaman/simulasi" class="fab bg-danger">
            <ion-icon name="calculator-outline"></ion-icon>
        </a>
    </div>
@endsection
