<aside id="app-sidebar" class="app-sidebar sidebar-mini bg-primary-substle elevation-4" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="{{ url('/') }}" class="brand-link">
            <img src="{{ asset('adminlte/dist/assets/img/AdminLTELogo.png') }}" alt="SIKAP APP Logo" class="brand-image opacity-75 shadow">
            <span class="brand-text fw-light">SIKAP <i>APP</i></span>
        </a>
    </div>
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                {{-- Dashboard Static --}}
                <li class="nav-item">
                    <a href="{{ url('/') }}" class="nav-link {{ Request::is('/') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p><strong>{{ __('messages.dashboard') }}</strong></p>
                    </a>
                </li>

                {{-- Dynamic Menu Loop --}}
                @foreach (config('menus') as $menu)
                    @php
                        $visibleChildren = collect($menu['children'])->filter(fn($child) => auth()->user()->can($child['permission']));
                        $active = $visibleChildren->contains(function ($child) {
                            return Request::is(ltrim($child['url'], '/') . '*');
                        });
                    @endphp

                    @if ($visibleChildren->isNotEmpty())
                        <li class="nav-item {{ $active ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa-solid {{ $menu['icon'] }}"></i>
                                <p>
                                    <strong>{{ __($menu['label']) }}</strong>
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @foreach ($visibleChildren as $item)
                                    <li class="nav-item">
                                        <a href="{{ url($item['url']) }}" class="nav-link {{ Request::is(ltrim($item['url'], '/') . '*') ? 'active' : '' }}">
                                            <i class="nav-icon fa-solid {{ $item['icon'] }}"></i>
                                            <p>{{ __($item['label']) }}</p>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endif
                @endforeach
            </ul>
        </nav>
    </div>
</aside>