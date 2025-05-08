@extends('layout.main')
@section('title', 'Tambah Menu')
@section('css')

@stop
@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="card card-solid col-lg-12">
            <button class="btn btn-sm btn-flat btn-info mb-2" onclick="addmenu()">Tambah menu</button>
            <div class="card-body pb-0">
                <table class="table table-bordered table-bordered table-sm">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Induk</th>
                            <th>Nama Menu</th>
                            <th>link</th>
                            <th>icon</th>
                            <th>aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($menus as $menu)
                            <tr>
                                <td></td>
                                <td>{{ $menu->induk }}</td>
                                <td>{{ $menu->nama }}</td>
                                <td>{{ $menu->link }}</td>
                                <td>{{ $menu->icon }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning btn-flat" id="editform"
                                        onclick="editmenu('{{ $menu->id }}')"> Edit </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </section>
    <!-- /.modal gudang-->
    <div class="modal fade" id="modal-menu" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="form-menu" method="post" class="form-horizontal" data-toggle="validator"
                    enctype="multipart/form-data">
                    {{ csrf_field() }} {{ method_field('POST') }}
                    <div class="modal-header">
                        <h3 class="modal-title"></h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                    </div>

                    <div class="modal-body">
                        <div class="box-body">
                            <div class="form-group">
                                <label>#</label>
                                <input type="text" class="form-control form-control-sm" id="id" name="id"
                                    autofocus required readonly>
                            </div>
                            <div class="form-group">
                                <label>Menu Induk</label>
                                <input type="text" class="form-control form-control-sm" id="induk" name="induk"
                                    autofocus required>
                            </div>
                            <div class="form-group">
                                <label>nama</label>
                                <input type="textarea" class="form-control form-control-sm" id="nama" name="nama"
                                    required>
                                <span class="help-block with-errors"></span>
                            </div>
                            <div class="form-group">
                                <label>link</label>
                                <input type="textarea" class="form-control form-control-sm" id="link" name="link"
                                    required>
                                <span class="help-block with-errors"></span>
                            </div>
                            <div class="form-group">
                                <label>icon</label>
                                <input type="textarea" class="form-control form-control-sm" id="icon" name="icon"
                                    value="far fa-circle nav-icon" required>
                                <span class="help-block with-errors"></span>
                            </div>

                        </div>
                        <!-- /.box-body -->

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="tambaheditmenu()">Simpan</button>
                    </div>

                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@stop
@section('script')

    <script src="{{ asset('lte/sweetalert2/sweetalert2@11.js') }}"></script>
    <script type="text/javascript">
        function addmenu() {
            $('#modal-menu').modal('show');
            $('#modal-menu form')[0].reset();
            $('.modal-title').text('Tambah menu');
        }

        function editmenu(id) {
            $('#modal-menu form')[0].reset();
            $.ajax({
                url: "{{ url('menu') }}" + '/' + id + '/edit',
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#modal-menu').modal('show');
                    $('.modal-title').text('Edit menu');
                    $('#id').val(data.id);
                    $('#induk').val(data.induk);
                    $('#nama').val(data.nama);
                    $('#link').val(data.link);
                    $('#icon').val(data.icon);
                },
                error: function() {
                    alert("Tidak ada data");
                }
            });
        }

        function tambaheditmenu() {
            var data = $('#form-menu').serialize();
            $.ajax({
                url: "{{ url('tambaheditmenu') }}",
                method: "POST",
                data: data,
                success: function(data) {
                    if (data == 1) {
                        $('#modal-menu').modal('hide');
                        Swal.fire({
                            position: 'top-end',
                            title: 'BERHASIL',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 900
                        }).then(function() {
                            location.reload();
                        });
                    } else {
                        alert('tidak ada data');
                    }
                },
                error: function(data) {
                    alert('error');
                }
            });
        }

        function deletemenu(id) {
            var _token = $('#hapusmenu').data("token");
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
                    $.ajax({
                        url: "{{ url('menu') }}" + '/' + id,
                        type: "POST",
                        data: {
                            '_method': 'DELETE',
                            '_token': _token
                        },
                        success: function(data) {
                            Swal.fire({
                                position: 'top-end',
                                title: data,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 900
                            }).then(function() {
                                locationreload();
                            });
                        },
                        error: function() {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'error',
                                title: data,
                                showConfirmButton: false,
                                timer: 900
                            })
                        }
                    });
                }
            })
        }
    </script>

@stop
