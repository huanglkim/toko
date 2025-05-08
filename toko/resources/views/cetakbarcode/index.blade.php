@extends('layout.main')
@section('title', 'Cetak Barcode')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/lte/DataTables/datatables.min.css" />
    <link href="{{ url('/') }}/lte/css/select2.min.css" rel="stylesheet" />
    <link href="{{ url('/') }}/lte/css/selectpicker.min.css" rel="stylesheet" />
    <style>
        .table-responsive-sm {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    
        .card {
            margin-bottom: 20px;
            border-radius: 0.5rem;
            box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.08);
            background-color: #fff;
            border: 1px solid #ededed;
        }
    
        .card-body {
            padding: 1.5rem;
        }
    
        .btn-sm1 {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 0.375rem;
            width: 300px;
        }
    
        .btn-primary,
        .btn-success,
        .btn-danger,
        .btn-warning {
            color: #fff;
        }
    
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
    
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004080;
        }
    
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }
    
        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
    
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
    
        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
    
        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #212529;
        }
    
        .btn-warning:hover {
            background-color: #e0a800;
            border-color: #d39e00;
        }
    
        .btn-outline-success {
            background-color: transparent;
            border-color: #28a745;
            color: #28a745;
        }
    
        .btn-outline-success:hover {
            background-color: #28a745;
            color: #fff;
        }
    
        .table-bordered td,
        .table-bordered th {
            border: 1px solid #e0e0e0;
            padding: 0.75rem;
        }
    
        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }
    
        .callout {
            border-left: 0.25rem solid #17a2b8;
            margin-bottom: 1rem;
            padding: 1rem;
            border-radius: 0.375rem;
            background-color: #e0f7fa;
            border-color: #b2ebf2;
        }
    
        .modal-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            padding: 1rem;
            border-bottom: 1px solid #dee2e6;
            background-color: #f8f9fa;
        }
    
        .modal-title {
            font-size: 1.25rem;
            font-weight: 500;
            color: #2c3e50;
        }
    
        .modal-footer {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 1rem;
            border-top: 1px solid #dee2e6;
            background-color: #f8f9fa;
        }
    
        #caribarang {
            position: relative;
            /* Pastikan elemen ini menjadi containing block */
        }
    
        .bootstrap-select {
            position: relative !important; /* Tambahkan ini */
        }
    
        .bootstrap-select.open > .dropdown-menu {
            position: absolute !important;
            top: 100% !important;
            left: 0 !important;
            z-index: 1050 !important;
            display: block !important;
            float: left !important;
            min-width: 100% !important;
            margin: 0 !important;
            border: 1px solid rgba(0, 0, 0, .15) !important;
            border-radius: 0.25rem !important;
            background-color: #fff !important;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .175) !important;
        }
    
        .bootstrap-select.open > .dropdown-menu.inner {
            position: static !important;
            float: none !important;
            border: 0 !important;
            padding: 0 !important;
            margin: 0 !important;
            border-radius: 0 !important;
            background-color: transparent !important;
            box-shadow: none !important;
        }
    
        .bootstrap-select.open > .dropdown-menu > .dropdown-item {
            display: block !important;
            width: 100% !important;
            padding: 0.25rem 1.5rem !important;
            clear: both !important;
            font-weight: 400 !important;
            color: #212529 !important;
            text-align: inherit !important;
            white-space: nowrap !important;
            background-color: transparent !important;
            border: 0 !important;
        }
    
        .bootstrap-select.open > .dropdown-menu > .dropdown-item:hover,
        .bootstrap-select.open > .dropdown-menu > .dropdown-item.active {
            color: #fff !important;
            text-decoration: none !important;
            background-color: #007bff !important;
        }
    
        .bootstrap-select .dropdown-toggle:focus {
            outline: none !important;
        }
    </style>
@stop

