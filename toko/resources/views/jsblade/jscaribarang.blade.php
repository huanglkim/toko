<script>
    // Function to trigger the button click when F2 is pressed
    $(document).keydown(function(e) {
        if (e.key === 'F2') { // Check if the F2 key is pressed
            e.preventDefault(); // Prevent default action (if any)
            $('#btn-cari-barang').click(); // Trigger click event on the button
        }
        if (e.key === 'F3') { // Check if the F2 key is pressed
            e.preventDefault(); // Prevent default action (if any)
            // Check if the select element is visible
            const selectElement = document.getElementById('barang_id');
            if (selectElement) {
                selectElement.focus(); // Focus on the select element
                $('.selectpicker').selectpicker('toggle'); // Open the dropdown
            }
        }
    });
    // Add arrow key navigation and Enter key for selection
    $('#tabel_data_barang tbody').on('keydown', 'tr', function(e) {
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
                $('#tabel_data_barang tbody tr').first().focus(); // Focus the first row
            }
        });
    }

    function caritabelbarang() {
        $('#tabel_data_barang').dataTable().fnClearTable();
        $('#tabel_data_barang').dataTable().fnDestroy();
        var urlget = "{{ url('/caritabelbarang') }}";
        $('#tabel_data_barang').DataTable({
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
            pilihbarang(rowDataId);
        }
    }
    // cari barcode
    $('#caribarcode').on('change', function() {
        var caribarcode = $(this).val();
        $.ajax({
            url: "{{ url('/caribarcode') }}",
            method: "POST",
            data: {
                '_token': '{{ csrf_token() }}',
                caribarcode: caribarcode
            },
            success: function(data) {
                document.getElementById('caribarcode').value = '';
                var id = data.id;
                if (id == null || id === "") {
                    alert('BARCODE TIDAK DITEMUKAN');
                } else {
                    ModalCart(id);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                document.getElementById('caribarcode').value = '';
                console.log('Error Details:', {
                    status: jqXHR.status, // HTTP status code
                    statusText: textStatus, // Status text (e.g., "error", "timeout")
                    responseText: jqXHR.responseText, // Server response
                    errorThrown: errorThrown // Exception text (e.g., "Not Found")
                });

                // Display a user-friendly error message
                toastr["error"](jqXHR.responseJSON?.message);
            }
        });
    });
    //cari barang
    $('#barang_id').on('changed.bs.select', function() {
        var selected = $(this).find('option:selected');
        var id = selected.data('id');
        if (id == null || id == "") {} else {
            ModalCart(id);
        }
    });

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
</script>
