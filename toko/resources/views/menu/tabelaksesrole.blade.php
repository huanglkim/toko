<div class="row">
    <div class="col-sm-6">
        <div class="row">
            <b>
                <p>Akses Terpakai</p>
            </b>
        </div>

        <table class="table table-bordered table-sm table-hover">
            <thead>
                <tr class="bg-info">
                    <th>No.</th>
                    <th>Induk Akses</th>
                    <th>Nama Akses</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($AksesRoles as $index => $AksesRole)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $AksesRole->Akses->induk }}</td>
                        <td>{{ $AksesRole->Akses->nama_akses }}</td>
                        <td>
                            <a onclick="hapusakses('{{ $AksesRole->id }}')" class="btn btn-danger btn-xs bg-dark"><i
                                    class="fas fa-trash"></i> Hapus</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
    <div class="col-sm-6">
        <div class="row">
            <b>
                <p>Akses Tidak Terpakai</p>
            </b>
        </div>

        <table class="table table-bordered table-sm table-hover">
            <thead>
                <tr class="bg-info">

                    <th>Induk Akses</th>
                    <th>Nama Akses</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($NonAksesRoles as $NonAksesRole)
                    <tr>
                        <td>{{ $NonAksesRole->induk }}</td>
                        <td>{{ $NonAksesRole->nama_akses }}</td>
                        <td>
                            <a onclick="tambahakses('{{ $NonAksesRole->id }}')"
                                class="btn btn-success btn-xs bg-green"><i class="fas fa-plus-circle"></i>
                                Tambah</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>
