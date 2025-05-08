<div class="modal fade" id="modal-form-merkbarang" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-merkbarang" method="post" class="form-horizontal" data-toggle="validator"
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
                                <label for="nama_merkbarang" class="col-sm-4 col-form-label">Nama Merk Barang</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control form-control-sm" id="nama_merkbarang"
                                        name="nama_merkbarang" autofocus>
                                    <div id="errornama_merkbarang" class="invalid-feedback">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="AddOrEditForm"
                        onclick="AddOrEditMerkbarang()">Simpan</button>
                </div>

            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
