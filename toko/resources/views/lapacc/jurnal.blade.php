@extends('layout.main')
@section('title', 'PENJUALAN')

@section('css')
    <link href="{{ url('/') }}/lte/plugins/datepicker/css/bootstrap-datepicker.min.css" type="text/css"
        rel="stylesheet" />
@stop
@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">LAPORAN JURNAL POSTING</h3>
            </div>
            <div class="card-body">
                <form id="formcetakpt" method="post" action="{{ url('/') }}/lpcetakjurnal" target="popup"
                    class="form-horizontal" data-toggle="validator">
                    @csrf
                    <div class="row">
                        <div class="col-sm-1">
                            <b>Periode</b>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control datepicker form-control-sm" id="tanggalawal"
                                name="tanggalawal" value="<?= date('01-m-Y') ?>">
                        </div>
                        <div class="col-sm-1">
                            <b>s/d</b>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control datepicker form-control-sm" id="tanggalakhir"
                                name="tanggalakhir" value="<?= date('d-m-Y') ?>">
                        </div>
                        <div class="col-sm-2">
                            <input type="hidden" class="form-control form-control-sm" id="jenislaporan" name="jenislaporan"
                                value="3">
                        </div>
                        <div class="col-sm-1 float-right">
                            <button class="btn btn-info btn-flat btn-sm"><i class="fas fa-print"></i> Cetak</button>
                        </div>
                    </div>
                </form>
                <!-- /.end row -->
            </div>
            <!-- /.card-footer-->
        </div>
    </section>
@stop
@section('script')
    <script src="{{ url('/') }}/lte/plugins/datepicker/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript">
        //datepicker
        $(function() {
            $(".datepicker").datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
            });
        });
    </script>
@stop
