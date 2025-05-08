@extends('layout.main')
@section('title', 'PEMBELIAN')
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
        <input type="hidden" name="jumlahpopending" id="jumlahpopending" value="{{ $jumlahpopending }}">
        <div class="row">
            <div class="card col-md-12">
                <div class="mb-4">
                    <h2 class="text-primary">
                        <i class="fas fa-shopping-cart mr-2"></i> DAFTAR PEMBELIAN
                    </h2>
                    <div class="card">
                        <div class="card-body pb-1 pt-1">
                            <div class="d-flex flex-wrap gap-2 justify-content-end mb-3">
                                <button type="button" onclick="cekpopending()" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-plus-square me-1"></i> Tambah Pembelian
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
                                        <h6 class="callout-panel-title">Suplier</h6>
                                        <div class="col-md-12" id="carisuplier">
                                            <select class="custom-select custom-select-lg" name="suplier_id"
                                                data-live-search="true" data-title="SEMUA SUPLIER" id="suplier_id"
                                                data-width="100%">
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
                                    <th>Kode Sup.</th>
                                    <th>Nama Sup.</th>
                                    <th class="text-end">Jumlah</th>
                                    <th>Keterangan</th>
                                    <th>Waktu</th>
                                    <th style="width: 90px;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Isi tabel di-generate via JS/Blade -->
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
    <script>
        function cekpopending() {
            var jumlahpopending = document.getElementById('jumlahpopending').value;
            if (jumlahpopending > 0) {
                Swal.fire({
                    title: 'Anda yakin?',
                    text: "MASIH ADA PENDING LANJUT BARU??!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'YA. LANJUT BARU!'
                }).then((result) => {
                    if (result.value) {
                        tambahpbbaru();
                    }
                });
            } else {
                tambahpbbaru();
            }
        }

        function tambahpbbaru() {
            if (reqsent == false) {
                //open login
                reqsent = true;
                var geturl = base_url + '/tambahpbbaru';
                $.ajax({
                    url: geturl,
                    type: "POST",
                    data: {
                        '_method': 'POST',
                        '_token': token
                    },
                    success: function(data) {
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
                        reqsent = true;
                        console.log(data);
                    }
                });
            }
        }

        function editdata(uuid) {
            var url = '{{ url('') }}' + '/editpb/' + uuid;
            window.location.href = url;
        }
    </script>
    <script>
        // suplier cari
        var carisuplier = document.getElementById("carisuplier");
        var suplier = $('#suplier_id').selectpicker('val');
        carisuplier.addEventListener("keyup", function(e) {
            if (e.which == 38 || e.which == 40) {
                e.preventDefault();
            } else {
                var searchData = e.target.value;
                if (searchData.length < 2) {} else {
                    $.ajax({
                        url: "{{ url('carisuplier') }}",
                        method: "POST",
                        minLength: 3,
                        data: {
                            '_token': '{{ csrf_token() }}',
                            carisuplier: searchData
                        },
                        success: function(data) {
                            var html = '';
                            for (var count = 0; count < data.length; count++) {
                                html += '<option value="' + data[count].id + '" data-id="' + data[count]
                                    .id + '">' +
                                    data[count].nama + '/' + data[count].kode + '</option>';
                            }
                            $('#suplier_id').html(html);
                            $('#suplier_id').selectpicker('refresh');
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                }
            }
        });
        $('#suplier_id').on('changed.bs.select', function() {
            datatable();
        });
    </script>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            reqsent = false;
            token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            base_url = document.querySelector('meta[name="base_url"]').getAttribute('content');
            datatable();
        }, false);

        function datatable() {
            $('#tabel_data').dataTable().fnClearTable();
            $('#tabel_data').dataTable().fnDestroy();
            var status = $('#status').val();
            var suplier_id = $('#suplier_id').val();
            var urlget = "{{ url('/tabelpb') }}";
            $('#tabel_data').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                deferRender: true,
                bStateSave: true,
                retrieve: true,
                searchHighlight: true,
                lengthMenu: [50, 10, 100, 75, 500, 1000],
                language: {
                    processing: '<i class="fas fa-sync-alt fa-spin fa-3x fa-fw"></i><span class="bg-lime color-palette">Loading......</span>'
                },

                ajax: $.fn.dataTable.pipeline({
                    url: urlget,
                    type: "POST",
                    dataType: "JSON",
                    pages: 2, // number of pages to cache
                    data: {
                        '_token': '{{ csrf_token() }}',
                        status: status,
                        suplier_id: suplier_id,
                    },
                }),
                order: [
                    [6, 'DESC']
                ],

                columns: [{
                        data: 'tanggal',
                        name: 'tanggal',
                    },
                    {
                        data: 'invoice',
                        name: 'invoice',
                    },
                    {
                        data: 'suplier.kode',
                        name: 'suplier.kode',
                    },
                    {
                        data: 'suplier.nama',
                        name: 'suplier.nama',
                    },
                    {
                        data: 'jumlah',
                        name: 'jumlah',
                        className: 'text-right'
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan',
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',

                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                    },
                ]
            });
        }

        $(function() {
            $(".datepicker").datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
            });
        });
    </script>
    <script type="text/javascript">
        function hapus(uuid) {
            if (reqsent == false) {
                Swal.fire({
                    title: 'Anda yakin?',
                    text: "Data Tidak akan Kembali setelah dihapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        //open login
                        reqsent = true;
                        var geturl = base_url + '/hapuspb/' + uuid;
                        $.ajax({
                            url: geturl,
                            type: "POST",
                            data: {
                                '_method': 'DELETE',
                                '_token': token
                            },
                            success: function(data) {
                                console.log(data);
                                reqsent = false;
                                if (data.success == 1) {
                                    $('#modal-form').modal('hide');
                                    Swal.fire({
                                        position: 'top-end',
                                        title: data.pesan,
                                        icon: 'success',
                                        showConfirmButton: false,
                                        timer: 900
                                    }).then(function() {
                                        datatable();
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
                                reqsent = true;
                                console.log(data);
                            }
                        });
                    }
                })
            }
        }
    </script>
@stop
