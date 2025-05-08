<script type="text/javascript">
    $('#cartModal').on('shown.bs.modal', function() {
        // reset modal if it isn't visible
        if (!($('.modal.in').length)) {
            $('.modal-dialog').css({
                top: 0,
                left: 0
            });
        }
        $('#myModal').modal({
            backdrop: false,
            show: true
        });

        $('.modal-dialog').draggable({
            handle: ".modal-header"
        });
    });

    function hitungharga() {
        var totalharga = document.getElementById('totalharga').value;
        var qty = document.getElementById('qty').value;
        var harga = totalharga / qty;
        document.getElementById('harga').value = harga;
        document.getElementById('rpharga').value = strtorp(harga);
    }

    function hitungtotalharga() {
        var harga = document.getElementById('harga').value;
        var qty = document.getElementById('qty').value;
        var totalharga = harga * qty;
        document.getElementById('totalharga').value = totalharga;
        document.getElementById('rptotalharga').value = strtorp(totalharga);
    }

    function rumusbayar() {
        let kas = document.getElementById('kas').value;
        let bank = document.getElementById('bank').value;
        let grand_total = document.getElementById('grand_total_barang').value;
        let hutang = parseInt(grand_total) - parseInt(bank) - parseInt(kas);
        document.getElementById('hutang').value = hutang;
        document.getElementById('rphutang').value = strtorp(hutang);
    }
