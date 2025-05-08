@extends('layout.main')
@section('title', 'EDIT PEMBELIAN')
@section('css')
    <link href="{{ url('/') }}/lte/plugins/datepicker/css/bootstrap-datepicker.min.css" type="text/css"
        rel="stylesheet" />
    <link href="{{ url('/') }}/lte/css/select2.min.css" rel="stylesheet" />
    <link href="{{ url('/') }}/lte/css/selectpicker.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/lte/DataTables/datatables.min.css" />
    <link rel="stylesheet" href="{{ url('/') }}/lte/plugins/toastr/toastr.min.css">

    <style>
        /* Updated styling for modern look */
        body {
            font-family: 'Inter', sans-serif;
            /* Use Inter font */
        }

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
    </style>
@stop
@section('content')
    <section class="content">
        <section class="content">
            <div class="card col-md-12">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h2 class="text-primary mb-2 mb-md-0">
                            <i class="fas fa-shopping-cart me-2"></i> Tambah Pembelian
                        </h2>
                        <button type="button" onclick="formsuplier()" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-plus-square me-1"></i> Suplier Baru
                        </button>
                    </div>
                </div>
                <form id="form-datapb" class="card-body">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tanggal" class="col-form-label font-weight-bold">Tanggal:</label>
                                <input data-column="tanggal" onchange="fastupdate(this)" data-id="{{ $pbhd->id }}"
                                       type="text" class="form-control datepicker " name="tanggal" id="tanggal"
                                       value="{{ datetotanggal($pbhd->tanggal) }}" onchange="simpandatapb()">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="invoice" class="col-form-label font-weight-bold">INV:</label>
                                <input type="text" readonly class="form-control " name="invoice"  id="invoice" value="{{ $pbhd->invoice }}">
                                <input type="hidden" name="id" id="id" value="{{ $pbhd->id }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="invoicepo" class="col-form-label font-weight-bold">INV PO:</label>
                                <div class="input-group">
                                    <input type="text" readonly class="form-control" name="invoicepo" id="invoicepo" value="{{ $pbhd->popbhd->invoice }}">
                                    <button type="button" onclick="listpo()" class="btn btn-outline-success btn-sm" {{ $pbhd->popbhd_id != null ? 'disabled' : '' }}>
                                        <i class="fas fa-search"></i>
                                        <span class="d-none d-sm-inline">Import PO</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="suplier_id" class="col-form-label font-weight-bold">Suplier:</label>
                                <select data-column="suplier_id" onchange="fastupdate(this)" data-id="{{ $pbhd->id }}"
                                        class="form-control selectpicker" id="suplier_id" name="suplier_id"
                                        data-size="7" data-live-search="true"
                                        data-title="{{ $pbhd->Suplier->nama . '/' . $pbhd->Suplier->kode }}" data-width="100%">
                                    <option value="{{ $pbhd->suplier_id }}" selected>
                                        {{ $pbhd->Suplier->nama . '/' . $pbhd->Suplier->kode }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                </form>
            </div>
            @include('barang.modulcaribarang')

            <div class="card" id="carttabel">

            </div>
            <div class="card" id="footerpb">

            </div>
        </section>
        <div class="modal fade" id="popbModal" tabindex="-1" role="dialog" aria-labelledby="sopbModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="sopbModalLabel">PO Tersimpan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-sm table-hover" id="tabel_daftarpb">
                            <thead>
                                <tr class="bg-info">
                                    <th>No.</th>
                                    <th>Tanggal</th>
                                    <th>Invoice</th>
                                    <th>Suplier</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($popbhd as $index => $po)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $po->tanggal }}</td>
                                        <td>{{ $po->invoice }}</td>
                                        <td>{{ $po->suplier->nama }}</td>
                                        <td>
                                            <a onclick="pilihpo('{{ $po->uuid }}')"
                                                class="btn btn-warning bg-pink btn-xs">
                                                <i class="fas fa-pencil-alt"></i> Pilih
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @include('suplier.form')
        @include('pembelian.formcart')

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
            $('#cartModalpb').on('keydown', function(e) {
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
                var url = base_url + '/fastupdatepbhd';
                $.ajax({
                    url: url,
                    method: "POST",
                    data: data,
                    success: function(data) {
                        reqsent = false;
                        if (data.success == 1) {
                            toastr["success"](data, data.pesan);
                            footerpb();
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
                    var geturl = base_url + '/updatepajakcartpb'; // Fixed the missing closing quote
                    $.ajax({
                        url: geturl,
                        method: "POST",
                        data: {
                            '_token': token,
                            jenisppn: document.getElementById('jenisppn').value,
                            persenpajak: document.getElementById('persenpajak').value,
                            pbhd_id: pbhd_id
                        },
                        success: function(data) {
                            reqsent = false;
                            loadcartfooter();
                            if (data.success == 1) {
                                document.getElementById("form-cartpb").reset();
                                $('#cartModalpb').modal('hide');
                                Swal.fire({
                                    position: 'top-end',
                                    title: data.pesan,
                                    icon: 'success',
                                    showConfirmButton: false,
                                    timer: 900
                                }).then(function() {
                                    cartpb(pbhd_id);
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
                cartpb();
                footerpb();
            }

            function cartpb() {
                var geturl = base_url + '/cartpb/' + pbhd_id;
                $.ajax({
                    url: geturl,
                    method: "POST",
                    data: {
                        '_token': token,
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

            function footerpb() {
                var geturl = base_url + '/footerpb/' + pbhd_id;
                $.ajax({
                    url: geturl,
                    method: "POST",
                    data: {
                        '_token': token,
                    },
                    success: function(data) {
                        $('#footerpb').html(data);
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
                    var id = document.getElementById('cart_id').value;
                    if (id == null || id == false || id == '') {
                        var url = "{{ url('/tambahcartpb') }}";
                        var method = 'POST';
                    } else {
                        var url = "{{ url('/updatecartpb') . '/' }}" + id;
                        var method = 'PATCH';
                    }
                    var data = $('#form-cartpb').serialize() + "&_method=" + method + "&pbhd_id=" + pbhd_id + "&invoice=" +
                        invoice;
                    $.ajax({
                        url: url,
                        timeout: 10000,
                        method: "POST",
                        data: data,
                        success: function(data) {
                            reqsent = false;
                            if (data.success == 1) {
                                document.getElementById("form-cartpb").reset();
                                $('#cartModalpb').modal('hide');
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
                        var geturl = base_url + '/hapuscartpb/' + id;
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
                        var geturl = base_url + '/getcartpb/' + id;
                        $.ajax({
                            url: geturl,
                            timeout: 1000,
                            method: "GET",
                            success: function(data) {
                                $('#cartModalpb').modal('show');
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
                                document.getElementById('total_pot').value = data.total_pot;
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
            $('#cartModalpb').on('shown.bs.modal', function() {
                $('#qty').focus();
            });

            function PilihBarang2(id) {
                pilihbarang(id);
            }

            function pilihbarang(id) {
                $('#btn-cari-barang').click();
                document.getElementById("form-cartpb").reset();
                var method = 'POST';
                ModalCart(id);
            }

            function ModalCart(id) {
                $.ajax({
                    url: "{{ url('/barang') }}" + "/" + id,
                    type: "GET",
                    success: function(data) {
                        if (data.success == 1) {
                            $('#cartModalpb').modal('show');
                            document.getElementById('cart_id').value = '';
                            document.getElementById('cart_barang_id').value = data.id;
                            document.getElementById('nama_barang').value = data.nama_barang;
                            document.getElementById('harga_bruto').value = data.harga_beli_terakhir;
                            document.getElementById('total_harga').value = data.harga_beli_terakhir;
                            document.getElementById('harga_netto').value = data.harga_beli_terakhir;
                            document.getElementById('total_harga_netto').value = data.harga_beli_terakhir;
                            document.getElementById('rpharga_bruto').value = strtorp(data.harga_beli_terakhir);
                            document.getElementById('rptotal_harga').value = strtorp(data.harga_beli_terakhir);
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
                pbhd_id = document.getElementById('id').value;
                invoice = document.getElementById('invoice').value;
                loadcartfooter();

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
            // suplier cari
            var carisuplier = document.getElementById("carisuplier");
            var suplier = $('#suplier_id').selectpicker('val');
            carisuplier.addEventListener("keyup", function(e) {
                if (e.which == 38 || e.which == 40) {
                    e.preventDefault();
                } else {
                    var searchData = e.target.value;
                    if (searchData.length < 2) {} else {
                        $.ajax({
                            url: "{{ url('carisuplier') }}",
                            method: "POST",
                            minLength: 3,
                            data: {
                                '_token': token,
                                carisuplier: searchData
                            },
                            success: function(data) {
                                var html = '';
                                for (var count = 0; count < data.length; count++) {
                                    html += '<option value="' + data[count].id + '" data-id="' + data[count]
                                        .id + '">' +
                                        data[count].nama + '/' + data[count].kode + '</option>';
                                }
                                $('#suplier_id').html(html);
                                $('#suplier_id').selectpicker('refresh');
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
