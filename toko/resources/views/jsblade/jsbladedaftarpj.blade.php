<script>
    function datatable() {
        $('#tabel_data').dataTable().fnClearTable();
        $('#tabel_data').dataTable().fnDestroy();
        var status = $('#status').val();
        var tipe = $('#tipe').val();
        var pelanggan_id = $('#pelanggan_id').val();
        var urlget = "{{ url('/tabelpj') }}";
        $('#tabel_data').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            deferRender: true,
            bStateSave: true,
            retrieve: true,
            searchHighlight: true,
            lengthMenu: [50, 10, 100, 75, 500, 1000],
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
                    pelanggan_id: pelanggan_id,
                    tipe: document.getElementById('tipe').value,
                },
            }),
            order: [
                [6, 'DESC']
            ],

            columns: [{
                    data: 'tanggal',
                    name: 'tanggal',
                },
                {
                    data: 'invoice',
                    name: 'invoice',
                },
                {
                    data: 'pelanggan.kode',
                    name: 'pelanggan.kode',
                },
                {
                    data: 'pelanggan.nama',
                    name: 'pelanggan.nama',
                },
                {
                    data: 'jumlah',
                    name: 'jumlah',
                    className: 'text-right'
                },
                {
                    data: 'keterangan',
                    name: 'keterangan',
                },
                {
                    data: 'created_at',
                    name: 'created_at',

                },
                {
                    data: 'aksi',
                    name: 'aksi',
                },
            ]
        });
    }
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
                        '_token': '{{ csrf_token() }}',
                        caripelanggan: searchData
                    },
                    success: function(data) {
                        var html = '';
                        for (var count = 0; count < data.length; count++) {
                            html += '<option value="' + data[count].id + '" data-id="' + data[count]
                                .id + '">' +
                                data[count].nama +
                                '/' + data[count].kode +
                                '/' + data[count].alamat +
                                '/' + data[count].wa +
                                '</option>';
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
    $('#pelanggan_id').on('changed.bs.select', function() {
        datatable();
    });
    $(function() {
        $(".datepicker").datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true,
        });
    });

    function cekpjpending() {
        var jumlahpjpending = document.getElementById('jumlahpjpending').value;
        if (jumlahpjpending > 0) {
            Swal.fire({
                title: 'Anda yakin?',
                text: "MASIH ADA PENDING LANJUT BARU??!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'YA. LANJUT BARU!'
            }).then((result) => {
                if (result.value) {
                    tambahpjbaru();
                }
            });
        } else {
            tambahpjbaru();
        }
    }
</script>
<script type="text/javascript">
    function editdata(uuid) {
        var url = '{{ url('') }}' + '/editpj/' + uuid;
        window.location.href = url;
    }

    function hapus(uuid) {
        if (reqsent == false) {
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
                    reqsent = true;
                    var geturl = base_url + '/hapuspj/' + uuid;
                    $.ajax({
                        url: geturl,
                        type: "POST",
                        data: {
                            '_method': 'DELETE',
                            '_token': token
                        },
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
                            }
                            if (data.success == 0) {
                                Swal.fire({
                                    position: 'top-end',
                                    title: data.pesan,
                                    icon: 'error',
                                }).then(function() {});
                            }
                        },
                        error: function(data) {
                            reqsent = true;
                            console.log(data);
                        }
                    });
                }
            })
        }
    }
</script>
