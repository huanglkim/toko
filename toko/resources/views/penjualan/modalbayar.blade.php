<div class="modal fade" id="bayarModal" tabindex="-1" role="dialog" aria-labelledby="bayarlModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-bayar">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="cartModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <b>Total Bayar:</b>
                            </div>
                            <div class="col-sm-6 pb-4">
                                <b>
                                    <div class="col text-right" id="total">
                                    </div>
                                </b>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3">
                                <a class="col-12 text-right btn btn-sm bg-info btn-dark" id="kas-btn">TUNAI :
                                </a>
                            </div>
                            <div class="col-lg-4">
                                <select data-column="kode_acc_kas" onchange="fastupdate(this)"
                                    data-id="{{ $pjhd->id }}" class="form-control form-control-sm"
                                    name="kode_acc_kas" id="kode_acc_kas">
                                    @foreach (acckas() as $ab)
                                        <option value="{{ $ab->kode_acc }}"
                                            {{ $ab->kode_acc == $pjhd->kode_acc_kas ? 'selected' : '' }}>
                                            {{ $ab->nama_acc }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-5">
                                <input data-column="kas" onchange="fastupdate(this);" onkeyup="hitungkembalian();"
                                    data-id="{{ $pjhd->id }}" type="text"
                                    class="form-control form-control-sm text-right" id="kas" name="kas"
                                    value="{{ $pjhd->kas + 0 }}" autocomplete="off">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-3">
                                <a class="col-12 text-right btn btn-sm bg-info btn-dark" id="bank-btn">TF
                                    :</a>
                            </div>
                            <div class="col-sm-4">
                                <select data-column="kode_acc_bank" onchange="fastupdate(this)"
                                    data-id="{{ $pjhd->id }}" class="form-control form-control-sm"
                                    name="kode_acc_bank" id="kode_acc_bank">
                                    @foreach (accbank() as $ak)
                                        <option value="{{ $ak->kode_acc }}"
                                            {{ $ak->kode_acc == $pjhd->kode_acc_bank ? 'selected' : '' }}>
                                            {{ $ak->nama_acc }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-5">
                                <input data-column="bank" onchange="fastupdate(this);" data-id="{{ $pjhd->id }}"
                                    type="text" class="form-control form-control-sm text-right" id="bank"
                                    onkeyup="hitungkembalian();" name="bank" value="{{ $pjhd->bank + 0 }}"
                                    onchange="fastupdate(this)" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-sm-6">
                                <b>KEMBALIAN :</b>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" style="text-align:right;" class="form-control" readonly
                                    id="kembalian" name="kembalian" value="0">
                            </div>
                        </div>
                    </div>
                </div>

            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="simpanksr"
                    onclick="simpan({{ $pjhd->id }})">BAYAR</button>
            </div>
        </div>
    </div>
</div>
