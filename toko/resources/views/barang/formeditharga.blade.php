<div class="modal fade" id="modal-editharga" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-editharga" method="post" class="form-horizontal" data-toggle="validator"
                enctype="multipart/form-data">
                {{ csrf_field() }} {{ method_field('POST') }}
                <div class="modal-header">
                    <h3 class="modal-title"></h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_editharga" name="id_editharga">
                    <div class="box-body">
                        <div class="form-row">
                            <label for="harga1_edit" class="col-sm-4 col-form-label">Harga 1</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm" id="harga1_edit"
                                    name="harga1_edit" onfocus="this.select()">
                                <span class="help-block with-errors"></span>
                                <div id="errorharga1_edit" class="invalid-feedback">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <label for="harga2_edit" class="col-sm-4 col-form-label">Harga 2</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm" id="harga2_edit"
                                    name="harga2_edit">
                                <span class="help-block with-errors"></span>
                                <div id="errorharga2_edit" class="invalid-feedback">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="AddOrEditharga()">Simpan</button>
                </div>

            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
