@extends('layout.main')
@section('title', 'Setting Akses')
@section('css')
@stop
@section('content')
    <section class="content">
        <div class="card">
            <div class="row">
                <div class="table-responsive-sm pt-1 col-sm-3" id="tabel_jabatan">
                    <table class="table table-bordered table-sm table-hover">
                        <thead>
                            <tr class="bg-info">
                                <th>No.</th>
                                <th>Jabatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($Role as $index => $row)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $row->nama_jabatan }}</td>
                                    <td>
                                        {{-- <a href="{{ url('/tabelAksesrole') }}/{{ $row->id }}"
                                            class="btn btn-danger btn-xs bg-dark"><i class="fas fa-bars"></i> Setup Akses</a> --}}
                                        <button onclick="dataakses(this)" data-id="{{ $row->id }}"
                                            data-nama="{{ $row->nama_jabatan }}" class="btn btn-danger btn-xs bg-dark"><i
                                                class="fas fa-bars"></i> Setup
                                            Akses</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive-sm pt-1 col-sm-9">
                    <input type="hidden" name="role_id" id="role_id">
                    <input type="hidden" name="req" id="req" value="0">
                    <input type="text" name="nama_jabatan" id="nama_jabatan" readonly>
                    <div class="col-sm-12" id="tabel_Akses">

                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
@section('script')
    <script src="{{ asset('lte/sweetalert2/sweetalert2@11.js') }}"></script>


    <script type="text/javascript">
        function dataakses(dataAkses) {
            var role_id = dataAkses.getAttribute("data-id");
            var nama = dataAkses.getAttribute("data-nama");
            document.getElementById("role_id").value = role_id;
            document.getElementById("nama_jabatan").value = nama;
            document.getElementById("req").value = 0;
            cartakses(role_id);
        }

        function cartakses(role_id) {
            $.ajax({
                url: "{{ url('/tabelaksesrole') }}/" + role_id,
                timeout: 500,
                method: "GET",
                success: function(data) {
                    $('#tabel_Akses').html(data);
                },
                error: function(request, status, err) {
                    if (status == "timeout") {
                        // timeout -> reload the page and try again
                        window.location.reload(); //make it comment if you don't want to reload page
                    } else {
                        // another error occured  
                        alert("error: " + request + status + err);
                    }
                }
            });
        }

        function hapusakses(id) {
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
                    var req = document.getElementById("req").value;
                    if (req == 0) {
                        document.getElementById("req").value = 1;
                        $.ajax({
                            url: "{{ url('/hapusakses') }}" + '/' + id,
                            type: "POST",
                            timeout: 500,
                            data: {
                                '_method': 'DELETE',
                                '_token': '{{ csrf_token() }}',
                            },
                            success: function(data) {
                                Swal.fire({
                                    position: 'top-end',
                                    title: data,
                                    icon: 'success',
                                    showConfirmButton: false,
                                    timer: 900
                                }).then(function() {
                                    var role_id = document.getElementById('role_id').value;
                                    cartakses(role_id);
                                    document.getElementById("req").value = 0;
                                });
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
                }
            })

        };

        function tambahakses(akses_id) {
            var req = document.getElementById("req").value;
            if (req == 0) {
                document.getElementById("req").value = 1;
                var role_id = document.getElementById('role_id').value;
                $.ajax({
                    url: "{{ url('/tambahakses') }}",
                    type: "POST",
                    timeout: 500,
                    data: {
                        '_token': '{{ csrf_token() }}',
                        role_id: role_id,
                        akses_id: akses_id,
                    },
                    success: function(data) {
                        Swal.fire({
                            position: 'top-end',
                            title: data,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 900
                        }).then(function() {
                            var role_id = document.getElementById('role_id').value;
                            cartakses(role_id);
                            document.getElementById("req").value = 0;
                        });
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
        };
    </script>
@stop
