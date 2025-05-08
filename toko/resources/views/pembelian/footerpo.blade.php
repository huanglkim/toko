<div class="row">
    <!-- Left Section -->
    <div class="col-md-4">
        <div class="form-row ml-1 pb-1">
            <label for="keterangan" class="col-md-12">Keterangan:</label>
            <textarea data-column="keterangan" onchange="fastupdate(this)" data-id="{{ $popbhd->id }}"
                class="col-md-10 form-control form-control-sm" name="keterangan" id="keterangan" cols="25" rows="2">{{ $popbhd->keterangan }}</textarea>
        </div>
    </div>
    <!-- Right Section -->
    <div class="col-md-4">
        <div class="form-row justify-content-end">
            <label for="jenisppn" class="col-5 text-right">Jenis PPN:</label>
            <select class="custom-select custom-select-sm col-7" id="jenisppn" name="jenisppn" onchange="updateppn()">
                <option value="exclude" {{ $popbhd->jenisppn == 'exclude' ? 'selected' : '' }}>EXCLUDE</option>
                <option value="include" {{ $popbhd->jenisppn == 'include' ? 'selected' : '' }}>INCLUDE</option>
                <option value="NON" {{ $popbhd->jenisppn == 'NON' ? 'selected' : '' }}>NON PPN</option>
            </select>
        </div>
        <div class="form-row justify-content-end">
            <label for="ppn" class="col-5 text-right">PPN:</label>
            <input type="text" class="form-control form-control-sm text-right col-2" id="persenpajak"
                name="persenpajak" value="{{ $popbhd->persenpajak }}" onchange="updateppn()">
            <input type="text" class="form-control form-control-sm text-right col-5" id="ppn" name="ppn"
                value="{{ rupiah0($popbhd->ppn + 0) }}" readonly>
        </div>
        <div class="form-row justify-content-end">
            <label for="dpp" class="col-5 text-right">DPP:</label>
            <input type="text" class="form-control form-control-sm text-right col-7" id="dpp" name="dpp"
                value="{{ Rupiah0($popbhd->dpp + 0) }}" readonly>
        </div>
        <div class="form-row justify-content-end">
            <label for="potongan" class="col-5 text-right">Potongan:</label>
            <input data-column="potongan" onchange="fastupdate(this)" data-id="{{ $popbhd->id }}" type="text"
                class="form-control form-control-sm text-right col-7" id="potongan" name="potongan"
                value="{{ $popbhd->potongan + 0 }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-row justify-content-end">
            <label for="total" class="col-5 text-right">Sub Total:</label>
            <input type="text" class="form-control form-control-sm text-right col-7" id="total" name="total"
                value="{{ Rupiah0($popbhd->total + 0) }}" readonly>
        </div>
        @php
            $totalakhir = $popbhd->dpp + $popbhd->ppn - $popbhd->potongan;
            $kekurangan = $totalakhir - $popbhd->dp;
        @endphp
        <div class="form-row justify-content-end">
            <label for="totalakhir" class="col-5 text-right">Total akhir:</label>
            <input type="text" class="form-control form-control-sm text-right col-7" id="totalakhir"
                name="totalakhir" value="{{ Rupiah0($totalakhir + 0) }}" readonly>
        </div>
        <div class="form-row justify-content-end">
            <label for="dp" class="col-5 text-right">Titip (dp):</label>
            <div class="col-4">
                <select data-column="kode_acc_kas" onchange="fastupdate(this)" data-id="{{ $popbhd->id }}"
                    class="form-control form-control-sm" name="kode_acc_kas" id="kode_acc_kas">
                    @foreach (acckasbank() as $kk)
                        <option value="{{ $kk->kode_acc }}"
                            {{ $kk->kode_acc == $popbhd->kode_acc_kas ? 'selected' : '' }}>{{ $kk->nama_acc }}
                        </option>
                    @endforeach
                </select>
            </div>
            <input data-column="dp" onchange="fastupdate(this)" data-id="{{ $popbhd->id }}" type="text"
                class="form-control form-control-sm text-right col-3" id="dp" name="dp"
                value="{{ $popbhd->dp + 0 }}" onchange="fastupdate(this)">
        </div>
        <div class="form-row justify-content-end">
            <label for="kekurangan" class="col-5 text-right">Kekurangan:</label>
            <input type="text" class="form-control form-control-sm text-right col-7" id="kekurangan"
                name="kekurangan" value="{{ Rupiah0($kekurangan + 0) }}" readonly>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <button onclick="simpan({{ $popbhd->id }})" type="button" id="btn-simpan"
            class="btn btn-sm btn-outline-warning color-palette btn-flat" {{ $popbhd->status == 2 ? 'disabled' : '' }}>
            <i class="fas fa-save"> </i> SIMPAN
        </button>
        <button onclick="cetak('{{ $popbhd->uuid }}')" type="button"
            class="btn btn-sm btn-outline-success color-palette btn-flat" {{ $popbhd->status == 1 ? 'disabled' : '' }}>
            <i class="fas fa-print"> </i> CETAK
        </button>
        <button onclick="batal('{{ $popbhd->uuid }}')" type="button"
            class="btn btn-sm btn-outline-danger color-palette btn-flat">
            <i class="fas fa-trash"> </i> BATAL (HAPUS SEMUA)
        </button>
        <button onclick="back()" type="button" class="btn btn-sm btn-outline-danger color-palette btn-flat"
            {{ $popbhd->status == 1 ? 'disabled' : '' }}>
            <i class="fas fa-times"> </i> TUTUP / KEMBALI
        </button>
    </div>
</div>
<script>
    document.addEventListener('keydown', function(e) {
        if (e.key === 'End') { // Check if the End key is pressed
            e.preventDefault(); // Prevent default action (if any)

            const button = document.getElementById('btn-simpan');
            if (button && !button.disabled) { // Check if button exists and is not disabled
                button.click(); // Trigger click event on the button
            }
        }
    });
</script>
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
                dp: document.getElementById('dp').value,
            };

            // Merge form data with additional fields
            formData.forEach(function(item) {
                additionalData[item.name] = item.value;
            });

            var geturl = base_url + '/simpanpopb'; // Fixed the missing closing quote
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
                            back();
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
        var url = base_url + '/popb';
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
                    var geturl = base_url + '/hapuspopb/' + uuid;
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
