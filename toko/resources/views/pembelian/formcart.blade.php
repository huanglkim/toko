<div class="modal fade" id="cartModalpb" tabindex="-1" role="dialog" aria-labelledby="cartlModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-cartpb" method="post" class="form-horizontal" data-toggle="validator">
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
                            <div class="col-sm-4">
                                <b>NAMA :</b>
                            </div>
                            <div class="col-sm-8">
                                <textarea type="text" class="form-control form-control-sm" id="nama_barang" name="nama_barang" readonly></textarea>

                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="cart_barang_id" id="cart_barang_id">
                        <input type="hidden" name="cart_id" id="cart_id">
                        <div class="form-row">
                            <div class="col-sm-5">
                                <b>QTY :</b>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" class="form-control form-control-sm" id="qty" name="qty"
                                    value="1" style="text-align:right;" onfocus="this.select();"
                                    autocomplete="off">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-sm-5">
                                <b>HARGA (@) :</b>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group mb-2">
                                    <div class="input-group input-group-sm">

                                        <div class="input-group-prepend">
                                            <div class="input-group-text">Rp.</div>
                                        </div>
                                        <input type="text" class="form-control form-control-sm"
                                            onkeyup="rupiahjs(this); hitungtotal_harga();" data-asli="harga_bruto"
                                            id="rpharga_bruto" value="0" style="text-align:right;"
                                            onfocus="this.select();" autocomplete="off">
                                    </div>
                                </div>
                                <input type="hidden" class="form-control form-control-sm" id="harga_bruto"
                                    name="harga_bruto" value="0" style="text-align:right;" onfocus="this.select();"
                                    autocomplete="off">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-sm-5">
                                <b>TOTAL HARGA:</b>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group mb-2">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">Rp.</div>
                                        </div>
                                        <input type="text" class="form-control"
                                            onkeyup="rupiahjs(this); hitungharga();" data-asli="total_harga"
                                            id="rptotal_harga" value="0" style="text-align:right;"
                                            onfocus="this.select();" autocomplete="off">
                                    </div>

                                </div>
                                <input type="hidden" class="form-control form-control-sm" id="total_harga"
                                    name="total_harga" value="0" style="text-align:right;" onfocus="this.select();"
                                    autocomplete="off">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-sm-5">
                                <b>POT. PERSEN:</b>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group mb-2">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">% .</div>
                                        </div>
                                        <input type="text" class="form-control" onkeyup=" hitungharga();"
                                            data-asli="potpersen" id="potpersen" name="potpersen" value="0"
                                            style="text-align:right;" onfocus="this.select();" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-sm-5">
                                <b>POT. RP :</b>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group mb-2">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"> Rp.</div>
                                        </div>
                                        <input type="text" class="form-control"
                                            onkeyup="rupiahjs(this); hitungharga();" data-asli="potrp" id="rppotrp"
                                            value="0" style="text-align:right;" onfocus="this.select();"
                                            autocomplete="off">
                                    </div>
                                </div>
                                <input type="hidden" class="form-control form-control-sm" id="potrp"
                                    name="potrp" value="0" style="text-align:right;" onfocus="this.select();"
                                    autocomplete="off">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-sm-5">
                                <b>TOTAL POT :</b>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group mb-2">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"> Rp.</div>
                                        </div>
                                        <input type="text" class="form-control" readonly data-asli="total_pot"
                                            id="rptotal_pot" value="0" style="text-align:right;"
                                            onfocus="this.select();" autocomplete="off">
                                    </div>
                                </div>
                                <input type="hidden" class="form-control form-control-sm" id="total_pot"
                                    name="total_pot" value="0" style="text-align:right;"
                                    onfocus="this.select();" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-sm-5">
                                <b>HARGA SET POT (@) :</b>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group mb-2">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"> Rp.</div>
                                        </div>
                                        <input type="text" class="form-control" readonly data-asli="harga_netto"
                                            id="rpharga_netto" value="0" style="text-align:right;"
                                            onfocus="this.select();" autocomplete="off">
                                    </div>
                                </div>
                                <input type="hidden" class="form-control form-control-sm" id="harga_netto"
                                    name="harga_netto" value="0" style="text-align:right;"
                                    onfocus="this.select();" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-sm-5">
                                <b>TOTAL HARGA SET POT:</b>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group mb-2">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"> Rp.</div>
                                        </div>
                                        <input type="text" class="form-control" readonly
                                            data-asli="total_harga_netto" id="rptotal_harga_netto" value="0"
                                            style="text-align:right;" onfocus="this.select();" autocomplete="off">
                                    </div>
                                </div>
                                <input type="hidden" class="form-control form-control-sm" id="total_harga_netto"
                                    name="total_harga_netto" value="0" style="text-align:right;"
                                    onfocus="this.select();" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btn-simpan-cart"
                    onclick="AddCartBarang()">[insert] TAMBAH</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal bayar -->
<script>
    function hitungharga() {
        var total_harga = document.getElementById('total_harga').value;
        var qty = document.getElementById('qty').value;
        var harga = total_harga / qty;
        document.getElementById('harga_bruto').value = harga;
        document.getElementById('rpharga_bruto').value = strtorp(harga);
        rumuspot()
    }

    function hitungtotal_harga() {
        var harga = document.getElementById('harga_bruto').value;
        var qty = document.getElementById('qty').value;
        var total_harga = harga * qty;
        document.getElementById('total_harga').value = total_harga;
        document.getElementById('rptotal_harga').value = strtorp(total_harga);
        rumuspot();
    }

    function rumuspot() {
        var qty = document.getElementById('qty').value;
        var total_harga = document.getElementById('total_harga').value;
        var potrp = document.getElementById('potrp').value;
        var potpersen = document.getElementById('potpersen').value;

        var total_pot = parseInt(potrp) + (parseInt(total_harga) * potpersen / 100);
        var total_harga_netto = parseInt(total_harga) - parseInt(total_pot);
        var harga_netto = total_harga_netto / qty;

        document.getElementById('rptotal_pot').value = strtorp(total_pot);
        document.getElementById('total_pot').value = total_pot;
        document.getElementById('rptotal_harga_netto').value = strtorp(total_harga_netto);
        document.getElementById('total_harga_netto').value = total_harga_netto;
        document.getElementById('rpharga_netto').value = strtorp(harga_netto);
        document.getElementById('harga_netto').value = harga_netto;
    }
</script>
