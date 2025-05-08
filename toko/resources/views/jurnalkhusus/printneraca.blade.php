<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neraca</title>
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

        .table-container {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .table-container .table {
            width: 48%;
        }

        .total-row {
            font-weight: bold;
            background-color: #f2f2f2;
        }

        .header-info {
            text-align: center;
            margin-bottom: 20px;
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

        .footer-total {
            width: 100%;
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            background-color: #f2f2f2;
            padding: 10px;
            border-top: 2px solid #000;
        }
    </style>
</head>

<body>
    <div class="rekap">
        @include('layout.heading')

        <div class="header-info">
            <h2>Neraca</h2>
            <p>Periode: {{ date('F', mktime(0, 0, 0, $bulan, 10)) }} - {{ $tahun }}</p>
        </div>

        <div class="table-container">
            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th colspan="3">Aktiva</th>
                        </tr>
                        <tr>
                            <th>Kode COA</th>
                            <th>Nama Akun</th>
                            <th>Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($aktiva as $data)
                            <tr>
                                <td>{{ $data->kode_acc }}</td>
                                <td>{{ $data->nama_acc }}</td>
                                <td>{{ Rupiah($data->saldo) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th colspan="3">Kewajiban</th>
                        </tr>
                        <tr>
                            <th>Kode COA</th>
                            <th>Nama Akun</th>
                            <th>Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kewajiban as $data)
                            <tr>
                                <td>{{ $data->kode_acc }}</td>
                                <td>{{ $data->nama_acc }}</td>
                                <td>{{ Rupiah($data->saldo) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <th colspan="3">Ekuitas</th>
                        </tr>
                        <tr>
                            <th>Kode COA</th>
                            <th>Nama Akun</th>
                            <th>Saldo</th>
                        </tr>
                        <tr>
                            <td>3-3000</td>
                            <td>Laba Tahun Berjalan</td>
                            <td>{{ Rupiah($labatahunberjalan) }}</td>
                        </tr>
                        <tr>
                            <td>3-2000</td>
                            <td>Laba Ditahan</td>
                            <td>{{ Rupiah($labaditahan) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="footer-total">
            <table>
                <tr class="total-row">
                    <td><strong>Total Aktiva:</strong></td>
                    <td><strong>{{ Rupiah($totalAktiva) }}</strong></td>
                    <td>||</td>
                    <td><strong>Total Kewajiban & Ekuitas:</strong></td>
                    <td><strong>{{ Rupiah($totalKewajiban + $labatahunberjalan + $labaditahan) }}</strong></td>
                </tr>
            </table>
        </div>
    </div>

    <script>
        window.print();
    </script>

</body>

</html>