<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Rincian Pembelian</title>
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
        @foreach ($laporans as $pbhd)
            <p><strong>Suplier:</strong> {{ isset($pbhd) ? $pbhd->suplier->nama : 'Tidak ada data' }}
                <strong>No. Invoice:</strong> {{ isset($pbhd) ? $pbhd->invoice : 'Tidak ada data' }}
                <strong>Tanggal:</strong>
                {{ isset($pbhd) && $pbhd->tanggal ? TanggalIndo($pbhd->tanggal) : 'Tidak ada data' }}
            </p>
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th style="text-align: center;">Nama Item</th>
                        <th style="text-align: center;">Qty</th>
                        <th style="text-align: center;">Harga Bruto</th>
                        <th style="text-align: center;">Potongan</th>
                        <th style="text-align: center;">Harga Netto</th>
                        <th style="text-align: center;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pbhd->pbdt as $item)
                        @php
                            $totalKeseluruhan += $item->total_harga_netto;
                        @endphp
                        <tr>
                            <td style="text-align: center;">{{ $item->barang->nama_barang }}</td>
                            <td style="text-align: center;">{{ $item->qty + 0 }}</td>
                            <td style="text-align: center;">{{ Rupiah($item->harga_bruto) }}</td>
                            <td style="text-align: center;">{{ Rupiah($item->total_pot) }}</td>
                            <td style="text-align: center;">{{ Rupiah($item->harga_netto) }}</td>
                            <td style="text-align: center;">{{ Rupiah($item->total_harga_netto) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th style="text-align: center;" colspan="1">Total</th>
                        <th style="text-align: center;">{{ $pbhd->pbdt->sum('qty') }}</th>
                        <th style="text-align: center;">{{ Rupiah($pbhd->pbdt->sum('harga_bruto')) }}</th>
                        <th style="text-align: center;">{{ Rupiah($pbhd->pbdt->sum('total_pot')) }}</th>
                        <th style="text-align: center;">{{ Rupiah($pbhd->pbdt->sum('harga_netto')) }}</th>
                        <th style="text-align: center;">{{ Rupiah($pbhd->pbdt->sum('total_harga_netto')) }}</th>

                    </tr>
                </tfoot>
            </table>
            <hr style="width: 100%; margin: 20px auto; border: 1px solid #000;">
        @endforeach
        <div class="total-footer">
            <p><strong>Total Pembelian: {{ Rupiah($totalKeseluruhan) }}</strong></p>
        </div>
    </div>

    <script>
        window.print();
    </script>
</body>

</html>
