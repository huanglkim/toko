<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Penjualan</title>
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
        }

        .detail {
            border: 1px solid #000;
            padding: 20px;
            margin-bottom: 30px;
        }

        .header {
            text-align: left;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #ffffff;
            color: rgb(0, 0, 0);
        }


        .total-footer {
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
            text-align: right;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>

<body>

    <div class="detail">

        @php
            $totalKeseluruhan = 0;
        @endphp
        @include('layout.heading')
        @foreach ($laporans as $pjhd)
            <p><strong>Pelanggan:</strong> {{ isset($pjhd) ? $pjhd->pelanggan->nama : 'Tidak ada data' }}
                <strong>No. Invoice:</strong> {{ isset($pjhd) ? $pjhd->invoice : 'Tidak ada data' }}
                <strong>Tanggal:</strong>
                {{ isset($pjhd) && $pjhd->tanggal ? TanggalIndo($pjhd->tanggal) : 'Tidak ada data' }}
            </p>
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Nama Item</th>
                        <th>Qty</th>
                        <th>Harga Bruto</th>
                        <th>Potongan</th>
                        <th>Harga Netto</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pjhd->pjdt as $item)
                        @php
                            $totalKeseluruhan += $item->total_harga_netto;
                        @endphp
                        <tr>
                            <td>{{ $item->barang->nama_barang }}</td>
                            <td>{{ $item->qty + 0 }}</td>
                            <td>{{ Rupiah($item->harga_bruto) }}</td>
                            <td>{{ Rupiah($item->total_pot) }}</td>
                            <td>{{ Rupiah($item->harga_netto) }}</td>
                            <td>{{ Rupiah($item->total_harga_netto) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="1">Total</th>
                        <th>{{ $pjhd->pjdt->sum('qty') }}</th>
                        <th>{{ Rupiah($pjhd->pjdt->sum('harga_bruto')) }}</th>
                        <th>{{ Rupiah($pjhd->pjdt->sum('total_pot')) }}</th>
                        <th>{{ Rupiah($pjhd->pjdt->sum('harga_netto')) }}</th>
                        <th>{{ Rupiah($pjhd->pjdt->sum('total_harga_netto')) }}</th>
                    </tr>
                </tfoot>
            </table>
            <hr style="width: 100%; margin: 20px auto; border: 1px solid #000;">
        @endforeach

        <div class="total-footer">
            <p><strong>Total Penjualan: {{ Rupiah($totalKeseluruhan) }}</strong></p>
        </div>
    </div>

    <script>
        window.print();
    </script>
</body>

</html>
