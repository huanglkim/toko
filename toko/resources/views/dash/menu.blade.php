{{-- @if (menuinduk('Operasional')->count() != 0)
    <div class="card-body  pt-0 pb-0 card-outline card-primary">
        <h4 class="text-center"><i class="nav-icon fas fa-tools"></i> OPERASIONAL</h4>
        <hr>
        <div class="row">
            @foreach (menuinduk('Operasional') as $DataOpr)
                <div class="col-4 col-sm-3 col-md-3 col-lg-2 ">
                    <a href="{{ url('/') }}/{{ $DataOpr->menu->link }}">
                        <div class="small-box bg-info text-center">
                            <div class="inner">
                                <i class="nav-icon fas fa-tools"></i>
                            </div>
                            <div class="icon">
                                <i class="nav-icon fas fa-tools"></i>
                            </div>
                            <div class="small-box-footer">
                                <b> {{ $DataOpr->menu->nama }}</b>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endif
@if (menuinduk('Mekanik')->count() != 0)
    <div class="card-body  pt-0 pb-0 card-outline card-primary">
        <h4 class="text-center"><i class="nav-icon fas fa-tools"></i> Mekanik</h4>
        <hr>
        <div class="row">
            @foreach (menuinduk('Mekanik') as $DataOpr)
                <div class="col-4 col-sm-3 col-md-3 col-lg-2 ">
                    <a href="{{ url('/') }}/{{ $DataOpr->menu->link }}">
                        <div class="small-box bg-info text-center">
                            <div class="inner">
                                <i class="nav-icon fas fa-tools"></i>
                            </div>
                            <div class="icon">
                                <i class="nav-icon fas fa-tools"></i>
                            </div>
                            <div class="small-box-footer">
                                {{ $DataOpr->menu->nama }}
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endif
@if (menuinduk('Transaksi')->count() != 0)
    <div class="card-body  pt-0 pb-0 card-outline card-primary">
        <h4 class="text-center"><i class="nav-icon fas fa-tools"></i> Transaksi</h4>
        <hr>
        <div class="row">
            @foreach (menuinduk('Transaksi') as $DataOpr)
                <div class="col-4 col-sm-3 col-md-3 col-lg-2 ">
                    <a href="{{ url('/') }}/{{ $DataOpr->menu->link }}">
                        <div class="small-box bg-info text-center">
                            <div class="inner">
                                <i class="nav-icon fas fa-cash-register"></i>
                            </div>
                            <div class="icon">
                                <i class="nav-icon fas fa-cash-register"></i>
                            </div>
                            <div class="small-box-footer">
                                {{ $DataOpr->menu->nama }}
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endif
@if (menuinduk('Persediaan')->count() != 0)
    <div class="card-body  pt-0 pb-0 card-outline card-primary">
        <h4 class="text-center"><i class="nav-icon fas fa-tools"></i> Persediaan</h4>
        <hr>
        <div class="row">
            @foreach (menuinduk('Persediaan') as $DataOpr)
                <div class="col-4 col-sm-3 col-md-3 col-lg-2 ">
                    <a href="{{ url('/') }}/{{ $DataOpr->menu->link }}">
                        <div class="small-box bg-info text-center">
                            <div class="inner">
                                <i class="nav-icon fas fa-cash-register"></i>
                            </div>
                            <div class="icon">
                                <i class="nav-icon fas fa-cash-register"></i>
                            </div>
                            <div class="small-box-footer">
                                {{ $DataOpr->menu->nama }}
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endif
@if (menuinduk('Akuntansi')->count() != 0)
    <div class="card-body  pt-0 pb-0 card-outline card-primary">
        <h4 class="text-center"><i class="nav-icon fas fa-tools"></i> Akuntansi</h4>
        <hr>
        <div class="row">
            @foreach (menuinduk('Akuntansi') as $DataOpr)
                <div class="col-4 col-sm-3 col-md-3 col-lg-2 ">
                    <a href="{{ url('/') }}/{{ $DataOpr->menu->link }}">
                        <div class="small-box bg-info text-center">
                            <div class="inner">
                                <i class="nav-icon fas fa-calculator"></i>
                            </div>
                            <div class="icon">
                                <i class="nav-icon fas fa-calculator"></i>
                            </div>
                            <div class="small-box-footer">
                                {{ $DataOpr->menu->nama }}
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endif
@if (menuinduk('Laporan')->count() != 0)
    <div class="card-body  pt-0 pb-0 card-outline card-primary">
        <h4 class="text-center"><i class="nav-icon fas fa-tools"></i> Laporan</h4>
        <hr>
        <div class="row">
            @foreach (menuinduk('Laporan') as $DataOpr)
                <div class="col-4 col-sm-3 col-md-3 col-lg-2 ">
                    <a href="{{ url('/') }}/{{ $DataOpr->menu->link }}">
                        <div class="small-box bg-info text-center">
                            <div class="inner">
                                <i class="nav-icon fas fa-book"></i>
                            </div>
                            <div class="icon">
                                <i class="nav-icon fas fa-book"></i>
                            </div>
                            <div class="small-box-footer">
                                {{ $DataOpr->menu->nama }}
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endif
@if (menuinduk('Karyawan')->count() != 0)
    <div class="card-body  pt-0 pb-0 card-outline card-primary">
        <h4 class="text-center"><i class="nav-icon fas fa-tools"></i> Karyawan</h4>
        <hr>
        <div class="row">
            @foreach (menuinduk('Karyawan') as $DataOpr)
                <div class="col-4 col-sm-3 col-md-3 col-lg-2 ">
                    <a href="{{ url('/') }}/{{ $DataOpr->menu->link }}">
                        <div class="small-box bg-info text-center">
                            <div class="inner">
                                <i class="nav-icon fas fa-users"></i>
                            </div>
                            <div class="icon">
                                <i class="nav-icon fas fa-users"></i>
                            </div>
                            <div class="small-box-footer">
                                {{ $DataOpr->menu->nama }}
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endif
@if (menuinduk('Absensi')->count() != 0)
    <div class="card-body  pt-0 pb-0 card-outline card-primary">
        <h4 class="text-center"><i class="nav-icon fas fa-tools"></i> Absensi</h4>
        <hr>
        <div class="row">
            @foreach (menuinduk('Absensi') as $DataOpr)
                <div class="col-4 col-sm-3 col-md-3 col-lg-2 ">
                    <a href="{{ url('/') }}/{{ $DataOpr->menu->link }}">
                        <div class="small-box bg-info text-center">
                            <div class="inner">
                                <i class="nav-icon fas fa-users"></i>
                            </div>
                            <div class="icon">
                                <i class="nav-icon fas fa-users"></i>
                            </div>
                            <div class="small-box-footer">
                                {{ $DataOpr->menu->nama }}
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endif
@if (menuinduk('Pengaturan')->count() != 0)
    <div class="card-body  pt-0 pb-0 card-outline card-primary">
        <h4 class="text-center"><i class="nav-icon fas fa-tools"></i> Pengaturan</h4>
        <hr>
        <div class="row">
            @foreach (menuinduk('Pengaturan') as $DataOpr)
                <div class="col-4 col-sm-3 col-md-3 col-lg-2 ">
                    <a href="{{ url('/') }}/{{ $DataOpr->menu->link }}">
                        <div class="small-box bg-info text-center">
                            <div class="inner">
                                <i class="nav-icon fas fa-cog"></i>
                            </div>
                            <div class="icon">
                                <i class="nav-icon fas fa-cog"></i>
                            </div>
                            <div class="small-box-footer">
                                {{ $DataOpr->menu->nama }}
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endif
@if (menuinduk('OpnameAwal')->count() != 0)
    <div class="card-body  pt-0 pb-0 card-outline card-primary">
        <h4 class="text-center"><i class="nav-icon fas fa-tools"></i> OpnameAwal</h4>
        <hr>
        <div class="row">
            @foreach (menuinduk('OpnameAwal') as $DataOpr)
                <div class="col-4 col-sm-3 col-md-3 col-lg-2 ">
                    <a href="{{ url('/') }}/{{ $DataOpr->menu->link }}">
                        <div class="small-box bg-info text-center">
                            <div class="inner">
                                <i class="nav-icon fas fa-cog"></i>
                            </div>
                            <div class="icon">
                                <i class="nav-icon fas fa-cog"></i>
                            </div>
                            <div class="small-box-footer">
                                {{ $DataOpr->menu->nama }}
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endif --}}

