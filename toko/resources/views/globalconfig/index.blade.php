@extends('layout.main')
@section('title', 'Data Globalconfig')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/lte/DataTables/datatables.min.css" />

@stop
@section('content')
    <section class="content">
        <div class="card">
            <div class="card-body pb-1 pt-1">
                <div class="col-sm">
                    <button onclick="addForm()" type="button" class="btn btn-sm bg-indigo color-palette btn-flat">
                        <i class="fas fa-plus-square"> </i> Tambah Globalconfig
                    </button>
                </div>
                <div class="row">
                    <div class="callout callout-success col-sm-4">
                        <div class="row">
                            <div class="col-sm-4">
                                Deleted :
                            </div>
                            <div class="col-sm-6">
                                <div class="col-sm-12">
                                    <select class="custom-select custom-select-sm" onchange="datatable();" id="getdeleted"
                                        name="getdeleted">
                                        <option value="1">NO</option>
                                        <option value="2">DIHAPUS</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive-sm">
                    <table class="table table-bordered table-sm table-hover" id="tabel_data">
                        <thead>
                            <tr class="bg-success">
                                <th>Nama</th>
                                <th>Data</th>
                                <th style="width: 10px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    @include('globalconfig.form')
    {{-- @include('login.otoritasform') --}}

@stop
@section('script')
    <script type="text/javascript" src="{{ url('/') }}/lte/DataTables/datatables.min.js"></script>
    <script src="{{ asset('lte/sweetalert2/sweetalert2@11.js') }}"></script>
    <script type="text/javascript">
        function addForm() { //form tambah show
            save_method = "POST";
            reqsent = false;
            $('input[name=_method]').val('POST');
            $('#modal-form-globalconfig').modal('show');
            $('#modal-form-globalconfig form')[0].reset();
            $('.modal-title').text('Tambah Globalconfig');
        }

        function AddOrEditconfig() { //tambah atau edit //store update
            if (reqsent == false) {
                reqsent = true;
                var id = $('#id').val();
                if (save_method == 'POST') url = "{{ url('globalconfig') }}";
                else url = "{{ url('globalconfig') . '/' }}" + id;
                var data = $('#form-globalconfig').serialize();
                $.ajax({
                    url: url,
                    method: "POST",
                    data: data,
                    success: function(data) {
                        reqsent = false;
                        if (data.success == 1) {
                            $('#modal-form-globalconfig').modal('hide');
                            Swal.fire({
                                position: 'top-end',
                                title: data.pesan,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 900
                            }).then(function() {
                                location.reload();
                            });
                        } else {
                            if (data.success == 0) {
                                Swal.fire({
                                    position: 'top-end',
                                    title: 'ERROR HUBUNGI ADMIN',
                                    icon: 'error',
                                    showConfirmButton: false,
                                    timer: 900
                                }).then(function() {
                                    console.log(data);
                                });
                            } else {
                                if (data.nama_config) {
                                    $('#nama_config').addClass('is-invalid');
                                    $('#errornama_config').html(data.nama_config);
                                }
                                if (data.data_config) {
                                    $('#data_config').addClass('is-invalid');
                                    $('#errordata_config').html(data.data_config);
                                }
                                console.log(data);

                            }
                        }
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            }

        }
        // otoritas
        function DeleteData(id) {
            document.getElementById('otidinput').value = id;
            document.getElementById('ottipe_akses').value = 0;
            //0 hapus, 1 edit, 2 edit readonly, 3 custom
            document.getElementById('otakses_id').value = 1;
            //1 delete/edit , 2/ed harga, 3/ed pj, 4/ed pb, 5/ed pl sp, 6/ed br
            otoritas();
        }

        function EditData(id) {
            document.getElementById('otidinput').value = id;
            document.getElementById('ottipe_akses').value = 1;
            //0 hapus, 1 edit, 2 edit readonly, 3 custom
            document.getElementById('otakses_id').value = 5;
            //1 delete/edit , 2/ed harga, 3/ed pj, 4/ed pb, 5/ed pl sp, 6/ed br
            otoritas();
        }
        $('#otoritasModal').on('shown.bs.modal', function() {
            $('#otusername').focus();
        });
        $('#otoritasrfidModal').on('shown.bs.modal', function() {
            $('#otrfid').focus();
        });

        function Custom(id) {
            alert('berhasil' + id);
        }
        //end otoritas
        function OtEdit(id) {
            reqsent = false;
            save_method = 'PATCH';
            $('input[name=_method]').val('PATCH');
            $('#modal-form-globalconfig form')[0].reset();
            $.ajax({
                url: "{{ url('globalconfig') }}" + '/' + id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    if (data.success == 1) {
                        $('#modal-form-globalconfig').modal('show');
                        $('.modal-title').text('Edit Globalconfig');
                        $('#id').val(data.id);
                        $('#nama_config').val(data.nama_config);
                        $('#data_config').val(data.data_config);
                    }
                    if (data.success == 0) {
                        $('#modal-form-globalconfig').modal('hide');
                        Swal.fire({
                            position: 'top-end',
                            title: data.pesan,
                            icon: 'error',
                            showConfirmButton: false,
                            timer: 900
                        }).then(function() {
                            console.log(data);
                            // location.reload();
                        });
                    }
                },
                error: function() {
                    alert("Tidak ada data");
                }
            });
        }

        function OtDelete(id) {
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
                    $.ajax({
                        url: "{{ url('globalconfig') }}" + '/' + id,
                        type: "POST",
                        data: {
                            '_method': 'DELETE',
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            if (data.success == 1) {
                                $('#modal-form-globalconfig').modal('hide');
                                Swal.fire({
                                    position: 'top-end',
                                    title: data.pesan,
                                    icon: 'success',
                                    showConfirmButton: false,
                                    timer: 900
                                }).then(function() {
                                    location.reload();
                                });
                            }
                            if (data.success == 0) {
                                Swal.fire({
                                    position: 'top-end',
                                    title: data.pesan,
                                    icon: 'error',
                                    showConfirmButton: false,
                                    timer: 900
                                }).then(function() {
                                    console.log(data);
                                });
                            }
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                }
            })
        }

        function RestoreTrash(id) {

            Swal.fire({
                title: 'Anda yakin?',
                text: "Data Akan DiKembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Kembalikan!'
            }).then((result) => {
                if (result.value) {
                    //open login
                    $.ajax({
                        url: "{{ url('restoreglobalconfig') }}" + '/' + id,
                        type: "POST",
                        data: {
                            '_method': 'PATCH',
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            if (data.success == 1) {
                                $('#modal-form-globalconfig').modal('hide');
                                Swal.fire({
                                    position: 'top-end',
                                    title: data.pesan,
                                    icon: 'success',
                                    showConfirmButton: false,
                                    timer: 900
                                }).then(function() {
                                    location.reload();
                                });
                            }
                            if (data.success == 0) {
                                Swal.fire({
                                    position: 'top-end',
                                    title: data.pesan,
                                    icon: 'error',
                                    showConfirmButton: false,
                                    timer: 900
                                }).then(function() {
                                    console.log(data);
                                });
                            }
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                }
            })
        }
    </script>

    <script type="text/javascript">
        $.fn.dataTable.pipeline = function(opts) {
            // Configuration options
            var conf = $.extend({
                pages: 5, // number of pages to cache
                url: '', // script url
                data: null, // function or object with parameters to send to the server
                // matching how `ajax.data` works in DataTables
                method: 'GET' // Ajax HTTP method
            }, opts);

            // Private variables for storing the cache
            var cacheLower = -1;
            var cacheUpper = null;
            var cacheLastRequest = null;
            var cacheLastJson = null;

            return function(request, drawCallback, settings) {
                var ajax = false;
                var requestStart = request.start;
                var drawStart = request.start;
                var requestLength = request.length;
                var requestEnd = requestStart + requestLength;

                if (settings.clearCache) {
                    // API requested that the cache be cleared
                    ajax = true;
                    settings.clearCache = false;
                } else if (cacheLower < 0 || requestStart < cacheLower || requestEnd > cacheUpper) {
                    // outside cached data - need to make a request
                    ajax = true;
                } else if (JSON.stringify(request.order) !== JSON.stringify(cacheLastRequest.order) ||
                    JSON.stringify(request.columns) !== JSON.stringify(cacheLastRequest.columns) ||
                    JSON.stringify(request.search) !== JSON.stringify(cacheLastRequest.search)
                ) {
                    // properties changed (ordering, columns, searching)
                    ajax = true;
                }

                // Store the request for checking next time around
                cacheLastRequest = $.extend(true, {}, request);

                if (ajax) {
                    // Need data from the server
                    if (requestStart < cacheLower) {
                        requestStart = requestStart - (requestLength * (conf.pages - 1));

                        if (requestStart < 0) {
                            requestStart = 0;
                        }
                    }

                    cacheLower = requestStart;
                    cacheUpper = requestStart + (requestLength * conf.pages);

                    request.start = requestStart;
                    request.length = requestLength * conf.pages;

                    // Provide the same `data` options as DataTables.
                    if (typeof conf.data === 'function') {
                        // As a function it is executed with the data object as an arg
                        // for manipulation. If an object is returned, it is used as the
                        // data object to submit
                        var d = conf.data(request);
                        if (d) {
                            $.extend(request, d);
                        }
                    } else if ($.isPlainObject(conf.data)) {
                        // As an object, the data given extends the default
                        $.extend(request, conf.data);
                    }

                    return $.ajax({
                        "type": conf.method,
                        "url": conf.url,
                        "data": request,
                        "dataType": "json",
                        "cache": false,
                        "success": function(json) {
                            cacheLastJson = $.extend(true, {}, json);

                            if (cacheLower != drawStart) {
                                json.data.splice(0, drawStart - cacheLower);
                            }
                            if (requestLength >= -1) {
                                json.data.splice(requestLength, json.data.length);
                            }

                            drawCallback(json);
                        }
                    });
                } else {
                    json = $.extend(true, {}, cacheLastJson);
                    json.draw = request.draw; // Update the echo for each response
                    json.data.splice(0, requestStart - cacheLower);
                    json.data.splice(requestLength, json.data.length);

                    drawCallback(json);
                }
            }
        };

        // Register an API method that will empty the pipelined data, forcing an Ajax
        // fetch on the next draw (i.e. `table.clearPipeline().draw()`)
        $.fn.dataTable.Api.register('clearPipeline()', function() {
            return this.iterator('table', function(settings) {
                settings.clearCache = true;
            });
        });

        function datatable() {
            $('#tabel_data').dataTable().fnClearTable();
            $('#tabel_data').dataTable().fnDestroy();

            var deleted = $('#getdeleted').val();
            var urlget = "{{ url('/tabelglobalconfig') }}" + "/" + deleted;

            $('#tabel_data').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                deferRender: true,
                bStateSave: true,
                language: {
                    processing: '<i class="fas fa-sync-alt fa-spin fa-3x fa-fw"></i><span class="bg-lime color-palette">Loading......</span>'
                },
                ajax: $.fn.dataTable.pipeline({
                    url: urlget,
                    pages: 5 // number of pages to cache
                }),
                order: [
                    [1, 'asc']
                ],
                columns: [{
                        data: 'nama_config',
                        name: 'nama_config'
                    },
                    {
                        data: 'data_config',
                        name: 'data_config'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        }


        $(document).ready(function() {
            reqsent = false;
            datatable();
        });
    </script>
@stop
