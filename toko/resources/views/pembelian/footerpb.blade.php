<div class="row">
    <!-- Left Section -->
    <div class="col-md-4">
        <div class="form-row ml-1 pb-1">
            <label for="keterangan" class="col-md-12">Keterangan:</label>
            <textarea data-column="keterangan" onchange="fastupdate(this)" data-id="{{ $pbhd->id }}"
                class="col-md-10 form-control form-control-sm" name="keterangan" id="keterangan" cols="25" rows="2">{{ $pbhd->keterangan }}</textarea>
        </div>
        <div class="form-row ml-1">
            <label for="jenisppn" class="col-5">Jenis PPN:</label>
            <select class="custom-select custom-select-sm col-5" id="jenisppn" name="jenisppn" onchange="updateppn()">
                <option value="exclude" {{ $pbhd->jenisppn == 'exclude' ? 'selected' : '' }}>EXCLUDE</option>
                <option value="include" {{ $pbhd->jenisppn == 'include' ? 'selected' : '' }}>INCLUDE</option>
                <option value="NON" {{ $pbhd->jenisppn == 'NON' ? 'selected' : '' }}>NON PPN</option>
            </select>
        </div>
    </div>
    <!-- Right Section -->
    <div class="col-md-4">
        <div class="form-row justify-content-end">
            <label for="total" class="col-5 text-right">Sub Total:</label>
            <input type="text" class="form-control form-control-sm text-right col-7" id="total" name="total"
                value="{{ Rupiah0($pbhd->total + 0) }}" readonly>
        </div>

        <div class="form-row justify-content-end">
            <label for="ppn" class="col-5 text-right">PPN:</label>
            <input type="text" class="form-control form-control-sm text-right col-2" id="persenpajak"
                name="persenpajak" value="{{ $pbhd->persenpajak }}" onchange="updateppn()">
            <input type="text" class="form-control form-control-sm text-right col-5" id="ppn" name="ppn"
                value="{{ rupiah0($pbhd->ppn + 0) }}" readonly>
        </div>
        <div class="form-row justify-content-end">
            <label for="dpp" class="col-5 text-right">DPP:</label>
            <input type="text" class="form-control form-control-sm text-right col-7" id="dpp" name="dpp"
                value="{{ Rupiah0($pbhd->dpp + 0) }}" readonly>
        </div>
        <div class="form-row justify-content-end">
            <label for="potongan" class="col-5 text-right">Potongan:</label>
            <input data-column="potongan" onchange="fastupdate(this)" data-id="{{ $pbhd->id }}" type="text"
                class="form-control form-control-sm text-right col-7" id="potongan" name="potongan"
                value="{{ $pbhd->potongan + 0 }}" autocomplete="off">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-row justify-content-end">
            <label for="totalakhir" class="col-5 text-right">Total akhir:</label>
            <input type="text" class="form-control form-control-sm text-right col-7" id="totalakhir"
                name="totalakhir" value="{{ Rupiah0($totalakhir + 0) }}" readonly>
        </div>
        <div class="form-row justify-content-end">
            <label for="dp" class="col-5 text-right">DP PO:</label>
            <input type="text" class="form-control form-control-sm text-right col-7" id="dp" name="dp"
                value="{{ Rupiah0($pbhd->dp + 0) }}" readonly>
        </div>
        <div class="form-row justify-content-end">
            <label for="dp" class="col-5 text-right">Tunai / DP:</label>
            <div class="col-4">
                <select data-column="kode_acc_kas" onchange="fastupdate(this)" data-id="{{ $pbhd->id }}"
                    class="form-control form-control-sm" name="kode_acc_kas" id="kode_acc_kas">
                    @foreach (acckasbank() as $kk)
                        <option value="{{ $kk->kode_acc }}"
                            {{ $kk->kode_acc == $pbhd->kode_acc_kas ? 'selected' : '' }}>{{ $kk->nama_acc }}
                        </option>
                    @endforeach
                </select>
            </div>
            <input data-column="kas" onchange="fastupdate(this)" data-id="{{ $pbhd->id }}" type="text"
                class="form-control form-control-sm text-right col-3" id="kas" name="kas"
                value="{{ $pbhd->kas + 0 }}" onchange="fastupdate(this)" autocomplete="off">
        </div>
        <div class="form-row justify-content-end">
            <label for="hutang" class="col-5 text-right">Kredit:</label>
            <input type="text" class="form-control form-control-sm text-right col-7" id="hutang"
                name="hutang" value="{{ Rupiah0($pbhd->hutang + 0) }}" readonly>
        </div>
    </div>
