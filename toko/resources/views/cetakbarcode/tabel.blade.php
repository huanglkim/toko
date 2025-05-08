
<div class="mb-4">
    <h2 class="text-primary">
        <i class="fas fa-barcode mr-2"></i> CETAK BARCODE
    </h2>
    <div class="table-responsive">
        <table class="table table-bordered table-sm table-hover">
            <thead class="thead-light text-center">
                <tr>
                    <th>No.</th>
                    <th>Nama Barang</th>
                    <th>Kode Internal</th>
                    <th>Barcode</th>
                    <th>Harga</th>
                    <th>Qty</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($CetakBarcode as $index => $cb)
                <tr class="table-row">
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        @if ($cb->Barang == null)
                            <span class="text-danger font-weight-bold">KEHAPUS (ID: {{ $cb->barang_id }})</span>
                        @else
                            {{ $cb->Barang->nama_barang }}
                        @endif
                    </td>
                    <td>
                        @if ($cb->Barang == null)
                            <span class="text-danger font-weight-bold">KEHAPUS (ID: {{ $cb->barang_id }})</span>
                        @else
                            {{ $cb->Barang->kode_internal }}
                        @endif
                    </td>
                    <td class="text-center">
                        @if ($cb->Barang == null)
                            <span class="text-danger font-weight-bold">KEHAPUS (ID: {{ $cb->barang_id }})</span>
                        @else
                            {{ $cb->Barang->barcode }}
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($cb->Barang == null)
                            <span class="text-danger font-weight-bold">KEHAPUS (ID: {{ $cb->barang_id }})</span>
                        @else
                            {{ Rupiah0($cb->Barang->harga_jual_dasar1) }}
                        @endif
                    </td>
                    <td class="text-center">{{ $cb->qty }}</td>
                    <td class="text-center">
                        <button onclick="editcart('{{ $cb->id }}')" class="btn btn-sm btn-outline-info action-btn"
                            title="Edit Item">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                        <button onclick="hapuscart('{{ $cb->id }}')" class="btn btn-sm btn-outline-danger action-btn"
                            title="Hapus Item">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<script>
    $('#master1').on('click', function(e) {
        if ($(this).is(':checked', true)) {
            $(".sub_chk1").prop('checked', true);
            ceklistcetakharga(1, 0);
        } else {
            $(".sub_chk1").prop('checked', false);
            ceklistcetakharga(0, 0);
        }
    });

    function centangcetakharga(elem) {
        if ($(elem).is(':checked', true)) {
            var cetak_harga = 1;
        } else {
            var cetak_harga = 0;
        }
        var id = $(elem).attr("data-id");

        ceklistcetakharga(cetak_harga, id);
    }

    function ceklistcetakharga(cetak_harga, id) {
        var csrf_token = "{{ csrf_token() }}";
        $.ajax({
            url: "{{ url('/') }}/ceklistcetakharga",
            type: 'POST',
            data: {
                '_token': csrf_token,
                cetak_harga: cetak_harga,
                id: id
            },
            success: function(data) {
                reqsent = false;
                if (data['success']) {

                } else {
                    location.reload();
                }
            },
            error: function(data) {
                reqsent = false;
                console.log(data);
                // /location.reload();
            }
        });
    }
</script>
