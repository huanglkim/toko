<form id="form-datapj">
    <div class="row">
        <!-- Left Section -->
        <div class="col-md-4">
            <div class="form-row ml-1 pb-1">
                <label for="keterangan" class="col-md-12">Keterangan:</label>
                <textarea data-column="keterangan" onchange="fastupdate(this)" data-id="{{ $pjhd->id }}"
                    class="col-md-10 form-control form-control-sm" name="keterangan" id="keterangan" cols="25" rows="2">{{ $pjhd->keterangan }}</textarea>
            </div>
            <div class="form-row ml-1">
                <label for="jenisppn" class="col-5">Jenis PPN:</label>
                <select class="custom-select custom-select-sm col-5" id="jenisppn" name="jenisppn" onchange="updateppn()">
                    <option value="exclude" {{ $pjhd->jenisppn == 'exclude' ? 'selected' : '' }}>EXCLUDE</option>
                    <option value="include" {{ $pjhd->jenisppn == 'include' ? 'selected' : '' }}>INCLUDE</option>
                    <option value="NON" {{ $pjhd->jenisppn == 'NON' ? 'selected' : '' }}>NON PPN</option>
                </select>
            </div>
        </div>

        <!-- Right Section -->
        <div class="col-md-4">
            <div class="form-row justify-content-end">
                <label for="total" class="col-5 text-right">Sub Total:</label>
                <input type="text" class="form-control form-control-sm text-right col-7" id="total" name="total"
                    value="{{ Rupiah0($pjhd->total + 0) }}" readonly>
            </div>
            <div class="form-row justify-content-end">
                <label for="ppn" class="col-5 text-right">PPN:</label>
                <input type="text" class="form-control form-control-sm text-right col-2" id="persenpajak"
                    name="persenpajak" value="{{ $pjhd->persenpajak }}" onchange="updateppn()">
                <input type="text" class="form-control form-control-sm text-right col-5" id="ppn" name="ppn"
                    value="{{ rupiah0($pjhd->ppn + 0) }}" readonly>
            </div>
            <div class="form-row justify-content-end">
                <label for="dpp" class="col-5 text-right">DPP:</label>
                <input type="text" class="form-control form-control-sm text-right col-7" id="dpp" name="dpp"
                    value="{{ Rupiah0($pjhd->dpp + 0) }}" readonly>
            </div>
            <div class="form-row justify-content-end">
                <label for="potongan" class="col-5 text-right">Potongan:</label>
                <input data-column="potongan" onchange="fastupdate(this)" data-id="{{ $pjhd->id }}" type="text"
                    class="form-control form-control-sm text-right col-7" id="potongan" name="potongan"
                    value="{{ $pjhd->potongan + 0 }}" autocomplete="off">
            </div>
        </div>

        <!-- Payment Section -->
        <div class="col-md-4">
            <div class="form-row justify-content-end">
                <label for="totalakhir" class="col-5 text-right">Total akhir:</label>
                <input type="text" class="form-control form-control-sm text-right col-7" id="totalakhir"
                    name="totalakhir" value="{{ Rupiah0($totalakhir + 0) }}" readonly>
                <input type="hidden" name="harusbayar" id="harusbayar" value="{{ $totalakhir + 0 }}" readonly>
                <input type="hidden" id="kembalian" value="0">
            </div>
            <div class="form-row justify-content-end">
                <button class="pl-3 col-4 text-right btn btn-sm bg-info btn-dark" id="kas-btn">TUNAI :</button>
                <div class="col-4">
                    <select data-column="kode_acc_kas" onchange="fastupdate(this)" data-id="{{ $pjhd->id }}"
                        class="form-control form-control-sm" name="kode_acc_kas" id="kode_acc_kas">
                        @foreach (acckas() as $ab)
                            <option value="{{ $ab->kode_acc }}"
                                {{ $ab->kode_acc == $pjhd->kode_acc_kas ? 'selected' : '' }}>{{ $ab->nama_acc }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <input data-column="kas" onblur="fastupdate(this)" data-id="{{ $pjhd->id }}" type="text"
                    class="form-control form-control-sm text-right col-3" id="kas" name="kas"
                    value="{{ $pjhd->kas + 0 }}" autocomplete="off">
                <button class="ml-3 col-4 text-right btn btn-sm bg-info btn-dark" id="bank-btn">TF :</button>
                <div class="col-4">
                    <select data-column="kode_acc_bank" onchange="fastupdate(this)" data-id="{{ $pjhd->id }}"
                        class="form-control form-control-sm" name="kode_acc_bank" id="kode_acc_bank">
                        @foreach (accbank() as $ak)
                            <option value="{{ $ak->kode_acc }}"
                                {{ $ak->kode_acc == $pjhd->kode_acc_bank ? 'selected' : '' }}>{{ $ak->nama_acc }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <input data-column="bank" onchange="fastupdate(this)" data-id="{{ $pjhd->id }}" type="text"
                    class="form-control form-control-sm text-right col-3" id="bank" name="bank"
                    value="{{ $pjhd->bank + 0 }}" autocomplete="off">
            </div>
            <div class="form-row justify-content-end">
                <button class="ml-3 col-4 text-right btn btn-sm bg-info btn-dark" id="piutang-btn">PIUTANG :</button>
                <input data-column="piutang" onchange="fastupdate(this)" data-id="{{ $pjhd->id }}" type="text"
                    class="form-control form-control-sm text-right col-7" id="piutang" name="piutang"
                    value="{{ Rupiah0($pjhd->piutang + 0) }}" readonly>
            </div>
        </div>
    </div>
</form>

<!-- Tombol Aksi -->
<div class="row text-right pt-2">
    <div class="col-md-12">
        <button onclick="simpan({{ $pjhd->id }})" type="button"
            class="btn btn-sm btn-outline-warning color-palette btn-flat" {{ $pjhd->status == 2 ? 'disabled' : '' }}>
            <i class="fas fa-save"> </i> SIMPAN
        </button>
        <button onclick="cetak('{{ $pjhd->uuid }}')" type="button"
            class="btn btn-sm btn-outline-success color-palette btn-flat" {{ $pjhd->status == 1 ? 'disabled' : '' }}>
            <i class="fas fa-print"> </i> CETAK
        </button>
        <button onclick="batal('{{ $pjhd->uuid }}')" type="button"
            class="btn btn-sm btn-outline-danger color-palette btn-flat">
            <i class="fas fa-trash"> </i> BATAL (HAPUS SEMUA)
        </button>
        <button onclick="back()" type="button" class="btn btn-sm btn-outline-danger color-palette btn-flat"
            {{ $pjhd->status == 1 ? 'disabled' : '' }}>
            <i class="fas fa-times"> </i> TUTUP / KEMBALI
        </button>
    </div>
</div>
<script>
    $('#kas-btn').on('click', function () {
        bayarcepat('bank', 0);
        const harusbayar = document.getElementById('harusbayar').value;
        bayarcepat('kas', harusbayar);
    });

    $('#bank-btn').on('click', function () {
        const harusbayar = document.getElementById('harusbayar').value;
        bayarcepat('bank', harusbayar);
    });

    $('#piutang-btn').on('click', function () {
        bayarcepat('bank', 0);
        bayarcepat('kas', 0);
    });

    function bayarcepat(form_id, value) {
        const input = document.getElementById(form_id);
        input.value = value;
        fastupdate(input);
    }

    function simpan(id) {
        const harusbayar = document.getElementById('harusbayar').value;
        if (harusbayar <= 0) {
            Swal.fire({
                position: 'top-end',
                title: 'DATA PENJUALAN MASIH KOSONG',
                icon: 'error',
                showConfirmButton: true,
            });
            return false;
        }

        if (!reqsent) {
            reqsent = true;

            const formData = $('#form-datapj').serializeArray();
            const additionalData = {
                persenpajak: $('#persenpajak').val(),
                keterangan: $('#keterangan').val(),
                jenisppn: $('#jenisppn').val(),
                potongan: $('#potongan').val(),
                kode_acc_kas: $('#kode_acc_kas').val(),
                kode_acc_bank: $('#kode_acc_bank').val(),
                kas: $('#kas').val(),
                bank: $('#bank').val(),
                kembalian: $('#kembalian').val(),
                _token: token
            };

            formData.forEach(item => {
                additionalData[item.name] = item.value;
            });

            $.ajax({
                url: base_url + '/simpanpj',
                method: "POST",
                data: additionalData,
                success: function (data) {
                    reqsent = false;
                    if (data.success == 1) {
                        Swal.fire({
                            position: 'top-end',
                            title: data.pesan,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 900
                        }).then(() => {
                            cetak(data.uuid);
                        });
                    } else {
                        Swal.fire({
                            position: 'top-end',
                            title: data.pesan,
                            icon: 'error',
                            showConfirmButton: true,
                        });
                    }
                },
                error: function (data) {
                    reqsent = false;
                    console.error("AJAX Error:", data);
                }
            });
        }
    }

    function cetak(uuid) {
        Swal.fire({
            title: 'CETAK NOTA?',
            text: "NOTA AKAN DI CETAK",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'YA, CETAK!'
        }).then((result) => {
            if (result.value) {
                reqsent = true;
                const url = base_url + "/cetaknotapj/" + uuid;
                const tmpWin = window.open();
                tmpWin.location = url;
                back();
            } else {
                back();
            }
        });
    }

    function back() {
        window.location.href = base_url + '/penjualan';
    }

    function batal(uuid) {
        if (!reqsent) {
            Swal.fire({
                title: 'Anda yakin?',
                text: "Data Tidak akan Kembali setelah dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    reqsent = true;
                    $.ajax({
                        url: base_url + '/hapuspj/' + uuid,
                        type: "POST",
                        data: {
                            '_method': 'DELETE',
                            '_token': token
                        },
                        success: function (data) {
                            reqsent = false;
                            if (data.success == 1) {
                                Swal.fire({
                                    position: 'top-end',
                                    title: data.pesan,
                                    icon: 'success',
                                    showConfirmButton: false,
                                    timer: 900
                                }).then(() => {
                                    back();
                                });
                            } else {
                                Swal.fire({
                                    position: 'top-end',
                                    title: data.pesan,
                                    icon: 'error',
                                });
                            }
                        },
                        error: function (data) {
                            reqsent = false;
                            console.error("Delete Error:", data);
                        }
                    });
                }
            });
        }
    }
</script>
