<div class="row mb-3" id="pagecaribarang">
    <div class="col-md-3 col-lg-2">
        <a class="btn btn-sm btn-outline-primary w-100" data-toggle="collapse" href="#tabelcaribarang" role="button"
            aria-expanded="false" aria-controls="tabelcaribarang" onclick="caritabelbarang();" id="btn-cari-barang">
            <i class="fas fa-search mr-2"></i> Cari Barang [F2]
        </a>
    </div>
    <div class="col-md-6 col-lg-7" id="caribarang">
        <select class="form-control selectpicker" name="barang_id" data-size="7"
            data-live-search="true" data-title="Cari Nama Barang" id="barang_id" data-width="100%">
        </select>
    </div>
    <div class="col-md-3 col-lg-3">
        <input type="text" class="form-control" id="caribarcode" name="caribarcode"
            autocomplete="off" placeholder="Cari Barcode">
    </div>
</div>
<div class="collapse row table-responsive" id="tabelcaribarang">
    <div class="col-12"> <table class="table table-bordered table-sm table-hover nowrap" id="tabel_data_barang">
            <thead>
                <tr class="table-light"> <th>Pilih</th>
                    <th>Barcode</th>
                    <th>Kode Internal</th>
                    <th>Nama Barang</th>
                    <th>Stok</th>
                    <th>Harga Jual</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
