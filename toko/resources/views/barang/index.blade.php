@extends('layout.main')
@section('title', 'Data Barang')
@section('css')
    <link rel="stylesheet" href="{{ url('/') }}/lte/plugins/ekko-lightbox/ekko-lightbox.css">
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/lte/DataTables/datatables.min.css" />
    <link rel="stylesheet" href="{{ url('/') }}/lte/plugins/toastr/toastr.min.css">
    <link href="{{ url('/') }}/lte/css/select2.min.css" rel="stylesheet" />
    <link href="{{ url('/') }}/lte/css/selectpicker.min.css" rel="stylesheet" />
    <link href="{{ url('/') }}/lte/plugins/datepicker/css/bootstrap-datepicker.min.css" type="text/css"
        rel="stylesheet" />
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

        .callout-panel-content {
            font-size: 0.9rem;
            color: #495057;
        }
    </style>
@stop
@section('content')
    <section class="content">
        <div class="row">
            <div class="card col-md-12">
                <div class="mb-4">
                    <h2 class="text-primary">
                        <i class="fas fa-tag mr-2"></i> DAFTAR BARANG
                    </h2>
                    <div class="card">
                        <div class="card-body pb-1 pt-1">
                            <div class="d-flex flex-wrap gap-2 justify-content-end mb-3">
                                <button type="button" onclick="addForm()" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-plus-square me-1"></i> Tambah Data Barang
                                </button>
                                {{-- <button onclick="openqo()" class="btn btn-sm bg-primary color-palette btn-flat btn-primary"
                            id="btnquickopname" type="button">
                            Quick Add and Opname
                        </button> --}}
                                <button type="button" class="btn btn-sm btn-outline-success" data-toggle="modal"
                                    data-target="#importExcel">
                                    <i class="fas fa-file-import me-2"></i> Import Excel
                                </button>
                                <a href="{{ publicfolder() }}/file_barang/ContohBarang.xlsx"
                                    class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-download me-2"></i> Contoh Excel
                                </a>
                                {{-- <button onclick="nonaktifallcek()" type="button"
                            class="btn btn-sm bg-danger color-palette btn-flat">
                            <i class="fas fa-plus-square"> </i> NON AKTIFKAN SEMUA BARANG TERCENTANG
                        </button> --}}
                                {{-- <button type="button" class="btn btn-primary reload float-right mb-3">Reload</button> --}}
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="callout-panel">
                                        <h6 class="callout-panel-title">Status</h6>
                                        <select class="custom-select custom-select-sm"
                                            onchange="datatable(); saveValuegetstatus(this);" id="getstatus"
                                            name="getstatus">
                                            <option value="1" selected>🟢 Aktif</option>
                                            <option value="0">🔴 Tidak Aktif</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="callout-panel">
                                        <h6 class="callout-panel-title"> Merk :</h6>
                                        <select class="custom-select custom-select-sm" data-live-search="true"
                                            onchange="datatable();" id="getmerkbarang" name="getmerkbarang">
                                            <option value=""selected>SEMUA</option>
                                            @foreach ($merkbarang as $mb)
                                                <option value="{{ $mb->id }}">{{ $mb->nama_merkbarang }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="callout-panel">
                                        <h6 class="callout-panel-title"> Jenis :</h6>
                                        <select class="custom-select custom-select-sm" onchange="datatable();"
                                            id="getjenisbarang" name="getjenisbarang" data-live-search="true">
                                            <option value="" selected>SEMUA</option>
                                            @foreach ($jenisbarang as $jb)
                                                <option value="{{ $jb->id }}">{{ $jb->nama_jenisbarang }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12" style="background-color:rgb(230, 230, 230);">
                                    <div class="collapse" id="quickopname">
                                        <form id="barang_qo">
                                            @csrf
                                            <div class="card-body">
                                                <div class="form-group row">
                                                    <div class="col-lg-2">
                                                        <input type="text" class="form-control form-control-sm"
                                                            id="qb_nama_barang" name="qb_nama_barang"
                                                            placeholder="NAMA ITEM">
                                                    </div>

                                                    <div class="col-lg-2">
                                                        <input type="text" class="form-control form-control-sm"
                                                            id="qb_part_number" name="qb_part_number"
                                                            placeholder="Part Number">
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <input type="text" class="form-control form-control-sm"
                                                            id="qb_kendaraan" name="qb_kendaraan" placeholder="Kendaraan">
                                                    </div>
                                                    <div class="col-lg-2">
                                                        {{-- {!! Form::select('satuan_id', PluckSatuan(), null, [
                                                'class' => 'form-control form-control-sm select',
                                                'id' => 'qb_satuan_id',
                                                'name' => 'qb_satuan_id',
                                                'required',
                                            ]) !!} --}}
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-2">

                                                        <input type="number" class="form-control form-control-sm"
                                                            id="qb_harga_beli" name="qb_harga_beli"
                                                            placeholder="Harga Beli">
                                                    </div>
                                                    <div class="col-lg-2">

                                                        <input type="number" class="form-control form-control-sm"
                                                            id="qb_qty_beli" name="qb_qty_beli" placeholder="Qty Beli">
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <input type="number" class="form-control form-control-sm"
                                                            id="qb_harga_jual" name="qb_harga_jual"
                                                            placeholder="Harga Jual">
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <button type="button" onclick="tambahqo()"
                                                            class="btn btn-sm btn-warning btn flat"><i
                                                                class="fas fa-plus-square">
                                                            </i>TAMBAH ITEM</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>

                            <div class="row pt-2">
                                @if ($errors->has('file'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('file') }}</strong>
                                    </span>
                                @endif
                                {{-- notifikasi sukses --}}
                                @if ($sukses = Session::get('sukses'))
                                    <div class="alert alert-success alert-block">
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                        <strong>{{ $sukses }}</strong>
                                    </div>
                                @endif
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm table-hover nowrap" id="tabel_data">
                                    <thead>
                                        <tr class="table-primary">
                                            {{-- <th>FOTO</th> --}}
                                            {{-- <th>
                                    <label for="master1">PILIH</label>
                                    <input type="checkbox" id="master1">
                                </th> --}}
                                            <th>Kode</th>
                                            <th>Kode Internal</th>
                                            <th>Barcode</th>
                                            <th>Nama Barang</th>
                                            <th>Satuan</th>
                                            <th>Stok</th>
                                            <th>Harga Jual 1</th>
                                            <th>Harga Jual 2</th>
                                            <th>Merk Barang</th>
                                            <th>Jenis Barang</th>
                                            <th>Aksi</th>
                                            <th>Suplier</th>
                                            <th>Ket.</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('barang.form')
    @include('barang.formeditharga')
    {{-- @include('login.otoritasform') --}}
    @include('barang.modalimport')
    @include('suplier.form')
    @include('merkbarang.form')
    @include('jenisbarang.form')
    @include('satuan.form')

    <!-- Import Excel -->

@stop
@section('script')
    <script src="{{ url('/') }}/lte/plugins/datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="{{ url('/') }}//lte/js/select2.min.js"></script>
    <script src="{{ url('/') }}/lte/js/selectpicker.js"></script>
    <script>
        $(function() {
            $(document).on('click', '[data-toggle="lightbox"]', function(event) {
                event.preventDefault();
                $(this).ekkoLightbox({
                    alwaysShowClose: true
                });
            });

            //$('.filter-container').filterizr({gutterPixels: 3});
            $('.btn[data-filter]').on('click', function() {
                $('.btn[data-filter]').removeClass('active');
                $(this).addClass('active');
            });
        })
    </script>
    <script src="{{ url('/') }}/lte/plugins/ekko-lightbox/ekko-lightbox.min.js"></script>
    <script src="{{ url('/') }}/lte/plugins/filterizr/jquery.filterizr.min.js"></script>
    <script src="{{ url('/') }}/lte/plugins/jquery/jquery.jscroll.min.js"></script>
    <script type="text/javascript" src="{{ url('/') }}/lte/DataTables/datatables.min.js"></script>
    @include('jsblade.datatablejs')
    <script src="{{ asset('lte/sweetalert2/sweetalert2@11.js') }}"></script>
    <script src="{{ url('/') }}/lte/plugins/toastr/toastr.min.js"></script>
    @include('jsblade.toasttrjs')
    <script>
        //jenisbarang
        function formjenisbarang() { //form tambah show
            $('#modal-form-jenisbarang').modal('show');
            $('#modal-form-jenisbarang form')[0].reset();
            $('.modal-title').text('Tambah Jenisbarang');
        }

        function AddOrEditJenisbarang() { //tambah atau edit //store update
            if (reqsent == false) {
                reqsent = true;
                var id = $('#id').val();
                if (save_method == 'POST') url = "{{ url('jenisbarang') }}";
                else url = "{{ url('jenisbarang') . '/' }}" + id;
                var data = $('#form-jenisbarang').serialize();
                $.ajax({
                    url: url,
                    method: "POST",
                    data: data,
                    success: function(data) {
                        reqsent = false;
                        if (data.success == 1) {
                            $('#modal-form-jenisbarang').modal('hide');
                            Swal.fire({
                                position: 'top-end',
                                title: data.pesan,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 900
                            }).then(function() {
                                // Get the select element
                                const selectElement = document.getElementById('jenisbarang_id');

                                // Create a new option element
                                const newOption = document.createElement('option');
                                newOption.value = data.result.id; // Set the value
                                newOption.text = data.result.nama_jenisbarang; // Set the display text

                                // Add the new option to the select element
                                selectElement.add(newOption);

                                // Select the new option
                                selectElement.value = data.result.id;

                                // Refresh Bootstrap Selectpicker to update the UI
                                $('.selectpicker').selectpicker('refresh');
                            });
                        } else {
                            if (data.success == 0) {
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
                                if (data.nama_jenisbarang) {
                                    $('#nama_jenisbarang').addClass('is-invalid');
                                    $('#errornama_jenisbarang').html(data.nama_jenisbarang);
                                }
                            }
                        }
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            }

        }
    </script>
    <script>
        //satuan
        function formsatuan() { //form tambah show
            $('#modal-form-satuan').modal('show');
            $('#modal-form-satuan form')[0].reset();
            $('.modal-title').text('Tambah Satuan');
        }

        function AddOrEditSatuan() { //tambah atau edit //store update
            if (reqsent == false) {
                reqsent = true;
                var id = $('#id').val();
                if (save_method == 'POST') url = "{{ url('satuan') }}";
                else url = "{{ url('satuan') . '/' }}" + id;
                var data = $('#form-satuan').serialize();
                $.ajax({
                    url: url,
                    method: "POST",
                    data: data,
                    success: function(data) {
                        reqsent = false;
                        if (data.success == 1) {
                            $('#modal-form-satuan').modal('hide');
                            Swal.fire({
                                position: 'top-end',
                                title: data.pesan,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 900
                            }).then(function() {
                                // Get the select element
                                const selectElement = document.getElementById('satuan_id');

                                // Create a new option element
                                const newOption = document.createElement('option');
                                newOption.value = data.result.id; // Set the value
                                newOption.text = data.result.nama_satuan; // Set the display text

                                // Add the new option to the select element
                                selectElement.add(newOption);

                                // Select the new option
                                selectElement.value = data.result.id;

                                // Refresh Bootstrap Selectpicker to update the UI
                                $('.selectpicker').selectpicker('refresh');
                            });
                        } else {
                            if (data.success == 0) {
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
                                if (data.nama_satuan) {
                                    $('#nama_satuan').addClass('is-invalid');
                                    $('#errornama_satuan').html(data.nama_satuan);
                                }
                            }
                        }
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            }

        }
    </script>
    <script>
        //merkbarang
        function formmerkbarang() { //form tambah show
            $('#modal-form-merkbarang').modal('show');
            $('#modal-form-merkbarang form')[0].reset();
            $('.modal-title').text('Tambah MERK');
        }

        function AddOrEditMerkbarang() { //tambah atau edit //store update
            if (reqsent == false) {
                reqsent = true;
                var id = $('#id').val();
                if (save_method == 'POST') url = "{{ url('merkbarang') }}";
                else url = "{{ url('merkbarang') . '/' }}" + id;
                var data = $('#form-merkbarang').serialize();
                $.ajax({
                    url: url,
                    method: "POST",
                    data: data,
                    success: function(data) {
                        reqsent = false;
                        if (data.success == 1) {
                            $('#modal-form-merkbarang').modal('hide');
                            Swal.fire({
                                position: 'top-end',
                                title: data.pesan,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 900
                            }).then(function() {
                                // Get the select element
                                const selectElement = document.getElementById('merkbarang_id');

                                // Create a new option element
                                const newOption = document.createElement('option');
                                newOption.value = data.result.id; // Set the value
                                newOption.text = data.result.nama_merkbarang; // Set the display text

                                // Add the new option to the select element
                                selectElement.add(newOption);

                                // Select the new option
                                selectElement.value = data.result.id;

                                // Refresh Bootstrap Selectpicker to update the UI
                                $('.selectpicker').selectpicker('refresh');
                            });
                        } else {
                            if (data.success == 0) {
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
                                if (data.nama_merkbarang) {
                                    $('#nama_merkbarang').addClass('is-invalid');
                                    $('#errornama_merkbarang').html(data.nama_merkbarang);
                                }
                            }
                        }
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            }

        }
    </script>

    <script type="text/javascript">
        $(function() {
            $(".datepicker").datepicker({
                format: 'ddmmyy',
                autoclose: true,
                todayHighlight: true,
            });
        });

        function formsuplier() { //form tambah show
            $('#modal-form-suplier').modal('show');
            $('#modal-form-suplier form')[0].reset();
            $('.modal-title').text('Tambah Suplier');
        }


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
                            '_token': '{{ csrf_token() }}',
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


        function AddOrEditsup() { //tambah atau edit //store update
            if (reqsent == false) {
                reqsent = true;
                var id = $('#id').val();
                var url = "{{ url('suplier') }}";
                var data = $('input[name!=_method]', '#form-suplier').serialize();
                // console.log(data);
                // return false;
                $.ajax({
                    url: url,
                    method: "POST",
                    data: data,
                    success: function(data) {
                        reqsent = false;
                        if (data.success == 1) {
                            $('#modal-form-suplier').modal('hide');
                            Swal.fire({
                                position: 'top-end',
                                title: data.pesan,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 900
                            }).then(function() {
                                document.getElementById('suplier_id').value = data.id_sup;
                                ubahtitle(data.nama_sup);
                            });
                        } else {
                            if (data.success == 0) {
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
                                if (data.kode) {
                                    $('#kode').addClass('is-invalid');
                                    $('#errorkode').html(data.kode);
                                }
                                if (data.nama) {
                                    $('#nama').addClass('is-invalid');
                                    $('#errornama').html(data.nama);
                                }
                                if (data.alamat) {
                                    $('#alamat').addClass('is-invalid');
                                    $('#erroralamat').html(data.alamat);
                                }
                                if (data.kota) {
                                    $('#kota').addClass('is-invalid');
                                    $('#errorkota').html(data.kota);
                                }
                                if (data.wa) {
                                    $('#wa').addClass('is-invalid');
                                    $('#errorwa').html(data.wa);
                                }
                                if (data.telp) {
                                    $('#telp').addClass('is-invalid');
                                    $('#errortelp').html(data.telp);
                                }
                            }
                        }
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            }
        }

        function ubahtitle(nama_sup) {
            var selectpicker = $("#suplier_id");
            selectpicker.selectpicker();
            selectpicker.selectpicker({
                title: nama_sup
            }).selectpicker('render');
            html = '';
            selectpicker.html(html);
            selectpicker.selectpicker("refresh");
        }

        function openqo() {
            $("#quickopname").collapse('toggle');
            $("#qb_nama_barang").focus();
            reqsent = false;
            $("#qb_nama_barang").focus();
            $('#qb_nama_barang').removeClass('is-invalid');
            $('#qb_harga_jual').removeClass('is-invalid');
            $('#qb_harga_beli').removeClass('is-invalid');
            $('#qb_qty_beli').removeClass('is-invalid');
        }

        function tambahqo() {
            if (reqsent == false) {
                reqsent = true;
                var url = "{{ url('tambahqo') }}";
                var data = $('#barang_qo').serialize();
                $.ajax({
                    url: url,
                    method: "POST",
                    data: data,
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
                                //berhasil
                                $('#barang_qo').trigger("reset");
                                openqo();
                            });
                        } else {
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
                            } else {
                                if (data.qb_nama_barang) {
                                    $('#qb_nama_barang').addClass('is-invalid');
                                }
                                if (data.qb_harga_jual) {
                                    $('#qb_harga_jual').addClass('is-invalid');
                                }
                                if (data.qb_harga_beli) {
                                    $('#qb_harga_beli').addClass('is-invalid');
                                }
                                if (data.qb_qty_beli) {
                                    $('#qb_qty_beli').addClass('is-invalid');
                                }
                            }
                        }
                    },
                    error: function(data) {
                        console.log(data);
                        reqsent = false;
                    }
                });
            }
        }
    </script>
    <script type="text/javascript">
        $('#modal-form').on('shown.bs.modal', function() {
            $('#suplier_id').selectpicker('refresh');
            $('#nama').focus();
        });
        $('#modal-editharga').on('shown.bs.modal', function() {
            $('#harga1_edit').focus();
        });
        var harga1_editlisten = document.getElementById("harga1_edit");
        harga1_editlisten.addEventListener("keyup", function(event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                $('#harga2_edit').focus();
            }
        });
        var harga2_editlisten = document.getElementById("harga2_edit");
        harga2_editlisten.addEventListener("keyup", function(event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                AddOrEditharga();
            }
        });

        function EditHarga(id) {
            reqsent = false;
            $('#modal-editharga form')[0].reset();
            $.ajax({
                url: "{{ url('barang') }}" + '/' + id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    if (data.success == 1) {
                        $('#modal-editharga').modal('show');
                        $('.modal-title').text('Edit harga');
                        $('#id_editharga').val(data.id);
                        $('#harga1_edit').val(data.harga1);
                        $('#harga2_edit').val(data.harga2);
                    }
                    if (data.success == 0) {
                        $('#modal-editharga').modal('hide');
                        Swal.fire({
                            position: 'top-end',
                            title: data.pesan,
                            icon: 'error',
                            showConfirmButton: false,
                            timer: 900
                        }).then(function() {
                            console.log(data);
                            // location.reload();
                        });
                    }
                },
                error: function() {
                    alert("Tidak ada data");
                }
            });
        }

        function AddOrEditharga() { //tambah atau edit //store update
            if (reqsent == false) {
                reqsent = true;
                var id = $('#id_editharga').val();
                var url = "{{ url('barangeditharga') . '/' }}" + id;
                var data = $('#form-editharga').serialize();
                $.ajax({
                    url: url,
                    method: "POST",
                    data: data,
                    success: function(data) {
                        reqsent = false;
                        if (data.success == 1) {
                            $('#modal-editharga').modal('hide');
                            Swal.fire({
                                position: 'top-end',
                                title: data.pesan,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 900
                            }).then(function() {
                                datatable();
                            });
                        } else {
                            if (data.success == 0) {
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
                                if (data.harga1) {
                                    $('#harga1_edit').addClass('is-invalid');
                                    $('#errorharga1_edit').html(data.harga1);
                                }
                                if (data.harga1) {
                                    $('#harga2_edit').addClass('is-invalid');
                                    $('#errorharga2_edit').html(data.harga2);
                                }
                            }
                        }
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            }
        }

        function addForm() { //form tambah show
            save_method = "POST";
            reqsent = false;
            $('input[name=_method]').val('POST');
            $('#modal-form').modal('show');
            $('#modal-form form')[0].reset();
            $('.modal-title').text('Tambah Barang');
        }

        function AddOrEdit() { //tambah atau edit //store update
            if (reqsent == false) {
                reqsent = true;
                var id = $('#id').val();
                if (save_method == 'POST') url = "{{ url('barang') }}";
                else url = "{{ url('barang') . '/' }}" + id;
                var data = $('#form-barang').serialize();
                var form = $('#form-barang')[0];
                var datafoto = new FormData(form);
                // console.log(data);
                // return false;
                $.ajax({
                    url: url,
                    method: "POST",
                    enctype: 'multipart/form-data',
                    processData: false,
                    contentType: false,
                    cache: false,
                    timeout: 600000,
                    data: datafoto ? datafoto : data,
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
                                datatable();
                            });
                        } else {
                            if (data.success == 0) {
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
                                if (data.nama_barang) {
                                    $('#nama_barang').addClass('is-invalid');
                                    $('#errornama_barang').html(data.nama_barang);
                                }

                            }
                        }
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            }
        }
        // otoritas
        function DeleteData(id) {
            document.getElementById('otidinput').value = id;
            document.getElementById('ottipe_akses').value = 0;
            //0 hapus, 1 edit, 2 edit readonly, 3 custom
            document.getElementById('otakses_id').value = 1;
            //1 delete/edit , 2/ed harga, 3/ed pj, 4/ed pb, 5/ed pl sp, 6/ed br
            otoritas();
        }

        function EditData(id) {
            document.getElementById('otidinput').value = id;
            document.getElementById('ottipe_akses').value = 1;
            //0 hapus, 1 edit, 2 edit readonly, 3 custom
            document.getElementById('otakses_id').value = 6;
            //1 delete/edit , 2/ed harga, 3/ed pj, 4/ed pb, 5/ed pl sp, 6/ed br
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
        function OtEdit(id) {
            $('#suplier_id').selectpicker('refresh');
            reqsent = false;
            save_method = 'PATCH';
            $('input[name=_method]').val('PATCH');
            $('#modal-form form')[0].reset();
            $.ajax({
                url: "{{ url('barang') }}" + '/' + id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    if (data.success == 1) {
                        $('#modal-form').modal('show');
                        $('.modal-title').text('Edit Harga');
                        $('#id').val(data.id);
                        $('#nama_barang').val(data.nama_barang);
                        $('#minimum').val(data.minimum);
                        $('#satuan_id').val(data.satuan_id);
                        $('#satuan_id').selectpicker('refresh');
                        $('#merkbarang_id').val(data.merkbarang_id);
                        $('#merkbarang_id').selectpicker('refresh');
                        $('#jenisbarang_id').val(data.jenisbarang_id);
                        $('#jenisbarang_id').selectpicker('refresh');
                        $('#harga_beli_terakhir').val(parseInt(data.harga_beli_terakhir) + 0);
                        $('#harga_jual_dasar1').val(parseInt(data.harga_jual_dasar1) + 0);
                        $('#harga_jual_dasar2').val(parseInt(data.harga_jual_dasar2) + 0);
                        $('#barcode').val(data.barcode);
                        $('#keterangan').val(data.keterangan);
                        $('#status').val(data.status);
                        $('#kode_internal').val(data.kode_internal);
                        var html = '<option value="' + data.suplier_id + '">' +
                            data.nama_sup + '/' + data.kode_sup + '</option>';
                        $('#suplier_id').html(html);
                        $('#suplier_id').selectpicker('refresh');
                        $('#suplier_id').val(data.suplier_id);
                    }
                    if (data.success == 0) {
                        $('#modal-form').modal('hide');
                        Swal.fire({
                            position: 'top-end',
                            title: data.pesan,
                            icon: 'error',
                            showConfirmButton: false,
                            timer: 900
                        }).then(function() {
                            console.log(data);
                            // location.reload();
                        });
                    }
                },
                error: function() {
                    alert("Tidak ada data");
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
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    //open login
                    $.ajax({
                        url: "{{ url('barang') }}" + '/' + id,
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
            })
        }

        function RestoreTrash(id) {

            Swal.fire({
                title: 'Anda yakin?',
                text: "Data Akan DiKembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Kembalikan!'
            }).then((result) => {
                if (result.value) {
                    //open login
                    $.ajax({
                        url: "{{ url('restorebarang') }}" + '/' + id,
                        type: "POST",
                        data: {
                            '_method': 'PATCH',
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
            })
        }

        function editsup(id) {
            $('#suplier_id2').selectpicker('refresh');
            reqsent = false;
            save_method = 'PATCH';
            $('input[name=_method]').val('PATCH');
            $('#modal-form form')[0].reset();
            $.ajax({
                url: "{{ url('barang') }}" + '/' + id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    if (data.success == 1) {
                        $('#modal-onlysup').modal('show');
                        $('.modal-title').text('Edit SUPLIER');
                        $('#id_barang2').val(data.id);
                        $('#kode_sup2').val(data.kode_sup);
                        $('#suplier_id2').val(data.suplier_id);
                    }
                    if (data.success == 0) {
                        $('#modal-onlysup').modal('hide');
                        Swal.fire({
                            position: 'top-end',
                            title: data.pesan,
                            icon: 'error',
                            showConfirmButton: false,
                            timer: 900
                        }).then(function() {
                            console.log(data);
                            // location.reload();
                        });
                    }
                },
                error: function() {
                    alert("Tidak ada data");
                }
            });
        }
        // suplier2 cari
        var carisuplier2 = document.getElementById("carisuplier2");
        var suplier2 = $('#suplier_id2').selectpicker('val');
        carisuplier2.addEventListener("keyup", function(e) {
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
                            '_token': '{{ csrf_token() }}',
                            carisuplier: searchData
                        },
                        success: function(data) {
                            var html = '';
                            for (var count = 0; count < data.length; count++) {
                                html += '<option value="' + data[count].id + '" data-id="' + data[count]
                                    .id + '">' +
                                    data[count].nama + '/' + data[count].kode + '</option>';
                            }
                            $('#suplier_id2').html(html);
                            $('#suplier_id2').selectpicker('refresh');
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                }
            }
        });

        function simpansuplier() {
            if (reqsent == false) {
                var url = "{{ url('barangsimpansuplier') }}";
                var data = $('#form-onlysup').serialize();
                reqsent = true;
                $.ajax({
                    url: url,
                    method: "POST",
                    processData: false,
                    contentType: false,
                    cache: false,
                    timeout: 600000,
                    data: data,
                    success: function(data) {
                        reqsent = false;
                        if (data.success == 1) {
                            $('#modal-onlysup').modal('hide');
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
                                    title: 'ERROR HUBUNGI ADMIN',
                                    icon: 'error',
                                    showConfirmButton: false,
                                    timer: 900
                                }).then(function() {
                                    console.log(data);
                                });
                            } else {}
                        }
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            }
        }
    </script>

    <script type="text/javascript">
        $('#master1').on('click', function(e) {
            if ($(this).is(':checked', true)) {
                $(".sub_chk1").prop('checked', true);
            } else {
                $(".sub_chk1").prop('checked', false);
            }
        });

        function nonaktifallcek() {
            Swal.fire({
                title: 'Anda yakin?',
                text: "Data Tercentang Akan Di Non aktifkan Semua!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Non aktifkan!'
            }).then((result) => {
                if (result.value) {
                    if (reqsent == false) {
                        var allVals = [];
                        $(".sub_chk1:checked").each(function() {
                            allVals.push($(this).attr('data-id'));
                        });
                        if (allVals.length <= 0) {
                            alert("Pilih Data Terlebih dahulu");
                        } else {
                            reqsent = true;
                            Swal.fire({
                                title: "Loading...",
                                text: "Harap Tunggu",
                                imageUrl: '{{ publicfolder() }}' + '/images/loadingt.gif',
                                showConfirmButton: false,
                                allowOutsideClick: false
                            })
                            var join_selected_values1 = allVals.join(",");
                            var csrf_token = "{{ csrf_token() }}";
                            $.ajax({
                                url: "{{ url('/nonaktifallcek') }}",
                                type: 'POST',
                                data: {
                                    '_token': csrf_token,
                                    'ids': join_selected_values1,
                                },
                                success: function(data) {
                                    reqsent = false;
                                    if (data['success']) {
                                        Swal.fire({
                                            position: 'top-end',
                                            title: data['success'],
                                            icon: 'success',
                                            showConfirmButton: false,
                                            timer: 800
                                        }).then(function() {
                                            datatable();
                                        });
                                    } else {
                                        console.log('data');
                                    }
                                },
                                error: function(data) {
                                    reqsent = false;
                                    console.log('data');
                                }
                            });
                        }
                    }
                }
            })
        }

        function datatable() {
            $('#tabel_data').dataTable().fnClearTable();
            $('#tabel_data').dataTable().fnDestroy();
            var status = $('#getstatus').val();
            var merkbarang = $('#getmerkbarang').val();
            var jenisbarang = $('#getjenisbarang').val();
            var urlget = "{{ url('/tabelbarang') }}";
            $('#tabel_data').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                deferRender: true,
                bStateSave: true,
                retrieve: true,
                searchHighlight: true,
                lengthMenu: [100, 10, 50, 75, 500, 1000],
                language: {
                    processing: '<i class="fas fa-sync-alt fa-spin fa-3x fa-fw"></i><span class="bg-lime color-palette">Loading......</span>'
                },

                ajax: $.fn.dataTable.pipeline({
                    url: urlget,
                    type: "POST",
                    dataType: "JSON",
                    pages: 2, // number of pages to cache
                    data: {
                        '_token': '{{ csrf_token() }}',
                        status: status,
                        merkbarang: merkbarang,
                        jenisbarang: jenisbarang,
                    },
                }),
                order: [
                    [3, 'ASC']
                ],

                columns: [{
                        data: 'kode',
                        name: 'kode',
                    },
                    {
                        data: 'kode_internal',
                        name: 'kode_internal',
                    },
                    {
                        data: 'barcode',
                        name: 'barcode',
                    },
                    {
                        data: 'nama_barang',
                        name: 'nama_barang',
                    },
                    {
                        data: 'satuan.nama_satuan',
                        name: 'satuan.nama_satuan',
                    },
                    {
                        data: 'stok',
                        name: 'stok',
                        className: 'text-center'

                    },
                    {
                        data: 'harga_jual_dasar1',
                        name: 'harga_jual_dasar1',
                        className: 'text-right'
                    },
                    {
                        data: 'harga_jual_dasar2',
                        name: 'harga_jual_dasar2',
                        className: 'text-right'

                    },
                    {
                        data: 'merkbarang.nama_merkbarang',
                        name: 'merkbarang.nama_merkbarang',
                    },
                    {
                        data: 'jenisbarang.nama_jenisbarang',
                        name: 'jenisbarang.nama_jenisbarang',
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'suplier.nama',
                        name: 'suplier.nama',
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan',
                    },

                ]
            });

        }
    </script>
    <script type="text/javascript">
        function fastupdatebarang(note) {
            var value = note.value;
            var data = {
                '_token': "{{ csrf_token() }}",
                id: $(note).attr("data-id"),
                column: $(note).attr("data-column"),
                value: value,
            };
            var url = "{{ url('/fastupdatebarang') }}";
            $.ajax({
                url: url,
                method: "POST",
                data: data,
                success: function(data) {
                    console.log(data);
                    reqsent = false;
                    if (data.success == 1) {
                        toastr["success"](data, data.pesan);
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

        function ubahhargasatu(note) {
            var hg = note.value;
            var harga = hg.replace(/\D/g, "");
            var data = {
                '_token': "{{ csrf_token() }}",
                id: $(note).attr("data-id"),
                harga1: harga,
            };
            var url = "{{ url('/updatehargajual') }}";
            $.ajax({
                url: url,
                method: "POST",
                data: data,
                success: function(data) {
                    reqsent = false;
                    toastr["success"](data, "Berhasil");
                },
                error: function(data) {
                    console.log(data);
                }
            });
        };

        function ubahhargadua(note) {
            var hg = note.value;
            var harga = hg.replace(/\D/g, "");
            var data = {
                '_token': "{{ csrf_token() }}",
                id: $(note).attr("data-id"),
                harga2: harga,
            };
            var url = "{{ url('/updatehargajual') }}";
            $.ajax({
                url: url,
                method: "POST",
                data: data,
                success: function(data) {
                    reqsent = false;
                    toastr["success"](data, "Berhasil");
                },
                error: function(data) {
                    console.log(data);
                }
            });
        };

        function saveValuegetstatus(e) {
            var id = e.id; // get the sender's id to save it . 
            var val = e.value; // get the value. 
            localStorage.setItem(id, val); // Every time user writing something, the localStorage's value will override . 
        }

        function getSavedValuegetstatus(v) {
            if (!localStorage.getItem(v)) {
                return "1"; // You can change this to your defualt value. 
            }
            return localStorage.getItem(v);
        }
        $(document).ready(function() {
            //document.getElementById("getstatus").value = getSavedValuegetstatus("getstatus");
            datatable();
            reqsent = false;
            // Get references to the search filter and "Show" entries dropdown
            //filterdulu();
        });
    </script>

@stop
