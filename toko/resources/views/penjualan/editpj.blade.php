@extends('layout.main')

@section('title', 'EDIT PENJUALAN')

@section('css')
    <link href="{{ url('/') }}/lte/plugins/datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" />
    <link href="{{ url('/') }}/lte/css/select2.min.css" rel="stylesheet" />
    <link href="{{ url('/') }}/lte/css/selectpicker.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ url('/') }}/lte/DataTables/datatables.min.css" />
    <link rel="stylesheet" href="{{ url('/') }}/lte/plugins/toastr/toastr.min.css" />
    <style>
        .nowrap th,
        .nowrap td {
            white-space: nowrap !important;
        }

        .table {
            font-size: 13px;
        }

        .btn-xs-custom {
            font-size: 11px;
            padding: 4px 8px;
        }

        .table input {
            max-width: 80px;
            padding: 0.25rem;
            height: 1.3rem;
            font-size: 0.8rem;
        }

        .form-label {
            font-weight: 500;
        }

        .card {
            border-radius: 0.75rem;
            margin-bottom: 1rem;
            box-shadow: 0 0.25rem 1rem rgba(0, 0, 0, 0.05);
        }

        .form-control-sm {
            font-size: 0.85rem;
        }

        .btn-flat {
            border-radius: 6px;
        }
    </style>
@stop

@section('content')
    <section class="content">

        {{-- Form Header --}}
        <div class="card col-md-12">
            <div class="card-header">
                <h2 class="card-title fw-bold">Tambah Penjualan Kredit</h2>
            </div>
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <form id="form-datapj">
                        @csrf
                        <div class="row g-3 align-items-end">
                            <!-- Tanggal -->
                            <div class="col-md-3">
                                <label for="tanggal" class="form-label">Tanggal</label>
                                <input type="text" class="form-control datepicker"
                                    name="tanggal" id="tanggal"
                                    data-column="tanggal" data-id="{{ $pjhd->id }}"
                                    value="{{ datetotanggal($pjhd->tanggal) }}"
                                    onchange="fastupdate(this)">
                            </div>
            
                            <!-- Invoice -->
                            <div class="col-md-3">
                                <label for="invoice" class="form-label">No. Invoice</label>
                                <input type="hidden" name="id" id="id" value="{{ $pjhd->id }}">
                                <input type="text" class="form-control" id="invoice" name="invoice"
                                    value="{{ $pjhd->invoice }}" readonly>
                            </div>
            
                            <!-- Pelanggan -->
                            <div class="col-md-4">
                                <label for="pelanggan_id" class="form-label">Pelanggan</label>
                                <select class="form-control selectpicker"
                                    id="pelanggan_id" name="pelanggan_id"
                                    data-column="pelanggan_id" data-id="{{ $pjhd->id }}"
                                    data-size="7" data-live-search="true"
                                    data-title="{{ $pjhd->pelanggan->nama . '/' . $pjhd->pelanggan->kode }}"
                                    data-width="100%" onchange="fastupdate(this)">
                                    <option value="{{ $pjhd->pelanggan_id }}" selected>
                                        {{ $pjhd->pelanggan->nama . '/' . $pjhd->pelanggan->kode }}
                                    </option>
                                </select>
                            </div>
            
                            <!-- Tombol Tambah -->
                            <div class="col-md-2 d-grid">
                                <label class="form-label">&nbsp;</label>
                                <button type="button" onclick="formpelanggan()" class="btn btn-outline-primary">
                                    <i class="fas fa-plus me-1"></i> Tambah Pelanggan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            
            
        </div>

        {{-- Modal for Barang --}}
        @include('barang.modulcaribarang')

        {{-- Toggle View Options --}}
        <div class="card p-3">
            <div class="row">
                <div class="col-md-4">
                    <label for="detailview" class="form-check-label">
                        <input type="checkbox" id="detailview" class="form-check-input me-2" onchange="cartpj()">
                        Tampilkan Detail
                    </label>
                </div>
            </div>
        </div>

        {{-- Cart Table --}}
        <div class="card p-2" id="carttabel"></div>

        {{-- Footer --}}
        <div class="card p-2" id="footerpj"></div>

    </section>

    {{-- Additional Components --}}
    @include('pelanggan.form')
    @include('penjualan.formcart')
    @include('penjualan.formcartpj')
@stop

