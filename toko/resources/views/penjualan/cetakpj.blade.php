<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 20px;
            color: #000;
            background-color: #fff;
        }

        h4.page-header {
            text-align: center;
            margin-bottom: 30px;
            font-size: 22px;
            font-weight: bold;
        }

        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .invoice-info .col {
            width: 32%;
            font-size: 14px;
        }

        .invoice-info b {
            font-size: 15px;
        }

        address {
            margin: 0;
            line-height: 1.5;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .double {
            border-top: 3px double #000;
        }

        @media print {

            html,
            body {
                width: 210mm;
                height: auto;
                margin: 0;
                padding: 0;
                background-color: #fff;
            }

            .pagebreak {
                page-break-before: always;
            }

            .invoice-info {
                flex-direction: row;
            }

            .col {
                break-inside: avoid;
            }
        }
    </style>
</head>


<body>
    <div class="wrapper">
        <section>
            <h4 class="page-header">{{ $title }}</h4>

            <div class="invoice-info">
                <div class="col">
                    @include('layout.heading')
                </div>
            </div>
                
                <div class="col">
                    <b>Pelanggan:</b>
                    <address>
                        {{ $pjhd->pelanggan->nama }}<br>
                        {{ $pjhd->pelanggan->alamat }}<br>
                        {{ $pjhd->pelanggan->kota }}<br>
                    </address>
                </div>
                
                <div class="col text-right">
                    <b>No. Nota :</b>{{ $pjhd->invoice }}<br>
                    <b>Tanggal :</b>{{ datetotanggal($pjhd->tanggal) }}
                </div>
               
            </div>
            
            @include('penjualan.isinotapj')
            @include('penjualan.footernotapj')
        </section>
       
    </div>
    <script>
        window.print();
    </script>
</body>

</html>
