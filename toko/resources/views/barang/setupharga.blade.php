@extends('layout.main')
@section('title', 'Setup Harga')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/lte/DataTables/datatables.min.css" />
    <link href="{{ url('/') }}/lte/css/select2.min.css" rel="stylesheet" />
    <link href="{{ url('/') }}/lte/css/selectpicker.min.css" rel="stylesheet" />
@stop
@section('content')
    <section class="content">
        <div class="card">
            <div class="card-body pb-1 pt-1">
                <div class="row">
                    <div class="callout callout-success col-sm-12">
                        <input type="hidden" id="getstatus" value="1">
                        <form id="form-gantiharga">
                            @csrf
                            <div class="row">
                                <div class="input-group col-sm-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">%</span>
                                    </div>
                                    <input type="text" class="form-control col-sm-7" name="persen" id="persen"
                                        value="0">
                                </div>
                                <div class="input-group col-sm-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">Rp.</span>
                                    </div>
                                    <input type="text" class="form-control col-sm-9" placeholder="HARGA RUPIAH"
                                        name="rupiah" id="rupiah" value="0">
                                </div>
                                <div class="input-group col-sm-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">NAIK/TURUN</span>
                                    </div>
                                    <select name="tipe_ganti_harga" id="tipe_ganti_harga" class="form-control col-sm-5">
                                        <option value="1">NAIK</option>
                                        <option value="2">TURUN</option>
                                    </select>
                                </div>
                                <a onclick="gantiharga()" class="btn btn-danger bg-dark btn-flat">GANTI HARGA</a>
                            </div>
                        </form>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-lg-4 row">
                        <label for="getstatus" class="col-4 text-right"> Status :
                        </label>
                        <select class="custom-select custom-select-sm col-8 form-control-sm" onchange="datatable();"
                            id="getstatus" name="getstatus">
                            <option value="1" selected:selected>Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="col-lg-4 row">
                        <label for="getmerkbarang" class="col-4 text-right"> Merk :
                        </label>
                        <select class="form-control-sm col-8 form-control selectpicker" data-live-search="true"
                            onchange="datatable();" id="getmerkbarang" name="getmerkbarang">
                            <option value=""selected>SEMUA</option>
                            @foreach ($merkbarang as $mb)
                                <option value="{{ $mb->id }}">{{ $mb->nama_merkbarang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4 row">
                        <label for="getjenisbarang" class="col-4 text-right"> Jenis :
                        </label>
                        <select class="form-control-sm col-8 form-control selectpicker" onchange="datatable();"
                            id="getjenisbarang" name="getjenisbarang" data-live-search="true">
                            <option value="" selected>SEMUA</option>
                            @foreach ($jenisbarang as $jb)
                                <option value="{{ $jb->id }}">{{ $jb->nama_jenisbarang }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm table-hover nowrap" id="tabel_data">
                        <thead>
                            <tr class="table-primary">
                                <th><input type="checkbox" id="master">
                                    <label class="form-check-label" for="master">Pilih
                                    </label>
                                </th>
                                <th>Kode Internal</th>
                                <th>Nama Barang</th>
                                <th>Satuan</th>
                                <th>Harga Jual 1</th>
                                <th>Harga Jual 2</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@stop
@section('script')
    <script type="text/javascript" src="{{ url('/') }}/lte/DataTables/datatables.min.js"></script>
    <script src="{{ url('/') }}//lte/js/select2.min.js"></script>
    <script src="{{ url('/') }}/lte/js/selectpicker.js"></script>
    @include('jsblade.datatablejs')
    <script src="{{ asset('lte/sweetalert2/sweetalert2@11.js') }}"></script>
    <script type="text/javascript">
        $('#master').on('click', function(e) {
            console.log(1);
            if ($(this).is(':checked', true)) {
                $(".sub_chk1").prop('checked', true);
            } else {
                $(".sub_chk1").prop('checked', false);
            }
        });

        function gantiharga() {
            var allVals = [];
            $(".sub_chk1:checked").each(function() {
                allVals.push($(this).attr('data-id'));
            });
            if (allVals.length <= 0) {
                alert("Pilih Barang Terlebih dahulu");
            } else {
                if (reqsent == false) {
                    reqsent = true;
                    var join_selected_values = allVals.join(",");
                    var data = $('#form-gantiharga').serialize() + '&ids=' + join_selected_values;
                    //console.log(data);
                    $.ajax({
                        url: "{{ url('gantihargamasal') }}",
                        type: 'POST',
                        data: data,
                        success: function(data) {
                            reqsent = false;
                            if (data['success']) {
                                Swal.fire({
                                    position: 'top-end',
                                    title: data['success'],
                                    icon: 'success',
                                    showConfirmButton: false,
                                    timer: 900
                                }).then(function() {
                                    datatable();
                                });
                            } else {
                                alert('Ada kesalahan!!');
                            }
                        },
                        error: function(data) {
                            alert('error');
                            console.log(data);
                            reqsent = false;
                        }
                    });
                }
            }
        }
    </script>

    <script type="text/javascript">
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
                        data: 'pilih',
                        name: 'pilih',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'kode_internal',
                        name: 'kode_internal',
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
                        data: 'harga_jual_dasar1',
                        name: 'harga_jual_dasar1',
                        className: 'text-right'
                    },
                    {
                        data: 'harga_jual_dasar2',
                        name: 'harga_jual_dasar2',
                        className: 'text-right'

                    },
                ]
            });

        }

        $(document).ready(function() {
            datatable();
            reqsent = false;
        });
    </script>
@stop
