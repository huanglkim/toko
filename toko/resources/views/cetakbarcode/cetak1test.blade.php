<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="{{ url('/') }}/lte/dist/css/adminlte.min.css">

    <style>
        p.dashed {
            border-style: dashed;
        }

        @media print {
            .pagebreak {
                page-break-before: always;
            }

            body {
                width: 1100px;
                margin-left: 20px;
                margin-bottom: 100px;
                margin-top: 10px;
            }

            /* page-break-after works, as well */
        }

        body.print {
            width: 1100px;
            margin-left: 20px;
            margin-bottom: 100px;
            margin-top: 10px;
        }

        div.barcode {
            width: 58mm;
            padding: 10px;
            border: 0.5px solid black;
            margin: 0px;
            text-align: center;
        }

        h6 {
            color: red;
        }

        h7 {
            color: green;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <section class="invoice">

            @php
                $number = 0;
            @endphp
            <div class="row">
                @foreach ($cetakbar as $index => $data)
                    <?php
                    for ($x = 0; $x < $data->qty; $x++) {
                        $sum = 1;
                        $number += $sum;
                        $countbreak = $number % 50;
                        
                    ?>
                    <div class="barcode">
                        <h6>{{ $data->Barang->nama }} </h6>
                        <h7>{{ $data->Barang->kendaraan }}|{{ $data->Barang->kompatibel }}</h7><br>
                        <h7> {{ $data->rahasia . date('dmy') }}</h7>
                        <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($data->Barang->kode, 'C128') }}"
                            height="40" width="185">
                        <h7> {{ $data->Barang->kode }}</h7>
                    </div>
                    @if ($countbreak == 0)
            </div>
            <div class="pagebreak"></div>
            <div class="row">
                @endif
                <?php } ?>
                @endforeach
            </div>

        </section>
    </div>
    <script>
        window.addEventListener("load", window.print());
    </script>
</body>

</html>
