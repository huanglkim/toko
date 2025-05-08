@extends('layout.main')
@section('title', 'Data Pelanggan')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/lte/DataTables/datatables.min.css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .table-responsive-sm {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
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
        <div class="row">
            <div class="card col-md-12">
                <div class="mb-4">
                    <h2 class="text-primary">
                        <i class="fas fa-handshake mr-2"></i> DAFTAR PELANGGAN
                    </h2>
                    <div class="card">
                        <div class="card-body pb-1 pt-1">
                            <div class="d-flex flex-wrap gap-2 justify-content-end mb-3">
                                <button type="button" onclick="addForm()" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-plus-square me-1"></i> Tambah Pelanggan
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-success" data-toggle="modal"
                                    data-target="#importExcel">
                                    <i class="fas fa-file-import me-2"></i> Import Excel
                                </button>
                                <a href="{{ asset('pelanggan/export') }}" class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-download me-2"></i> Download Excel
                                </a>
                                <a href="{{ url('/') }}/toko/public/file_pelanggan/ContohPelanggan.xlsx"
                                    class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-download me-2"></i> Contoh Excel
                                </a>
                                <button class="btn btn-sm btn-outline-danger" onclick="marge()">
                                    <i class="fas fa-sitemap me-2"></i> Marge Pelanggan
                                </button>
                                @if (Auth()->User()->id == 1)
                                    <button class="btn btn-sm btn-outline-warning" onclick="repairwa()">
                                        <i class="fas fa-wrench me-2"></i> Perbaiki WA
                                    </button>
                                @endif
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="callout-panel">
                                        <h6 class="callout-panel-title">Filter Status Hapus</h6>
                                        <select class="custom-select custom-select-sm" onchange="datatable();"
                                            id="getdeleted" name="getdeleted">
                                            <option value="1" selected>Tidak Dihapus</option>
                                            <option value="2">Dihapus</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="callout-panel">
                                        <h6 class="callout-panel-title">Status</h6>
                                        <select class="custom-select custom-select-sm"
                                            onchange="datatable(); saveValuegetstatus(this);" id="getstatus"
                                            name="getstatus">
                                            <option value="1" selected>🟢 Aktif</option>
                                            <option value="0">🔴 Tidak Aktif</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="callout-panel">
                                        <h6 class="callout-panel-title">Group R/G</h6>
                                        <select class="custom-select custom-select-sm"
                                            onchange="datatable(); saveValuegetgroup(this);" id="getgroup" name="getgroup">
                                            <option value="0" selected>Semua Group</option>
                                            <option value="1">Reguler</option>
                                            <option value="2">Grosir</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                @if ($errors->has('file'))
                                    <div class="alert alert-danger">
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('file') }}</strong>
                                        </span>
                                    </div>
                                @endif
                                @if ($sukses = Session::get('sukses'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <strong>{{ $sukses }}</strong>
                                    </div>
                                @endif
                            </div>

                            <div class="table-responsive-sm">
                                <table class="table table-bordered table-hover table-sm" id="tabel_data">
                                    <thead class="thead-light">
                                        <tr class="table-success">
                                            <th>Pilih</th>
                                            <th>Kode</th>
                                            <th>Nama</th>
                                            <th>Wa</th>
                                            <th>Alamat</th>
                                            <th>Kota</th>
                                            <th style="width: 120px;">Aksi</th>
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
        </div>
    </section>
    @include('pelanggan.form')
    @include('pelanggan.modalimport')
@stop

@section('script')
    <script type="text/javascript" src="{{ url('/') }}/lte/DataTables/datatables.min.js"></script>
    <script src="{{ asset('lte/sweetalert2/sweetalert2@11.js') }}"></script>
    <script type="text/javascript">
        function repairwa() {
            Swal.fire({
                title: 'Anda yakin?',
                text: "WA AKAN DI REPAIR....!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'YA, JADIKAN SATU!'
            }).then((result) => {
                if (result.value) {
                    if (reqsent == false) {
                        reqsent = true;
                        $.ajax({
                            url: "{{ url('repairwapelanggan') }}",
                            type: 'get',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(data) {
                                console.log(data);
                                reqsent = false;
                                if (data.success == 1) {
                                    Swal.fire({
                                        position: 'top-end',
                                        title: data.pesan,
                                        icon: 'success',
                                        showConfirmButton: false,
                                        timer: 800
                                    }).then(function() {
                                        datatable();
                                    });
                                } else {
                                    alert('Ada kesalahan!!');
                                }
                            },
                            error: function(data) {
                                reqsent = false;
                                console.log(data.responseText);
                            }
                        });

                    }
                }
            })
        }
    </script>
    <script type="text/javascript">
        function marge() {
            Swal.fire({
                title: 'Anda yakin?',
                text: "DATA PELANGGAN AKAN DI MARGE....!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'YA, JADIKAN SATU!'
            }).then((result) => {
                if (result.value) {
                    if (reqsent == false) {
                        var allVals = [];
                        $(".sub_chk2:checked").each(function() {
                            allVals.push($(this).attr('data-id'));
                        });
                        if (allVals.length <= 1) {
                            alert("Pilih Pelanggan Terlebih dahulu MINIMAL 2 PELANGGAN");
                        } else {
                            reqsent = true;
                            var join_selected_values = allVals.join(",");
                            $.ajax({
                                url: "{{ url('margepelanggan') }}",
                                type: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: 'ids=' + join_selected_values,
                                success: function(data) {
                                    console.log(data);
                                    reqsent = false;
                                    if (data.success == 1) {
                                        Swal.fire({
                                            position: 'top-end',
                                            title: data.pesan,
                                            icon: 'success',
                                            showConfirmButton: false,
                                            timer: 800
                                        }).then(function() {
                                            datatable();
                                        });
                                    } else {
                                        alert('Ada kesalahan!!');
                                    }
                                },
                                error: function(data) {
                                    reqsent = false;
                                    console.log(data.responseText);
                                }
                            });
                        }
                    }
                }
            })
        }
    </script>
    <script type="text/javascript">
        $('#modal-form-pelanggan').on('shown.bs.modal', function() {
            $('#kode').focus();
        });

        function addForm() { //form tambah show
            save_method = "POST";
            reqsent = false;
            $('input[name=_method]').val('POST');
            $('#modal-form-pelanggan').modal('show');
            $('#modal-form-pelanggan form')[0].reset();
            $('.modal-title').text('Tambah Pelanggan');
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
            $('#modal-form-pelanggan form')[0].reset();
            $.ajax({
                url: "{{ url('pelanggan') }}" + '/' + id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    if (data.success == 1) {
                        $('#modal-form-pelanggan').modal('show');
                        $('.modal-title').text('Edit User');
                        $('#id').val(data.id);
                        $('#kode').val(data.kode);
                        $('#nama').val(data.nama);
                        $('#alamat').val(data.alamat);
                        $('#wa').val(data.wa);
                        $('#kota').val(data.kota);
                        $('#group').val(data.group);
                        $('#status').val(data.status);
                    }
                    if (data.success == 0) {
                        $('#modal-form-pelanggan').modal('hide');
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
                        url: "{{ url('pelanggan') }}" + '/' + id,
                        type: "POST",
                        data: {
                            '_method': 'DELETE',
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            if (data.success == 1) {
                                $('#modal-form-pelanggan').modal('hide');
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
                        url: "{{ url('restorepelanggan') }}" + '/' + id,
                        type: "POST",
                        data: {
                            '_method': 'PATCH',
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            if (data.success == 1) {
                                $('#modal-form-pelanggan').modal('hide');
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

            var group = $('#getgroup').val();
            var status = $('#getstatus').val();
            var deleted = $('#getdeleted').val();

            var urlget = "{{ url('/tabelpelanggan') }}" + "/" + group + "/" + status + "/" + deleted;

            $('#tabel_data').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                deferRender: true,
                bStateSave: true,
                lengthMenu: [100, 10, 50, 75],
                language: {
                    processing: '<i class="fas fa-sync-alt fa-spin fa-3x fa-fw"></i><span class="bg-lime color-palette">Loading......</span>'
                },
                ajax: $.fn.dataTable.pipeline({
                    url: urlget,
                    pages: 5 // number of pages to cache
                }),
                order: [
                    [2, 'asc']
                ],
                columns: [{
                        data: 'pilih',
                        name: 'pilih'
                    },
                    {
                        data: 'kode',
                        name: 'kode'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'wa',
                        name: 'wa'
                    },
                    {
                        data: 'alamat',
                        name: 'alamat'
                    },
                    {
                        data: 'kota',
                        name: 'kota'
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

        function saveValuegetstatus(e) {
            var id = e.id; // get the sender's id to save it . 
            var val = e.value; // get the value. 
            localStorage.setItem(id, val); // Every time user writing something, the localStorage's value will override . 
        }

        function getSavedValuegetstatus(v) {
            if (!localStorage.getItem(v)) {
                return "1"; // You can change this to your defualt value. 
            }
            return localStorage.getItem(v);
        }

        function saveValuegetgroup(e) {
            var id = e.id; // get the sender's id to save it . 
            var val = e.value; // get the value. 
            localStorage.setItem(id, val); // Every time user writing something, the localStorage's value will override . 
        }

        function getSavedValuegetgroup(v) {
            if (!localStorage.getItem(v)) {
                return "1"; // You can change this to your defualt value. 
            }
            return localStorage.getItem(v);
        }
        $(document).ready(function() {
            document.getElementById("getgroup").value = getSavedValuegetgroup("getgroup");
            document.getElementById("getstatus").value = getSavedValuegetstatus("getstatus");
            datatable();
            reqsent = false;
        });
    </script>
@stop