</script>
<script type="text/javascript">
    function formsuplier() { //form tambah show
        $('#modal-form-suplier').modal('show');
        $('#modal-form-suplier form')[0].reset();
    }

    function ubahtitle(nama_sup) {
        var selectpicker = $("#suplier");
        selectpicker.selectpicker();
        selectpicker.selectpicker({
            title: nama_sup
        }).selectpicker('render');
        html = '';
        selectpicker.html(html);
        selectpicker.selectpicker("refresh");
    }

    function AddOrEditsup() { //tambah atau edit //store update
        if (reqsent == false) {
            reqsent = true;
            var id = $('#id').val();
            var url = "{{ url('suplier') }}";
            var data = $('#form-suplier').serialize();
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
                            simpandatapb();
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
</script>
<script type="text/javascript">
    function simpandatapb() {
        var id = document.getElementById('id').value;
        var data = $('#form-datapb').serialize();
        $.ajax({
            url: "{{ url('updatedatapb') }}" + "/" + id,
            method: "POST",
            data: data,
            success: function(data) {
                console.log(data);
            },
            error: function(data) {
                console.log(data);
            }
        });
    }

    function bayarpb() {
        if (reqsent == false) {
            reqsent = true;
            var id = document.getElementById('id').value;
            var data = $('#form-bayar').serialize();
            $.ajax({
                url: "{{ url('updatebayarpb') }}" + "/" + id,
                method: "POST",
                data: data,
                success: function(data) {
                    console.log(data);
                    reqsent = false;
                    if (data.success == 1) {
                        Swal.fire({
                            position: 'top-end',
                            title: data.pesan,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 900
                        }).then(function() {
                            // var url = "{{ url('/pembelian') }}" + "/create";
                            // window.open(url, '_self');
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
                            //location.reload();
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

    function updatebayarpbcepat(e) {
        if (reqsent == false) {
            reqsent = true;
            var id = document.getElementById('id').value;
            $.ajax({
                url: "{{ url('updatebayarpbcepat') }}" + "/" + id,
                method: "POST",
                data: {
                    '_token': '{{ csrf_token() }}',
                    carabayar: e,
                    kode_acc: document.getElementById('mkode_acc').value,
                },
                success: function(data) {
                    console.log(data);
                    reqsent = false;
                    if (data.success == 1) {
                        Swal.fire({
                            position: 'top-end',
                            title: data.pesan,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 900
                        }).then(function() {
                            // var url = "{{ url('/pembelian') }}" + "/create";
                            // window.open(url, '_self');
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
                            //location.reload();
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
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        reqsent = false;
        CartBarang();
        $('#barang_id').on('changed.bs.select', function() {
            var selected = $(this).find('option:selected');
            var id = selected.data('id');
            if (id == null || id == "") {} else {
                ModalCart(id);
            }
        });

    }, false);

    function searchfocus() {
        var cari = document.getElementById('findbarcode').value;
        $('div.dataTables_filter input').val(cari);
        $('div.dataTables_filter input').focus();
    }

    $('#bayarModal').on('shown.bs.modal', function() {
        $('#rpkas').focus();
    });
    $('#modal-form').on('shown.bs.modal', function() {
        $('#kode').focus();
    });
    $('#modal-formbarang').on('shown.bs.modal', function() {
        $('#nama').focus();
    });
    $('#cartModal').on('shown.bs.modal', function() {
        $('#qty').focus();
    });

    $(function() {
        $(".datepicker").datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true,
        });
    });

    // suplier cari
    var carisuplier = document.getElementById("carisuplier");
    var suplier = $('#suplier').selectpicker('val');
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
                            html += '<option value="' + data[count].id +
                                '" data-id="' + data[count].id +
                                '" data-tempo="' + data[count].tempo +
                                '">' +
                                data[count].nama + '/' + data[count].kode + '</option>';
                        }
                        $('#suplier').html(html);
                        $('#suplier').selectpicker('refresh');
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            }
        }
    });
    $('#suplier').on('changed.bs.select', function() {
        var selected = $(this).find('option:selected');
        var id = selected.data('id');
        var tempo = selected.data('tempo');
        if (id != null || id != "") {
            document.getElementById('suplier_id').value = id;
            var tanggal = document.getElementById('tanggal').value;
            // var date = new Date(tanggal);
            // date.setDate(date.getDate() + tempo);
            // var formattedDate = date.toISOString().substring(0, 10);
            document.getElementById('tempo').value = adddays(tanggal, tempo);
            simpandatapb();
        }
    });
    var qtylisten = document.getElementById("qty");
    qtylisten.addEventListener("keyup", function(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            $('#rpharga').focus();
        }
    });
    var hargalisten = document.getElementById("rpharga");
    hargalisten.addEventListener("keyup", function(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            AddCartBarang();
        }
    });

    //keydown shortcut
    document.onkeydown = function(e) {
        if (e.which == 112) { //F1
            e.preventDefault();
            $('#barang_id').selectpicker('toggle');
        } else if (e.which == 113) { //f2
            e.preventDefault();
            $('#modal-formbarang').modal('show');
        } else if (e.which == 114) { //f3
            e.preventDefault();
            $('#bayarModal').modal('show');
        } else if (e.which == 115) { //f4
            e.preventDefault();
            $('.collapse').collapse('show');
            caritabelbarang();
            searchfocus();
        } else if (e.which == 116) { //f5
            $('#btncetakbarcode').click();
            e.preventDefault();
        } else if (e.which == 117) { //f6
            e.preventDefault();
            $('#modal-form').modal('show');
        } else if (e.altKey && e.which == 66) { //alt+B
            e.preventDefault();
        } else if (e.altKey && e.which == 192) {}
    };
</script>
<script type="text/javascript">
    function CartBarang(e) {
        var id = document.getElementById('id').value;
        $.ajax({
            url: "{{ url('/cartbarangpb') }}" + "/" + id,
            timeout: 6500,
            method: "GET",
            success: function(data) {
                $('#CartBarang').html(data);
                if (e != null) {
                    $('#baris' + e).addClass('bg-calmyellow')
                } else {}
                $('#findbarcode').focus();
            },
            error: function(data) {
                console.log(data);
            }
        });
    };

    // function PilihBarang(note) {
    //     $('#modalcaribarang').modal('hide');
    //     var id = $(note).attr("data-id");
    //     var nama = $(note).attr("data-nama");
    //     var part_number = $(note).attr("data-part_number");
    //     var kendaraan = $(note).attr("data-kendaraan");
    //     var nama_barang = '#' + nama + ' #' + part_number + ' #kendaraan' + kendaraan;
    //     var html = '<option value="' + id + '" data-id="' + id + '" selected>' + nama +
    //         ' #' + part_number +
    //         ' #' + kendaraan +
    //         '</option>';
    //     setTimeout(focusoldqty, 400);
    //     $('#old_barang_id').html(html);
    //     $('#old_barang_id').selectpicker('refresh');

    // }

    function focusoldqty() {
        $('#oldqty').focus();
        $('#oldqty').select();
    }

    function ModalCart(id) {
        $.ajax({
            url: "{{ url('/barang') }}" + "/" + id,
            type: "GET",
            success: function(data) {
                if (data.success == 1) {
                    $('#cartModal').modal('show');
                    document.getElementById('cart_barang_id').value = data.id;
                    document.getElementById('nama_barang').value = data.nama + ' | ' + data.part_number;
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

    //tambah barang baru
    function TambahBarang() { //tambah atau edit //store update
        if (reqsent == false) {
            reqsent = true;
            url = "{{ url('pbtambahbarang') }}";
            var data = $('#form-barang').serialize();
            $.ajax({
                url: url,
                method: "POST",
                data: data,
                success: function(data) {
                    reqsent = false;
                    if (data.success == 1) {
                        $('#modal-formbarang').modal('hide');
                        document.getElementById("form-barang").reset();
                        Swal.fire({
                            position: 'top-end',
                            title: data.pesan,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 900
                        }).then(function() {
                            // $('#cartModal').modal('show');
                            // document.getElementById('cart_barang_id').value = data.barang.id;
                            // document.getElementById('nama_barang').value = data.barang.nama +
                            //     ' | ' + data.barang.part_number;
                            var html = '<option value="' + data.barang.id + '" data-id="' + data
                                .barang.id + '" selected>' +
                                data.barang.nama +
                                ' #' + data.barang.part_number +
                                ' #' + data.barang.kendaraan +
                                '</option>';
                            setTimeout(focusoldqty, 400)
                            $('#old_barang_id').html(html);
                            $('#old_barang_id').selectpicker('refresh');

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
                            if (data.nama) {
                                $('#nama').addClass('is-invalid');
                                $('#errornama').html(data.nama);
                            }
                            if (data.part_number) {
                                $('#part_number').addClass('is-invalid');
                                $('#errorpart_number').html(data.part_number);
                            }
                            if (data.harga1) {
                                $('#harga1').addClass('is-invalid');
                                $('#errorharga1').html(data.harga1);
                            }
                            if (data.barcode) {
                                $('#barcode').addClass('is-invalid');
                                $('#errorbarcode').html(data.barcode);
                            }
                        }
                    }
                },
                error: function(data) {
                    reqsent = false;
                    console.log(data);
                }
            });
        }
    };

    function AddCartBarang() {
        if (reqsent == false) {
            reqsent = true;
            var id = document.getElementById('barang_in_id').value;
            if (id == null || id == false || id == '') {
                var url = "{{ url('/addcartbarangpb') }}";
                var method = 'POST';
            } else {
                var url = "{{ url('/updatecartbarangpb') . '/' }}" + id;
                var method = 'PATCH';
            }
            $.ajax({
                url: url,
                timeout: 2500,
                method: "POST",
                data: {
                    '_token': '{{ csrf_token() }}',
                    '_method': method,
                    invoice: document.getElementById('invoice').value,
                    transaksi_in_id: document.getElementById('id').value,
                    barang_id: document.getElementById('cart_barang_id').value,
                    harga: document.getElementById('harga').value,
                    qty: document.getElementById('qty').value,
                    tanggal: document.getElementById('tanggal').value,
                    hj1: document.getElementById('hj1').value,
                    hj2: document.getElementById('hj2').value,
                    hj3: document.getElementById('hj3').value,

                },
                success: function(data) {
                    $('#cartModal').modal('hide');
                    reqsent = false;
                    if (data.success == 1) {
                        Swal.fire({
                            position: 'top-end',
                            title: data.pesan,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 900
                        }).then(function() {
                            CartBarang(data.id);
                            document.getElementById("form-cart").reset();
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
    };

    function EditCartBarang(id) {
        $.ajax({
            url: "{{ url('/barangpb') }}" + "/" + id,
            timeout: 2500,
            method: "GET",
            success: function(data) {
                document.getElementById('barang_in_id').value = data.id;
                document.getElementById('cart_barang_id').value = data.barang_id;
                document.getElementById('qty').value = data.qty;
                var harga = parseInt(data.harga) + 0;
                document.getElementById('harga').value = harga;
                document.getElementById('rpharga').value = parseInt(data.harga) + 0;
                document.getElementById('nama_barang').value = data.nama + ' | ' + data.part_number;
                document.getElementById('rptotalharga').value = parseInt(data.harga) * parseInt(data.qty);
                document.getElementById('totalharga').value = parseInt(data.harga) * parseInt(data.qty);
                var persenhj1 = data.persenhj1;
                var hj1 = parseInt(data.hargajual1) + 0;

                if (harga <= 0) {
                    document.getElementById('persenhj1').value = persenhj1;
                    document.getElementById('hj1').value = hj1;
                    document.getElementById('rphj1').value = hj1;
                } else {
                    if (hj1 <= 0) {
                        var margin = harga * persenhj1 / 100;
                        var h1 = harga + parseInt(margin);
                        document.getElementById('hj1').value = h1;
                        document.getElementById('rphj1').value = h1;
                        hitungpersenhj('rphj1');
                    } else {
                        document.getElementById('hj1').value = hj1;
                        document.getElementById('rphj1').value = hj1;
                        hitungpersenhj('rphj1');
                    }
                }
                var hj2 = parseInt(data.hargajual2) + 0;
                var persenhj2 = data.persenhj2;
                if (harga <= 0) {
                    document.getElementById('persenhj2').value = persenhj2;
                    document.getElementById('hj2').value = hj2;
                    document.getElementById('rphj2').value = hj2;
                } else {
                    if (hj2 <= 0) {
                        var margin = harga * persenhj2 / 100;
                        var h1 = harga + parseInt(margin);
                        document.getElementById('hj2').value = h1;
                        document.getElementById('rphj2').value = h1;
                        hitungpersenhj('rphj2');
                    } else {
                        document.getElementById('hj2').value = hj2;
                        document.getElementById('rphj2').value = hj2;
                        hitungpersenhj('rphj2');
                    }
                }
                var hj3 = parseInt(data.hargajual3) + 0;
                var persenhj3 = data.persenhj3;
                if (harga <= 0) {
                    document.getElementById('persenhj3').value = persenhj3;
                    document.getElementById('hj3').value = hj3;
                    document.getElementById('rphj3').value = hj3;
                } else {
                    if (hj3 <= 0) {
                        var margin = harga * persenhj3 / 100;
                        var h1 = harga + parseInt(margin);
                        document.getElementById('hj3').value = h1;
                        document.getElementById('rphj3').value = h1;
                        hitungpersenhj('rphj3');
                    } else {
                        document.getElementById('hj3').value = hj3;
                        document.getElementById('rphj3').value = hj3;
                        hitungpersenhj('rphj3');
                    }
                }
                $('#cartModal').modal('show');
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
    };

    function HapusCartBarang(id) {
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
                    url: "{{ url('hapuscartbarangpb') }}" + '/' + id,
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
                                CartBarang();
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
</script>
<script type="text/javascript">
    function caritabelbarang() {
        $('#tabel_data').dataTable().fnClearTable();
        $('#tabel_data').dataTable().fnDestroy();
        var urlget = "{{ url('/caritabelbarangpb') }}";
        $('#tabel_data').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            deferRender: true,
            // lengthChange: false,
            lengthMenu: [100, 10, 50, 75, 1000],
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
                [1, 'asc']
            ],
            columns: [{
                    data: 'aksi',
                    name: 'aksi',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'nama',
                    name: 'nama'
                },
                {
                    data: 'part_number',
                    name: 'part_number'
                },
                {
                    data: 'kendaraan',
                    name: 'kendaraan'
                },
                {
                    data: 'stok',
                    name: 'stok'
                },
                {
                    data: 'riwayatpb',
                    name: 'riwayatpb'
                },
                {
                    data: 'merk',
                    name: 'merk'
                },

            ]
        });
        setTimeout(searchfocus, 400);
        filterdulu();
    };
</script>
