<div class="table-responsive" id="drag-scroll-container">
    <table class="table table-bordered table-sm table-hover nowrap">
        <thead>
            <tr class="table-info">
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Qty</th>
                <th>Satuan</th>
                <th class="text-right">Harga</th>
                <th>T. Pot</th>
                <th class="text-right">Total Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pjdt as $index => $pd)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $pd->barang->kode_internal }}</td>
                    <td>{{ $pd->barang->nama_barang }}</td>
                    <td class="text-right">
                        <input type="text" class="form-control form-control-sm text-right col-lg-12"
                            value="{{ $pd->qty + 0 }}" autocomplete="off" onchange="ubahqty(this)"
                            name="qty{{ $pd->id }}" id="qty{{ $pd->id }}" data-id="{{ $pd->id }}"
                            onClick="this.select();">
                    </td>
                    <td>{{ $pd->barang->satuan->nama_satuan }}</td>
                    <td class="text-right">{{ Rupiah0($pd->harga_bruto) }}
                    </td>
                    <td class="text-right">{{ Rupiah0($pd->total_pot) }}</td>
                    <td class="text-right">{{ Rupiah0($pd->total_harga_netto) }}</td>
                    <td>
                        <a onclick="editcart('{{ $pd->id }}')"
                            class="btn btn-warning bg-warning btn-xs-custom"><i class="fas fa-pencil-alt"></i>
                            Edit</a><a onclick="hapuscart('{{ $pd->id }}')"
                            class="btn btn-light bg-danger btn-xs-custom"><i class="fas fa-trash-alt"></i> Hapus</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