@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header bg-secondary d-flex justify-content-between align-items-center">
                <h3 class="card-title">Cetak Barcode</h3>
            </div>

            <div class="card-body">
                <!-- Select Barcode Quantity -->
                <div class="form-group row align-items-center">
                    <label for="br" class="col-custom-label col-lg-3">Jumlah Barcode per lembar:</label>
                    <div class="col-lg-6">
                        <select name="br" id="br" class="form-control form-control-sm">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                            <option value="25">25</option>
                            <option value="30">30</option>
                            <option value="35">35</option>
                            <option value="40">40</option>
                            <option value="45">45</option>
                            <option value="50">50</option>
                            <option value="55" selected>55 (A4)</option>
                            <option value="60">60</option>
                        </select>
                    </div>
                    <div class="col-lg-3 d-flex justify-content-end">
                        <button type="button" class="btn btn-outline-primary btn-sm1" onclick="cetak1()">
                            <i class="fas fa-print"></i> Cetak Item
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm1" onclick="cetakharga()">
                            <i class="fas fa-print"></i> Cetak Label Rak
                        </button>
                    </div>
                </div>

                <!-- Search Barang -->
                <div class="form-row align-items-center">
                    <div class="col-md-3">
                        <a class="btn btn-outline-danger btn-sm1" data-toggle="collapse" href="#tabelcaribarang"
                            role="button" aria-expanded="false" aria-controls="tabelcaribarang"
                            onclick="caritabelbarang();" id="btn-cari-barang">
                            <i class="fas fa-search"></i> Cari Barang
                        </a>
                    </div>
                    <div class="col-lg-6" id="caribarang">
                        <select class="form-control form-control-sm selectpicker" name="barang_id" data-size="5"
                            data-live-search="true" data-title="Cari Nama Barang" id="barang_id" data-width="100%">
                        </select>
                    </div>
                    <div class="col-md-3 d-flex justify-content-end">
                        <button type="button" class="btn btn-outline-danger btn-sm1" onclick="reset()">
                            <i class="fas fa-trash"></i> Clear
                        </button>
                    </div>
                </div>
            </div>

            <!-- Table Data Barang -->
            <div class="collapse" id="tabelcaribarang">
                <div class="table-responsive-sm">
                    <table class="table table-bordered table-hover table-sm" id="tabel_data">
                        <thead class="thead-light">
                            <tr>
                                <th>Pilih</th>
                                <th>Barcode</th>
                                <th>Kode Internal</th>
                                <th>Nama Barang</th>
                                <th>Stok</th>
                                <th>Harga Jual</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data tabel akan dimuat di sini -->
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="table-responsive-sm" id="tabel">
                <!-- Tabel hasil pencarian barang atau lainnya -->
            </div>
        </div>
    </section>

    @include('cetakbarcode.formcart')
@stop

