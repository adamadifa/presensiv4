@extends('layouts.presensi')
@section('header')
    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">{{ $pinjaman->no_pinjaman }}</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->
@endsection
@section('content')
    <div class="row" style="margin-top: 70px">
        <div class="col">
            <table class="table">
                <tr>
                    <th>No. Pinjaman</th>
                    <td>{{ $pinjaman->no_pinjaman }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td>{{ DateToIndo2($pinjaman->tanggal) }}</td>
                </tr>
                <tr>
                    <th>Angsuran</th>
                    <td>{{ $pinjaman->angsuran }} x Angsuran</td>
                </tr>
                <tr>
                    <th>Jumlah Pinjaman</th>
                    <td style="text-align: right">{{ number_format($pinjaman->jumlah_pinjaman, '0', '', '.') }}</td>
                </tr>
                <tr>
                    <th>Jumlah Angsuran</th>
                    <td style="text-align: right">{{ number_format($pinjaman->jumlah_angsuran, '0', '', '.') }}</td>
                </tr>
                <tr>
                    <th>Total Bayar</th>
                    <td style="text-align: right">{{ number_format($pinjaman->totalpembayaran, '0', '', '.') }}</td>
                </tr>
                <tr>
                    <th>Sisa Tagihan</th>
                    <td style="text-align: right">
                        {{ number_format($pinjaman->jumlah_pinjaman - $pinjaman->totalpembayaran, '0', '', '.') }}</td>
                </tr>
                <tr>
                    <th>Keterangan</th>
                    <td>
                        @php
                            $sisatagihan = $pinjaman->jumlah_pinjaman - $pinjaman->totalpembayaran;
                        @endphp
                        @if ($sisatagihan != 0)
                            <span class="badge bg-danger">Belum Lunas</span>
                        @else
                            <span class="badge bg-success">Lunas</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <table class="table">
                <thead>
                    <tr>
                        <th>No.Bukti</th>
                        <th>Tanggal</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($historibayar as $d)
                        <tr>
                            <td>{{ $d->no_bukti }}</td>
                            <td>{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                            <td style="text-align: right">{{ number_format($d->jumlah, '0', '', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
