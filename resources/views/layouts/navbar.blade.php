<nav class="app-header navbar navbar-expand bg-body-tertiary">
    <div class="container-fluid">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                    <i class="fa-solid fa-bars"></i>
                </a>
            </li>
        </ul>
        <ul class="navbar-nav ms-auto">
        <li class="nav-item dropdown">
                <button class="btn btn-link nav-link py-2 px-0 px-lg-2 dropdown-toggle d-flex align-items-center" id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown" data-bs-display="static">
                    <span class="theme-icon-active">
                        <i class="my-1"></i>
                    </span>
                    <span class="d-lg-none ms-2" id="bd-theme-text">{{ __('messages.theme') }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="bd-theme-text" style="--bs-dropdown-min-width: 8rem;">
                    <li>
                        <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="light" aria-pressed="false">
                            <i class="bi bi-sun-fill me-2"></i>
                            {{ __('messages.light') }}
                            <i class="bi bi-check-lg ms-auto d-none"></i>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
                            <i class="bi bi-moon-fill me-2"></i>
                            {{ __('messages.dark') }}
                            <i class="bi bi-check-lg ms-auto d-none"></i>
                        </button>
                    </li>
                </ul>
            </li>
            <!--begin::Fullscreen Toggle-->
            <li class="nav-item">
                <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                    <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen" style=""></i>
                    <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
                </a>
            </li>
            <!--end::Fullscreen Toggle-->
            <!--begin::Language Dropdown-->
            <li class="nav-item dropdown">
                <button class="btn btn-link nav-link py-2 px-0 px-lg-2 dropdown-toggle d-flex align-items-center"
                        id="lang-switcher" type="button" aria-expanded="false" data-bs-toggle="dropdown"
                        data-bs-display="static">
                    <span class="theme-icon-active">
                        <i class="fas fa-globe me-1"></i>
                    </span>
                    <span class="d-lg-none ms-2">{{ __('messages.language') }}</span>
                </button>

                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="lang-switcher" style="--bs-dropdown-min-width: 8rem;">
                    <li>
                        <a class="dropdown-item d-flex align-items-center {{ app()->getLocale() === 'id' ? 'active' : '' }}"
                        href="{{ route('set-locale', 'id') }}"> ID
                            @if(app()->getLocale() === 'id')
                                <i class="fas fa-check ms-auto"></i>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center {{ app()->getLocale() === 'en' ? 'active' : '' }}"
                        href="{{ route('set-locale', 'en') }}"> EN
                            @if(app()->getLocale() === 'en')
                                <i class="fas fa-check ms-auto"></i>
                            @endif
                        </a>
                    </li>
                </ul>
            </li>
            <!--end::Language Dropdown-->
            <!--begin::User Menu Dropdown-->
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                <img
                    src="{{ asset('adminlte/dist/assets/img/user2-160x160.jpg') }}"
                    class="user-image rounded-circle shadow"
                    alt="User Image"
                />
                <span class="d-none d-md-inline">Alexander Pierce</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <!--begin::User Image-->
                <li class="user-header text-bg-primary">
                    <img
                    src="{{ asset('adminlte/dist/assets/img/user2-160x160.jpg') }}"
                    class="rounded-circle shadow"
                    alt="User Image"
                    />
                    <p>
                    Alexander Pierce - Web Developer
                    <small>Member since Nov. 2023</small>
                    </p>
                </li>
                <!--end::User Image-->
                <!--begin::Menu Footer-->
                <li class="user-footer">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="btn btn-default btn-flat float-end">
                            {{ __('messages.sign_out') }}
                        </a>
                    </form>
                </li>
                <!--end::Menu Footer-->
                </ul>
            </li>
            <!--end::User Menu Dropdown-->
        </ul>
    </div>
</nav>
