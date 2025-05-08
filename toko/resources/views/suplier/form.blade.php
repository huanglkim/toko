<div class="modal fade" id="modal-form-suplier" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-suplier" method="post" class="form-horizontal" data-toggle="validator"
                enctype="multipart/form-data">
                {{ csrf_field() }} {{ method_field('POST') }}
                <div class="modal-header">
                    <h3 class="modal-title"></h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id" name="id">
                    <div class="box-body">
                        <div class="form-group">
                            <div class="form-row">
                                <label for="kode" class="col-sm-4 col-form-label">Kode Suplier</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control form-control-sm" id="kode"
                                        name="kode" autofocus>
                                    <div id="errorkode" class="invalid-feedback">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-row">
                                <label for="nama" class="col-sm-4 col-form-label">Nama</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control form-control-sm" id="nama"
                                        name="nama" autofocus>
                                    <div id="errornama" class="invalid-feedback">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <label for="alamat" class="col-sm-4 col-form-label">Alamat</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm" id="alamat"
                                    name="alamat">
                                <div id="erroralamat" class="invalid-feedback">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <label for="kota" class="col-sm-4 col-form-label">Kota</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm " id="kota"
                                    name="kota">
                                <div id="errorkota" class="invalid-feedback">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <label for="wa" class="col-sm-4 col-form-label">Wa</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm " id="wa"
                                    name="wa" placeholder="ex: 62812345678 (0 jadi 62)" required>
                                <span class="help-block with-errors"></span>
                                <div id="errorwa" class="invalid-feedback">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <label for="telp" class="col-sm-4 col-form-label">Telp</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm " id="telp"
                                    name="telp" required>
                                <span class="help-block with-errors"></span>
                                <div id="errortelp" class="invalid-feedback">
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <label for="group" class="col-sm-4 col-form-label">Group</label>
                            <div class="col-sm-8">
                                {!! Form::select('group', ['2' => 'PPN', '1' => 'NON PPN'], '2', [
                                    'class' => 'form-control form-control-sm select',
                                    'id' => 'group',
                                    'name' => 'group',
                                    'required',
                                ]) !!}
                            </div>
                        </div>
                        <div class="form-row">
                            <label for="status" class="col-sm-4 col-form-label">Status</label>
                            <div class="col-sm-8">
                                {!! Form::select('status', ['1' => 'Aktif', '0' => 'Tidak Aktif'], '1', [
                                    'class' => 'form-control form-control-sm select',
                                    'id' => 'status',
                                    'name' => 'status',
                                    'required',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="AddOrEditsup()">Simpan</button>
                </div>

            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script>
    function formsuplier() { //form tambah show
        $('#modal-form-suplier').modal('show');
        $('#modal-form-suplier form')[0].reset();
        $('.modal-title').text('Tambah Suplier');
    }

    function AddOrEditsup() { //tambah atau edit //store update
        if (reqsent == false) {
            reqsent = true;
            var id = $('#id').val();
            var url = "{{ url('suplier') }}";
            var data = $('input[name!=_method]', '#form-suplier').serialize();
            // console.log(data);
            // return false;
            $.ajax({
                url: url,
                method: "POST",
                data: data,
                success: function(data) {
                    reqsent = false;
                    if (data.success == 1) {
                        $('#modal-form-suplier').modal('hide');
                        Swal.fire({
                            position: 'top-end',
                            title: data.pesan,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 900
                        }).then(function() {
                            // document.getElementById('suplier_id').value = data.id_sup;
                            // ubahtitle(data.nama_sup);
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
                            if (data.kode) {
                                $('#kode').addClass('is-invalid');
                                $('#errorkode').html(data.kode);
                            }
                            if (data.nama) {
                                $('#nama').addClass('is-invalid');
                                $('#errornama').html(data.nama);
                            }
                            if (data.alamat) {
                                $('#alamat').addClass('is-invalid');
                                $('#erroralamat').html(data.alamat);
                            }
                            if (data.kota) {
                                $('#kota').addClass('is-invalid');
                                $('#errorkota').html(data.kota);
                            }
                            if (data.wa) {
                                $('#wa').addClass('is-invalid');
                                $('#errorwa').html(data.wa);
                            }
                            if (data.telp) {
                                $('#telp').addClass('is-invalid');
                                $('#errortelp').html(data.telp);
                            }
                        }
                    }
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }
    }

    function ubahtitle(nama_sup) {
        var selectpicker = $("#suplier_id");
        selectpicker.selectpicker();
        selectpicker.selectpicker({
            title: nama_sup
        }).selectpicker('render');
        html = '';
        selectpicker.html(html);
        selectpicker.selectpicker("refresh");
    }
</script>
