<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pembelian</title>
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            background-color: #fdfdfd;

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
        }

        th,
        td {
            border: 1px solid #fcfcfc;
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #ffffff;
            color: rgb(0, 0, 0);
        }

        tr:hover {
            background-color: #81c6ff;
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

        .total-row {
            font-weight: bold;
            background-color: #55d7ff;
        }
    </style>
</head>

<body>

    <div class="rekap">

        @include('layout.heading')
        <div class="table-container">
            <div class="row">
                <div class="col-12 text-center">
                    <h2>Laporan Pembelian {{ ucfirst($jenis) }}</h2>
                    <p>Periode: {{ TanggalIndo($tanggal_awal) }} - {{ TanggalIndo($tanggal_akhir) }}</p>
                </div>
            </div>

            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>Invoice</th>
                        <th>Tanggal</th>
                        <th>Suplier</th>
                        <th>DPP</th>
                        <th>PPN</th>
                        <th>Total Pembelian</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $totalKeseluruhan = 0;
                    @endphp
                    @foreach ($laporans as $no => $laporan)
                    <tr>
                        <td>{{ $no + 1 }}</td>
                        <td>{{ $laporan->invoice }}</td>
                        <td>{{ TanggalIndo($laporan->tanggal) }}</td>
                        <td>{{ $laporan->suplier->nama }}</td>
                        <td>{{ Rupiah($laporan->dpp) }}</td>
                        <td>{{ Rupiah($laporan->ppn) }}</td>
                        <td>{{ Rupiah($laporan->total) }}</td>
                    </tr>
                    @php
                    $totalKeseluruhan += $laporan->total;
                    @endphp
                    @endforeach
                    <tr class="total-row">
                        <td colspan="6">Total Pembelian Keseluruhan</td>
                        <td>{{ Rupiah($totalKeseluruhan) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        window.print();
    </script>

</body>

</html>
