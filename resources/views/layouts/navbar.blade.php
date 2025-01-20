<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="false">
    <div class="container-fluid pt-4 px-3">
        <x-breadcrumb />

        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
            <!-- Navbar items, including logout button -->
            <ul class="navbar-nav ms-auto">
                <form action="{{ route('logout') }}" method="POST" style="display:inline">
                    @csrf
                    <button type="submit" class="nav-link" style="border: none; background: none; display: flex; align-items: center;">
                        <i class="bi bi-box-arrow-left text-white text-lg opacity-10" style="margin-right: 12px;"></i>
                        <span class="nav-link-text text-lg fw-bold text-white">Logout</span>
                    </button>
                </form>                
            </ul>

            <!-- Navbar end section -->
            <ul class="navbar-nav justify-content-end">
                <!-- Sidebar toggle button (only visible on mobile) -->
                <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-white p-0" id="iconNavbarSidenav">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line bg-white"></i>
                            <i class="sidenav-toggler-line bg-white"></i>
                            <i class="sidenav-toggler-line bg-white"></i>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
