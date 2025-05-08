@extends('layout.main')
@section('title', 'RINCIAN PIUTANG KARYAWAN')
@section('css')
    <link href="{{ url('/') }}/lte/plugins/datepicker/css/bootstrap-datepicker.min.css" type="text/css"
        rel="stylesheet" />
@stop
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">RINCIAN PIUTANG KARYAWAN</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                        title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <p>TOTAL PIUTANG :</p>
                                <b>{{ Rupiah(Auth()->User()->piutang) }}</b>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="card col-sm-12 table-responsive">
                        <table class="table table-bordered table-sm table-hover">
                            <thead>
                                <tr class="bg-info">
                                    <th>Tanggal</th>
                                    <th>Piutang/Bayar</th>
                                    <th class="text-right">Jumlah</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($RincianPiutang as $index => $kk)
                                    <tr>
                                        <td>{{ datetotanggal($kk->tanggal) }}</td>
                                        <td>{{ $kk->posisi == 'K' ? 'PIUTANG' : 'BAYAR PIUTANG' }}</td>
                                        <td class="text-right">{{ Rupiah0($kk->jumlah) }}</td>
                                        <td>{{ $kk->keterangan }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $RincianPiutang->links() }}
                    </div>
                </div>
            </div>


    </section>
@stop
@section('script')

@stop
