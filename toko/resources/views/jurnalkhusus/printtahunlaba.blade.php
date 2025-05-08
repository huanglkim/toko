<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laba Rugi Tahunan</title>
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            background-color: #ffffff;
        }

        .header-logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .header-logo img {
            max-width: 120px;
        }

        .rekap {
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 30px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            page-break-inside: auto;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
            color: black;
        }

        th {
            background-color: #ffffff;
            color: black;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #e0f7fa;
        }

        .total-row {
            font-weight: bold;
            background-color: #d1f2eb;
            color: #0c3027;
        }

        .no-data {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            margin-top: 20px;
        }

        .table-container {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .table-container .table {
            width: 48%;
        }

        .header-info {
            text-align: center;
            margin-bottom: 20px;
            color: #0c3027;
        }

        .header-info h2 {
            color: #ffffff;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
                background-color: #fff;
            }

            .rekap {
                border: 1px solid #000;
                background-color: #fff;
            }

            table {
                page-break-inside: auto;
                background-color: #fff;
            }

            tr {
                page-break-inside: avoid;
            }
        }

        .laba-rugi-bersih {
            margin-top: 20px;
        }

        .laba-rugi-bersih table {
            width: 100%;
            border-collapse: collapse;
        }

        .laba-rugi-bersih td {
            border: none;
            padding: 12px;
            text-align: center;
            font-weight: bold;
            background-color: #f2f2f2;
            color: #0c3027;
        }
    </style>
</head>

