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
        .webcam-capture {
            display: inline-block;
            width: 100% !important;
            margin: auto;
            height: 350px !important;
            border-radius: 15px;
            overflow: hidden;
        }

        .webcam-capture video {
            display: inline-block;
            width: 100% !important;
            margin: auto;
            height: auto !important;
            border-radius: 15px;
            object-fit: fill;

        }

        #map {
            height: 130px;
        }
    </style>
    <style>
        .jam-digital-malasngoding {

            background-color: #27272783;
            position: absolute;
            top: 70px;
            right: 5px;
            z-index: 9999;
            width: 150px;
            border-radius: 10px;
            padding: 5px;
        }



        .jam-digital-malasngoding p {
            color: #fff;
            font-size: 14px;
            text-align: left;
            margin-top: 0;
            margin-bottom: 0;
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
@endsection
@section('content')
    <input type="hidden" id="lokasi">
    <div class="row" style="margin-top: 60px">
        <div class="col">
            <div class="webcam-capture"></div>
        </div>
    </div>
    <div class="jam-digital-malasngoding">
        <p>{{ DateToIndo2(date('Y-m-d')) }}</p>
        <p id="jam"></p>
        <p>{{ $jadwal->nama_jadwal }} - {{ $jadwal->kode_cabang }}</p>
        <p style="display: flex; justify-content:space-between">
            <span>Jam Masuk</span>
            <span>{{ date('H:i', strtotime($jam_kerja->jam_masuk)) }}</span>
        </p>
        <p style="display: flex; justify-content:space-between">
            <span>Jam Pulang</span>
            <span>{{ date('H:i', strtotime($jam_kerja->jam_pulang)) }}</span>
        </p>
    </div>
    <div class="row">
        <div class="col">
            <div id="map"></div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col d-flex justify-content-between">
            <button class="btn btn-success takeabsen" statuspresensi="masuk" style="height: 100px !important">
                <ion-icon name="finger-print-outline" style="font-size: 32px !important"></ion-icon>
                <span style="font-size:16px">Scan Masuk</span>
            </button>
            <button class="btn btn-danger takeabsen" statuspresensi="pulang" style="height: 100px !important">
                <ion-icon name="finger-print-outline" style="font-size: 32px !important"></ion-icon>
                <span style="font-size:16px">Scan Pulang</span>
            </button>
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
            var e = document.getElementById('jam'),
                d = new Date(),
                h, m, s;
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
        Webcam.set({
            height: 480,
            width: 640,
            image_format: 'jpeg',
            jpeg_quality: 80
        });

        Webcam.attach('.webcam-capture');

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
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);
            var marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);
            var circle = L.circle([lat_kantor, long_kantor], {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.5,
                radius: radius
            }).addTo(map);

            setInterval(function() {
                map.invalidateSize();
            }, 100);
        }

        function errorCallback() {

        }

        $(".takeabsen").click(function(e) {
            $(".takeabsen").prop('disabled', true);
            Webcam.snap(function(uri) {
                image = uri;
            });
            var lokasi = $("#lokasi").val();
            var statuspresensi = $(this).attr('statuspresensi');
            $.ajax({
                type: 'POST',
                url: '/presensi/store',
                data: {
                    _token: "{{ csrf_token() }}",
                    lokasi: lokasi,
                    statuspresensi: statuspresensi,
                    image: image
                },
                cache: false,
                success: function(respond) {
                    var status = respond.split("|");
                    if (status[0] == "success") {
                        if (status[2] == "in") {
                            notifikasi_in.play();
                        } else {
                            notifikasi_out.play();
                        }
                        Swal.fire({
                            title: 'Berhasil !',
                            text: status[1],
                            icon: 'success'
                        })
                        setTimeout("location.href='/dashboard'", 3000);
                    } else {
                        if (status[2] == "radius") {
                            radius_sound.play();
                        } else if (status[2] == "error") {

                        }
                        Swal.fire({
                            title: 'Error !',
                            text: status[1],
                            icon: 'error'
                        })
                        $(".takeabsen").prop('disabled', false);
                    }
                }
            });

        });
    </script>
@endpush
