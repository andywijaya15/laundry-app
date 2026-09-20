<div class="sidebar sidebar-main sidebar-expand-lg">
    <div class="sidebar-content">
        <div class="sidebar-section">
            <div class="sidebar-section-body d-flex justify-content-center">
                <h5 class="sidebar-resize-hide flex-grow-1 my-auto">Navigation</h5>
                <div>
                    <button type="button"
                        class="btn btn-light btn-icon btn-sm rounded-pill border-transparent sidebar-mobile-main-toggle d-lg-none">
                        <i class="ph-x"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="sidebar-section">
            <ul class="nav nav-sidebar" data-nav-type="accordion">
                {{-- Dashboard - Owner & Staff --}}
                @php
                    $dashboardPattern = 'dashboard';
                @endphp
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->routeIs($dashboardPattern) ? 'active' : '' }}">
                        <i class="ph-house"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                {{-- Services - Owner only --}}
                @if(auth()->user()->isOwner())
                @php
                    $servicesPattern = 'services*';
                @endphp
                <li class="nav-item">
                    <a href="{{ route('services.index') }}"
                        class="nav-link {{ request()->routeIs($servicesPattern) ? 'active' : '' }}">
                        <i class="ph-list-plus"></i>
                        <span>Layanan</span>
                    </a>
                </li>
                @endif

                {{-- Customers - Owner & Staff --}}
                @php
                    $customersPattern = 'customers*';
                @endphp
                <li class="nav-item">
                    <a href="{{ route('customers.index') }}"
                        class="nav-link {{ request()->routeIs($customersPattern) ? 'active' : '' }}">
                        <i class="ph-users"></i>
                        <span>Pelanggan</span>
                    </a>
                </li>

                {{-- Orders - Owner & Staff --}}
                @php
                    $ordersPattern = 'orders*';
                @endphp
                <li class="nav-item">
                    <a href="{{ route('orders.index') }}"
                        class="nav-link {{ request()->routeIs($ordersPattern) ? 'active' : '' }}">
                        <i class="ph-shopping-cart"></i>
                        <span>Order</span>
                    </a>
                </li>

                {{-- Payments - Owner & Staff --}}
                @php
                    $paymentsPattern = 'payments*';
                @endphp
                <li class="nav-item">
                    <a href="{{ route('payments.index') }}"
                        class="nav-link {{ request()->routeIs($paymentsPattern) ? 'active' : '' }}">
                        <i class="ph-coins"></i>
                        <span>Pembayaran</span>
                    </a>
                </li>

                {{-- Reports - Owner only (collapsible submenu) --}}
                @if(auth()->user()->isOwner())
                @php
                    $reportsPattern = 'reports*';
                    $hasActiveReport = request()->routeIs($reportsPattern);
                @endphp
                <li class="nav-item nav-item-submenu {{ $hasActiveReport ? 'nav-item-expanded nav-item-open active' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="ph-file-text"></i>
                        <span>Laporan</span>
                    </a>
                    <ul class="nav-group-sub collapse {{ $hasActiveReport ? 'show' : '' }}" {{ $hasActiveReport ? 'style=display:block' : '' }}>
                        <li class="nav-item">
                            <a href="{{ route('reports.daily') }}"
                                class="nav-link {{ request()->routeIs('reports.daily') ? 'active' : '' }}">
                                <i class="ph-calendar"></i>
                                <span>Harian</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('reports.monthly') }}"
                                class="nav-link {{ request()->routeIs('reports.monthly') ? 'active' : '' }}">
                                <i class="ph-calendar"></i>
                                <span>Bulanan</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('reports.transactions') }}"
                                class="nav-link {{ request()->routeIs('reports.transactions') ? 'active' : '' }}">
                                <i class="ph-list-checks"></i>
                                <span>Transaksi</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
            </ul>
        </div>
    </div>
</div>