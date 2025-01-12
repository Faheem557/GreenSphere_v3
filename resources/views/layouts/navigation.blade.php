<header class="app-header">
    <!-- Header Nav -->
    <nav class="main-header-nav d-flex">
        <div class="header-nav-left d-flex">
            <!-- Logo -->
            <a class="header-brand" href="{{ route('home') }}">
                <img src="{{ asset('assets/images/brand/logo.png') }}" class="header-brand-img desktop-logo" alt="logo">
                <img src="{{ asset('assets/images/brand/logo-light.png') }}" class="header-brand-img desktop-logo-dark" alt="logo">
            </a>
            <!-- Toggle Menu -->
            <a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-bs-toggle="sidebar" href="javascript:void(0)"></a>
        </div>

        <div class="header-nav-right d-flex">
            <!-- User Menu -->
            <div class="dropdown d-flex profile-1">
                <a href="javascript:void(0)" data-bs-toggle="dropdown" class="nav-link leading-none d-flex">
                    <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}"
                        class="avatar profile-user brround cover-image">
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <div class="drop-heading">
                        <div class="text-center">
                            <h5 class="text-dark mb-0 fs-14 fw-semibold">{{ auth()->user()->name }}</h5>
                            <small class="text-muted">{{ auth()->user()->email }}</small>
                        </div>
                    </div>
                    <div class="dropdown-divider m-0"></div>

                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                        <i class="dropdown-icon fe fe-user"></i> Profile
                    </a>

                    @role('user')
                    <a class="dropdown-item" href="{{ route('user.orders.index') }}">
                        <i class="dropdown-icon fe fe-shopping-bag"></i> My Orders
                    </a>
                    @endrole

                    @role('seller')
                    <a class="dropdown-item" href="{{ route('seller.orders.index') }}">
                        <i class="dropdown-icon fe fe-shopping-cart"></i> Orders
                    </a>
                    <a class="dropdown-item" href="{{ route('seller.inventory') }}">
                        <i class="dropdown-icon fe fe-package"></i> Inventory
                    </a>
                    @endrole

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="dropdown-icon fe fe-log-out"></i> Sign out
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </nav>
</header>

<!-- Sidebar Menu -->
<div class="sticky">
    <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
    <div class="app-sidebar">
        <div class="side-header">
            <a class="header-brand1" href="{{ route('home') }}">
                <img src="{{ asset('assets/images/brand/logo.png') }}" class="header-brand-img desktop-logo" alt="logo">
                <img src="{{ asset('assets/images/brand/logo-light.png') }}" class="header-brand-img toggle-logo" alt="logo">
            </a>
        </div>
        <div class="main-sidemenu">
            <div class="slide-left disabled" id="slide-left"><svg></svg></div>
            <ul class="side-menu">
                @role('user')
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('user.dashboard') }}">
                        <i class="side-menu__icon fe fe-home"></i>
                        <span class="side-menu__label">Dashboard a</span>
                    </a>
                </li>
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('user.orders.index') }}">
                        <i class="side-menu__icon fe fe-shopping-bag"></i>
                        <span class="side-menu__label">My Orders</span>
                    </a>
                </li>
                @endrole

                @role('seller')
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('seller.dashboard') }}">
                        <i class="side-menu__icon fe fe-home"></i>
                        <span class="side-menu__label">Dashboard</span>
                    </a>
                </li>
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('seller.orders.index') }}">
                        <i class="side-menu__icon fe fe-shopping-cart"></i>
                        <span class="side-menu__label">Orders</span>
                    </a>
                </li>
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('seller.inventory') }}">
                        <i class="side-menu__icon fe fe-package"></i>
                        <span class="side-menu__label">Inventory</span>
                    </a>
                </li>
                @endrole
            </ul>
            <div class="slide-right" id="slide-right"><svg></svg></div>
        </div>
    </div>
</div>