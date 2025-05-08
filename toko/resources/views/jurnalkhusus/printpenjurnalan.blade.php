<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjurnalan</title>
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
                    <h2>Laporan Penjurnalan</h2>
                    <p>Periode: {{ tanggalIndo($tanggal_awal) }} - {{ tanggalIndo($tanggal_akhir) }}</p>
                </div>
            </div>

            @if ($accjurnals->isEmpty())
                <p>Data tidak ditemukan untuk periode ini.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Invoice</th>
                            <th>Tanggal</th>
                            <th>Kode Akun</th>
                            <th>Nama Akun</th>
                            <th>Debet</th>
                            <th>Kredit</th>
                            <th>Lawan</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalDebet = 0;
                            $totalKredit = 0;
                        @endphp
                        @foreach ($accjurnals as $no => $accjurnal)
                            <tr>
                                <td>{{ $no + 1 }}</td>
                                <td>{{ $accjurnal->invoice }}</td>
                                <td>{{ $accjurnal->tanggal }}</td>
                                <td>{{ $accjurnal->kode_acc }}</td>
                                <td>{{ $accjurnal->accperkiraan->nama_acc }}</td>
                                <td>{{ Rupiah($accjurnal->debet) }}</td>
                                <td>{{ Rupiah($accjurnal->kredit) }}</td>
                                <td>{{ $accjurnal->kode_lawan }} / {{ $accjurnal->accperkiraanLawan->nama_acc }}</td>
                                <td>{{ $accjurnal->keterangan }}</td>
                            </tr>
                            @php
                                $totalDebet += $accjurnal->debet;
                                $totalKredit += $accjurnal->kredit;
                            @endphp
                        @endforeach
                    </tbody>
                </table>

                <!-- Total Debit dan Kredit -->
                <table>
                    <tfoot>
                        <tr>
                            <td colspan="5" style="text-align: right; font-weight: bold;">Total</td>
                            <td>{{ Rupiah($totalDebet) }}</td>
                            <td>{{ Rupiah($totalKredit) }}</td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            @endif
        </div>
    </div>

    <script>
        window.print();
    </script>

</body>

</html>