<body>
    <div class="rekap">
        @include('layout.heading')
        <div class="header-info">
            <h2>Laba Rugi Tahunan</h2>
            <p>
                Periode:
                {{ \Carbon\Carbon::createFromFormat('Y-m', $tahun_awal . '-' . $bulan_awal)->translatedFormat('F Y') }}
                -
                {{ \Carbon\Carbon::createFromFormat('Y-m', $tahun_akhir . '-' . $bulan_akhir)->translatedFormat('F Y') }}
            </p>
        </div>

        @if ((int) $bulan_awal > (int) $bulan_akhir)
            <div class="no-data">Periode tidak valid: Bulan awal tidak boleh lebih besar dari bulan akhir.</div>
        @else
            <table>
                <thead>
                    <tr>
                        <th colspan="7">Pendapatan</th>
                    </tr>
                    <tr>
                        <th>Kode Akun</th>
                        <th>Nama Akun</th>
                        @for ($i = (int) $bulan_awal; $i <= (int) $bulan_akhir; $i++)
                            <th>{{ date('F', mktime(0, 0, 0, $i, 1, $tahun_awal)) }}</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalPendapatanPerBulan = array_fill((int) $bulan_awal, (int) $bulan_akhir - (int) $bulan_awal + 1, 0);
                    @endphp
                    @foreach ($pendapatanData as $kodeAcc => $dataBulan)
                        <tr>
                            <td>{{ $kodeAcc }}</td>
                            <td>{{ $dataBulan[str_pad($bulan_awal, 2, '0', STR_PAD_LEFT)]['nama_acc'] }}</td>
                            @for ($i = (int) $bulan_awal; $i <= (int) $bulan_akhir; $i++)
                                <td>{{ Rupiah($dataBulan[str_pad($i, 2, '0', STR_PAD_LEFT)]['saldo']) }}</td>
                                @php
                                    $totalPendapatanPerBulan[$i] += $dataBulan[str_pad($i, 2, '0', STR_PAD_LEFT)]['saldo'];
                                @endphp
                            @endfor
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="2">Total Pendapatan</td>
                        @for ($i = (int) $bulan_awal; $i <= (int) $bulan_akhir; $i++)
                            <td>{{ Rupiah($totalPendapatanPerBulan[$i]) }}</td>
                        @endfor
                    </tr>
                    <tr>
                        <th colspan="7">Hpp</th>
                    </tr>
                    <tr>
                        <th>Kode Akun</th>
                        <th>Nama Akun</th>
                        @for ($i = (int) $bulan_awal; $i <= (int) $bulan_akhir; $i++)
                            <th>{{ date('F', mktime(0, 0, 0, $i, 1, $tahun_awal)) }}</th>
                        @endfor
                    </tr>
                    @php
                        $totalHppPerBulan = array_fill((int) $bulan_awal, (int) $bulan_akhir - (int) $bulan_awal + 1, 0);
                    @endphp
                    @foreach ($hppData as $kodeAcc => $dataBulan)
                        <tr>
                            <td>{{ $kodeAcc }}</td>
                            <td>{{ $dataBulan[str_pad($bulan_awal, 2, '0', STR_PAD_LEFT)]['nama_acc'] }}</td>
                            @for ($i = (int) $bulan_awal; $i <= (int) $bulan_akhir; $i++)
                                <td>{{ Rupiah($dataBulan[str_pad($i, 2, '0', STR_PAD_LEFT)]['saldo']) }}</td>
                                @php
                                    $totalHppPerBulan[$i] += $dataBulan[str_pad($i, 2, '0', STR_PAD_LEFT)]['saldo'];
                                @endphp
                            @endfor
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="2">Total HPP</td>
                        @for ($i = (int) $bulan_awal; $i <= (int) $bulan_akhir; $i++)
                            <td>{{ Rupiah($totalHppPerBulan[$i]) }}</td>
                        @endfor
                    </tr>
                    <tr class="total-row">
                        <td colspan="2">Laba Kotor</td>
                        @for ($i = (int) $bulan_awal; $i <= (int) $bulan_akhir; $i++)
                            <td>{{ Rupiah($totalPendapatanPerBulan[$i] - $totalHppPerBulan[$i]) }}</td>
                        @endfor
                    </tr>
                    <tr>
                        <th colspan="7">Biaya</th>
                    </tr>
                    <tr>
                        <th>Kode Akun</th>
                        <th>Nama Akun</th>
                        @for ($i = (int) $bulan_awal; $i <= (int) $bulan_akhir; $i++)
                            <th>{{ date('F', mktime(0, 0, 0, $i, 1, $tahun_awal)) }}</th>
                        @endfor
                    </tr>
                    @php
                        $totalBiayaPerBulan = array_fill((int) $bulan_awal, (int) $bulan_akhir - (int) $bulan_awal + 1, 0);
                    @endphp
                    @foreach ($biayaData as $kodeAcc => $dataBulan)
                        <tr>
                            <td>{{ $kodeAcc }}</td>
                            <td>{{ $dataBulan[str_pad($bulan_awal, 2, '0', STR_PAD_LEFT)]['nama_acc'] }}</td>
                            @for ($i = (int) $bulan_awal; $i <= (int) $bulan_akhir; $i++)
                                <td>{{ Rupiah($dataBulan[str_pad($i, 2, '0', STR_PAD_LEFT)]['saldo']) }}</td>
                                @php
                                    $totalBiayaPerBulan[$i] += $dataBulan[str_pad($i, 2, '0', STR_PAD_LEFT)]['saldo'];
                                @endphp
                            @endfor
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="2">Total Biaya</td>
                        @for ($i = (int) $bulan_awal; $i <= (int) $bulan_akhir; $i++)
                            <td>{{ Rupiah($totalBiayaPerBulan[$i]) }}</td>
                        @endfor
                    </tr>
                    <tr class="total-row">
                        <td colspan="2">Laba Rugi/Bersih</td>
                        @for ($i = (int) $bulan_awal; $i <= (int) $bulan_akhir; $i++)
                            <td>{{ Rupiah($totalPendapatanPerBulan[$i] - $totalHppPerBulan[$i] - $totalBiayaPerBulan[$i]) }}</td>
                        @endfor
                    </tr>
                </tbody>
            </table>
        @endif
    </div>
    <script>
        window.print();
    </script>
</body>

</html>