@section('script')
    <script src="{{ asset('lte/sweetalert2/sweetalert2@11.js') }}"></script>
    <script src="{{ url('/') }}/lte/plugins/datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="{{ url('/') }}//lte/js/select2.min.js"></script>
    <script src="{{ url('/') }}/lte/js/selectpicker.js"></script>
    <script type="text/javascript" src="{{ url('/') }}/lte/DataTables/datatables.min.js"></script>
    <script src="{{ url('/') }}/lte/js/rupiah.js"></script>
    <script src="{{ url('/') }}/lte/plugins/toastr/toastr.min.js"></script>
    @include('jsblade.toasttrjs')
    @include('jsblade.datatablejsget')
    @include('jsblade.jscaribarang')
    <script>
        function ubahqty(input) {
            if (reqsent == false) {
                reqsent = true;
                var data = {
                    '_token': token,
                    qty: input.value,
                };
                var id = $(input).attr("data-id");
                var url = base_url + "/updateqtycartpj/" + id;
                $.ajax({
                    url: url,
                    method: "POST",
                    data: data,
                    success: function(data) {
                        reqsent = false;
                        if (data.success == 1) {
                            toastr["success"](data.pesan, "Berhasil");
                            loadcartfooter();
                        } else {
                            Swal.fire({
                                position: 'top-end',
                                title: data.pesan,
                                icon: 'error',
                                showConfirmButton: false,
                                timer: 900
                            }).then(function() {

                            });
                        }
                    },
                    error: function(data) {
                        reqsent = false;
                        console.log(data);
                    }
                });
            }
        }
    </script>
    <script>
        function listpo() {
            $('#popbModal').modal('show');
        }
        //po
        function pilihpo(uuid) {
            if (reqsent == false) {
                reqsent = true;
                var geturl = base_url + '/importpopbtopb'; // Fixed the missing closing quote
                var data = {
                    '_token': token,
                    'uuid': uuid,
                    'id': document.getElementById('id').value,
                };
                $.ajax({
                    url: geturl,
                    method: "POST",
                    data: data,
                    success: function(data) {
                        reqsent = false;
                        loadcartfooter();
                        if (data.success == 1) {
                            $('#popbModal').modal('hide');
                            Swal.fire({
                                position: 'top-end',
                                title: data.pesan,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 900
                            }).then(function() {
                                location.reload();
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
                    error: function(xhr, status, error) { // Corrected error callback signature
                        reqsent = false;
                        if (status == "timeout") {
                            // timeout -> reload the page and try again
                            window.location.reload(); //make it comment if you don't want to reload page
                        } else {
                            // another error occurred
                            console.log(error); // Log the actual error message
                        }
                    }
                });
            }
        }
    </script>
    <script>
        $('#cartModalpj').on('keydown', function(e) {
            if (e.key === 'Insert') {
                e.preventDefault();
                $('#btn-simpan-cart').click(); // Trigger click event on the button
            }
        });
    </script>
    <script>
        function fastupdate(note) {
            var value = note.value;
            var data = {
                '_token': token,
                id: $(note).attr("data-id"),
                column: $(note).attr("data-column"),
                value: value,
            };
            var url = base_url + '/fastupdatepjhd';
            $.ajax({
                url: url,
                method: "POST",
                data: data,
                success: function(data) {
                    reqsent = false;
                    if (data.success == 1) {
                        toastr["success"](data, data.pesan);
                        footerpj();
                    }
                    if (data.success == 0) {
                        toastr["error"](data, data.pesan);
                    }
                },
                error: function(data) {
                    console.log(data);
                }
            });
        };

        function updateppn() {
            if (reqsent == false) {
                reqsent = true;
                var geturl = base_url + '/updatepajakcartpj'; // Fixed the missing closing quote
                $.ajax({
                    url: geturl,
                    method: "POST",
                    data: {
                        '_token': token,
                        jenisppn: document.getElementById('jenisppn').value,
                        persenpajak: document.getElementById('persenpajak').value,
                        pjhd_id: pjhd_id
                    },
                    success: function(data) {
                        reqsent = false;
                        loadcartfooter();
                        if (data.success == 1) {
                            document.getElementById("form-cartpj").reset();
                            $('#cartModalpj').modal('hide');
                            Swal.fire({
                                position: 'top-end',
                                title: data.pesan,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 900
                            }).then(function() {
                                cartpj();
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
                    error: function(xhr, status, error) { // Corrected error callback signature
                        reqsent = false;
                        if (status == "timeout") {
                            // timeout -> reload the page and try again
                            window.location.reload(); //make it comment if you don't want to reload page
                        } else {
                            // another error occurred
                            console.log(error); // Log the actual error message
                        }
                    }
                });
            }
        }

        function loadcartfooter() {
            cartpj();
            footerpj();
        }

        function cartpj() {
            var checkbox = document.getElementById('detailview');
            if (checkbox.checked) {
                var detail = 1;
            } else {
                var detail = 0;
            }
            var geturl = base_url + '/cartpj/' + pjhd_id;
            $.ajax({
                url: geturl,
                method: "POST",
                data: {
                    '_token': token,
                    detail: detail,
                },
                success: function(data) {
                    $('#carttabel').html(data);
                },
                error: function(data) {
                    reqsent = false;
                    if (status == "timeout") {
                        // timeout -> reload the page and try again
                        window.location.reload(); //make it comment if you don't want to reload page
                    } else {
                        // another error occured  
                        console.log(data);
                    }
                }
            });
        }

        function footerpj() {
            var geturl = base_url + '/footerpj/' + pjhd_id;
            $.ajax({
                url: geturl,
                method: "POST",
                data: {
                    '_token': token,
                },
                success: function(data) {
                    $('#footerpj').html(data);
                },
                error: function(data) {
                    reqsent = false;
                    if (status == "timeout") {
                        // timeout -> reload the page and try again
                        window.location.reload(); //make it comment if you don't want to reload page
                    } else {
                        // another error occured  
                        console.log(data);
                    }
                }
            });
        }

        function AddCartBarang() {
            if (reqsent == false) {
                var url = base_url + '/tambahcartpj';
                var method = 'POST';
                var data = $('#form-addcartpj').serialize() + "&_method=" + method + "&pjhd_id=" + pjhd_id;
                $.ajax({
                    url: url,
                    timeout: 10000,
                    method: "POST",
                    data: data,
                    success: function(data) {
                        reqsent = false;
                        if (data.success == 1) {
                            document.getElementById("form-cartpj").reset();
                            $('#modalcartpj').modal('hide');
                            Swal.fire({
                                position: 'top-end',
                                title: data.pesan,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 900
                            }).then(function() {
                                loadcartfooter();
                                $('#caribarcode').focus();
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
                    error: function(data) {
                        reqsent = false;
                        if (status == "timeout") {
                            // timeout -> reload the page and try again
                            window.location.reload(); //make it comment if you don't want to reload page
                        } else {
                            // another error occured  
                            console.log(data);
                        }
                    }
                });
            }
        }

        function UpdateCartBarang() {
            if (reqsent == false) {
                var id = document.getElementById('cart_id').value;
                if (id == null || id == false || id == '') {
                    var url = "{{ url('/tambahcartpj') }}";
                    var method = 'POST';
                } else {
                    var url = "{{ url('/updatecartpj') . '/' }}" + id;
                    var method = 'PATCH';
                }
                var data = $('#form-cartpj').serialize() + "&_method=" + method + "&pjhd_id=" + pjhd_id + "&invoice=" +
                    invoice;
                $.ajax({
                    url: url,
                    timeout: 10000,
                    method: "POST",
                    data: data,
                    success: function(data) {
                        reqsent = false;
                        if (data.success == 1) {
                            document.getElementById("form-cartpj").reset();
                            $('#cartModalpj').modal('hide');
                            Swal.fire({
                                position: 'top-end',
                                title: data.pesan,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 900
                            }).then(function() {
                                loadcartfooter();
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
                    error: function(data) {
                        reqsent = false;
                        if (status == "timeout") {
                            // timeout -> reload the page and try again
                            window.location.reload(); //make it comment if you don't want to reload page
                        } else {
                            // another error occured  
                            console.log(data);
                        }
                    }
                });
            }
        }

        function hapuscart(id) {
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
                    var geturl = base_url + '/hapuscartpj/' + id;
                    $.ajax({
                        url: geturl,
                        type: "POST",
                        data: {
                            '_method': 'DELETE',
                            '_token': token
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
                                    loadcartfooter();
                                });
                            }
                            if (data.success == 0) {
                                Swal.fire({
                                    position: 'top-end',
                                    title: data.pesan,
                                    icon: 'error',
                                }).then(function() {

                                });
                            }
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                }
            })
        }

        function editcart(id) {
            Swal.fire({
                title: 'Anda yakin?',
                text: "YAKIN UNTUK EDIT !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'YA. EDIT'
            }).then((result) => {
                if (result.value) {
                    //open login
                    var geturl = base_url + '/getcartpj/' + id;
                    $.ajax({
                        url: geturl,
                        timeout: 1000,
                        method: "GET",
                        success: function(data) {
                            $('#cartModalpj').modal('show');
                            document.getElementById('cart_id').value = data.id;
                            document.getElementById('cart_barang_id').value = data.barang_id;
                            document.getElementById('nama_barang').value = data.nama_barang;
                            document.getElementById('qty').value = data.qty;
                            document.getElementById('harga_bruto').value = data.harga_bruto;
                            document.getElementById('rpharga_bruto').value = strtorp(data.harga_bruto);
                            document.getElementById('total_harga').value = data.harga_bruto * data.qty;
                            document.getElementById('rptotal_harga').value = strtorp(data.harga_bruto *
                                data.qty);
                            document.getElementById('harga_netto').value = data.harga_netto;
                            document.getElementById('rpharga_netto').value = strtorp(data.harga_netto);
                            document.getElementById('total_harga_netto').value = data.total_harga_netto;
                            document.getElementById('rptotal_harga_netto').value = strtorp(data
                                .total_harga_netto);
                            document.getElementById('potpersen').value = data.potpersen;
                            document.getElementById('potrp').value = data.potrp;
                            document.getElementById('rppotrp').value = strtorp(data.potrp);
                            document.getElementById('total_pot').value = data.total_pot;
                            document.getElementById('rptotal_pot').value = strtorp(data.total_pot);

                        },
                        error: function(request, status, err) {
                            if (status == "timeout") {
                                // timeout -> reload the page and try again
                                window.location
                                    .reload(); //make it comment if you don't want to reload page
                            } else {
                                // another error occured  
                                alert("error: " + request + status + err);
                            }
                        }
                    });
                }
            })
        }
    </script>
    <script>
        $('#cartModalpj').on('shown.bs.modal', function() {
            $('#qty').focus();
        });
        $('#modalcartpj').on('shown.bs.modal', function() {
            $('#addcartqty').focus();
            $('#addcartqty').val(1);
        });

        function PilihBarang2(id) {
            pilihbarang(id);
        }

        function pilihbarang(id) {
            $('#btn-cari-barang').click();
            document.getElementById("form-cartpj").reset();
            var method = 'POST';
            ModalCart(id);
        }

        function ModalCart(id) {
            $.ajax({
                url: "{{ url('/barang') }}" + "/" + id,
                type: "GET",
                success: function(data) {
                    if (data.success == 1) {
                        $('#modalcartpj').modal('show');
                        document.getElementById('addcart_barang_id').value = data.id;
                        document.getElementById('addcartnama_barang').value = data.nama_barang;
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
    </script>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            reqsent = false;
            token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            base_url = document.querySelector('meta[name="base_url"]').getAttribute('content');
            pjhd_id = document.getElementById('id').value;
            invoice = document.getElementById('invoice').value;
            loadcartfooter();
            $('#caribarcode').focus();
        }, false);

        $(function() {
            $(".datepicker").datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
            });
        });
    </script>
    <script>
        // pelanggan cari
        var caripelanggan = document.getElementById("caripelanggan");
        var pelanggan = $('#pelanggan_id').selectpicker('val');
        caripelanggan.addEventListener("keyup", function(e) {
            if (e.which == 38 || e.which == 40) {
                e.preventDefault();
            } else {
                var searchData = e.target.value;
                if (searchData.length < 2) {} else {
                    $.ajax({
                        url: "{{ url('caripelanggan') }}",
                        method: "POST",
                        minLength: 3,
                        data: {
                            '_token': token,
                            caripelanggan: searchData
                        },
                        success: function(data) {
                            var html = '';
                            for (var count = 0; count < data.length; count++) {
                                html += '<option value="' + data[count].id + '" data-id="' + data[count]
                                    .id + '">' +
                                    data[count].nama + '/' + data[count].kode + '</option>';
                            }
                            $('#pelanggan_id').html(html);
                            $('#pelanggan_id').selectpicker('refresh');
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                }
            }
        });
    </script>
@stop
