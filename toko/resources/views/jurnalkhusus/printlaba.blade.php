<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laba Rugi - {{ Toko(1)->nama_toko }} - {{ date('F', mktime(0, 0, 0, $bulan, 10)) }} {{ $tahun }}</title>
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            font-size: 12pt;
        }

        .header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .logo {
            width: 100px;
            margin-right: 20px;
        }

        .logo img {
            max-width: 100%;
            height: auto;
        }

        .company-info {
            flex-grow: 1;
            text-align: center;
        }

        .report-title {
            text-align: center;
            margin-bottom: 10px;
            font-size: 1.5em;
            font-weight: bold;
        }

        .period {
            text-align: center;
            margin-bottom: 20px;
            font-style: italic;
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
            padding: 8px;
            text-align: right;
        }

        th {
            background-color: #f8f9fa;
            text-align: center;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .text-left {
            text-align: left;
        }

        .font-weight-bold {
            font-weight: bold;
        }

        .total-row td {
            background-color: #e9ecef;
            font-weight: bold;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
                font-size: 12pt;
            }

            .header {
                display: flex;
                align-items: center;
            }

            .logo {
                width: 80px;
                margin-right: 15px;
            }

            .report-title {
                font-size: 1.2em;
            }

            .period {
                font-size: 0.9em;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 10px;
            }

            th,
            td {
                border: 1px solid #ddd;
                padding: 6px;
                text-align: right;
            }

            th {
                background-color: #f8f9fa;
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        @include('layout.heading')
    </div>
    <hr>

    <div class="report-title">LAPORAN LABA RUGI</div>
    <div class="period">Periode: {{ date('F', mktime(0, 0, 0, $bulan, 10)) }} - {{ $tahun }}</div>

    <table>
        <thead>
            <tr>
                <th colspan="3">Pendapatan</th>
            </tr>
            <tr>
                <th>Kode Akun</th>
                <th class="text-center">Nama Akun</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            @if ($pendapatan->isEmpty())
                <tr>
                    <td colspan="3" class="text-center">Tidak ada data pendapatan</td>
                </tr>
            @else
                @foreach ($pendapatan as $data)
                    <tr>
                        <td>{{ $data->kode_acc }}</td>
                        <td class="text-center">{{ $data->nama_acc }}</td>
                        <td>{{ Rupiah($data->saldo) }}</td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="2" class="text-center font-weight-bold">Total Pendapatan</td>
                    <td>{{ Rupiah($totalPendapatan) }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <table>
        <thead>
            <tr>
                <th colspan="3">Harga Pokok Penjualan (HPP)</th>
            </tr>
            <tr>
                <th>Kode Akun</th>
                <th class="text-center">Nama Akun</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            @if ($hpp->isEmpty())
                <tr>
                    <td colspan="3" class="text-center">Tidak ada data HPP</td>
                </tr>
            @else
                @foreach ($hpp as $data)
                    <tr>
                        <td>{{ $data->kode_acc }}</td>
                        <td class="text-center">{{ $data->nama_acc }}</td>
                        <td>{{ Rupiah($data->saldo) }}</td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="2" class="text-center font-weight-bold">Total HPP</td>
                    <td>{{ Rupiah($totalHpp) }}</td>
                </tr>
            @endif
            <tr class="total-row">
                <td colspan="2" class="text-center font-weight-bold">Laba Kotor</td>
                <td>{{ Rupiah($totalPendapatan - $totalHpp) }}</td>
            </tr>
        </tbody>
    </table>

    <table>
        <thead>
            <tr>
                <th colspan="3">Biaya-biaya</th>
            </tr>
            <tr>
                <th>Kode Akun</th>
                <th class="text-center">Nama Akun</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            @if ($biaya->isEmpty())
                <tr>
                    <td colspan="3" class="text-center">Tidak ada data biaya</td>
                </tr>
            @else
                @foreach ($biaya as $data)
                    <tr>
                        <td>{{ $data->kode_acc }}</td>
                        <td class="text-center">{{ $data->nama_acc }}</td>
                        <td>{{ Rupiah($data->saldo) }}</td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="2" class="text-center font-weight-bold">Total Biaya</td>
                    <td>{{ Rupiah($totalBiaya) }}</td>
                </tr>
            @endif
            <tr class="total-row">
                <td colspan="2" class="text-center font-weight-bold">Laba Rugi Bersih</td>
                <td>{{ Rupiah($labaataurugi) }}</td>
            </tr>
        </tbody>
    </table>

    <script>
        window.print();
    </script>

</body>

</html>