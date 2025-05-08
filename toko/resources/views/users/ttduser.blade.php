@extends('layout.main')
@section('title', 'User Signature')
@section('css')
    <style type="text/css">
        .signature-pad {
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            height: 260px;
        }
    </style>
@stop
@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="card card-solid col-sm-5">
            <div class="row">

            </div>
            <div class="row">
                <b>TTD</b><br>
                <img src="{{ url('/') }}/wu/public/userfoto/ttd/{{ Auth()->User()->id }}.png?{{ rand() }}"
                    alt="ttd">
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <hr>
                    <h4>Signature Pad</h4>
                    <div class="text-right">
                        <button type="button" class="btn btn-default btn-sm" id="undo"><i class="fa fa-undo"></i>
                            Undo</button>
                        <button type="button" class="btn btn-danger btn-sm" id="clear"><i class="fa fa-eraser"></i>
                            Clear</button>
                    </div>
                    <br>
                    <form method="POST" action="{{ url('/simpanttduser') }}">
                        @csrf
                        <div class="wrapper">
                            <canvas id="signature-pad" class="signature-pad"></canvas>
                        </div>
                        <br>
                        <button type="button" class="btn btn-primary btn-sm" id="save-png">PREVIEW</button>
                        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                                aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Preview Tanda Tangan</h4>
                                    </div>
                                    <div class="modal-body">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                                                class="fa fa-times"></i> Cancel</button>
                                        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i>
                                            SIMPAN</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <hr>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
@stop
@section('script')
    <script src="{{ url('/') }}/lte/js/signature/signature_pad.min.js"></script>
    <script src="{{ url('/') }}/lte/js/signature/pad.js"></script>
@stop
