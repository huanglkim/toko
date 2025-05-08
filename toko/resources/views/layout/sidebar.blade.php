<aside class="main-sidebar sidebar-dark-primary elevation-4 bg-dark">
    @php
        $toko = \App\Models\Toko::first();
    @endphp

    <a href="#" class="brand-link">
        <img src="{{ asset('toko/public/storage/' . $toko->logo) }}" alt="{{ $toko->nama_toko }}" class="img-fluid rounded-circle"
            style="width: 40px; height: 40px; object-fit: cover;">
        <span class="brand-text font-weight-light">WU BANGUNAN</span>
    </a>


    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a href="#" class="d-block text-white font-weight-medium">{{ Auth()->User()->nama }}</a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                @if (menuinduk('DataUsers')->count() != 0)
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-users text-light"></i>
                            <p class="text-light"> Kelola User
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview bg-gray-800">
                            @foreach (menuinduk('DataUsers') as $DataUser)
                                <li class="nav-item">
                                    <a href="{{ url('/') }}/{{ $DataUser->menu->link }}"
                                        class="nav-link {{ Request::segment(1) == $DataUser->menu->link ? 'active' : '' }}">
                                        <i class="{{ $DataUser->menu->icon }} text-gray-300"></i>
                                        <p class="text-gray-300">{{ $DataUser->menu->nama }}</p>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif
                @if (menuinduk('MasterData')->count() != 0)
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-database text-light"></i>
                            <p class="text-light">
                                MASTER DATA
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview bg-gray-800">
                            @foreach (menuinduk('MasterData') as $MasterData)
                                <li class="nav-item">
                                    <a href="{{ url('/') }}/{{ $MasterData->menu->link }}"
                                        class="nav-link {{ Request::segment(1) == $MasterData->menu->link ? 'active' : '' }}">
                                        <i class="{{ $MasterData->menu->icon }} text-gray-300"></i>
                                        <p class="text-gray-300">{{ $MasterData->menu->nama }}</p>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif
                @if (menuinduk('Operasional')->count() != 0)
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-tools text-light"></i>
                            <p class="text-light">
                                OPERASIONAL
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview bg-gray-800">
                            @foreach (menuinduk('Operasional') as $DataOpr)
                                <li class="nav-item">
                                    <a href="{{ url('/') }}/{{ $DataOpr->menu->link }}"
                                        class="nav-link {{ Request::segment(1) == $DataOpr->menu->link ? 'active' : '' }}">
                                        <i class="{{ $DataOpr->menu->icon }} text-gray-300"></i>
                                        <p class="text-gray-300">{{ $DataOpr->menu->nama }}</p>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif
                @if (menuinduk('Pembelian')->count() != 0)
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-shopping-cart text-light"></i>
                            <p class="text-light">
                                Pembelian
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview bg-gray-800">
                            @foreach (menuinduk('Pembelian') as $Datapb)
                                <li class="nav-item">
                                    <a href="{{ url('/') }}/{{ $Datapb->menu->link }}"
                                        class="nav-link {{ Request::segment(1) == $Datapb->menu->link ? 'active' : '' }}">
                                        <i class="{{ $Datapb->menu->icon }} text-gray-300"></i>
                                        <p class="text-gray-300">{{ $Datapb->menu->nama }}</p>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif
                @if (menuinduk('Penjualan')->count() != 0)
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cash-register text-light"></i>
                            <p class="text-light">
                                Penjualan
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview bg-gray-800">
                            @foreach (menuinduk('Penjualan') as $Datapj)
                                <li class="nav-item">
                                    <a href="{{ url('/') }}/{{ $Datapj->menu->link }}"
                                        class="nav-link {{ Request::segment(1) == $Datapj->menu->link ? 'active' : '' }}">
                                        <i class="{{ $Datapj->menu->icon }} text-gray-300"></i>
                                        <p class="text-gray-300">{{ $Datapj->menu->nama }}</p>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif
                @if (menuinduk('Persediaan')->count() != 0)
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-boxes text-light"></i>
                            <p class="text-light">
                                PERSEDIAAN
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview bg-gray-800">
                            @foreach (menuinduk('Persediaan') as $DataPrs)
                                <li class="nav-item">
                                    <a href="{{ url('/') }}/{{ $DataPrs->menu->link }}"
                                        class="nav-link {{ Request::segment(1) == $DataPrs->menu->link ? 'active' : '' }}">
                                        <i class="{{ $DataPrs->menu->icon }} text-gray-300"></i>
                                        <p class="text-gray-300">{{ $DataPrs->menu->nama }}</p>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif
                @if (menuinduk('Akuntansi')->count() != 0)
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-calculator text-light"></i>
                            <p class="text-light">
                                Akuntansi
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview bg-gray-800">
                            @foreach (menuinduk('Akuntansi') as $DataAkuntansi)
                                <li class="nav-item">
                                    <a href="{{ url('/') }}/{{ $DataAkuntansi->menu->link }}"
                                        class="nav-link {{ Request::segment(1) == $DataAkuntansi->menu->link ? 'active' : '' }}">
                                        <i class="{{ $DataAkuntansi->menu->icon }} text-gray-300"></i>
                                        <p class="text-gray-300">{{ $DataAkuntansi->menu->nama }}</p>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif
                @if (menuinduk('Laporan')->count() != 0)
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-book text-light"></i>
                            <p class="text-light">
                                Laporan
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview bg-gray-800">
                            @foreach (menuinduk('Laporan') as $DataLaporan)
                                <li class="nav-item">
                                    <a href="{{ url('/') }}/{{ $DataLaporan->menu->link }}"
                                        class="nav-link {{ Request::segment(1) == $DataLaporan->menu->link ? 'active' : '' }}">
                                        <i class="{{ $DataLaporan->menu->icon }} text-gray-300"></i>
                                        <p class="text-gray-300">{{ $DataLaporan->menu->nama }}</p>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif
                @if (menuinduk('Karyawan')->count() != 0)
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-users text-light"></i>
                            <p class="text-light">
                                Kelola Karyawan
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview bg-gray-800">
                            @foreach (menuinduk('Karyawan') as $DataKaryawan)
                                <li class="nav-item">
                                    <a href="{{ url('/') }}/{{ $DataKaryawan->menu->link }}"
                                        class="nav-link {{ Request::segment(1) == $DataKaryawan->menu->link ? 'active' : '' }}">
                                        <i class="{{ $DataKaryawan->menu->icon }} text-gray-300"></i>
                                        <p class="text-gray-300">{{ $DataKaryawan->menu->nama }}</p>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif
                @if (menuinduk('Absensi')->count() != 0)
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-user-check text-light"></i>
                            <p class="text-light">
                                ABSENSI
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview bg-gray-800">
                            @foreach (menuinduk('Absensi') as $DataAbsensi)
                                <li class="nav-item">
                                    <a href="{{ url('/') }}/{{ $DataAbsensi->menu->link }}"
                                        class="nav-link {{ Request::segment(1) == $DataAbsensi->menu->link ? 'active' : '' }}">
                                        <i class="{{ $DataAbsensi->menu->icon }} text-gray-300"></i>
                                        <p class="text-gray-300">{{ $DataAbsensi->menu->nama }}</p>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif
                <li class="nav-item">
                    <a href="{{ url('/') }}/laporan"
                        class="nav-link {{ Request::segment(1) == 'laporan' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-bar text-light"></i>
                        <p class="text-light">Laporan</p>
                    </a>
                </li>

                @if (menuinduk('Pengaturan')->count() != 0)
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cog text-light"></i>
                            <p class="text-light">
                                Pengaturan
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview bg-gray-800">
                            @foreach (menuinduk('Pengaturan') as $DataPengaturan)
                                <li class="nav-item">
                                    <a href="{{ url('/') }}/{{ $DataPengaturan->menu->link }}"
                                        class="nav-link {{ Request::segment(1) == $DataPengaturan->menu->link ? 'active' : '' }}">
                                        <i class="{{ $DataPengaturan->menu->icon }} text-gray-300"></i>
                                        <p class="text-gray-300">{{ $DataPengaturan->menu->nama }}</p>
                                    </a>
                                </li>
                            @endforeach
                            <li class="nav-item">
                                <a href="{{ url('/') }}/profiletoko"
                                    class="nav-link {{ Request::segment(1) == 'profiletoko' ? 'active' : '' }}">
                                    <p class="text-gray-300">PROFILE TOKO</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                <li class="nav-divider my-2 border-bottom border-gray-700"></li>
                <li class="nav-item">
                    <a href="{{ url('/') }}/logout" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt text-danger"></i>
                        <p class="text-danger">Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
