<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neraca Saldo</title>
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
        }

        .header-logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .header-logo img {
            max-width: 120px;
        }

        .rekap {
            border: 1px solid #000;
            padding: 20px;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            page-break-inside: auto;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #ffffff;
            color: rgb(0, 0, 0);
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        .no-data {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            padding: 20px;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            table {
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>

    <div class="rekap">
        @include('layout.heading')

        <div class="table-container">
            <div class="row">
                <div class="col-12 text-center">
                    <h2>Neraca Saldo</h2>
                    <p>Periode: {{ date('F', mktime(0, 0, 0, $bulan, 10)) }} - {{ $tahun }}</p>
                </div>
            </div>

            @if ($accmutasis->isEmpty())
                <p>Data tidak ditemukan untuk periode ini.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th rowspan="2">Kode COA</th>
                            <th rowspan="2">Saldo Normal</th>
                            <th rowspan="2">Nama Akun</th>
                            <th colspan="2">Saldo Awal</th>
                            <th colspan="2">Mutasi</th>
                            <th colspan="2">Saldo Akhir</th>
                        </tr>
                        <tr>
                            <th>Debet</th>
                            <th>Kredit</th>
                            <th>Debet</th>
                            <th>Kredit</th>
                            <th>Debet</th>
                            <th>Kredit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalSaldoAwalDebet = 0;
                            $totalSaldoAwalKredit = 0;
                            $totalDebet = 0;
                            $totalKredit = 0;
                            $totalSaldoAkhirDebet = 0;
                            $totalSaldoAkhirKredit = 0;
                        @endphp

                        @foreach ($accmutasis as $no => $accmutasi)
                            @php
                                $debetAwal = $accmutasi['debetAwal'];
                                $kreditAwal = $accmutasi['kreditAwal'];
                                $debetMutasi = $accmutasi['debetMutasi'];
                                $kreditMutasi = $accmutasi['kreditMutasi'];
                                $debetAkhir = $accmutasi['debetAkhir']; // Ini sudah ada dari controller
                                $kreditAkhir = $accmutasi['kreditAkhir']; // Ini juga sudah ada dari controller

                                $totalSaldoAwalDebet += $debetAwal;
                                $totalSaldoAwalKredit += $kreditAwal;
                                $totalDebet += $debetMutasi;
                                $totalKredit += $kreditMutasi;
                                $totalSaldoAkhirDebet += $debetAkhir;
                                $totalSaldoAkhirKredit += $kreditAkhir;
                            @endphp
                            <tr>
                                <td>{{ $accmutasi['kode_acc'] }}</td>
                                <td>{{ $accmutasi['posisi'] }}</td>
                                <td>{{ $accmutasi['nama_acc'] }}</td>
                                <td>{{ Rupiah($debetAwal) }}</td>
                                <td>{{ Rupiah($kreditAwal) }}</td>
                                <td>{{ Rupiah($debetMutasi) }}</td>
                                <td>{{ Rupiah($kreditMutasi) }}</td>
                                <td>{{ Rupiah($debetAkhir) }}</td> <!-- Saldo akhir debet -->
                                <td>{{ Rupiah($kreditAkhir) }}</td> <!-- Saldo akhir kredit -->
                            </tr>
                        @endforeach

                        <!-- Total row -->
                        <tr>
                            <td colspan="3" style="text-align: center; font-weight: bold;">Total</td>
                            <td>{{ Rupiah($totalSaldoAwalDebet) }}</td>
                            <td>{{ Rupiah($totalSaldoAwalKredit) }}</td>
                            <td>{{ Rupiah($totalDebet) }}</td>
                            <td>{{ Rupiah($totalKredit) }}</td>
                            <td>{{ Rupiah($totalSaldoAkhirDebet) }}</td>
                            <td>{{ Rupiah($totalSaldoAkhirKredit) }}</td>
                        </tr>
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <script>
        window.print();
    </script>

</body>

</html>