<div class="card-body pt-0 pb-0 card-outline card-primary">
    <h4 class="text-center">FAVOURIT MENU</h4>
    <hr>
    <div class="row">
        @foreach ($favmenus as $fm)
            @if (cekmenuuser($fm->id) == true)
                <div class="col-lg-1 col-md-2 col-3 mb-2">
                    <div class="text-center mb-1">
                        <img class="img" style="max-width: 20px; cursor: pointer;" onclick="favunfav('{{ $fm->id }}')"
                            src="{{ url('/') }}/bengkel/public/iconmenu/fav.png" alt="Favorit">
                    </div>
                    <a href="{{ url('/') }}/{{ $fm->link }}" class="text-decoration-none">
                        <div class="custom-button">
                            <div class="icon-position">
                                <img class="img img-thumbnail img-square img-fluid" style="max-width: 25px; height: 25px;"
                                    src="{{ url('/') }}/bengkel/public/iconmenu/{{ $fm->icon }}" alt="{{ $fm->nama }}">
                            </div>
                            <div class="custom-label">
                                {{ $fm->nama }}
                            </div>
                        </div>
                    </a>
                </div>
            @endif
        @endforeach
    </div>
</div>
<div class="card-body pt-0 pb-0 card-outline card-danger">
    @foreach ($induks as $induk)
        @php
            $MenuRoles = \App\Models\Menus::where(function ($query) use ($caris) {
                foreach ($caris as $cari) {
                    $query->where('nama', 'like', '%' . $cari . '%');
                }
            })
                ->whereIn('id', $menu_ids)
                ->where('induk', $induk->induk)
                ->get();
        @endphp
        @if ($MenuRoles->count() != 0)
            <div class="col-12 pt-1">
                <div class="card ">
                    <div class="card-header pt-1 pb-1 bg-danger">
                        <h4 class="card-title"> {{ $induk->induk }}</h4>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body pt-1 pb-1 pl-1 pr-1" style="display: block;">
                        <div class="row text-center">
                            @foreach ($MenuRoles as $um)
                                <div class="mb-2 col-lg-1 col-md-2 col-3">
                                    <div class="text-center mb-1">
                                        @if (Auth()->User()->role_id == 1)
                                            <img class="img" style="max-width: 20px; cursor: pointer;"
                                                onclick="favunfav('{{ $um->id }}')"
                                                src="{{ url('/') }}/bengkel/public/iconmenu/{{ $um->fav == 1 ? 'fav.png' : 'unfav.png' }}"
                                                alt="{{ $um->fav == 1 ? 'Hapus dari Favorit' : 'Tambah ke Favorit' }}">
                                        @endif
                                    </div>
                                    <a href="{{ url('/') }}/{{ $um->link }}" class="nav-link text-decoration-none">
                                        <div class="custom-button">
                                            <div class="icon-position">
                                                <img class="img img-thumbnail img-square img-fluid"
                                                    style="max-width: 25px; height: 25px;"
                                                    src="{{ url('/') }}/bengkel/public/iconmenu/{{ $um->icon }}"
                                                    alt="{{ $um->nama }}">
                                            </div>
                                            <div class="custom-label">
                                                {{ $um->nama }}
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
    <script>
        function favunfav(id) {
            var data = {
                '_token': "{{ csrf_token() }}",
            };
            $.ajax({
                url: "{{ url('/favunfav') }}/" + id,
                method: "POST",
                data: data,
                success: function(data) {
                    alert(data.pesan);
                    location.reload();
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }
    </script>
</div>