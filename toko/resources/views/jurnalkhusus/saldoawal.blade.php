@extends('layout.main')

@section('title', 'Saldo Awal Perkiraan')

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link
        href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css"
        rel="stylesheet">
    <link
        href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.21/dist/sweetalert2.min.css"
        rel="stylesheet">
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

        .total-row td {
            font-weight: bold;
            background-color: #e9ecef; /* Memberikan sedikit pembeda pada baris total */
        }

        .footer-total {
            margin-top: 1rem;
            padding: 0.5rem;
            background-color: #f8f9fa;
            border-top: 1px solid #eaeaea;
            text-align: right;
        }

        .footer-total div {
            margin-bottom: 0.25rem;
        }
    </style>
@endsection

@section('content')
    <div class="container py-4">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mb-0">Laporan Saldo Awal Perkiraan</h3>
            </div>
            <div class="card-body">
                <form class="row g-3 mb-4" id="filter-form" method="GET" action="{{ route('saldoawal.index') }}">
                    <div class="col-md-9">
                        <select name="tahun" id="tahun" class="form-control form-control-sm">
                            @for ($i = date('Y'); $i >= 2000; $i--)
                                <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="button" id="proses-saldo-btn" class="btn btn-primary btn-sm ml-2">
                            <i class="fas fa-cog"></i> Proses Hitung Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped mb-0 table-sm">
                        <thead>
                            <tr>
                                <th colspan="6">Aktiva</th>
                            </tr>
                            <tr>
                                <th>Kode COA</th>
                                <th>Nama Akun</th>
                                <th>D/K</th>
                                <th>Debet</th>
                                <th>Kredit</th>
                                <th>Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalAktiva = 0; @endphp
                            @forelse ($aktiva as $data)
                                <tr>
                                    <td>{{ $data->kode_acc }}</td>
                                    <td>{{ $data->nama_acc }}</td>
                                    <td>{{ $data->posisi }}</td>
                                    <td>{{ Rupiah($data->accsa->first()->debet ?? 0) }}</td>
                                    <td>{{ Rupiah($data->accsa->first()->kredit ?? 0) }}</td>
                                    <td>{{ Rupiah($data->saldo ?? 0) }}</td>
                                </tr>
                                @php $totalAktiva += $data->saldo ?? 0; @endphp
                            @empty
                                <tr>
                                    <td colspan="6">Data tidak ditemukan</td>
                                </tr>
                            @endforelse
                            <tr class="total-row">
                                <td colspan="5">Total Aktiva</td>
                                <td>{{ Rupiah($totalAktiva) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive mt-3">
                    <table class="table table-striped mb-0 table-sm">
                        <thead>
                            <tr>
                                <th colspan="6">Kewajiban & Ekuitas</th>
                            </tr>
                            <tr>
                                <th>Kode COA</th>
                                <th>Nama Akun</th>
                                <th>D/K</th>
                                <th>Debet</th>
                                <th>Kredit</th>
                                <th>Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalKewajiban = 0; @endphp
                            @forelse ($kewajiban as $data)
                                <tr>
                                    <td>{{ $data->kode_acc }}</td>
                                    <td>{{ $data->nama_acc }}</td>
                                    <td>{{ $data->posisi }}</td>
                                    <td>{{ Rupiah($data->accsa->first()->debet ?? 0) }}</td>
                                    <td>{{ Rupiah($data->accsa->first()->kredit ?? 0) }}</td>
                                    <td>{{ Rupiah($data->saldo ?? 0) }}</td>
                                </tr>
                                @php $totalKewajiban += $data->saldo ?? 0; @endphp
                            @empty
                                <tr>
                                    <td colspan="6">Data tidak ditemukan</td>
                                </tr>
                            @endforelse
                            <tr class="total-row">
                                <td colspan="5">Laba Ditahan</td>
                                <td>{{ Rupiah($labaditahan) }}</td>
                            </tr>
                            <tr class="total-row">
                                <td colspan="5">Total Kewajiban & Ekuitas</td>
                                <td>{{ Rupiah($totalKewajiban + $labaditahan) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer text-right">
                <div>Total Aktiva: {{ Rupiah($totalAktiva) }}</div>
                <div>Total Kewajiban & Ekuitas: {{ Rupiah($totalKewajiban + $labaditahan) }}</div>
                @if (!$seimbang)
                    <div class="alert alert-danger mt-2 mb-0">
                        <strong>Saldo tidak seimbang!</strong> Selisih: {{ Rupiah($selisih) }}
                    </div>
                @else
                    <div class="alert alert-success mt-2 mb-0">
                        <strong>Saldo seimbang.</strong> Saldo sesuai. ✅
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.21/dist/sweetalert2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const prosesSaldoBtn = document.getElementById('proses-saldo-btn');
            const tahunSelect = document.getElementById('tahun');

            prosesSaldoBtn.addEventListener('click', function() {
                const tahun = tahunSelect.value;

                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah Anda yakin ingin memproses saldo awal untuk tahun ' + tahun + '?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, proses!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post('{{ route('saldoaccsa') }}', {
                            tahun: tahun,
                            _token: '{{ csrf_token() }}'
                        }, function(res) {
                            if (res.redirect_url) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: 'Saldo awal berhasil diproses.',
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.href = res.redirect_url;
                                });
                            } else {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: 'Gagal memproses saldo awal. Respon tidak sesuai.',
                                    icon: 'error',
                                });
                                console.log(res);
                            }
                        }).fail(function(err) {
                            Swal.fire({
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan saat memproses saldo awal.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                            console.error(err);
                        });
                    }
                });
            });
        });
    </script>
@endsection