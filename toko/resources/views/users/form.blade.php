<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="form-user" method="post" class="needs-validation" novalidate enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ method_field('POST') }}
                <div class="modal-header bg-secondary text-white">
                    <h5 class="modal-title">Form Pengguna</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="id" name="id">

                    <div class="form-group">
                        <label for="nama">Nama <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan Nama" required autofocus>
                        <div class="invalid-feedback" id="errorNama"></div>
                    </div>

                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Masukkan Alamat">
                        <div class="invalid-feedback" id="errorAlamat"></div>
                    </div>

                    <div class="form-group">
                        <label for="kota">Kota</label>
                        <input type="text" class="form-control" id="kota" name="kota" placeholder="Masukkan Kota">
                        <div class="invalid-feedback" id="errorKota"></div>
                    </div>

                    <div class="form-group">
                        <label for="wa">WhatsApp <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">+62</span>
                            </div>
                            <input type="text" class="form-control" id="wa" name="wa" placeholder="81234567890" required>
                        </div>
                        <small class="form-text text-muted">Contoh: 81234567890</small>
                        <div class="invalid-feedback" id="errorWa"></div>
                    </div>

                    <div class="form-group">
                        <label for="username">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan Username" required>
                        <div class="invalid-feedback" id="errorUsername"></div>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password">
                        <div class="invalid-feedback" id="errorPassword"></div>
                    </div>

                    <div class="form-group">
                        <label for="cpassword">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="cpassword" name="cpassword" placeholder="Konfirmasi Password">
                        <div class="invalid-feedback" id="errorCpassword"></div>
                    </div>

                    <div class="form-group">
                        <label for="rfid">RFID</label>
                        <input type="text" class="form-control" id="rfid" name="rfid" placeholder="Masukkan RFID">
                        <div class="invalid-feedback" id="errorRfid"></div>
                    </div>

                    <div class="form-group">
                        <label for="role_id">Jabatan <span class="text-danger">*</span></label>
                        {!! Form::select('role_id', $Roles, null, [
                            'class' => 'form-control select2',
                            'placeholder' => '-- Pilih Jabatan --',
                            'id' => 'role_id',
                            'name' => 'role_id',
                            'required',
                        ]) !!}
                        <div class="invalid-feedback" id="errorRoleid"></div>
                    </div>

                    <div class="form-group">
                        <label for="status">Status <span class="text-danger">*</span></label>
                        {!! Form::select('status', ['1' => 'Aktif', '0' => 'Tidak Aktif'], '1', [
                            'class' => 'form-control',
                            'id' => 'status',
                            'name' => 'status',
                            'required',
                        ]) !!}
                        <div class="invalid-feedback" id="errorStatus"></div>
                    </div>

                    <div class="form-group">
                        <label for="created_at">Tanggal Masuk <span class="text-danger">*</span></label>
                        <input type="text" class="form-control datepicker" id="created_at" name="created_at"
                            value="{{ date('Y-m-d') }}" placeholder="Pilih Tanggal" required>
                        <div class="invalid-feedback" id="errorCreated_at"></div>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="AddOrEditForm" onclick="AddOrEdit()">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap4',
        });

        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
            todayBtn: 'linked',
            clearBtn: true,
        });
    });
</script>