</div>
<div class="row text-right pt-2">
    <div class="col-md-12">
        <button onclick="simpan({{ $pbhd->id }})" type="button"
            class="btn btn-sm btn-outline-warning color-palette btn-flat" {{ $pbhd->status == 2 ? 'disabled' : '' }}>
            <i class="fas fa-save"> </i> SIMPAN
        </button>
        <button onclick="cetak('{{ $pbhd->uuid }}')" type="button"
            class="btn btn-sm btn-outline-success color-palette btn-flat" {{ $pbhd->status == 1 ? 'disabled' : '' }}>
            <i class="fas fa-print"> </i> CETAK 
        </button>
        <button onclick="batal('{{ $pbhd->uuid }}')" type="button"
            class="btn btn-sm btn-outline-danger color-palette btn-flat">
            <i class="fas fa-trash"> </i> BATAL (HAPUS SEMUA)
        </button>
        <button onclick="back()" type="button" class="btn btn-sm btn-outline-danger color-palette btn-flat"
            {{ $pbhd->status == 1 ? 'disabled' : '' }}>
            <i class="fas fa-times"> </i> TUTUP / KEMBALI
        </button>
    </div>
</div>
<script>
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
                //open login
                reqsent = true;
                var tmpWin = window.open();
                var url = base_url + "/cetaknotapo/" + uuid;
                tmpWin.location = url;
                back();
            } else {
                back();
            }
        })
    }

    function simpan(id) {
        if (reqsent == false) {
            reqsent = true;
            // Collect form data
            var formData = $('#form-datapb').serializeArray(); // Serialize form fields into an array
            var additionalData = {
                persenpajak: document.getElementById('persenpajak').value,
                keterangan: document.getElementById('keterangan').value,
                jenisppn: document.getElementById('jenisppn').value,
                potongan: document.getElementById('potongan').value,
                kode_acc_kas: document.getElementById('kode_acc_kas').value,
                kas: document.getElementById('kas').value,
            };

            // Merge form data with additional fields
            formData.forEach(function(item) {
                additionalData[item.name] = item.value;
            });

            var geturl = base_url + '/simpanpb'; // Fixed the missing closing quote
            $.ajax({
                url: geturl,
                method: "POST",
                data: additionalData,
                success: function(data) {
                    reqsent = false;
                    if (data.success == 1) {
                        Swal.fire({
                            position: 'top-end',
                            title: data.pesan,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 900
                        }).then(function() {
                            cetak();
                        });
                    } else {
                        if (data.success == 0) {
                            Swal.fire({
                                position: 'top-end',
                                title: data.pesan,
                                icon: 'error',
                                showConfirmButton: true,
                            }).then(function() {
                                swal.close();
                            });
                        } else {
                            console.log(data);
                        }
                    }
                },
                error: function(data) { // Corrected error callback signature
                    reqsent = false;
                    console.log(data);

                }
            });
        }
    }


    function back() {
        var url = base_url + '/pembelian';
        window.location.href = url;
    }

    function batal(uuid) {
        if (reqsent == false) {
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
                    //open login
                    reqsent = true;
                    var geturl = base_url + '/hapuspb/' + uuid;
                    $.ajax({
                        url: geturl,
                        type: "POST",
                        data: {
                            '_method': 'DELETE',
                            '_token': token
                        },
                        success: function(data) {
                            console.log(data);
                            reqsent = false;
                            if (data.success == 1) {
                                $('#modal-form').modal('hide');
                                Swal.fire({
                                    position: 'top-end',
                                    title: data.pesan,
                                    icon: 'success',
                                    showConfirmButton: false,
                                    timer: 900
                                }).then(function() {
                                    back();
                                });
                            }
                            if (data.success == 0) {
                                Swal.fire({
                                    position: 'top-end',
                                    title: data.pesan,
                                    icon: 'error',
                                }).then(function() {});
                            }
                        },
                        error: function(data) {
                            reqsent = true;
                            console.log(data);
                        }
                    });
                }
            })
        }
    }
</script>
