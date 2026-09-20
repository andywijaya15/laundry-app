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
                @foreach(config('menu') as $group)
                <li class="nav-item-header">
                    <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">
                        {{ $group['header'] }}
                    </div>
                    <i class="ph-dots-three sidebar-resize-show"></i>
                </li>
                @foreach($group['items'] as $item)
                    @if(!$item['permission'] || auth()->user()->can($item['permission']))
                    @if(isset($item['children']) && count($item['children']))
                        @php
                            $hasActiveChild = collect($item['children'])->contains(function($child) {
                                $prefix = explode('.', $child['route'])[0];
                                $pattern = str_contains($child['route'], '.') ? $prefix . '.*' : $prefix;
                                return request()->routeIs($pattern);
                            });
                        @endphp
                        <li class="nav-item nav-item-submenu {{ $hasActiveChild ? 'nav-item-expanded nav-item-open active' : '' }}">
                            <a href="#" class="nav-link">
                                <i class="{{ $item['icon'] }}"></i>
                                <span>{{ $item['label'] }}</span>
                            </a>
                            <ul class="nav-group-sub collapse {{ $hasActiveChild ? 'show' : '' }}" {{ $hasActiveChild ? 'style=display:block' : '' }}>
                                @foreach($item['children'] as $child)
                                @php
                                    $childPrefix = explode('.', $child['route'])[0];
                                    $childPattern = str_contains($child['route'], '.') ? $childPrefix . '.*' : $childPrefix;
                                @endphp
                                <li class="nav-item">
                                    <a href="{{ route($child['route']) }}"
                                        class="nav-link {{ request()->routeIs($childPattern) ? 'active' : '' }}">
                                        {{ $child['label'] }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        @php
                            $itemPrefix = explode('.', $item['route'])[0];
                            $itemPattern = str_contains($item['route'], '.') ? $itemPrefix . '.*' : $itemPrefix;
                        @endphp
                        <li class="nav-item">
                            <a href="{{ route($item['route']) }}"
                                class="nav-link {{ request()->routeIs($itemPattern) ? 'active' : '' }}">
                                <i class="{{ $item['icon'] }}"></i>
                                <span>{{ $item['label'] }}</span>
                            </a>
                        </li>
                    @endif
                    @endif
                @endforeach
                @endforeach
            </ul>
        </div>
    </div>
</div>