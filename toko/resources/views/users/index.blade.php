@extends('layout.main')
@section('title', 'Data Pengguna')

@section('css')
    <link href="{{ url('/') }}/lte/plugins/datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" />
    <style>
        .callout-panel {
            padding: 15px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-left: 4px solid #007bff;
            border-radius: 6px;
            margin-bottom: 1rem;
        }

        .callout-panel-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
        }


    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="card col-md-12">
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h2 class="text-primary mb-2 mb-md-0">
                            <i class="fas fa-user-circle me-2"></i> DAFTAR USER
                        </h2>
                        <button type="button" onclick="addForm()" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-plus-square me-1"></i> Tambah User
                        </button>
                    </div>
                </div>

                <form id="filterForm">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="callout-panel">
                                <h6 class="callout-panel-title">Status</h6>
                                <select class="custom-select custom-select-sm" name="status_user_option"
                                    id="status_user_option" onchange="datauser(1)">
                                    <option value="1" selected>🟢 Aktif</option>
                                    <option value="0">🔴 Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="callout-panel">
                                <h6 class="callout-panel-title">Urutkan Berdasarkan</h6>
                                <select class="custom-select custom-select-sm" name="orderby" id="orderby"
                                    onchange="datauser(1)">
                                    <option value="1">Tanggal Masuk A-Z</option>
                                    <option value="2" selected>Tanggal Masuk Z-A</option>
                                    <option value="3">Nama A-Z</option>
                                    <option value="4">Nama Z-A</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="callout-panel">
                                <h6 class="callout-panel-title">Cari Pengguna</h6>
                                <input type="text" id="cariuser" name="cariuser" class="form-control form-control-sm"
                                    placeholder="Masukkan Nama Pengguna">
                            </div>
                        </div>
                    </div>
                </form>

                @if (session('status'))
                    <div class="alert alert-success col-12">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="row">
                    <div class="col-sm-12" id="datauser">
                        {{-- Data user akan ditampilkan di sini --}}
                    </div>
                </div>
            </div>
        </div>
    </div>


    </section>
    @include('users.form')
@endsection
@section('script')
    <script src="{{ asset('lte/sweetalert2/sweetalert2@11.js') }}"></script>
    <script src="{{ asset('lte/plugins/datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            let page = localStorage.getItem("page") ? localStorage.getItem("page") : 1;
            datauser(page);

            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                page = $(this).attr('href').split('page=')[1];
                datauser(page);
                localStorage.setItem("page", page);
            });

            $('#cariuser').on('keypress', function(event) {
                if (event.which === 13) {
                    event.preventDefault();
                    datauser(1);
                }
            });
        });

        function datauser(page) {
            $.ajax({
                url: "{{ url('/') }}/datausertabel?page=" + page,
                method: "POST",
                data: {
                    '_token': '{{ csrf_token() }}',
                    cariuser: $('#cariuser').val(),
                    status_user: $('#status_user_option').val(),
                    orderby: $('#orderby').val(),
                },
                success: function(data) {
                    $('#datauser').html(data);
                },
                error: function(data) {
                    console.log(data);
                }
            });
        };

        //datepicker
        $(function() {
            $(".datepicker").datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
            });
        });

        function addForm() {
            save_method = "POST";
            $('input[name=_method]').val('POST');
            $('#modal-form').modal('show');
            $('#modal-form form')[0].reset();
            $('.modal-title').text('Tambah Pengguna');
        }

        function OtEdit(id) {
            save_method = 'PATCH';
            $('input[name=_method]').val('PATCH');
            $('#modal-form form')[0].reset();
            $.ajax({
                url: "{{ url('datauser') }}" + '/' + id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#modal-form').modal('show');
                    $('.modal-title').text('Edit Pengguna');
                    $('#id').val(data.id);
                    $('#nama').val(data.nama);
                    $('#alamat').val(data.alamat);
                    $('#wa').val(data.wa);
                    $('#kota').val(data.kota);
                    $('#username').val(data.username);
                    $('#rfid').val(data.rfid);
                    $('#role_id').val(data.role_id);
                    $('#status').val(data.status);
                    var d = data.created_at;
                    var date = new Date(d).toLocaleDateString('en-US');
                    $('#created_at').val(date);
                },
                error: function() {
                    alert("Tidak ada data");
                }
            });
        }

        function AddOrEdit() {
            var id = $('#id').val();
            var password = $('#password').val();
            var cpassword = $('#cpassword').val();
            let url = (save_method == 'POST') ? "{{ url('datauser') }}" : "{{ url('datauser') . '/' }}" + id;

            if (password != cpassword) {
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Password',
                    text: 'Password dan Konfirmasi password tidak sama',
                });
                return;
            }

            $.ajax({
                url: url,
                method: "POST",
                data: $('#form-user').serialize(),
                success: function(data) {
                    if (data.success == 1) {
                        $('#modal-form').modal('hide');
                        Swal.fire({
                            position: 'top-end',
                            title: data.pesan,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 900
                        }).then(function() {
                            location.reload();
                        });
                    } else if (data.success == 0) {
                        Swal.fire({
                            position: 'top-end',
                            title: 'ERROR HUBUNGI ADMIN',
                            icon: 'error',
                            showConfirmButton: false,
                            timer: 900
                        }).then(function() {
                            console.log(data);
                        });
                    } else {
                        $('.is-invalid').removeClass('is-invalid');
                        $('.invalid-feedback').html('');

                        if (data.errors) {
                            $.each(data.errors, function(key, value) {
                                $('#' + key).addClass('is-invalid');
                                $('#error' + key.charAt(0).toUpperCase() + key.slice(1)).html(value);
                            });
                        }
                    }
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }

        function OtDelete(id) {
            Swal.fire({
                title: 'Anda yakin?',
                text: "Data Tidak akan Kembali setelah dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus saja!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: "{{ url('datauser') }}" + '/' + id,
                        type: "POST",
                        data: {
                            '_method': 'DELETE',
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            if (data.success == 1) {
                                $('#modal-form').modal('hide');
                                Swal.fire({
                                    position: 'top-end',
                                    title: data.pesan,
                                    icon: 'success',
                                    showConfirmButton: false,
                                    timer: 900
                                }).then(function() {
                                    location.reload();
                                });
                            }
                            if (data.success == 0) {
                                Swal.fire({
                                    position: 'top-end',
                                    title: data.pesan,
                                    icon: 'error',
                                    showConfirmButton: false,
                                    timer: 900
                                }).then(function() {
                                    console.log(data);
                                });
                            }
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                }
            });
        }

        // otoritas
        function deleteData(id) {
            document.getElementById('otidinput').value = id;
            document.getElementById('ottipe_akses').value = 0;
            document.getElementById('otakses_id').value = 1;
            otoritas();
        }

        function editForm(id) {
            document.getElementById('otidinput').value = id;
            document.getElementById('ottipe_akses').value = 1;
            document.getElementById('otakses_id').value = 5;
            otoritas();
        }
        $('#otoritasModal').on('shown.bs.modal', function() {
            $('#otusername').focus();
        });
        $('#otoritasrfidModal').on('shown.bs.modal', function() {
            $('#otrfid').focus();
        });

        function Custom(id) {
            alert('berhasil' + id);
        }
        //end otoritas
    </script>
@endsection
