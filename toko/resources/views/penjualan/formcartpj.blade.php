<div class="modal fade" id="modalcartpj" tabindex="-1" role="dialog" aria-labelledby="cartlModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-addcartpj" method="post" class="form-horizontal" data-toggle="validator">
                @csrf @method('POST')
                <div class="modal-header">
                    <h5 class="modal-title" id="cartModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-sm-12">
                                <textarea type="text" class="form-control form-control-sm" id="addcartnama_barang" name="addcartnama_barang"
                                    readonly></textarea>

                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="addcart_barang_id" id="addcart_barang_id">
                        <div class="form-row">
                            <div class="col-sm-5">
                                <b>QTY :</b>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" class="form-control form-control-sm" id="addcartqty"
                                    name="addcartqty" value="1" style="text-align:right;" onfocus="this.select();"
                                    autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btn-simpan-cart" onclick="AddCartBarang()">[insert]
                    TAMBAH</button>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('addcartqty').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault(); // Prevents the default action of the Enter key
            // Add your custom JavaScript logic here
            AddCartBarang();
        }
    });
</script>
