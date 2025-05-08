<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Besar</title>
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
                    <h2>Buku Besar</h2>
                    <p>Periode: {{ tanggalIndo($tanggal_awal) }} - {{ tanggalIndo($tanggal_akhir) }}</p>
                    <p>Kode Akun : {{ $kode_acc }} | Nama Akun : {{ $accperkiraan->nama_acc }} | Posisi : {{ $posisi }} | Saldo Awal : {{ Rupiah($saldo_awal['hasil']) }}</p>
                </div>
            </div>
            @if ($accjurnals->isEmpty())
            <p>Data tidak ditemukan untuk periode ini.</p>
            @else
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Invoice</th>
                        <th>Keterangan</th>
                        <th>Kode Lawan</th>
                        <th>Debet</th>
                        <th>Kredit</th>
                        <th>Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $totalDebet = 0;
                    $totalKredit = 0;
                    $totalSaldoAkhir = $saldo_awal['hasil']; // Inisialisasi dengan saldo awal
                    @endphp
                    @foreach ($accjurnals as $accjurnal)
                    @php
                    $totalDebet += $accjurnal->debet;
                    $totalKredit += $accjurnal->kredit;
                    $totalSaldoAkhir = $accjurnal->saldo; // Gunakan saldo yang sudah dihitung
                    @endphp
                    <tr>
                        <td>{{ datetotanggal($accjurnal->tanggal) }}</td>
                        <td>{{ $accjurnal->invoice }}</td>
                        <td>{{ $accjurnal->keterangan }}</td>
                        <td>{{ $accjurnal->kode_lawan }} / {{ $accjurnal->accperkiraanLawan->nama_acc ?? '-' }}</td>
                        <td>{{ Rupiah($accjurnal->debet) }}</td>
                        <td>{{ Rupiah($accjurnal->kredit) }}</td>
                        <td>{{ Rupiah($accjurnal->saldo) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" style="text-align: center; font-weight: bold;">Total</td>
                        <td>{{ Rupiah($totalDebet) }}</td>
                        <td>{{ Rupiah($totalKredit) }}</td>
                        <td>{{ Rupiah($totalSaldoAkhir) }}</td>
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