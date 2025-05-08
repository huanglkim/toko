<div class="modal fade" id="cartModal" tabindex="-1" role="dialog" aria-labelledby="cartlModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-cart" method="post" class="form-horizontal" data-toggle="validator">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="cartModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control form-control-sm col-lg-1 col-6" name="id"
                        id="id" readonly>
                    <input type="hidden" class="col-lg-1 col-6" name="cart_barang_id" id="cart_barang_id" readonly>
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-sm-4">
                                <b>Nama :</b>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm" id="nama_barang"
                                    name="nama_barang" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">

                        <div class="form-row">
                            <div class="col-sm-4">
                                <b>Qty :</b>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control form-control-sm" id="qty" name="qty"
                                    value="1" style="text-align:right;" onfocus="this.select();"
                                    autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="AddOrEditcart()">Simpan</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal bayar -->
