<aside class="sidenav bg-white opacity-75 navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 " id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href=" https://demos.creative-tim.com/argon-dashboard/pages/dashboard.html " target="_blank">
            <img src="{{ asset('assets/img/logoDLH.jpeg') }}" class="navbar-brand-img img-fluid" alt="main_logo">
            <span class="ms-3 font-weight-bold">DLH KOTA MALANG</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="w-auto " id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                    <div class="icon icon-sm border-radius-md me-2 d-flex align-items-center justify-content-center">
                        <i class="bi bi-laptop text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('kecamatan') || request()->is('kelurahan') ? 'active' : '' }}" href="#" id="dataWilayahDropdown" role="button" aria-expanded="false">
                    <div class="icon icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="bi bi-geo-alt-fill text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1"> Data Wilayah</span>
                    <i class="bi bi-chevron-down ms-3" id="dropdownIcon"></i>
                    <span class="ms-2"></span>
                </a>
                <ul class="dropdown-menu" id="subMenu" aria-labelledby="dataWilayahDropdown" style="margin-top: -4px; display: none;">
                    <li class="nav-item">
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('kecamatan') }}">
                            <div class="icon icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center" style="margin-left: 32px;">
                                <i class="bi bi-map text-primary text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Data Kecamatan</span>
                        </a>
                    </li>
                    <li class="nav-item" style="margin-top: 4px">
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('kelurahan') }}">
                            <div class="icon icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center" style="margin-left: 32px;">
                                <i class="bi bi-geo-fill text-primary text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Data Kelurahan</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('parameter') ? 'active' : '' }}" href="{{ route('parameter') }}">
                    <div class="icon icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="bi bi-box-fill text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Data Parameter</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('tps') ? 'active' : '' }}" href="{{ route('tps') }}">
                    <div class="icon icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="bi bi-database-fill text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Data TPS</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('jarak') ? 'active' : '' }}" href="{{ route('jarak') }}">
                    <div class="icon icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="bi bi-clipboard-data-fill text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Data Jarak Antar TPS</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('sampah') ? 'active' : '' }}" href="{{ route('sampah') }}">
                    <div class="icon icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="bi bi-table text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Data Sampah</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('proses') ? 'active' : '' }}" href="{{ route('proses') }}">
                    <div class="icon icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="bi bi-globe text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Proses Clustering</span>
                </a>
            </li>
</aside>