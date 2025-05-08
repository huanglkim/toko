<div class="modal fade" id="popbModal" tabindex="-1" role="dialog" aria-labelledby="sopbModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sopbModalLabel">PO Tersimpan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-sm table-hover" id="tabel_daftarpb">
                    <thead>
                        <tr class="bg-info">
                            <th>No.</th>
                            <th>Tanggal</th>
                            <th>Invoice</th>
                            <th>pelanggan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dopjhd as $index => $po)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $po->tanggal }}</td>
                                <td>{{ $po->invoice }}</td>
                                <td>{{ $po->pelanggan->nama }}</td>
                                <td>
                                    <a onclick="pilihpo('{{ $po->uuid }}')" class="btn btn-warning bg-pink btn-xs">
                                        <i class="fas fa-pencil-alt"></i> Pilih
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
