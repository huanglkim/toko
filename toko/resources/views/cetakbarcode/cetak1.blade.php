<!DOCTYPE html>
<html>

<head>
    <title> Cetak Barcode</title>
    <link rel="stylesheet" href="{{ url('/') }}/lte/dist/css/adminlte.min.css">
</head>

<style>
    div.barcode {
        width: 58mm;
        height: 37mm;
        padding: 10px;
        border: 0.5px solid black;
        margin: 0px;
        text-align: center;
    }

    .breakword {
        overflow-wrap: break-word;
    }


    body.print {
        width: 1100px;
        margin-left: 20px;
        margin-bottom: 100px;
        margin-top: 10px;
    }

    @media print {
        h1 {
            color: rgba(0, 0, 0, 0);
            text-shadow: 0 0 0 #ccc;
        }

        .bel {
            position: fixed;
            size: auto;
            bottom: 0;
        }

        html,
        body {
            width: 215mm;
            height: 330mm;
        }

        .pagebreak {
            page-break-before: always;
        }
    }

    h6 {
        color: rgb(0, 0, 0);
        line-height: 0.9;
    }
</style>

<body class="print">
    <div class="row d-flex align-items-stretch">
        @php
            $number = 0;
        @endphp
        @foreach ($cetakbar as $index => $data)
            <?php
        for ($x = 0; $x < $data->qty; $x++) {
            $sum = 1;
            $number += $sum;
            $countbreak = $number % $break;
            ?>
            <div class="barcode" style="margin:0px; padding:0px;">
                <h6 class="mt-1 mb-0 ml-1 text-left breakword">
                    {{ $data->Barang == null ? 'KEHAPUS' : $data->Barang->nama_barang }} </h6>
                <div class="row mt-0 mb-0 ">
                    <h6 class="col-12 ml-1 mt-0 mb-1">
                        {{ $data->Barang == null ? 'KEHAPUS' : $data->Barang->kode_internal }} </h6>
                    </h6>
                    {{-- <h6 class="col-5 mt-0 mb-0 text-right"> </h6> --}}
                </div>
                <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($data->Barang == null ? 'KEHAPUS' : $data->Barang->barcode, 'C128') }}"
                    height="37" width="185">
                <h6 class="mt-1 mb-0 ml-1 text-left breakword">
                    {{ $data->Barang == null ? 'KEHAPUS' : $data->Barang->barcode }}
                </h6>
                {{-- @if ($data->Barang->harga1 > 0 && $data->cetak_harga == 1)
                    <div class="row">
                        <div class="col-6">
                            {{ $data->Barang == null ? 'KEHAPUS' : $data->Barang->kode }}
                        </div>
                        <div class="col-6">
                            {{ $data->Barang == null ? 'KEHAPUS' : Rupiah0($data->Barang->harga1) }}
                        </div>

                    </div>
                @else
                @endif --}}
            </div>
            @if ($countbreak == 0)
    </div>
    <div class="pagebreak"></div>
    <div class="row">
        @endif
        <?php } ?>
        @endforeach
    </div>
</body>

<script>
    setTimeout(function() {
        window.print();
    }, 500);
    window.onfocus = function() {
        setTimeout(function() {
            window.close();
        }, 500);
    }
</script>

</html>
