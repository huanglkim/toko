@extends('layout.main')
@section('title', 'PO PEMBELIAN')
@section('css')
    <link href="{{ url('/') }}/lte/plugins/datepicker/css/bootstrap-datepicker.min.css" type="text/css"
        rel="stylesheet" />
    <link href="{{ url('/') }}/lte/css/select2.min.css" rel="stylesheet" />
    <link href="{{ url('/') }}/lte/css/selectpicker.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/lte/DataTables/datatables.min.css" />
    <style>
        .table-responsive-sm {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .card {
            margin-bottom: 20px;
            border-radius: 0.5rem;
            /* Increased border-radius for a softer look */
            box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.08);
            /* Refined shadow */
            background-color: #fff;
            border: 1px solid #ededed;
            /* Added border */
        }

        .card-body {
            padding: 1.5rem;
            /* Increased padding for more spacing */
        }

        .btn-sm {
            padding: 0.375rem 0.75rem;
            /* Adjusted padding */
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 0.375rem;
            /* Slightly increased border-radius */
        }

        .btn-primary {
            background-color: #007bff;
            /* Bootstrap's primary color */
            border-color: #007bff;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            /* Darker shade on hover */
            border-color: #004080;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
            color: #fff;
        }

        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            color: #fff;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }

        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #212529;
        }

        .btn-warning:hover {
            background-color: #e0a800;
            border-color: #d39e00;
        }


        .btn-outline-success {
            background-color: transparent;
            border-color: #28a745;
            color: #28a745;
        }

        .btn-outline-success:hover {
            background-color: #28a745;
            color: #fff;
        }

        .btn-flat {
            border-radius: 0.25rem;
            /* Added border radius to flat buttons */
            box-shadow: none;
        }

        .btn-flat:hover {
            box-shadow: 0 0.125rem 0.375rem rgba(0, 0, 0, 0.08);
            /* Subtle shadow on hover */
        }

        .callout {
            border-left: 0.25rem solid #17a2b8;
            margin-bottom: 1rem;
            padding: 1rem;
            border-radius: 0.375rem;
            background-color: #e0f7fa;
            /* Light background for callouts */
            border-color: #b2ebf2;
            /* Light border color */
        }

        .callout-success {
            border-left-color: #28a745;
            background-color: #f0fdf4;
            border-color: #d1f2e1;
            color: #155724;
        }

        .callout-danger {
            border-left-color: #dc3545;
            background-color: #fef0f0;
            border-color: #fecdce;
            color: #721c24;
        }

        .callout-warning {
            border-left-color: #ffc107;
            background-color: #fff8e1;
            border-color: #fff1c2;
            color: #856404;
        }

        .table-bordered {
            border: 1px solid #e0e0e0;
            /* Lighter border color */
        }

        .table-bordered td,
        .table-bordered th {
            border: 1px solid #e0e0e0;
            padding: 0.75rem;
            /* Increased cell padding */
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.05);
            /* Slightly lighter hover color */
        }

        .table-success th {
            background-color: #f0fdf4;
            color: #155724;
        }

        .custom-select {
            display: inline-block;
            width: 100%;
            padding: 0.375rem 2.25rem 0.375rem 0.75rem;
            /* Adjusted padding for arrow */
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'%3E%3Cpath fill='%23343a40' d='M2 0L0 2h4z'%3E%3C/path%3E%3C/svg%3E");
            /* Added arrow icon */
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 0.75rem 1rem;
        }

        .custom-select:focus {
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .custom-select-sm {
            padding-top: 0.25rem;
            padding-bottom: 0.25rem;
            padding-left: 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 0.25rem;
        }

        .invalid-feedback {
            display: block;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875rem;
            color: #dc3545;
        }

        .alert-success {
            background-color: #f0fdf4;
            border-color: #d1f2e1;
            color: #155724;
        }

        .alert-success .close {
            color: #155724;
        }

        .alert-success hr {
            border-top-color: #b1dfbb;
        }

        .alert-success .alert-link {
            color: #0b4020;
        }

        .close {
            float: right;
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1;
            color: #000;
            text-shadow: 0 1px 0 #fff;
            opacity: 0.5;
        }

        .close:hover {
            opacity: 0.75;
        }

        .modal-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            padding: 1rem;
            border-bottom: 1px solid #dee2e6;
            border-top-left-radius: 0.3rem;
            border-top-right-radius: 0.3rem;
            background-color: #f8f9fa;
            /* Light background for modal header */
        }

        .modal-title {
            margin-bottom: 0;
            line-height: 1.5;
            font-size: 1.25rem;
            font-weight: 500;
            color: #2c3e50;
            /* Darker title color */
        }

        .modal-body {
            position: relative;
            padding: 1rem;
        }

        .modal-footer {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 1rem;
            border-top: 1px solid #dee2e6;
            border-bottom-left-radius: 0.3rem;
            border-bottom-right-radius: 0.3rem;
            background-color: #f8f9fa;
            /* Light background for modal footer */
        }

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


        .callout-panel-content {
            font-size: 0.9rem;
            color: #495057;
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
                        <i class="fas fa-shopping-basket mr-2"></i> DAFTAR PO
                    </h2>
                    <div class="card">
                        <div class="card-body pb-1 pt-1">
                            <div class="d-flex flex-wrap gap-2 justify-content-end mb-3">
                                <button type="button" onclick="cekpopending()" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-plus-square me-1"></i> Tambah PO Pembelian
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

                    <div class="row">
                        <div class="card col-12 table-responsive">
                            <table class="table table-bordered table-sm table-hover" id="tabel_data">
                                <thead>
                                    <tr class="table-info">
                                        <th>Tanggal</th>
                                        <th>Invoice</th>
                                        <th>Kode Sup.</th>
                                        <th>Nama Sup.</th>
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
                    text: "MASIH ADA PO PENDING LANJUT PO BARU??!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'YA. LANJUT PO BARU!'
                }).then((result) => {
                    if (result.value) {
                        tambahpobaru();
                    }
                });
            } else {
                tambahpobaru();
            }
        }

        function tambahpobaru() {
            if (reqsent == false) {
                //open login
                reqsent = true;
                var geturl = base_url + '/tambahpobaru';
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
            var url = '{{ url('') }}' + '/editpo/' + uuid;
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
            var urlget = "{{ url('/tabelpopb') }}";
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
                        var geturl = base_url + '/hapuspopb/' + uuid;
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
