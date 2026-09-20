<div class="page-header page-header-light shadow">
    <div class="page-header-content d-lg-flex">
        <div class="d-flex">
            <div class="breadcrumb py-2">
                <a href="{{ route('dashboard') }}" class="breadcrumb-item">
                    <i class="ph-house"></i>
                </a>
                @yield('breadcrumb')
            </div>
            <a href="#breadcrumb_elements"
                class="btn btn-light align-self-center collapsed d-lg-none border-transparent rounded-pill p-0 ms-auto"
                data-bs-toggle="collapse">
                <i class="ph-caret-down collapsible-indicator ph-sm m-1"></i>
            </a>
        </div>
        <div class="collapse d-lg-block ms-lg-auto" id="breadcrumb_elements">
            <div class="d-lg-flex mb-2 mb-lg-0">
                @yield('breadcrumb-actions')
            </div>
        </div>
    </div>
</div>