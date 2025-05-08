<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Hutang</title>
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
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
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

        .total-footer {
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
            text-align: right;
        }

        .total-footer p {
            margin: 5px 0;
        }

        .no-data {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            padding: 20px;
        }

        .status-belum-lunas {
            color: red;
            font-weight: bold;
        }

        .status-lunas {
            color: green;
            font-weight: bold;
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
                    <h2>Laporan Hutang {{ ucfirst($jenis) }}</h2>
                    <p>Periode: {{ TanggalIndo($tanggal_awal) }} - {{ TanggalIndo($tanggal_akhir) }}</p>
                </div>
            </div>

            @if ($laporans->count() > 0)
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>Invoice</th>
                        <th>Tanggal</th>
                        <th>Suplier</th>
                        <th>Total Hutang</th>
                        <th>Status Hutang</th> <!-- Tambahan Kolom -->
                    </tr>
                </thead>
                <tbody>
                    @php $totalKeseluruhan = 0; @endphp
                    @foreach ($laporans as $no => $laporan)
                        <tr>
                            <td>{{ $no + 1 }}</td>
                            <td>{{ $laporan->invoice }}</td>
                            <td>{{ TanggalIndo($laporan->tanggal) }}</td>
                            <td>{{ $laporan->suplier->nama }}</td>
                            <td>{{ Rupiah($laporan->hutang) }}</td>
                            <td>
                                @if ($laporan->status_hutang == 1)
                                    <span class="text-danger font-weight-bold">Belum Lunas</span>
                                @else
                                    <span class="text-success font-weight-bold">Lunas</span>
                                @endif
                            </td>
                        </tr>
                        @php $totalKeseluruhan += $laporan->hutang; @endphp
                    @endforeach
                </tbody>
            </table>
            

                <div class="total-footer">
                    <p><strong>Total Hutang Keseluruhan: {{ Rupiah($totalKeseluruhan) }}</strong></p>
                </div>
            @else
                <p class="no-data">Tidak ada data hutang untuk periode ini.</p>
            @endif
        </div>
    </div>

    <script>
        window.print();
    </script>

</body>

</html>