@section('script')
    <script type="text/javascript" src="{{ url('/') }}/lte/DataTables/datatables.min.js"></script>
    <script src="{{ asset('lte/sweetalert2/sweetalert2@11.js') }}"></script>
    <script src="{{ url('/') }}/lte/js/select2.min.js"></script>
    <script src="{{ url('/') }}/lte/js/selectpicker.js"></script>
    @include('jsblade.datatablejsget')

    <script type="text/javascript">
        function cetak1() {
            var br = document.getElementById('br').value;
            var url = "{{ url('/cetakbar1') }}" + '/' + br;
            window.open(url, 'about:blank', 'Popup_Window');
        }

        function cetakharga() {
            var br = document.getElementById('br').value;
            var url = "{{ url('/cetakbarharga') }}" + '/' + br;
            window.open(url, 'about:blank', 'Popup_Window');

        }
        //on load
        document.addEventListener("DOMContentLoaded", function() {
            cart();
            reqsent = false;
        });
        $('#barang_id').on('changed.bs.select', function() {
            var selected = $(this).find('option:selected');
            var id = selected.data('id');
            if (id == null || id == "") {} else {
                ModalCart(id);
            }
        });

        function ModalCart(id) {
            document.getElementById("form-cart").reset();
            $.ajax({
                url: "{{ url('/barang') }}" + "/" + id,
                type: "GET",
                success: function(data) {
                    $('#cartModal').modal('show');
                    document.getElementById('cart_barang_id').value = data.id;
                    document.getElementById('nama_barang').value = data.nama_barang;
                    $('.collapse').collapse('hide')
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }
        //cari barang
        var caribarang = document.getElementById("caribarang");
        var barang_id = $('#barang_id').selectpicker('val');
        caribarang.addEventListener("keyup", function(e) {
            if (e.which == 38 || e.which == 40) {
                e.preventDefault();
            } else {
                var searchData = e.target.value;
                if (searchData.length < 3) {} else {
                    $.ajax({
                        url: "{{ url('/caribarang') }}",
                        method: "POST",
                        delay: 250,
                        minLength: 3,
                        data: {
                            '_token': '{{ csrf_token() }}',
                            caribarang: searchData
                        },
                        success: function(data) {
                            var html = '';
                            for (var count = 0; count < data.length; count++) {
                                html += '<option value="' + data[count].id + '" data-id="' + data[count]
                                    .id + '">' + data[count].nama_barang +
                                    '|' + data[count].kode_internal +
                                    '|' + data[count].barcode +
                                    '</option>';
                            }
                            $('#barang_id').html(html);
                            $('#barang_id').selectpicker('refresh');
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                }
            }
        });
        $('#cartModal').on('shown.bs.modal', function() {
            $('#qty').focus();
        });
        $('#cartModal').on('hidden.bs.modal', function() {
            document.getElementById("form-cart").reset();
            $('#barang_id').selectpicker('refresh');
            $('#barang_id').selectpicker('toggle');
        });
        document.onkeydown = function(e) {
            if (e.which == 112) {
                e.preventDefault();
            } else if (e.which == 113) { //f2
                e.preventDefault();
            } else if (e.which == 114) { //f3
                e.preventDefault();
                $('#barang_id').selectpicker('toggle');
            } else if (e.altKey && e.which == 192) {}
        };
        $('#qty').keypress(function(event) {
            if (event.keyCode == 13) {
                AddOrEditcart();
            }
        });

        function cart() {
            $.ajax({
                url: "{{ url('/tabelcetakbarcode') }}",
                method: "GET",
                success: function(data) {
                    $('#tabel').html(data);
                },
                error: function(data) {
                    console.log(data);
                }
            });
        };

        function AddOrEditcart() {
            var qty = document.getElementById("qty").value;
            $('#cartModal').modal('hide');
            if (parseInt(qty) < 1) {
                Swal.fire({
                    position: 'top-end',
                    title: 'Qty kuran dari 0',
                    icon: 'error',
                    showConfirmButton: false,
                    timer: 900
                }).then(function() {
                    $('#cartModal').modal('hide');
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Qty Kurang atau kosong. Isi Qty lebih dari 0',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#cartModal').modal('show');
                        }
                    })
                });
            } else {
                var id = $('#id').val();
                if (id == '') {
                    url = "{{ url('/cetakbarcode') }}";
                    var data = $('#form-cart').serialize() + '&_method=' + 'POST';
                } else {
                    url = "{{ url('/cetakbarcode') . '/' }}" + id;
                    var data = $('#form-cart').serialize() + '&_method=' + 'PATCH';
                }
                var _token = $('#cariitem').data("token");
                var method = $('input[name=_method]').val();
                $.ajax({
                    url: url,
                    method: "POST",
                    data: data,
                    success: function(data) {
                        cart();
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            }
        };

        function hapuscart(id) {
            Swal.fire({
                title: 'Anda yakin?',
                text: "Data akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: "{{ url('cetakbarcode') }}" + '/' + id,
                        type: "POST",
                        data: {
                            '_method': 'DELETE',
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            Swal.fire({
                                position: 'top-end',
                                title: data,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 900
                            }).then(function() {
                                cart();
                            });
                        },
                    });
                }
            })
        };

        function reset() {
            Swal.fire({
                title: 'Anda yakin?',
                text: "Semua Data akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: "{{ url('/clearcetakbarcode') }}",
                        type: "GET",
                        success: function(data) {
                            Swal.fire({
                                position: 'top-end',
                                title: data,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 900
                            }).then(function() {
                                cart();
                            });
                        },
                        error: function(data) {
                            console.log(data);
                        },
                    });
                }
            })
        };

        function editcart(id) {
            document.getElementById("form-cart").reset();
            $.ajax({
                url: "{{ url('/cetakbarcode') }}" + "/" + id,
                method: "get",
                success: function(data) {
                    $('#cartModal').modal('show');
                    $('.modal-title').text('Edit');
                    document.getElementById("id").value = data.id;
                    document.getElementById("qty").value = data.qty;
                    document.getElementById("cart_barang_id").value = data.barang_id;
                    document.getElementById("nama_barang").value = data.nama_barang;
                }
            });
        };
    </script>

    <script>
        function PilihBarang2(id) {
            if (reqsent == false) {
                ModalCart(id);

            }
        }
        // Function to trigger the button click when F2 is pressed
        $(document).keydown(function(e) {
            if (e.key === 'F2') { // Check if the F2 key is pressed
                e.preventDefault(); // Prevent default action (if any)
                $('#btn-cari-barang').click(); // Trigger click event on the button
            }
        });
        // Add arrow key navigation and Enter key for selection
        $('#tabel_data tbody').on('keydown', 'tr', function(e) {
            const currentRow = $(this);

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                const nextRow = currentRow.next('tr');
                if (nextRow.length) {
                    nextRow.focus();
                }
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                const prevRow = currentRow.prev('tr');
                if (prevRow.length) {
                    prevRow.focus();
                }
            } else if (e.key === 'Enter') {
                e.preventDefault();
                selectRow(currentRow);
            }
        });

        function searchfocus() {
            $('div.dataTables_filter input').focus();
            // var top = document.getElementById('anchor').offsetTop - 180; //Getting Y of target element
            // window.scrollTo(0, top); //Go there directly or some transitio
            // Add keydown event listener to detect Enter or ArrowDown
            $('div.dataTables_filter input').on('keydown', function(e) {
                // Check if the key pressed is ArrowDown or Enter
                if (e.key === 'ArrowDown' || e.key === 'Enter') {
                    e.preventDefault(); // Prevent the default action (e.g., form submission for Enter)

                    // Focus the first row of the DataTable
                    $('#tabel_data tbody tr').first().focus(); // Focus the first row
                }
            });
        }

        function caritabelbarang() {
            $('#tabel_data').dataTable().fnClearTable();
            $('#tabel_data').dataTable().fnDestroy();
            var urlget = "{{ url('/caritabelbarang') }}";
            $('#tabel_data').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                deferRender: true,
                // lengthChange: false,
                lengthMenu: [10, 50, 75, 100, 1000],
                //bStateSave: true,
                language: {
                    processing: '<i class="fas fa-sync-alt fa-spin fa-3x fa-fw"></i><span class="bg-lime color-palette">Loading......</span>'
                },
                ajax: $.fn.dataTable.pipeline({
                    url: urlget,
                    type: "GET",
                    dataType: "JSON",
                    pages: 5, // number of pages to cache
                }),
                order: [
                    [3, 'asc']
                ],
                columns: [{
                        data: 'aksi2',
                        name: 'aksi2',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'barcode',
                        name: 'barcode'
                    },
                    {
                        data: 'kode_internal',
                        name: 'kode_internal'
                    },
                    {
                        data: 'nama_barang',
                        name: 'nama_barang'
                    },
                    {
                        data: 'stok',
                        name: 'stok'
                    },
                    {
                        data: 'harga_jual_dasar1',
                        name: 'harga_jual_dasar1'
                    },

                ],
                rowCallback: function(row, data, index) {
                    // Set both data-id and tabindex for each row
                    $(row).attr('data-id', data.id).attr('tabindex', 0); // Corrected the syntax
                }
            });
            setTimeout(searchfocus, 400);
        };
        // Handle Enter key press

        function selectRow(row) {
            const rowDataId = row.data('id'); // Get the data-id attribute
            if (reqsent == false) {
                ModalCart(rowDataId);
            }
        }
    </script>
@stop
