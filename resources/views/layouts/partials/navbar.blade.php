<!-- Main navbar -->
<div class="navbar navbar-dark navbar-expand-lg fixed-top mb-3">
    <div class="container-fluid">
        <div class="d-flex d-lg-none me-2">
            <button type="button" class="navbar-toggler sidebar-mobile-main-toggle rounded-pill">
                <i class="ph-list"></i>
            </button>
        </div>

        <div class="navbar-brand flex-1 flex-lg-0">
            <a href="{{ route('dashboard') }}" class="d-inline-flex align-items-center">
                <span class="fw-bold text-white fs-4">Laundry App</span>
            </a>
        </div>

        <ul class="nav flex-row justify-content-end order-1 order-lg-2">
            <li class="nav-item nav-item-dropdown-lg dropdown ms-lg-2">
                <a href="#" class="navbar-nav-link align-items-center rounded-pill p-1" data-bs-toggle="dropdown">
                    <div class="w-32px h-32px rounded-pill bg-primary d-flex align-items-center justify-content-center">
                        <i class="ph-user text-white"></i>
                    </div>
                    <span class="d-none d-lg-inline-block mx-lg-2">{{ auth()->user()->name }}</span>
                </a>

                <div class="dropdown-menu dropdown-menu-end">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="ph-sign-out me-2"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </div>
</div>
<!-- /main navbar -->