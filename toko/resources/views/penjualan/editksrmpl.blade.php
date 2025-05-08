@extends('layout.main')
@section('title', 'EDIT PENJUALAN TUNAI')
@section('css')
    <link href="{{ url('/') }}/lte/plugins/datepicker/css/bootstrap-datepicker.min.css" type="text/css" rel="stylesheet" />
    <link href="{{ url('/') }}/lte/css/select2.min.css" rel="stylesheet" />
    <link href="{{ url('/') }}/lte/css/selectpicker.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/lte/DataTables/datatables.min.css" />
    <link rel="stylesheet" href="{{ url('/') }}/lte/plugins/toastr/toastr.min.css">
    <style>
        /* Updated styling for modern look */
        .card {
            border-radius: 0.75rem;
            /* Rounded corners for cards */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* Subtle shadow for depth */
            border: 1px solid #e0e0e0;
            /* Add a border */
        }

        .card-header {
            background-color: #f8f9fa;
            /* Light background for header */
            border-bottom: 1px solid #e0e0e0;
            padding: 1rem;
            border-top-left-radius: 0.75rem;
            border-top-right-radius: 0.75rem;
        }

        .card-title {
            font-size: 1.25rem;
            /* Larger title */
            font-weight: 500;
            /* Medium font weight */
            color: #343a40;
            /* Darker text */
        }

        .form-control {
            border-radius: 0.375rem;
            /* Rounded corners for form controls */
            border: 1px solid #ced4da;
            padding: 0.75rem;
            /* Increased padding */
            font-size: 0.9rem;
            /* Slightly larger font */
        }

        .form-control:focus {
            border-color: #007bff;
            /* Highlight on focus */
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            /* Focus shadow */
        }

        .btn {
            border-radius: 0.375rem;
            /* Rounded corners for buttons */
            font-weight: 500;
            /* Medium font weight for buttons */
            padding: 0.75rem 1.5rem;
            /* Increased button padding */
            font-size: 0.9rem;
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
        }

        .btn-primary {
            background-color: #007bff;
            /* Primary button color */
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            /* Darker shade on hover */
            border-color: #0056b3;
        }

        .btn-secondary {
            background-color: #e0e0e0;
            border-color: #e0e0e0;
            color: #343a40;
        }

        .btn-secondary:hover {
            background-color: #c8c8c8;
            border-color: #c8c8c8;
        }

        .bg-indigo {
            background-color: #6610f2 !important;
        }

        .bg-indigo:hover {
            background-color: #5606c2 !important;
        }

        .color-palette {
            color: rgb(0, 0, 0);
        }

        .selectpicker {
            border-radius: 0.375rem;
            border: 1px solid #ced4da;
            padding: 0.375rem 0.75rem;
            font-size: 0.9rem;
            width: 100%;
            /* Ensure full width */
        }

        .selectpicker:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .nowrap th,
        .nowrap td {
            white-space: nowrap !important;
        }

        /* Styles for thead */
        .table thead th {
            background-color: #f0f0f0;
            /* Light background for table header */
            color: #343a40;
            /* Darker text for header */
            font-weight: 600;
            /* Stronger font weight for header */
            border-bottom: 2px solid #e0e0e0;
            /* Thicker bottom border */
        }

        /* Styles for tbody tr */
        .table tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
            /* Very light background for odd rows */
        }

        .table tbody tr:hover {
            background-color: #f0f0f0;
            /* Slightly darker background on hover */
        }

        /* Styles for td */
        .table td {
            border-bottom: 1px solid #e0e0e0;
            /* Lighter bottom border for cells */
            padding: 0.75rem;
            font-size: 0.9rem;
        }

        .table {
            border-collapse: separate;
            /* Important for rounded corners on table cells */
            border-spacing: 0;
            width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
            border-radius: 0.75rem;
            /* Rounded corners for the entire table */
            overflow: hidden;
            /*needed for the rounded corners to apply*/
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            /* Subtle shadow */
            border: 1px solid #e0e0e0;
        }

        .table thead tr:first-child th:first-child,
        .table tbody tr:first-child td:first-child {
            border-top-left-radius: 0.75rem;
            /* Top left rounded corner for first cell in first row */
        }

        .table thead tr:first-child th:last-child,
        .table tbody tr:first-child td:last-child {
            border-top-right-radius: 0.75rem;
            /* Top right rounded corner for last cell in first row */
        }

        .table tbody tr:last-child td:first-child {
            border-bottom-left-radius: 0.75rem;
            /* Bottom left rounded corner for first cell in last row */
        }

        .table tbody tr:last-child td:last-child {
            border-bottom-right-radius: 0.75rem;
            /* Bottom right rounded corner for last cell in last row */
        }

        .datepicker {
            border-radius: 0.375rem;
            border: 1px solid #ced4da;
            padding: 0.375rem 0.75rem;
            font-size: 0.9rem;
            width: 100%;
        }

        .datepicker:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        /* Custom styles for label and input alignment */
        .form-group-sm label {
            margin-bottom: 0.25rem;
            font-size: 0.8rem;
            font-weight: bold;
            color: #555;
        }

        .form-group-sm .form-control,
        .form-group-sm .selectpicker {
            padding: 0.375rem 0.75rem;
            font-size: 0.85rem;
            line-height: 1.5;
            border-radius: 0.375rem;
        }

        .form-group-sm .form-control:focus,
        .form-group-sm .selectpicker:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
    </style>
