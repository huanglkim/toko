<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $title }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 4 -->
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">
    <!-- Google Font: Source Sans Pro -->
    <style>
        @page {
            size: auto;

        }

        .double {
            border-top: double;
        }

        @media print {
            .bel {
                position: fixed;
                size: auto;
                bottom: 0;
            }

            html,
            body {
                width: 215mm;
                height: 190mm;
            }

            .pagebreak {
                page-break-before: always;
            }


            /* page-break-after works, as well */
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Main content -->
        <section>
            <div class="row">
                <div class="col-12">
                    <h4 class="page-header">
                        {{ $title }}<br>
                    </h4>
                </div>
            </div>
            <!-- info row -->
            <div class="row invoice-info">
                <div class="col-sm-5 invoice-col">
                    <div class="row">
                        <div class="col-3">
                            <img src="{{ url('/') }}/images/logo.jpg" alt="LOGO"
                                class="brand-image elevation-3" style="opacity: .8" width="100" height="100">
                        </div>
                        <div class="col-9">
                            <address>
                                <b> {{ Toko(1)->nama_toko }} </b><br>
                                {{ Toko(1)->alamat }} .
                                +{{ Toko(1)->wa }}
                            </address>
                        </div>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                    <b>
                        Pelanggan :
                    </b>
                    <address>
                        {{ $pjhd->pelanggan->nama }}<br>
                        {{ $pjhd->pelanggan->alamat }}<br>
                        {{ $pjhd->pelanggan->kota }}<br>
                    </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-3 invoice-col text-right">
                    <b>No. Nota : {{ $pjhd->invoice }}</b>
                    <br>
                    <b>Tanggal :</b>{{ datetotanggal($pjhd->tanggal) }}
                </div>
                <!-- /.col -->
            </div>
            <!-- Table row -->
            @include('penjualan.isinotapj')
            @include('penjualan.footernotapj')
        </section>
        <!-- /.content -->
    </div>
    <!-- ./wrapper -->
    <script type="text/javascript">
        window.addEventListener("load", window.print());
        //setTimeout(window.close, 16500);
    </script>
</body>

</html>
