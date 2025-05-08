@extends('layout.main')

@section('title', 'Pendapatan per Hari')

@section('css')
    <style>
        #filterForm .form-group input {
            width: 100%;
            /* Membuat input mengisi lebar container */
            max-width: 450px;
            /* Memberikan batas lebar maksimum */
        }

        /* Menata form agar elemen-elemennya lebih terstruktur */
        #filterForm .row.g-3 {
            margin-bottom: 1rem;
            /* Memberikan sedikit ruang di bawah baris input */
        }

        /* Membuat tombol filter mengisi lebar kolomnya */
        #filterForm .col-md-4.d-grid button {
            width: 100%;
        }

        #filterForm .form-control {
            max-width: 100%;
            /* Pastikan input responsif penuh */
        }

        @media (min-width: 768px) {
            #filterForm .form-control {
                max-width: 100%;
                /* Biarkan Bootstrap atur dengan grid */
            }
        }
    </style>
@endsection

@section('content')
    <section class="container">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Pendapatan Harian</h5>
            </div>
            <div class="card-body">
                <form id="filterForm" method="GET" action="{{ url('/laporanchartbulan') }}">
                    @csrf
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label for="tanggal_awal" class="form-label">Tanggal Awal:</label>
                            <input type="date" value="{{ $tanggal_awal->format('Y-m-01') }}" id="tanggal_awal"
                                   name="tanggal_awal" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label for="tanggal_akhir" class="form-label">Tanggal Akhir:</label>
                            <input type="date" value="{{ $tanggal_akhir->format('Y-m-d') }}" id="tanggal_akhir"
                                   name="tanggal_akhir" class="form-control" required>
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
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Grafik Pendapatan per Hari</h5>
            </div>
            <div class="card-body">
                <canvas id="pendapatan" width="400" height="200"></canvas>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    <script>
        $(document).ready(function() {
            const salesData = @json($sales); // Data dari controller
            const labels = Object.keys(salesData); // Mendapatkan tanggal sebagai label
            const data = labels.map(tanggal => salesData[tanggal] ?? 0); // Mendapatkan data pendapatan

            const ctx = document.getElementById('pendapatan').getContext('2d');

            // Membuat grafik
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Pendapatan',
                        data: data,
                        backgroundColor: 'rgba(255, 0, 0, 0.7)',
                        borderColor: 'rgba(255, 0, 0, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        datalabels: {
                            anchor: 'end',
                            align: 'top',
                            formatter: function(value, context) {
                                return value.toLocaleString('id-ID');
                            },
                            font: {
                                weight: 'bold',
                                size: 12
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });
        });
    </script>
@endsection