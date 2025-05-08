<div class="card-body pt-0 pb-0 card-outline card-primary">
    <div class="row mb-2">
        <div class="col-lg-12">
            <div class="float-right">
                <a class="btn btn-sm btn-primary" data-toggle="collapse" href="#collapseExample" role="button"
                    aria-expanded="false" aria-controls="collapseExample">
                    <i class="fas fa-bars"></i> Set Fav
                </a>
            </div>
            <h4>FAVOURIT MENU</h4>
        </div>
    </div>
    <hr>
    <div class="collapse" id="collapseExample">
        <div class="card card-body">
            <div class="col-lg-12 text-center">
                @foreach ($listmenuuser as $lmu)
                    @php
                        $menufav = App\Models\Menufav::where('user_id', Auth()->User()->id)
                            ->where('menu_id', $lmu->menu_id)
                            ->first();
                    @endphp
                    <button
                        class="btn btn-sm mt-2 mr-2 text-bold text-uppercase {{ $menufav ? 'bg-success' : 'bg-gray' }}"
                        onclick="favunfav(this)" data-menu_id="{{ $lmu->menu_id }}"
                        data-menufav_id="{{ $menufav ? $menufav->id : 0 }}">
                        {{ $lmu->menu->nama }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>
    <div class="row">
        @foreach ($favmenus as $fm)
            @if (cekmenuuser($fm->menu_id) == true)
            <div class="col-lg-1 col-md-2 col-3">
                <a href="{{ url('/') }}/{{ $fm->menu->link }}" class="custom-button bg-danger">
                    @if ($fm->menu->icon)
                        <img src="{{ asset('icon/' . $fm->menu->icon) }}" alt="Icon"
                            class="icon-position">
                    @else
                        <p>No Icon</p>
                    @endif
                    <span class="custom-label"> {{ $fm->menu->nama }}</span>
                </a>
            </div>
            @endif
        @endforeach
</div>
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
                <div class="card-header pt-1 pb-1 bg-gray-light">
                    <h4 class="card-title"> {{ $induk->induk }}</h4>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body pt-1 pb-1 pl-1 pr-1 text-center" style="display: block;">
                    <div class="row">
                        @foreach ($MenuRoles as $um)
                            <div class="mb-2 col-lg-1 col-md-2 col-3">
                                <a href="{{ url('/') }}/{{ $um->link }}" class="text-decoration-none">
                                    <div class="custom-button bg-primary">
                                        <div class="icon-position">
                                            @if ($um->icon)
                                                <img class="img img-thumbnail img-square img-fluid"
                                                    style="max-width: 25px; height: 25px;"
                                                    src="{{ asset('icon/' . $um->icon) }}" alt="{{ $um->nama }}">
                                            @else
                                                <i class="nav-icon fas fa-link"></i> {{-- Icon pengganti jika tidak ada --}}
                                            @endif
                                        </div>
                                        <div class="custom-label text-uppercase text-bold">
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
    function favunfav(e) {
        var menu_id = e.dataset.menu_id;
        var menufav_id = e.dataset.menufav_id;
        var link = '{{ url('') }}/menufav';
        var token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: link,
            method: "POST",
            data: {
                '_token': token,
                menu_id: menu_id,
                menufav_id: menufav_id,
            },
            success: function(data) {
                toastr["success"](data.pesan, "Berhasil");
            },
            error: function(data) {
                console.log(data);
            }
        });

        if (e.classList.contains('bg-calmyellow')) {
            e.classList.remove('bg-calmyellow');
            e.classList.add('bg-gray');
        } else {
            e.classList.remove('bg-gray');
            e.classList.add('bg-calmyellow');
        }
    }
</script>