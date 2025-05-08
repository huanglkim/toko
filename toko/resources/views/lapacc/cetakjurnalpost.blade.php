<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $title }} | Print</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 4 -->

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ url('/') }}/lte/plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ url('/') }}/lte/dist/css/adminlte.min.css">

    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
        <!-- Main content -->
        <section class="invoice">
            <!-- title row -->
            <div class="row">
                <div class="col-12">
                    <h2 class="page-header">
                        <img class="profile-user-img img-fluid img-circle"
                            src="{{ url('/') }}/upload/uimage/{{ $toko->logo_toko }}" alt="User profile picture">
                        {{ $toko->nama_toko }}
                    </h2>
                </div>
                <!-- /.col -->
            </div>
            <!-- info row -->
            <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">

                </div>
                <!-- /.col -->

                <!-- /.col -->
                <div class="col-sm-4 invoice-col text-center">
                    <strong>{{ $title }}</strong><br>
                    <b>Periode:</b> {{ $periode }}<br>
                </div>
                <div class="col-sm-4 invoice-col text-right">


                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- Table row -->
            <div class="row">
                <div class="col-12 table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr class="bg-info">
                                <th>KODE</th>
                                <th>NAMA</th>
                                <th>INVOICE</th>
                                <th>TANGGAL</th>
                                <th>KETERANGAN</th>
                                <th class="text-right">DEBET</th>
                                <th class="text-right">KREDIT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cetakrk as $ct)
                                <tr class="{{ $ct->induk == 1 ? 'table-primary' : '' }}">
                                    <td>{{ $ct->kode_acc }}</td>
                                    <td>{{ $ct->accperkiraan->nama_acc }}</td>
                                    <td>{{ $ct->invoice }}</td>
                                    <td>{{ datetotanggal($ct->tanggal) }}</td>
                                    <td>{{ $ct->keterangan }}</td>
                                    <td class="text-right">{{ rupiah($ct->debet) }}</td>
                                    <td class="text-right">{{ rupiah($ct->kredit) }}</td>
                                </tr>
                            @endforeach
                            <tr class="bg-info">
                                <td class="text-right" colspan="5">Total :</td>
                                <td class="text-right">{{ rupiah($cetakrk->sum('debet')) }}</td>
                                <td class="text-right">{{ rupiah($cetakrk->sum('kredit')) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="row">
                <!-- accepted payments column -->
                <div class="col-6">
                    <div class="callout callout-info">

                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
    <!-- ./wrapper -->

    <script type="text/javascript">
        // window.addEventListener("load", window.print());
    </script>
</body>

</html>
