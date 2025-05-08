@extends('layout.main')
@section('title', 'Upah Kerja')
@section('css')
    <link href="{{ url('/') }}/lte/plugins/datepicker/css/bootstrap-datepicker.min.css" type="text/css"
        rel="stylesheet" />
@stop
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">UPAH KERJA (BELI JASA)</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="form-kaskeluar">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-row">
                                        <label for="tanggal" class="col-sm-4 col-form-label">Tanggal</label>
                                        <div class="col-sm-8">
                                            <input type="hidden" id="id" name="id">
                                            <input type="hidden" id="tipe" name="tipe" value="KK">
                                            <input type="hidden" id="posisi" name="posisi" value="K">
                                            <input type="hidden" id="kode_lawan" name="kode_lawan" value="6-3002">
                                            <input type="text" class="form-control form-control-sm datepicker"
                                                value="{{ Date('d-m-Y') }}" id="tanggal" name="tanggal">
                                            <div id="errortanggal" class="invalid-feedback">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <label for="kode_acc" class="col-sm-4 col-form-label">Keluar Dari</label>
                                        <div class="col-sm-8">
                                            <select class="form-control form-control-sm select" name="kode_acc"
                                                id="kode_acc">
                                                @foreach ($KasBank as $kk)
                                                    <option value="{{ $kk->kode_acc }}">{{ $kk->nama_acc }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <label for="jumlah" class="col-sm-4 col-form-label">jumlah</label>
                                        <div class="col-sm-8">
                                            <input type="number" class="form-control form-control-sm" id="jumlah"
                                                name="jumlah" autofocus autocomplete="off">
                                            <div id="errorjumlah" class="invalid-feedback">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">

                                    <div class="form-row">
                                        <label for="keterangan" class="col-sm-4 col-form-label">Keterangan</label>
                                        <div class="col-sm-8">
                                            <textarea id="keterangan" name="keterangan" class="form-control" rows="4"></textarea>
                                            <div id="errorketerangan" class="invalid-feedback">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row mt-2">
                                        <label for="btn" class="col-sm-4 col-form-label "></label>

                                        <button type="button" class="btn btn-primary " onclick="AddOrEdit()"><i
                                                class="fas fa-plus-circle"></i> SIMPAN</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="card col-sm-12 table-responsive">
                <table class="table table-bordered table-sm table-hover">
                    <thead>
                        <tr class="bg-info">
                            <th>Invoice</th>
                            <th>Tanggal</th>
                            <th>Keluar Dari</th>
                            <th>Untuk</th>
                            <th class="text-right">Jumlah</th>
                            <th>Keterangan</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($KasKeluar as $index => $kk)
                            <tr>
                                <td>{{ $kk->invoice }}</td>
                                <td>{{ datetotanggal($kk->tanggal) }}</td>
                                <td>{{ $kk->AccPerkiraan->nama_acc }}</td>
                                <td>{{ $kk->kode_lawan }}|{{ $kk->AccPerkiraanLawan->nama_acc }}</td>
                                <td class="text-right">{{ Rupiah0($kk->jumlah) }}</td>
                                <td>{{ $kk->keterangan }}</td>
                                <td class="text-right">
                                    <button onclick="edit({{ $kk->id }})" class="btn  btn-sm btn-warning"><i
                                            class="fas fa-pencil-alt"></i> EDIT</button>
                                    <button onclick="hapus({{ $kk->id }})" class="btn  btn-sm btn-danger"><i
                                            class="fas fa-trash"></i> HAPUS</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $KasKeluar->links() }}
            </div>
        </div>

    </section>
@stop
@section('script')
    <script src="{{ asset('lte/sweetalert2/sweetalert2@11.js') }}"></script>
    <script src="{{ url('/') }}/lte/plugins/datepicker/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            reqsent = false;
        }, false);

        $(function() {
            $(".datepicker").datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
            });
        });
    </script>
    <script type="text/javascript">
        function AddOrEdit() {
            if (reqsent == false) {
                var id = document.getElementById('id').value;
                if (id == null || id == false || id == '') {
                    var url = "{{ url('/tambahjurnalkas') }}";
                    var method = 'POST';
                } else {
                    var url = "{{ url('/updatejurnalkas') . '/' }}" + id;
                    var method = 'PATCH';
                }
                var data = $('#form-kaskeluar').serialize() + "&_method=" + method;
                $.ajax({
                    url: url,
                    timeout: 2500,
                    method: "POST",
                    data: data,
                    success: function(data) {
                        document.getElementById("form-kaskeluar").reset();
                        reqsent = false;
                        if (data.success == 1) {
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
                                    showConfirmButton: false,
                                    timer: 900
                                }).then(function() {

                                });
                            } else {
                                if (data.jumlah) {
                                    $('#jumlah').addClass('is-invalid');
                                    $('#errorjumlah').html(data.jumlah);
                                }
                                if (data.tanggal) {
                                    $('#tanggal').addClass('is-invalid');
                                    $('#errortanggal').html(data.tanggal);
                                }
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
        };

        function edit(id) {
            Swal.fire({
                title: 'Anda yakin?',
                text: "YAKIN UNTUK EDIT KAS!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'YA. EDIT'
            }).then((result) => {
                if (result.value) {
                    //open login
                    $.ajax({
                        url: "{{ url('/editjurnalkas') }}" + "/" + id,
                        timeout: 2500,
                        method: "GET",
                        success: function(data) {
                            document.getElementById('id').value = data.id;
                            document.getElementById('tanggal').value = data.tanggal;
                            document.getElementById('kode_acc').value = data.kode_acc;
                            document.getElementById('kode_lawan').value = data.kode_lawan;
                            document.getElementById('tipe').value = data.tipe;
                            document.getElementById('jumlah').value = data.jumlah;
                            document.getElementById('keterangan').value = data.keterangan;
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

        function hapus(id) {
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
                        url: "{{ url('hapusjurnalkas') }}" + '/' + id,
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
                                    location.reload();
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
    </script>
@stop
