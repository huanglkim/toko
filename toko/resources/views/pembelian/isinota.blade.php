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
            </tr>
        </thead>
        <tbody>
            @foreach ($datacart as $index => $dc)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $dc->barang->kode_internal }}</td>
                    <td>{{ $dc->barang->nama_barang }}</td>
                    <td>{{ $dc->qty + 0 }}</td>
                    <td>{{ $dc->barang->satuan->nama_satuan }}</td>
                    <td class="text-right">{{ RupiahNonRp($dc->harga_bruto) }}</td>
                    <td>{{ $dc->potpersen }}%</td>
                    <td class="text-right">{{ RupiahNonRp($dc->potrp) }}</td>
                    <td class="text-right">{{ RupiahNonRp($dc->total_pot) }}</td>
                    <td class="text-right">{{ RupiahNonRp($dc->totalppn) }}</td>
                    <td class="text-right">{{ RupiahNonRp($dc->totalhpp) }}</td>
                    <td class="text-right">{{ RupiahNonRp($dc->total_harga_netto) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
