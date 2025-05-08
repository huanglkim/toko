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
            vertical-align: middle;
            /* Jika kamu juga ingin teks berada di tengah secara vertikal */
        }
    </style>
@endsection

@section('content')
    <div class="container py-4">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mb-0">Laporan Neraca Saldo</h3>
            </div>
            <div class="card-body">
                <form id="filter-form-bulanan" action="{{ url('neraca/print') }}" method="POST">
                    @csrf
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label for="bulan">Bulan</label>
                            <select id="bulan" name="bulan" class="form-control" required>
                                <option value="01" {{ now()->month == 1 ? 'selected' : '' }}>Januari</option>
                                <option value="02" {{ now()->month == 2 ? 'selected' : '' }}>Februari</option>
                                <option value="03" {{ now()->month == 3 ? 'selected' : '' }}>Maret</option>
                                <option value="04" {{ now()->month == 4 ? 'selected' : '' }}>April</option>
                                <option value="05" {{ now()->month == 5 ? 'selected' : '' }}>Mei</option>
                                <option value="06" {{ now()->month == 6 ? 'selected' : '' }}>Juni</option>
                                <option value="07" {{ now()->month == 7 ? 'selected' : '' }}>Juli</option>
                                <option value="08" {{ now()->month == 8 ? 'selected' : '' }}>Agustus</option>
                                <option value="09" {{ now()->month == 9 ? 'selected' : '' }}>September</option>
                                <option value="10" {{ now()->month == 10 ? 'selected' : '' }}>Oktober</option>
                                <option value="11" {{ now()->month == 11 ? 'selected' : '' }}>November</option>
                                <option value="12" {{ now()->month == 12 ? 'selected' : '' }}>Desember</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="tahun-bulanan">Tahun</label>
                            <select id="tahun-bulanan" name="tahun" class="form-control" required>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="tipe-bulanan">Tipe:</label>
                            <select name="tipe" id="tipe-bulanan" class="form-control">
                                <option value="cetak">Cetak</option>
                                <option value="excel">Download Excel</option>
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('tipe-bulanan').addEventListener('change', function() {
                var form = document.getElementById('filter-form-bulanan');
                var tipe = this.value;

                if (tipe === 'cetak') {
                    form.action = '{{ url('neraca/print') }}';
                } else if (tipe === 'excel') {
                    form.action = '{{ url('neraca/exportNeraca') }}';
                }
            });

            var currentYear = new Date().getFullYear();
            var selectTahun = document.getElementById('tahun-bulanan');
            for (var i = currentYear; i >= currentYear - 20; i--) {
                var option = document.createElement('option');
                option.value = i;
                option.text = i;
                option.selected = (i === currentYear); // Set tahun saat ini sebagai default
                selectTahun.appendChild(option);
            }
        });
    </script>
@endsection
