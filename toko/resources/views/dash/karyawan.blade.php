@extends('layout.main')
@section('title', 'Dashboard')
@section('css')
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="{{ url('/') }}/lte/plugins/toastr/toastr.min.css">
    <style>
        .custom-button {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            background-color: #ffffff;
            border-radius: 6px;
            border: 2px solid #96afff;
            padding: 10px;
            transition: all 0.3s ease-in-out;
            width: 80px;
            height: 80px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .custom-button:hover {
            background-color: #e2e6ea;
            transform: scale(1.05);
        }

        .icon-position {
            margin-bottom: 4px;
            width: 25px;
            height: 25px;
        }

        .custom-label {
            font-size: 10px;
            text-align: center;
            word-wrap: break-word;
            white-space: normal;
            line-height: 1.2;
        }

        .col-lg-1.col-md-2.col-3 {
            margin-bottom: 15px;
            /* Tambahkan jarak antar tombol */
        }
    </style>
@stop
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <input type="text" class="form-control form-control-sm" name="cari" id="cari"
                                placeholder="Cari Menu . . . . . ." onkeyup="detailmenu()">
                            <input type="hidden" name="linkcari" id="linkcari" value="{{ url('/detailmenudashboard') }}">
                        </div>
                        <div class="col-sm-8">
                            {{-- Area untuk notifikasi atau elemen lain di kanan atas --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" id="menudetail">
                {{-- Konten menu akan dimuat di sini melalui AJAX --}}
            </div>
        </div>
    </section>
@stop

@section('script')
    <script src="{{ url('/') }}/lte/plugins/toastr/toastr.min.js"></script>
    {{-- @include('jsblade.toasttrjs') --}}
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            localStorage.clear();
            detailmenu(); // Panggil detailmenu saat halaman pertama kali dimuat
        }, false);

        function detailmenu() {
            var cari = document.getElementById('cari').value;
            var linkcari = document.getElementById('linkcari').value;
            var token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: linkcari,
                method: "POST",
                data: {
                    '_token': token,
                    cari: cari,
                },
                success: function(data) {
                    $('#menudetail').html(data);
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }
    </script>
@stop