@stop
@section('content')
    <section class="content">
        <div class="card col-md-12">
            <div class="card-header">
                <h3 class="card-title">Tambah Marketplace</h3>
            </div>
            <form id="form-datapj" class="card-body">
                @csrf
                <div class="row">
                    <div class="col-md-3 col-lg-4">
                        <div class="form-group form-group-sm">
                            <label for="tanggal">Tanggal:</label>
                            <input data-column="tanggal" onchange="fastupdate(this)" data-id="{{ $pjhd->id }}" type="text"
                                class="form-control datepicker" name="tanggal" id="tanggal"
                                value="{{ datetotanggal($pjhd->tanggal) }}" onchange="simpandatapb()">
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-2">
                        <div class="form-group form-group-sm">
                            <label for="invoice">INV:</label>
                            <input type="text" readonly class="form-control" name="invoice" id="invoice"
                                value="{{ $pjhd->invoice }}">
                            <input type="hidden" name="id" id="id" value="{{ $pjhd->id }}">
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-2">
                        <div class="form-group form-group-sm">
                            <label for="marketplace_id">Marketplace:</label>
                            <select name="marketplace_id" id="marketplace_id" class="form-control selectpicker"
                                data-live-search="true" data-column="marketplace_id" onchange="fastupdate(this)"
                                data-id="{{ $pjhd->id }}">
                                <option value="">Pilih Marketplace</option>
                                @foreach ($marketplaces as $marketplace)
                                    <option value="{{ $marketplace->id }}"
                                        {{ $marketplace->id == $pjhd->marketplace_id ? 'selected' : '' }}>
                                        {{ $marketplace->nama_marketplace }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="form-group form-group-sm">
                            <label for="tipe_admin_mpl">Biaya Admin:</label>
                            <select name="tipe_admin_mpl" id="tipe_admin_mpl" class="form-control"
                                data-column="tipe_admin_mpl" onchange="fastupdate(this); hitungadminmpl(this);"
                                data-id="{{ $pjhd->id }}">
                                <option value="include" {{ $pjhd->tipe_admin_mpl == 'include' ? 'selected' : '' }}>Include
                                </option>
                                <option value="exclude" {{ $pjhd->tipe_admin_mpl == 'exclude' ? 'selected' : '' }}>Exclude /
                                    Tanpa Admin</option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" id="pelanggan_id" name="pelanggan_id" value="1">
                </div>
            </form>
        </div>
        @include('barang.modulcaribarang')
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 col-lg-4">
                        <div class="form-check form-check-inline">
                            <input onchange="cartpj()" type="checkbox" id="detailview" class="form-check-input">
                            <label class="form-check-label" for="detailview">Detail</label>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="form-check form-check-inline">
                            <input onchange="cartpj()" type="checkbox" id="askqty" class="form-check-input" checked>
                            <label class="form-check-label" for="askqty">QTY 1</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card" id="carttabel">
        </div>
        <div class="card" id="footerpj">
        </div>
    </section>

    @include('penjualan.modalbayar')
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
        function hitungadminmpl(note) {
            var value = note.value;
            var data = {
                '_token': token,
                id: $(note).attr("data-id"),
            };
            var url = base_url + '/hitungbadminmpl';
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
        }
    </script>
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
            var geturl = base_url + '/footerksrmpl/' + pjhd_id;
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
                        document.getElementById('addcart_barang_id').value = data.id;
                        document.getElementById('addcartnama_barang').value = data.nama_barang;
                        let isChecked = document.getElementById('askqty').checked;
                        if (isChecked) {
                            AddCartBarang();
                        } else {
                            $('#modalcartpj').modal('show');
                        }
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
        // var caripelanggan = document.getElementById("caripelanggan");
        // var pelanggan = $('#pelanggan_id').selectpicker('val');
        // caripelanggan.addEventListener("keyup", function(e) {
        //     if (e.which == 38 || e.which == 40) {
        //         e.preventDefault();
        //     } else {
        //         var searchData = e.target.value;
        //         if (searchData.length < 2) {} else {
        //             $.ajax({
        //                 url: "{{ url('caripelanggan') }}",
        //                 method: "POST",
        //                 minLength: 3,
        //                 data: {
        //                     '_token': token,
        //                     caripelanggan: searchData
        //                 },
        //                 success: function(data) {
        //                     var html = '';
        //                     for (var count = 0; count < data.length; count++) {
        //                         html += '<option value="' + data[count].id + '" data-id="' + data[count]
        //                             .id + '">' +
        //                             data[count].nama + '/' + data[count].kode + '</option>';
        //                     }
        //                     $('#pelanggan_id').html(html);
        //                     $('#pelanggan_id').selectpicker('refresh');
        //                 },
        //                 error: function(data) {
        //                     console.log(data);
        //                 }
        //             });
        //         }
        //     }
        // });
    </script>
@stop
