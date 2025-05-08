<nav class="main-header navbar navbar-expand navbar-dark">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars text-white"></i>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('/') }}" class="nav-link">
                <i class="fas fa-home text-white"></i>
                <span class="ml-2 d-none d-lg-inline text-white font-weight-medium">Home</span>
            </a>
        </li>
        @if (Auth()->User()->role_id == 1)
            <li class="nav-item">
                <a href="{{ url('/') }}/laporan" class="nav-link">
                    <i class="fas fa-chart-line text-white"></i>
                    <span class="ml-2 d-none d-lg-inline text-white font-weight-medium">Analisa</span>
                </a>
            </li>
        @endif
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link d-flex align-items-center" data-toggle="dropdown" href="#">
                <span class="mr-2 d-none d-lg-inline text-white font-weight-medium">{{ Auth()->user()->nama }}</span>
                <img class="img-circle img-bordered-sm" width="35" height="35"
                     src="{{ asset('toko/public/storage/' . Auth()->User()->foto) }}"
                    style="border: 2px solid #fff; border-radius: 50%; object-fit: cover;">
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a href="{{ url('/') }}/userprofile" class="dropdown-item">
                    <i class="fas fa-user-tie mr-2 text-gray-700"></i> <span class="text-gray-900">Profil</span>
                </a>
                <a href="{{ url('/') }}/ttduser" class="dropdown-item">
                    <i class="fas fa-key mr-2 text-gray-700"></i> <span class="text-gray-900">Tanda Tangan</span>
                </a>
                <a href="{{ url('/') }}/presensi" class="dropdown-item">
                    <i class="far fa-calendar-check mr-2 text-gray-700"></i> <span class="text-gray-900">Rincian Absensi</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ url('/') }}/editpass" class="dropdown-item">
                    <i class="fas fa-key mr-2 text-gray-700"></i> <span class="text-gray-900">Edit Password</span>
                </a>
                <a href="{{ url('/') }}/logout" class="dropdown-item">
                    <i class="fas fa-unlock-alt mr-2 text-gray-700"></i> <span class="text-gray-900">Logout</span>
                </a>
            </div>
        </li>
    </ul>
</nav>
