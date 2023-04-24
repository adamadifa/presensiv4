@extends('layouts.presensi')
@section('header')
<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">E-Presensi</div>
    <div class="right"></div>
</div>
<!-- * App Header -->
<style>
    .webcam-capture,
    .webcam-capture video {
        display: inline-block;
        width: 100% !important;
        margin: auto;
        height: auto !important;
        border-radius: 15px;

    }

    #map {
        height: 200px;
    }

</style>
<style>
    .jam-digital-malasngoding {

        background-color: #27272783;
        position: absolute;
        top: 60px;
        right: 5px;
        z-index: 9999;
        width: 150px;
        border-radius: 10px;
        padding: 5px;
    }



    .jam-digital-malasngoding p {
        color: #fff;
        font-size: 16px;
        text-align: center;
        margin-top: 0;
        margin-bottom: 0;
    }

</style>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
@endsection
@section('content')
@php
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
@endphp
<input type="hidden" id="lokasi">
{{-- <div class="row" style="margin-top: 70px">
    <div class="col">

        <div class="webcam-capture"></div>
    </div>
</div> --}}
<div class="jam-digital-malasngoding">
    <p>{{ DateToIndo2(date('Y-m-d'))}}</p>
    <p id="jam"></p>
</div>
<div class="row" style="margin-top: 60px">
    <div class="col">
        <div id="map"></div>
    </div>
</div>
<div class="row mt-2">
    <div class="col">
        @if ($cek > 0)
        <button id="takeabsen" class="btn btn-danger btn-block">
            <ion-icon name="finger-print-outline"></ion-icon>
            Scan Pulang
        </button>
        @else
        <button id="takeabsen" class="btn btn-success btn-block">
            <ion-icon name="finger-print-outline"></ion-icon>
            Scan Masuk
        </button>
        @endif

    </div>
</div>

<div class="row mt-2">
    <div class="col">
        <table class="table table-striped">
            <tr>
                <th>Jadwal</th>
                <td>{{ $jadwal->nama_jadwal }}</td>
            </tr>
            <tr>
                <th>Awal Jam Masuk</th>
                <td>{{ $jam_kerja->awal_jam_masuk }}</td>
            </tr>
            <tr>
                <th>Jam Masuk</th>
                <td>{{ $jam_kerja->jam_masuk }}</td>
            </tr>
            <tr>
                <th>Akhir Jam Masuk</th>
                <td>{{ $jam_kerja->akhir_jam_masuk }}</td>
            </tr>
            <tr>
                <th>Jam Pulang</th>
                <td>{{ $jam_kerja->jam_pulang }}</td>
            </tr>
        </table>
    </div>
</div>

<audio id="notifikasi_in">
    <source src="{{ asset('assets/sound/notifikasi_in.mp3') }}" type="audio/mpeg">
</audio>
<audio id="notifikasi_out">
    <source src="{{ asset('assets/sound/notifikasi_out.mp3') }}" type="audio/mpeg">
</audio>
<audio id="radius_sound">
    <source src="{{ asset('assets/sound/radius.mp3') }}" type="audio/mpeg">
</audio>
@endsection

@push('myscript')
<script type="text/javascript">
    window.onload = function() {
        jam();
    }

    function jam() {
        var e = document.getElementById('jam')
            , d = new Date()
            , h, m, s;
        h = d.getHours();
        m = set(d.getMinutes());
        s = set(d.getSeconds());

        e.innerHTML = h + ':' + m + ':' + s;

        setTimeout('jam()', 1000);
    }

    function set(e) {
        e = e < 10 ? '0' + e : e;
        return e;
    }

</script>
<script>
    var notifikasi_in = document.getElementById('notifikasi_in');
    var notifikasi_out = document.getElementById('notifikasi_out');
    var radius_sound = document.getElementById('radius_sound');
    // Webcam.set({
    //     height: 480
    //     , width: 640
    //     , image_format: 'jpeg'
    //     , jpeg_quality: 80
    // });

    // Webcam.attach('.webcam-capture');

    var lokasi = document.getElementById('lokasi');
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
    }

    function successCallback(position) {
        lokasi.value = position.coords.latitude + "," + position.coords.longitude;
        var map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 18);
        var lokasi_kantor = "{{ $lok_kantor->lokasi_cabang }}";
        var lok = lokasi_kantor.split(",");
        var lat_kantor = lok[0];
        var long_kantor = lok[1];
        var radius = "{{ $lok_kantor->radius_cabang }}";
        L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
            maxZoom: 20
            , subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
        }).addTo(map);
        var marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);
        var circle = L.circle([lat_kantor, long_kantor], {
            color: 'red'
            , fillColor: '#f03'
            , fillOpacity: 0.5
            , radius: radius
        }).addTo(map);
    }

    function errorCallback() {

    }

    $("#takeabsen").click(function(e) {
        // Webcam.snap(function(uri) {
        //     image = uri;
        // });
        var lokasi = $("#lokasi").val();
        $.ajax({
            type: 'POST'
            , url: '/presensi/store'
            , data: {
                _token: "{{ csrf_token() }}"
                , lokasi: lokasi
            }
            , cache: false
            , success: function(respond) {
                var status = respond.split("|");
                if (status[0] == "success") {
                    if (status[2] == "in") {
                        notifikasi_in.play();
                    } else {
                        notifikasi_out.play();
                    }
                    Swal.fire({
                        title: 'Berhasil !'
                        , text: status[1]
                        , icon: 'success'
                    })
                    setTimeout("location.href='/dashboard'", 3000);
                } else {
                    if (status[2] == "radius") {
                        radius_sound.play();
                    } else if (status[2] == "error") {

                    }
                    Swal.fire({
                        title: 'Error !'
                        , text: status[1]
                        , icon: 'error'
                    })
                }
            }
        });

    });

</script>
@endpush
