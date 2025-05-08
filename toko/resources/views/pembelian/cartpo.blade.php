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
                <th>Pot %</th>
                <th class="text-right">Pot Rp</th>
                <th>T. Pot</th>
                <th>T. PPN</th>
                <th>T. DPP</th>
                <th class="text-right">Total Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($popbdt as $index => $podt)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $podt->barang->kode_internal }}</td>
                    <td>{{ $podt->barang->nama_barang }}</td>
                    <td>{{ $podt->qty + 0 }}</td>
                    <td>{{ $podt->barang->satuan->nama_satuan }}</td>
                    <td class="text-right">{{ Rupiah0($podt->harga_bruto) }}</td>
                    <td>{{ $podt->potpersen }}%</td>
                    <td class="text-right">{{ Rupiah0($podt->potrp) }}</td>
                    <td class="text-right">{{ Rupiah0($podt->total_pot) }}</td>
                    <td class="text-right">{{ Rupiah0($podt->totalppn) }}</td>
                    <td class="text-right">{{ Rupiah0($podt->totalhpp) }}</td>
                    <td class="text-right">{{ Rupiah0($podt->total_harga_netto) }}</td>
                    <td>
                        <a onclick="editcart('{{ $podt->id }}')" class="btn btn-warning bg-warning btn-xs"><i
                                class="fas fa-pencil-alt"></i>
                            Edit</a><a onclick="hapuscart('{{ $podt->id }}')"
                            class="btn btn-light bg-danger btn-xs"><i class="fas fa-trash-alt"></i> Hapus</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
