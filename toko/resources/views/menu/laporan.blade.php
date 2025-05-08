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
            background-color: #01409f;
            border-radius: 6px;
            border: 2px solid #000000;
            padding: 10px;
            transition: all 0.3s ease-in-out;
            width: 80px;
            height: 80px;
            box-shadow: 0 4px 6px rgba(255, 0, 0, 0.357);
        }

        .custom-button:hover {
            background-color: #1400a67c;
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
            color: #fbfdff; /* Perubahan di sini */
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
        <div class="card">
            <div class="card-header">
                Laporan Analisa
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-1 col-md-2 col-3">
                        <a href="{{ url('/') }}/laporanchart" class="custom-button">
                            <img src="{{ asset('icon/graph.png') }}" alt="Icon" class="icon-position">
                            <span class="custom-label">Chart Laporan Tahunan</span>
                        </a>
                    </div>
                    <div class="col-lg-1 col-md-2 col-3">
                        <a href="{{ url('/') }}/laporanchartbulan" class="custom-button">
                            <img src="{{ asset('icon/graph.png') }}" alt="Icon" class="icon-position">
                            <span class="custom-label">Chart Laporan Bulanan</span>
                        </a>
                    </div>
                    <div class="col-lg-1 col-md-2 col-3">
                        <a href="{{ url('/') }}/laporanfavpro" class="custom-button">
                            <img src="{{ asset('icon/product.png') }}" alt="Icon" class="icon-position">
                            <span class="custom-label">Top 10 Product Terlaris</span>
                        </a>
                    </div>
                    <div class="col-lg-1 col-md-2 col-3">
                        <a href="{{ url('/') }}/laporanpembelian" class="custom-button">
                            <img src="{{ asset('icon/keranjang.png') }}" alt="Icon" class="icon-position">
                            <span class="custom-label">Laporan Pembelian</span>
                        </a>
                    </div>
                    <div class="col-lg-1 col-md-2 col-3">
                        <a href="{{ url('/') }}/laporanpenjualan" class="custom-button">
                            <img src="{{ asset('icon/keranjang_penuh.png') }}" alt="Icon" class="icon-position">
                            <span class="custom-label">Laporan Penjualan</span>
                        </a>
                    </div>
                    <div class="col-lg-1 col-md-2 col-3">
                        <a href="{{ url('/') }}/laporanhutang" class="custom-button">
                            <img src="{{ asset('icon/kalkulator.png') }}" alt="Icon" class="icon-position">
                            <span class="custom-label">Laporan Hutang</span>
                        </a>
                    </div>
                    <div class="col-lg-1 col-md-2 col-3">
                        <a href="{{ url('/') }}/laporanpiutang" class="custom-button">
                            <img src="{{ asset('icon/rp.png') }}" alt="Icon" class="icon-position">
                            <span class="custom-label">Laporan Piutang</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                Laporan Penjurnalan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-1 col-md-2 col-3">
                        <a href="{{ url('/') }}/laporanpenjurnalan" class="custom-button">
                            <img src="{{ asset('icon/kalkulator.png') }}" alt="Icon" class="icon-position">
                            <span class="custom-label">Laporan Jurnal</span>
                        </a>
                    </div>
                    <div class="col-lg-1 col-md-2 col-3">
                        <a href="{{ url('/') }}/buku-besar" class="custom-button">
                            <img src="{{ asset('icon/laporan.png') }}" alt="Icon" class="icon-position">
                            <span class="custom-label">Buku Besar</span>
                        </a>
                    </div>
                    <div class="col-lg-1 col-md-2 col-3">
                        <a href="{{ url('/') }}/neracasaldo" class="custom-button">
                            <img src="{{ asset('icon/dollar.png') }}" alt="Icon" class="icon-position">
                            <span class="custom-label">Neraca Saldo</span>
                        </a>
                    </div>
                    <div class="col-lg-1 col-md-2 col-3">
                        <a href="{{ url('/') }}/neraca" class="custom-button">
                            <img src="{{ asset('icon/kalkulator.png') }}" alt="Icon" class="icon-position">
                            <span class="custom-label">Neraca</span>
                        </a>
                    </div>
                    <div class="col-lg-1 col-md-2 col-3">
                        <a href="{{ url('/') }}/laba" class="custom-button">
                            <img src="{{ asset('icon/kalkulator.png') }}" alt="Icon" class="icon-position">
                            <span class="custom-label">Laporan Laba Rugi</span>
                        </a>
                    </div>
                    <div class="col-lg-1 col-md-2 col-3">
                        <a href="{{ url('/') }}/saldoawal" class="custom-button">
                            <img src="{{ asset('icon/kalkulator.png') }}" alt="Icon" class="icon-position">
                            <span class="custom-label">Saldo Awal Perkiraan</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

@section('script')
    <script src="{{ url('/') }}/lte/plugins/toastr/toastr.min.js"></script>
    {{-- @include('jsblade.toasttrjs') --}}
@stop