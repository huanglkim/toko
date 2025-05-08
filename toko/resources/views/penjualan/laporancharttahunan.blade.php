@extends('layout.main')

@section('title', 'Pendapatan per Bulan')

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
                <h5 class="mb-0">Pendapatan Bulanan</h5>
            </div>
            <div class="card-body">
                <form id="filterForm" method="GET" action="{{ url('/') }}/laporanchart">
                    @csrf
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label for="bulan_awal" class="form-label">Bulan Awal:</label>
                            <input type="month" id="bulan_awal" name="bulan_awal" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label for="bulan_akhir" class="form-label">Bulan Akhir:</label>
                            <input type="month" id="bulan_akhir" name="bulan_akhir" class="form-control" required>
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
                <h5 class="mb-0">Grafik Pendapatan per Bulan</h5>
            </div>
            <div class="card-body">
                <canvas id="pendapatan" width="400" height="100"></canvas>
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
            // Kirim permintaan default saat halaman pertama kali dimuat untuk tahun ini
            function loadDefaultData() {
                $.ajax({
                    url: '{{ url('/') }}/laporanchart',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        bulan_awal: '{{ \Carbon\Carbon::now()->startOfYear()->format('Y-m') }}', // Januari tahun ini
                        bulan_akhir: '{{ \Carbon\Carbon::now()->format('Y-m') }}', // Bulan sekarang
                    },
                    dataType: 'json',
                    success: function(response) {
                        const ctx = document.getElementById('pendapatan').getContext('2d');
                        const labels = Object.keys(response.data);
                        const data = labels.map(bulan => response.data[bulan] ?? 0);

                        if (window.myChart) {
                            window.myChart.destroy();
                        }

                        window.myChart = new Chart(ctx, {
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
                    },
                    error: function(response) {
                        console.error("Error:", response);
                        alert("Terjadi kesalahan saat memproses data. Silakan coba lagi.");
                    }
                });
            }

            // Memuat data default pertama kali
            loadDefaultData();

            // Memproses saat filter dikirim
            $('#filterForm').on('submit', function(event) {
                event.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        const ctx = document.getElementById('pendapatan').getContext('2d');
                        const labels = Object.keys(response.data);
                        const data = labels.map(bulan => response.data[bulan] ?? 0);

                        if (window.myChart) {
                            window.myChart.destroy();
                        }

                        window.myChart = new Chart(ctx, {
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
                    },
                    error: function(response) {
                        console.error("Error:", response);
                        alert("Terjadi kesalahan saat memproses data. Silakan coba lagi.");
                    }
                });
            });
        });
    </script>
@endsection
