<div class="row mr-3">
    <div class="col-md-4">
        <div class="form-row ml-1 pb-1">
            <label for="keterangan" class="col-md-12">Keterangan:</label>
            <textarea data-column="keterangan" onchange="fastupdate(this)" data-id="{{ $pjhd->id }}"
                class="col-md-10 form-control form-control-sm" name="keterangan" id="keterangan" cols="25" rows="2">{{ $pjhd->keterangan }}</textarea>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-row justify-content-end">
            <label for="total" class="col-5 text-right">Sub Total:</label>
            <input type="text" class="form-control form-control-sm text-right col-7" id="total" name="total"
                value="{{ Rupiah0($pjhd->total + 0) }}" readonly>
        </div>
        <div class="form-row justify-content-end">
            <label for="potongan" class="col-5 text-right">Total Potongan:</label>
            <input data-column="potongan" onchange="fastupdate(this)" data-id="{{ $pjhd->id }}" type="text"
                class="form-control form-control-sm text-right col-7" id="potongan" name="potongan"
                value="{{ $pjhd->potongan + 0 }}" autocomplete="off" readonly>
        </div>
        <div class="form-row justify-content-end">
            <label for="totalakhir" class="col-5 text-right">Total akhir:</label>
            <input type="text" class="form-control form-control-sm text-right col-7" id="totalakhir"
                name="totalakhir" value="{{ Rupiah0($totalakhir + 0) }}" readonly>
            <input type="hidden" name="harusbayar" id="harusbayar" value="{{ $totalakhir + 0 }}" readonly>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-row justify-content-end">
            <label for="total" class="col-5 text-right">B.Admin MPL:</label>
            <input type="text" class="form-control form-control-sm text-right col-7" id="admin_mpl" name="admin_mpl"
                value="{{ Rupiah0($pjhd->admin_mpl + 0) }}" readonly>
        </div>
        <div class="form-row justify-content-end">
            <label for="potongan" class="col-5 text-right">B.Admin Lain:</label>
            <input data-column="admin_lain" onchange="fastupdate(this)" data-id="{{ $pjhd->id }}" type="text"
                class="form-control form-control-sm text-right col-7" id="admin_lain" name="admin_lain"
                value="{{ $pjhd->admin_lain + 0 }}" autocomplete="off">
        </div>
        <div class="form-row justify-content-end">
            <label for="totalakhir" class="col-5 text-right">Total B.Admin:</label>
            <input type="text" class="form-control form-control-sm text-right col-7" id="totalakhiradminmpl"
                name="totalakhiradminmpl" value="{{ Rupiah0($pjhd->admin_lain + $pjhd->admin_mpl + 0) }}" readonly>
        </div>

    </div>
