@extends('layout.main')
@section('title', 'Dashboard')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link
        href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.21/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f7fa;
        }

        .card {
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid #eaeaea;
        }

        .card-body {
            background-color: #ffffff;
        }

        .table thead {
            background-color: #fcd5ab;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .btn {
            border-radius: 8px;
        }

        .btn i {
            margin-right: 4px;
        }

        /* Tambahkan CSS berikut untuk membuat teks di tengah */
        .table th,
        .table td {
            text-align: center;
            vertical-align: middle; /* Jika kamu juga ingin teks berada di tengah secara vertikal */
        }
    </style>
@endsection

@section('content')
        <div class="container py-4">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Laporan Buku Besar</h3>
                </div>
                <div class="card-body">
                    <form id="filter-form" action="{{ url('') }}/bukubesar/print" method="POST">
                        @csrf
                        <div class="row g-3 align-items-end">
                            <div class="col-lg-2">
                                <label for="tanggal_awal">Tanggal Awal:</label>
                                <input type="date" value="{{ date('Y-m-01') }}" id="tanggal_awal" name="tanggal_awal"
                                    class="form-control" required>
                            </div>
                            <div class="col-lg-2">
                                <label for="tanggal_akhir">Tanggal Akhir:</label>
                                <input type="date" value="{{ date('Y-m-d') }}" id="tanggal_akhir" name="tanggal_akhir"
                                    class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label for="kode_acc" class="form-label">Nama Akun:</label>
                                <select class="form-control selectpicker w-100" name="kode_acc" id="kode_acc"
                                    data-live-search="true">
                                    <option value="" disabled selected>Pilih Akun</option>
                                    @foreach ($accperkiraans as $accperkiraan)
                                        <option value="{{ $accperkiraan->kode_acc }}">{{ $accperkiraan->kode_acc }} || {{ $accperkiraan->nama_acc }}</option> @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <label for="tipe" class="form-label">Tipe:</label>
                                <select class="form-control selectpicker w-100" name="tipe" id="tipe">
                                    <option value="cetak">
                                        cetak</option>
                                    <option value="excel">
                                        download excel</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button id="print-btn" class="btn btn-success w-100"> <i class="fas fa-print"></i> Cetak</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
@endsection
@section('script')
    <script src="{{ url('/') }}/lte/js/bootstrap-select.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.selectpicker').selectpicker({
                container: 'body' // Memastikan dropdown tetap di dalam card
            });
        });
    </script>
@endsection
