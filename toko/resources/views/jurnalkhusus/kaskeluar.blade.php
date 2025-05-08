@extends('layout.main')
@section('title', 'Kas Keluar')
@section('css')
    <link href="{{ url('/') }}/lte/plugins/datepicker/css/bootstrap-datepicker.min.css" type="text/css"
        rel="stylesheet" />
    <link href="{{ url('/') }}/lte/css/select2.min.css" rel="stylesheet" />
    <link href="{{ url('/') }}/lte/css/selectpicker.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/lte/DataTables/datatables.min.css" />

@stop
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">KAS KELUAR</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="form-kaskeluar">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-row">
                                        <label for="tanggal" class="col-sm-4 col-form-label">Tanggal</label>
                                        <div class="col-sm-8">
                                            <input type="hidden" id="id" name="id">
                                            <input type="hidden" id="tipe" name="tipe" value="KK">
                                            <input type="hidden" id="posisi" name="posisi" value="K">
                                            <input type="text" class="form-control form-control-sm datepicker"
                                                value="{{ Date('d-m-Y') }}" id="tanggal" name="tanggal">
                                            <div id="errortanggal" class="invalid-feedback">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <label for="kode_acc" class="col-sm-4 col-form-label">Keluar Dari</label>
                                        <div class="col-sm-8">
                                            <select class="form-control form-control-sm selectpicker" name="kode_acc"
                                                id="kode_acc" data-live-search="true">
                                                @foreach ($KasBank as $kk)
                                                    <option value="{{ $kk->kode_acc }}">{{ $kk->nama_acc }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <label for="kode_lawan" class="col-sm-4 col-form-label">Untuk</label>
                                        <div class="col-sm-8">
                                            <select class="form-control form-control-sm select selectpicker"
                                                name="kode_lawan" id="kode_lawan" data-live-search="true">
                                                @foreach ($KodeAcc as $index => $ka)
                                                    <option value="{{ $ka->kode_acc }}">
                                                        {{ $ka->kode_acc }}|{{ $ka->nama_acc }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <label for="jumlah" class="col-sm-4 col-form-label">Jumlah</label>
                                        <div class="col-sm-8">
                                            <input type="number" class="form-control form-control-sm text-right"
                                                id="jumlah" name="jumlah" autofocus autocomplete="off">
                                            <div id="errorjumlah" class="invalid-feedback">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label for="keterangan" class="col-sm-4 col-form-label">Keterangan :</label>
                                    <div class="form-row">
                                        <div class="col-sm-12">
                                            <textarea id="keterangan" name="keterangan" class="form-control" rows="2"></textarea>
                                            <div id="errorketerangan" class="invalid-feedback">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row mt-2 float-right">
                                        <button type="button" class="btn btn-primary " onclick="AddOrEdit()"><i
                                                class="fas fa-plus-circle"></i> SIMPAN</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="card col-sm-12 table-responsive">
                <table class="table table-bordered table-sm table-hover" id="tabel_data">
                    <thead>
                        <tr class="table-info">
                            <th>Invoice</th>
                            <th>Tanggal</th>
                            <th>Keluar Dari</th>
                            <th>Untuk</th>
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

    </section>
@stop
@section('script')
    <script src="{{ asset('lte/sweetalert2/sweetalert2@11.js') }}"></script>
    <script src="{{ url('/') }}/lte/plugins/datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="{{ url('/') }}//lte/js/select2.min.js"></script>
    <script src="{{ url('/') }}/lte/js/selectpicker.js"></script>
    <script type="text/javascript" src="{{ url('/') }}/lte/DataTables/datatables.min.js"></script>
    @include('jsblade.datatablejs')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            reqsent = false;
            datatable();
        }, false);

        function datatable() {
            $('#tabel_data').dataTable().fnClearTable();
            $('#tabel_data').dataTable().fnDestroy();
            var tipe = $('#tipe').val();
            var urlget = "{{ url('/tabeljurnalkhusus') }}";
            $('#tabel_data').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                deferRender: true,
                bStateSave: true,
                retrieve: true,
                searchHighlight: true,
                lengthMenu: [100, 10, 50, 75, 500, 1000],
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
                        tipe: tipe,
                    },
                }),
                order: [
                    [0, 'DESC']
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
                        data: 'accperkiraan.nama_acc',
                        name: 'accperkiraan.nama_acc',
                    },
                    {
                        data: 'accperkiraanlawan.nama_acc',
                        name: 'accperkiraanlawan.nama_acc',
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
        function AddOrEdit() {
            if (reqsent == false) {
                var id = document.getElementById('id').value;
                if (id == null || id == false || id == '') {
                    var url = "{{ url('/tambahjurnalkas') }}";
                    var method = 'POST';
                } else {
                    var url = "{{ url('/updatejurnalkas') . '/' }}" + id;
                    var method = 'PATCH';
                }
                var data = $('#form-kaskeluar').serialize() + "&_method=" + method;
                $.ajax({
                    url: url,
                    timeout: 2500,
                    method: "POST",
                    data: data,
                    success: function(data) {
                        document.getElementById("form-kaskeluar").reset();
                        reqsent = false;
                        if (data.success == 1) {
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
                                    title: data.pesan,
                                    icon: 'error',
                                    showConfirmButton: false,
                                    timer: 900
                                }).then(function() {

                                });
                            } else {
                                if (data.jumlah) {
                                    $('#jumlah').addClass('is-invalid');
                                    $('#errorjumlah').html(data.jumlah);
                                }
                                if (data.tanggal) {
                                    $('#tanggal').addClass('is-invalid');
                                    $('#errortanggal').html(data.tanggal);
                                }
                                if (data.keterangan) {
                                    $('#keterangan').addClass('is-invalid');
                                    $('#errorketerangan').html(data.keterangan);
                                }
                            }
                        }
                    },
                    error: function(data) {
                        reqsent = false;
                        if (status == "timeout") {
                            // timeout -> reload the page and try again
                            window.location.reload(); //make it comment if you don't want to reload page
                        } else {
                            // another error occured  
                            console.log(data);
                        }
                    }
                });
            }
        };

        function edit(id) {
            Swal.fire({
                title: 'Anda yakin?',
                text: "YAKIN UNTUK EDIT KAS!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'YA. EDIT'
            }).then((result) => {
                if (result.value) {
                    //open login
                    $.ajax({
                        url: "{{ url('/editjurnalkas') }}" + "/" + id,
                        timeout: 2500,
                        method: "GET",
                        success: function(data) {
                            console.log(data);
                            document.getElementById('id').value = data.id;
                            document.getElementById('tanggal').value = data.tanggal;
                            document.getElementById('kode_acc').value = data.kode_acc;
                            $('#kode_acc').selectpicker('refresh');
                            $('#kode_lawan').selectpicker('refresh');
                            document.getElementById('kode_lawan').value = data.kode_lawan;
                            document.getElementById('tipe').value = data.tipe;
                            document.getElementById('jumlah').value = data.jumlah;
                            document.getElementById('keterangan').value = data.keterangan;
                        },
                        error: function(request, status, err) {
                            if (status == "timeout") {
                                // timeout -> reload the page and try again
                                window.location
                                    .reload(); //make it comment if you don't want to reload page
                            } else {
                                // another error occured  
                                alert("error: " + request + status + err);
                            }
                        }
                    });
                }
            })
        }

        function hapus(id) {
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
                        url: "{{ url('hapusjurnalkas') }}" + '/' + id,
                        type: "POST",
                        data: {
                            '_method': 'DELETE',
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            if (data.success == 1) {
                                $('#modal-form').modal('hide');
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
                                    location.reload();
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
@stop
