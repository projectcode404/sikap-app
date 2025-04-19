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
                <li class="nav-item">
                    <a href="{{ url('/') }}" class="nav-link {{ Request::is('/') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('employee/*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa-solid fa-user-group"></i>
                        <p>
                            Employees
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('/employee/data') }}" class="nav-link {{ Request::is('employee/data') ? 'active' : '' }}">
                                <i class="nav-icon fa-solid fa-id-card"></i>
                                <p>Employees</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/employee/divisions') }}" class="nav-link {{ Request::is('employee/divisions') ? 'active' : '' }}">
                                <i class="nav-icon fa-solid fa-layer-group"></i>
                                <p>Divisions</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/employee/positions') }}" class="nav-link {{ Request::is('employee/positions') ? 'active' : '' }}">
                                <i class="nav-icon fa-solid fa-briefcase"></i>
                                <p>Positions</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/employee/work-units') }}" class="nav-link {{ Request::is('employee/work-units') ? 'active' : '' }}">
                                <i class="nav-icon fa-solid fa-building-user"></i>
                                <p>Work Units</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {{ Request::is('atk/*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa-solid fa-boxes-stacked"></i>
                        <p>
                            ATK
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('/atk/stock') }}" class="nav-link {{ Request::is('atk/stock') ? 'active' : '' }}">
                                <i class="nav-icon fa-solid fa-warehouse"></i>
                                <p>Stock</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/atk/po') }}" class="nav-link {{ Request::is('atk/po') ? 'active' : '' }}">
                                <i class="nav-icon fa-solid fa-file-signature"></i>
                                <p>PO ATK</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/atk/receive') }}" class="nav-link {{ Request::is('atk/receive') ? 'active' : '' }}">
                                <i class="nav-icon fa-solid fa-truck-ramp-box"></i>
                                <p>Receive ATK</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/atk/request') }}" class="nav-link {{ Request::is('atk/request') ? 'active' : '' }}">
                                <i class="nav-icon fa-solid fa-cart-plus"></i>
                                <p>Request ATK</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/atk/request') }}" class="nav-link {{ Request::is('atk/request') ? 'active' : '' }}">
                                <i class="nav-icon fa-solid fa-box-open"></i>
                                <p>Return ATK</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/user/data') }}" class="nav-link {{ Request::is('user/data') ? 'active' : '' }}">
                        <i class="nav-icon fa-solid fa-users"></i>
                        <p>Users</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
