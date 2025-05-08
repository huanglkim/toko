<div class="modal fade" id="modal-form" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-barang" method="post" class="form-horizontal" data-toggle="validator"
                enctype="multipart/form-data">
                {{ csrf_field() }} {{ method_field('POST') }}
                <div class="modal-header">
                    <h3 class="modal-title"></h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_barang" name="id_barang">
                    <div class="box-body">
                        <div class="form-row">
                            <label for="minimum" class="col-sm-3 col-form-label-sm"> Nama Barang:
                            </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm" id="nama_barang"
                                    name="nama_barang" autofocus autocomplete="off" placeholder="Nama Barang">
                                <div id="errornama_barang" class="invalid-feedback">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <label for="barcode" class="col-sm-3 col-form-label-sm"> Kode Internal :
                            </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm" id="kode_internal"
                                    name="kode_internal" placeholder="kode_internal">
                                <div id="errorkode_internal" class="invalid-feedback">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <label for="barcode" class="col-sm-3 col-form-label-sm"> Barcode :
                            </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm" id="barcode" name="barcode"
                                    placeholder="barcode">
                                <div id="errorbarcode" class="invalid-feedback">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <label for="keterangan" class="col-sm-3 col-form-label-sm"> Keterangan :
                            </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm " id="keterangan"
                                    name="keterangan" placeholder="KETERANGAN">
                                <span class="help-block with-errors"></span>
                                <div id="errorketerangan" class="invalid-feedback">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <label for="satuan_id" class="col-sm-3 col-form-label-sm"> Satuan :
                            </label>
                            <div class="col-sm-7">
                                <select class="form-control-sm form-control selectpicker" id="satuan_id"
                                    name="satuan_id" data-live-search="true">
                                    @foreach ($satuan as $st)
                                        <option value="{{ $st->id }}"
                                            {{ $st->nama_satuan == 'PCS' ? 'selected' : '' }}>{{ $st->nama_satuan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <button onclick="formsatuan()" type="button"
                                    class="btn btn-sm bg-indigo color-palette btn-flat">
                                    <i class="fas fa-plus-square"> Baru</i>
                                </button>
                            </div>
                        </div>
                        <div class="form-row">
                            <label for="merkbarang_id" class="col-sm-3 col-form-label-sm"> MERK :
                            </label>
                            <div class="col-sm-7">
                                <select class="form-control-sm form-control selectpicker" id="merkbarang_id"
                                    name="merkbarang_id" data-live-search="true">
                                    @foreach ($merkbarang as $mb)
                                        <option value="{{ $mb->id }}"
                                            {{ $mb->nama_merkbarang == 'TANPA MERK' ? 'selected' : '' }}>
                                            {{ $mb->nama_merkbarang }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <button onclick="formmerkbarang()" type="button"
                                    class="btn btn-sm bg-indigo color-palette btn-flat">
                                    <i class="fas fa-plus-square"> Baru</i>
                                </button>
                            </div>
                        </div>
                        <div class="form-row">
                            <label for="jenisbarang_id" class="col-sm-3 col-form-label-sm"> Jenis Brng. :
                            </label>
                            <div class="col-sm-7">
                                <select class="form-control-sm form-control selectpicker" id="jenisbarang_id"
                                    name="jenisbarang_id" data-live-search="true">
                                    @foreach ($jenisbarang as $mb)
                                        <option value="{{ $mb->id }}"
                                            {{ $mb->nama_jenisbarang == 'TANPA MERK' ? 'selected' : '' }}>
                                            {{ $mb->nama_jenisbarang }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <button onclick="formjenisbarang()" type="button"
                                    class="btn btn-sm bg-indigo color-palette btn-flat">
                                    <i class="fas fa-plus-square"> Baru</i>
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <label for="carisuplier" class="col-sm-3 col-form-label-sm"> Suplier :
                            </label>
                            <div class="col-sm-7" id="carisuplier">
                                <select class="form-control form-control-sm selectpicker" name="suplier_id"
                                    data-size="7" data-live-search="true" data-title="TANPA SUPLIER"
                                    id="suplier_id" data-width="100%">
                                </select>
                            </div>

                            <div class="col-sm-2">
                                <button onclick="formsuplier()" type="button"
                                    class="btn btn-sm bg-indigo color-palette btn-flat">
                                    <i class="fas fa-plus-square"> Baru</i>
                                </button>
                            </div>
                        </div>
                        <div class="form-row">
                            <label for="minimum" class="col-sm-3 col-form-label-sm"> Minimum :
                            </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm" id="minimum"
                                    name="minimum" value="0" placeholder="MINIMUM">
                                <div id="errorminimum" class="invalid-feedback">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <label for="harga_beli_terakhir" class="col-sm-3 col-form-label-sm"> Harga Beli :
                            </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm " id="harga_beli_terakhir"
                                    name="harga_beli_terakhir" value="0" placeholder="HARGA POKOK">
                                <span class="help-block with-errors"></span>
                                <div id="errorharga_beli_terakhir" class="invalid-feedback">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <label for="harga_jual_dasar1" class="col-sm-3 col-form-label-sm"> Harga1 :
                            </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm " id="harga_jual_dasar1"
                                    name="harga_jual_dasar1" value="0" placeholder="Harga Jual 1">
                                <span class="help-block with-errors"></span>
                                <div id="errorharga_jual_dasar1" class="invalid-feedback">
                                </div>
                            </div>
                            <label for="harga_jual_dasar2" class="col-sm-3 col-form-label-sm"> Harga2 :
                            </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm " id="harga_jual_dasar2"
                                    name="harga_jual_dasar2" value="0" placeholder="Harga Jual 2">
                                <span class="help-block with-errors"></span>
                                <div id="errorharga_jual_dasar2" class="invalid-feedback">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <label for="status" class="col-sm-3 col-form-label-sm"> STATUS. :
                            </label>
                            <div class="col-sm-7">
                                <select class="form-control-sm form-control" id="status" name="status">
                                    <option value="1">AKTIF</option>
                                    <option value="0">NON AKTIF</option>
                                </select>
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
<div class="modal fade" id="modal-onlysup" tabindex="1" role="dialog" aria-hidden="true"
    data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-onlysup" method="post" class="form-horizontal" data-toggle="validator"
                enctype="multipart/form-data">
                {{ csrf_field() }} {{ method_field('POST') }}
                <div class="modal-header">
                    <h3 class="modal-title"></h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_barang2" name="id_barang2">
                    <div class="box-body">

                        <div class="from-row">
                            <div class="row">
                                <label for="carisuplier2" class="col-sm-3 col-form-label-sm"> Suplier :
                                </label>
                                <div class="col-sm-5" id="carisuplier2">
                                    <select class="form-control form-control-sm selectpicker" name="suplier_id2"
                                        data-size="7" data-live-search="true" data-title="" id="suplier_id2"
                                        data-width="100%">
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control form-control-sm" id="kode_sup2"
                                        name="kode_sup2" readonly>
                                </div>
                                <div class="col-sm-2">
                                    <button onclick="formsuplier()" type="button"
                                        class="btn btn-sm bg-indigo color-palette btn-flat">
                                        <i class="fas fa-plus-square"> Baru</i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="simpansuplier()">Simpan</button>
                </div>

            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
