@extends('layout.main')
@section('title', 'Setting Menu')
@section('css')
@stop
@section('content')
    <section class="content">
        <div class="card">
            <div class="row">
                <div class="table-responsive-sm pt-1 col-sm-4" id="tabel_jabatan">
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
                                <tr class="table-info">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $row->nama_jabatan }}</td>
                                    <td>
                                        {{-- <a href="{{ url('/tabelmenurole') }}/{{ $row->id }}"
                                            class="btn btn-danger btn-xs bg-dark"><i class="fas fa-bars"></i> Setup Menu</a> --}}
                                        <button onclick="datamenu(this)" data-id="{{ $row->id }}"
                                            data-nama="{{ $row->nama_jabatan }}" class="btn btn-danger btn-xs bg-dark"><i
                                                class="fas fa-bars"></i> Setup
                                            Menu</button>
                                    </td>
                                </tr>
                                @if ($row->user->count() > 0)
                                    <tr>
                                        <td colspan="3">
                                            @foreach ($row->User as $user)
                                                #{{ $user->username }}
                                            @endforeach
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive-sm pt-1 col-sm-8">
                    <input type="text" name="role_id" id="role_id">
                    <input type="hidden" name="req" id="req" value="0">
                    <input type="text" name="nama_jabatan" id="nama_jabatan" readonly>
                    <div class="col-sm-12" id="tabel_menu">

                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
@section('script')
    <script src="{{ asset('lte/sweetalert2/sweetalert2@11.js') }}"></script>


    <script type="text/javascript">
        function datamenu(datamenu) {
            var role_id = datamenu.getAttribute("data-id");
            var nama = datamenu.getAttribute("data-nama");
            document.getElementById("role_id").value = role_id;
            document.getElementById("nama_jabatan").value = nama;
            document.getElementById("req").value = 0;
            cartmenu(role_id);
        }

        function cartmenu(role_id) {
            $.ajax({
                url: "{{ url('/tabelmenurole') }}/" + role_id,
                timeout: 500,
                method: "GET",
                success: function(data) {
                    $('#tabel_menu').html(data);
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

        function hapusmenu(id) {
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
                            url: "{{ url('/hapusmenu') }}" + '/' + id,
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
                                    cartmenu(role_id);
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

        function tambahmenu(menu_id) {
            var req = document.getElementById("req").value;
            if (req == 0) {
                document.getElementById("req").value = 1;
                var role_id = document.getElementById('role_id').value;
                $.ajax({
                    url: "{{ url('/tambahmenu') }}",
                    type: "POST",
                    timeout: 500,
                    data: {
                        '_token': '{{ csrf_token() }}',
                        role_id: role_id,
                        menu_id: menu_id,
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
                            cartmenu(role_id);
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