</div>
<div class="row text-right pt-2">
    <div class="col-md-12">
        <button type="button" class="btn btn-sm btn-outline-warning color-palette btn-flat"
            onclick="simpan({{ $pjhd->id }})" {{ $pjhd->status == 2 ? 'disabled' : '' }}>
            <i class="fas fa-save"> </i> <b><u>[B]</u></b>AYAR/SIMPAN
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
<div class="row invisible">
    <!-- Left Section -->
    <div class="col-md-4">
        <div class="form-row ml-1">
            <label for="jenisppn" class="col-5">Jenis PPN:</label>
            <select class="custom-select custom-select-sm col-5" id="jenisppn" name="jenisppn" onchange="updateppn()"
                disabled>
                <option value="exclude" {{ $pjhd->jenisppn == 'exclude' ? 'selected' : '' }}>EXCLUDE</option>
                <option value="include" {{ $pjhd->jenisppn == 'include' ? 'selected' : '' }}>INCLUDE</option>
                <option value="NON" {{ $pjhd->jenisppn == 'NON' ? 'selected' : '' }}>NON PPN</option>
            </select>
        </div>
    </div>
    <!-- Right Section -->
    <div class="col-md-4">
        <div class="form-row justify-content-end">
            <label for="ppn" class="col-5 text-right">PPN:</label>
            <input type="text" class="form-control form-control-sm text-right col-2" id="persenpajak"
                name="persenpajak" value="{{ $pjhd->persenpajak }}" onchange="updateppn()" readonly>
            <input type="text" class="form-control form-control-sm text-right col-5" id="ppn"
                name="ppn" value="{{ rupiah0($pjhd->ppn + 0) }}" readonly>
        </div>
        <div class="form-row justify-content-end">
            <label for="dpp" class="col-5 text-right">DPP:</label>
            <input type="text" class="form-control form-control-sm text-right col-7" id="dpp"
                name="dpp" value="{{ Rupiah0($pjhd->dpp + 0) }}" readonly>
        </div>

    </div>
    <div class="col-md-4">
        {{-- <div class="form-row justify-content-end">
            <label for="dp" class="col-5 text-right">DP PO:</label>
            <input type="text" class="form-control form-control-sm text-right col-7" id="dp" name="dp"
                value="{{ Rupiah0($pjhd->dp + 0) }}" readonly>
    </div> --}}
        <div class="form-row justify-content-end">

            <div class="col-4">

            </div>

            {{-- //transfer --}}
            <div class="col-4">

            </div>

        </div>
        <div class="form-row justify-content-end">
            {{-- <button class="ml-3 col-4 text-right btn btn-sm bg-info btn-dark" id="piutang-btn">PIUTANG :</button>
            <input data-column="bank" onchange="fastupdate(this)" data-id="{{ $pjhd->id }}" type="text"
                class="form-control form-control-sm text-right col-7" id="piutang" name="piutang"
                value="{{ Rupiah0($pjhd->piutang + 0) }}" readonly> --}}
        </div>
    </div>
</div>

<script>
    $('#kas-btn').on('click', function() {
        var harusbayar = document.getElementById('harusbayar').value;
        document.getElementById('kas').value = harusbayar;
        document.getElementById('bank').value = 0;
        document.getElementById('kembalian').value = 0;
    });
    $('#bank-btn').on('click', function() {
        var harusbayar = document.getElementById('harusbayar').value;
        document.getElementById('kas').value = 0;
        document.getElementById('kembalian').value = 0;
        document.getElementById('bank').value = harusbayar;
    });
    $('#piutang-btn').on('click', function() {
        bayarcepat('bank', 0);
        bayarcepat('kas', 0);
    });

    function hitungkembalian() {
        var kas = document.getElementById('kas').value;
        var bank = document.getElementById('bank').value;
        var harusbayar = document.getElementById('harusbayar').value;
        var kembalian = parseInt(kas) + parseInt(bank) - parseInt(harusbayar);
        document.getElementById('kembalian').value = kembalian;
    }

    function bayarcepat(form_id, value) {
        var input = document.getElementById(form_id);
        input.value = value;
        fastupdate(input);
    }

    function cetak(uuid) {
        reqsent = true;
        var tmpWin = window.open();
        var url = base_url + "/cetaknotapj/" + uuid;
        tmpWin.location = url;
        reqsent = false;
        tambahpjbaru();
    }

    function simpan(id) {
        var harusbayar = document.getElementById('harusbayar').value;
        if (harusbayar <= 0) {
            Swal.fire({
                position: 'top-end',
                title: 'DATA PENJUALAN MASIH KOSONG',
                icon: 'error',
                showConfirmButton: true,
            }).then(function() {
                swal.close();
            });
            return false;
        }
        if (reqsent == false) {
            reqsent = true;
            // Collect form data
            var formData = $('#form-datapj').serializeArray(); // Serialize form fields into an array
            var additionalData = {
                persenpajak: document.getElementById('persenpajak').value,
                keterangan: document.getElementById('keterangan').value,
                jenisppn: document.getElementById('jenisppn').value,
                potongan: document.getElementById('potongan').value,
                kode_acc_kas: document.getElementById('kode_acc_kas').value,
                kode_acc_bank: document.getElementById('kode_acc_bank').value,
            };

            // Merge form data with additional fields
            formData.forEach(function(item) {
                additionalData[item.name] = item.value;
            });

            var geturl = base_url + '/simpanpj'; // Fixed the missing closing quote
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
                            cetak(data.uuid);
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
        var url = base_url + '/kasirmpl';
        window.location.href = url;
    }

    function tambahpjbaru() {
        Swal.fire({
            title: 'TAMBAH PJ BARU?',
            text: "TAMBAH PENJUALAN BARU?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'YA,!'
        }).then((result) => {
            if (result.value) {
                if (reqsent == false) {
                    //open login
                    reqsent = true;
                    Swal.fire({
                        position: 'top-center',
                        title: "Creating...",
                        imageUrl: '{{ asset('icon/loading.gif') }}',
                        showConfirmButton: false,
                        allowOutsideClick: false
                    });
                    var geturl = base_url + '/tambahksrmplbaru';
                    $.ajax({
                        url: geturl,
                        type: "POST",
                        data: {
                            '_method': 'POST',
                            '_token': token
                        },
                        success: function(data) {
                            swal.close();
                            reqsent = false;
                            if (data.success == 1) {
                                Swal.fire({
                                    position: 'top-end',
                                    title: data.pesan,
                                    icon: 'success',
                                    showConfirmButton: false,
                                    timer: 900
                                }).then(function() {
                                    editdata(data.uuid);
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
                            swal.close();
                            reqsent = true;
                            console.log(data);
                        }
                    });
                } else {
                    console.log('reqsent true');
                }
            } else {
                back();
            }
        })
    }

    function editdata(uuid) {
        var url = '{{ url('') }}' + '/editpj/' + uuid;
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
                    var geturl = base_url + '/hapuspj/' + uuid;
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
