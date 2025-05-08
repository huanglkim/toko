<div class="modal fade" id="modal-form" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
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
                                {!! Form::select('group', ['1' => 'NON', '2' => 'PJK'], '1', ['class' => 'form-control form-control-sm select', 'id' => 'group', 'name' => 'group', 'required']) !!}
                            </div>
                        </div>
                        <div class="form-row">
                            <label for="status" class="col-sm-4 col-form-label">Status</label>
                            <div class="col-sm-8">
                                {!! Form::select('status', ['1' => 'Aktif', '0' => 'Tidak Aktif'], '1', ['class' => 'form-control form-control-sm select', 'id' => 'status', 'name' => 'status', 'required']) !!}
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="AddOrEditForm"
                        onclick="AddOrEdit()">Simpan</button>
                </div>

            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
