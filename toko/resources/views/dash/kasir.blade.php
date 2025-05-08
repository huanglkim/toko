@extends('layout.main')
@section('title', 'Dashboard')
@section('css')
@stop
@section('content')
    <section class="content">
        <div class="card-body pb-0 pt-1">
            <div class="col-12  col-md-6 d-flex align-items-stretch">
                <div class="card">
                    <div class="card-body pt-0 pb-0 card-outline card-olive">
                        <div class="card-header pt-0 pb-0 ">
                            <p><strong> ID CARD</strong></p>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <h2 class="lead"><b>Nama : {{ Auth()->User()->nama }}</b></h2>
                                <ul class="ml-4 mb-0 fa-ul text-muted">
                                    <li class="small"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span>
                                        Alamat :
                                        {{ Auth()->User()->alamat }}</li>
                                    <li class="small"><span class="fa-li"><i class="fas fa-lg fa-phone"></i></span> Phone
                                        #:
                                        {{ Auth()->User()->wa }}
                                    </li>
                                    <li class="small"><span class="fa-li"><i class="fas fa-lg fa-phone"></i></span>
                                        Sebagai:
                                        {{ Auth()->User()->Role->nama_role }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-5 text-center ">
                                <img src="{{ url('img/' . Auth()->User()->foto) }}" alt=""
                                    class="img-circle img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 table table-responsive table-sm table-hover">
                <header>
                </header>
            </div>
        </div>
    </section>
@stop
@section('script')

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            localStorage.clear();
        }, false);
    </script>
@stop
