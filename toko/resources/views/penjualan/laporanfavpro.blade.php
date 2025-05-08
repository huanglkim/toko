@extends('layout.main')
@section('title', 'Dashboard')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
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
            <h3 class="mb-0">Laporan Produk Paling Diminati</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ url('/') }}/laporanfavpro" class="mt-3" id="filterForm">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="tanggal_awal" class="form-label">Tanggal Awal:</label>
                        <input type="date" id="tanggal_awal" name="tanggal_awal"
                            value="{{ $tanggal_awal->format('Y-m-d') }}" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label for="tanggal_akhir" class="form-label">Tanggal Akhir:</label>
                        <input type="date" id="tanggal_akhir" name="tanggal_akhir"
                            value="{{ $tanggal_akhir->format('Y-m-d') }}" class="form-control" required>
                    </div>
                    <div class="col-md-4 d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            @if ($topProducts->isEmpty())
                <div class="alert alert-info text-center">Tidak ada data untuk tanggal yang dipilih.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-bordered mb-0 table-sm">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topProducts as $index => $product)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $product->barang->nama_barang }}</td>
                                    <td>{{ $product->total_qty + 0 }}</td>
                                </tr> 
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
