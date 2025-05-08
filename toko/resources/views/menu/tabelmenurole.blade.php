<div class="row">
    <div class="col-sm-6">
        <div class="row">
            <b>
                <p>Menu Terpakai</p>
            </b>
        </div>

        <table class="table table-bordered table-sm table-hover">
            <thead>
                <tr class="bg-info">
                    <th>No.</th>
                    <th>Induk Menu</th>
                    <th>Nama Menu</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($MenuRoles as $index => $MenuRole)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $MenuRole->menu->induk }}</td>
                        <td>{{ $MenuRole->menu->nama }}</td>
                        <td>
                            <a onclick="hapusmenu('{{ $MenuRole->id }}')" class="btn btn-danger btn-xs bg-dark"><i
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
                <p>Menu Tidak Terpakai</p>
            </b>
        </div>

        <table class="table table-bordered table-sm table-hover">
            <thead>
                <tr class="bg-info">

                    <th>Induk Menu</th>
                    <th>Nama Menu</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($NonMenuRoles as $NonMenuRole)
                    <tr>
                        <td>{{ $NonMenuRole->induk }}</td>
                        <td>{{ $NonMenuRole->nama }}</td>
                        <td>
                            <a onclick="tambahmenu('{{ $NonMenuRole->id }}')"
                                class="btn btn-success btn-xs bg-green"><i class="fas fa-plus-circle"></i>
                                Tambah</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>
