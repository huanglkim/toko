@extends('layout.main')
@section('title')
    {{ $judul }}
@stop
@section('css')
    <link href="{{ url('/') }}/lte/plugins/datepicker/css/bootstrap-datepicker.min.css" type="text/css"
        rel="stylesheet" />
    <link href="{{ url('/') }}/lte/css/select2.min.css" rel="stylesheet" />
    <link href="{{ url('/') }}/lte/css/selectpicker.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/lte/DataTables/datatables.min.css" />
    <style>
        .callout-panel {
            padding: 15px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-left: 4px solid #007bff;
            border-radius: 6px;
            margin-bottom: 1rem;
        }

        .callout-panel-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
        }
    </style>
@stop
@section('content')
    <section class="content">
        <input type="hidden" name="jumlahpjpending" id="jumlahpjpending" value="{{ $jumlahpjpending }}">
        <input type="hidden" name="tipe" id="tipe" value="{{ $tipe }}">
        <div class="row">
            <div class="card col-md-12">
                <div class="mb-4">
                    <h2 class="text-primary">
                        <i class="fas fa-dollar-sign mr-2"></i> DAFTAR {{ $judul }}
                    </h2>
                    <div class="card">
                        <div class="card-body pb-1 pt-1">
                            <div class="d-flex flex-wrap gap-2 justify-content-end mb-3">
                                <button type="button" onclick="cekpjpending()" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-plus-square me-1"></i> Tambah {{ $judul }}
                                </button>
                            </div>
                            <div class="row g-12 mb-4">
                                <div class="col-md-6">
                                    <div class="callout-panel">
                                        <h6 class="callout-panel-title">Status</h6>
                                        <select class="custom-select custom-select-lg" onchange="datatable();"
                                            id="status" name="status">
                                            <option value="">Semua</option>
                                            <option value="1">Pending</option>
                                            <option value="2">Selesai Simpan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="callout-panel">
                                        <h6 class="callout-panel-title">Pelanggan</h6>
                                        <div class="col-md-12" id="caripelanggan">
                                            <select class="custom-control custom-control-lg" name="pelanggan_id"
                                                data-size="7" data-live-search="true" data-title="SEMUA PELANGGAN"
                                                id="pelanggan_id" data-width="100%">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive-sm">
                        <table class="table table-bordered table-hover table-sm align-middle text-nowrap" id="tabel_data">
                            <thead class="bg-light text-dark">
                                <tr class="table-info">
                                    <th>Tanggal</th>
                                    <th>Invoice</th>
                                    <th>Kode Cust.</th>
                                    <th>Nama Cust.</th>
                                    <th class="text-right">Jumlah</th>
                                    <th>Keterangan</th>
                                    <th>Time_cr</th>
                                    <th class="text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
@section('script')
    <script src="{{ asset('lte/sweetalert2/sweetalert2@11.js') }}"></script>
    <script src="{{ url('/') }}/lte/plugins/datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="{{ url('/') }}//lte/js/select2.min.js"></script>
    <script src="{{ url('/') }}/lte/js/selectpicker.js"></script>
    <script type="text/javascript" src="{{ url('/') }}/lte/DataTables/datatables.min.js"></script>
    @include('jsblade.datatablejs')
    @include('jsblade.jsbladedaftarpj')
    <script>
        function tambahpjbaru() {
            if (reqsent == false) {
                //open login
                reqsent = true;
                Swal.fire({
                    position: 'top-center',
                    title: "Creating...",
                    imageUrl: '{{ asset('icon/loading.gif') }}',
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
                if (tipe == 'PJ') {
                    var geturl = base_url + '/tambahpjbaru';
                }
                if (tipe == 'KSR') {
                    var geturl = base_url + '/tambahksrbaru';
                }
                if (tipe == 'MPL') {
                    var geturl = base_url + '/tambahksrmplbaru';
                }
                $.ajax({
                    url: geturl,
                    type: "POST",
                    data: {
                        '_method': 'POST',
                        '_token': token
                    },
                    success: function(data) {
                        swal.close();
                        reqsent = false;
                        if (data.success == 1) {
                            Swal.fire({
                                position: 'top-end',
                                title: data.pesan,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 900
                            }).then(function() {
                                editdata(data.uuid);
                            });
                        }
                        if (data.success == 0) {
                            Swal.fire({
                                position: 'top-end',
                                title: data.pesan,
                                icon: 'error',
                            }).then(function() {});
                        }
                    },
                    error: function(data) {
                        swal.close();
                        reqsent = true;
                        console.log(data);
                    }
                });
            }
        }
    </script>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            reqsent = false;
            token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            base_url = document.querySelector('meta[name="base_url"]').getAttribute('content');
            datatable();
            tipe = document.getElementById('tipe').value;
        }, false);
    </script>

@stop
