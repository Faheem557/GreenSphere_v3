<!--APP-SIDEBAR-->
<div class="sticky">
    <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
    <div class="app-sidebar">
        <div class="side-header">
            <a class="header-brand1" href="{{ url('/') }}">
                <img src="{{ URL::asset('assets/images/brand/logo-white.png') }}" class="header-brand-img desktop-logo" alt="logo">
                <img src="{{ URL::asset('assets/images/brand/icon-white.png') }}" class="header-brand-img toggle-logo" alt="logo">
                <img src="{{ URL::asset('assets/images/brand/icon-dark.png') }}" class="header-brand-img light-logo" alt="logo">
                <img src="{{ URL::asset('assets/images/brand/logo-dark.png') }}" class="header-brand-img light-logo1" alt="logo">
            </a>
            <!-- LOGO -->
        </div>
        <div class="main-sidemenu">
            <div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
            </svg></div>
            <ul class="side-menu">
                <li class="sub-category">
                    <h3>Main</h3>
                </li>
                <li class="slide">
                    <a class="side-menu__item has-link" data-bs-toggle="slide" href="{{ url('/') }}">
                        <i class="side-menu__icon fe fe-home"></i>
                        <span class="side-menu__label">Dashboard</span>
                    </a>
                </li>
                <li class="sub-category">
                    {{-- <h3>UI Kit</h3> --}}
                </li>
                @role('admin')
                    <li class="slide {{ request()->routeIs('plants.add') ? 'is-expanded' : '' }}">
                        <a class="side-menu__item {{ request()->routeIs('plants.add') ? 'active' : '' }}" data-bs-toggle="slide" href="javascript:void(0)">
                            <i class="side-menu__icon fe fe-slack"></i>
                            <span class="side-menu__label">Apps</span>
                            <i class="angle fe fe-chevron-right"></i>
                        </a>
                        <ul class="slide-menu {{ request()->routeIs('plants.add') ? 'show' : '' }}">
                            <li class="panel sidetab-menu">
                                <div class="tab-menu-heading p-0 pb-2 border-0">
                                    <div class="tabs-menu">
                                        <!-- Tabs -->
                                        <ul class="nav panel-tabs">
                                            <li><a href="#side1" class="d-flex active" data-bs-toggle="tab"><i class="fe fe-monitor me-2"></i><p>Home</p></a></li>
                                            <li><a href="#side2" data-bs-toggle="tab" class="d-flex"><i class="fe fe-message-square me-2"></i><p>Chat</p></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="panel-body tabs-menu-body p-0 border-0">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="side1">
                                            <ul class="sidemenu-list">
                                                <li class="side-menu-label1"><a href="javascript:void(0)">Apps</a></li>
                                                <li><a href="{{ route('seller.plants.add') }}" class="slide-item {{ request()->routeIs('seller.plants.add') ? 'active' : '' }}"> Cards design</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </li>
                @endrole
               
                @role('seller')
                    <li class="slide {{ request()->routeIs('seller.*') ? 'is-expanded' : '' }}">
                        <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)">
                            <i class="side-menu__icon fe fe-shopping-cart"></i>
                            <span class="side-menu__label">Seller Dashboard</span>
                            <i class="angle fe fe-chevron-right"></i>
                        </a>
                        <ul class="slide-menu {{ request()->routeIs('seller.*') ? 'show' : '' }}">
                            <li><a href="{{ route('seller.dashboard') }}" class="slide-item {{ request()->routeIs('seller.dashboard') ? 'active' : '' }}">
                                <i class="side-menu__icon fe fe-home"></i> Dashboard
                            </a></li>
                            
                            <!-- Products Management -->
                            <li class="sub-slide {{ request()->routeIs('seller.plants.*') || request()->routeIs('seller.inventory') ? 'is-expanded' : '' }}">
                                <a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="javascript:void(0)">
                                    <i class="side-menu__icon fe fe-package"></i> Products
                                    <i class="sub-angle fe fe-chevron-right"></i>
                                </a>
                                <ul class="sub-slide-menu">
                                    <li><a href="{{ route('seller.inventory') }}" class="sub-slide-item {{ request()->routeIs('seller.inventory') ? 'active' : '' }}">Inventory</a></li>
                                    <li><a href="{{ route('seller.plants.add') }}" class="sub-slide-item {{ request()->routeIs('seller.plants.add') ? 'active' : '' }}">Add New Plant</a></li>
                                </ul>
                            </li>

                            <!-- Orders Management -->
                            <li class="sub-slide {{ request()->routeIs('seller.orders.*') ? 'is-expanded' : '' }}">
                                <a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="javascript:void(0)">
                                    <i class="side-menu__icon fe fe-shopping-bag"></i> Orders
                                    @if(auth()->user()->unread_pending_orders_count > 0)
                                        <span class="badge bg-warning">{{ auth()->user()->unread_pending_orders_count }}</span>
                                    @endif
                                    <i class="sub-angle fe fe-chevron-right"></i>
                                </a>
                                <ul class="sub-slide-menu">
                                    <li><a href="{{ route('seller.orders.index') }}" class="sub-slide-item {{ request()->routeIs('seller.orders.index') ? 'active' : '' }}">All Orders</a></li>
                                    <li><a href="{{ route('seller.orders.pending') }}" class="sub-slide-item {{ request()->routeIs('seller.orders.pending') ? 'active' : '' }}">Pending Orders</a></li>
                                    <li><a href="{{ route('seller.orders.completed') }}" class="sub-slide-item {{ request()->routeIs('seller.orders.completed') ? 'active' : '' }}">Completed Orders</a></li>
                                </ul>
                            </li>

                            <li><a href="{{ route('seller.reviews.index') }}" class="slide-item {{ request()->routeIs('seller.reviews.*') ? 'active' : '' }}">
                                <i class="side-menu__icon fe fe-star"></i> Reviews
                            </a></li>
                            
                            <li><a href="{{ route('seller.profile') }}" class="slide-item {{ request()->routeIs('seller.profile') ? 'active' : '' }}">
                                <i class="side-menu__icon fe fe-user"></i> Store Profile
                            </a></li>
                        </ul>
                    </li>
                @endrole

                @role('user')
                    <li class="slide {{ request()->routeIs('user.*') || request()->routeIs('plants.catalog') || request()->routeIs('cart.*') ? 'is-expanded' : '' }}">
                        <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)">
                            <i class="side-menu__icon fe fe-user"></i>
                            <span class="side-menu__label">My Account</span>
                            <i class="angle fe fe-chevron-right"></i>
                        </a>
                        <ul class="slide-menu {{ request()->routeIs('user.*') || request()->routeIs('plants.catalog') || request()->routeIs('cart.*') ? 'show' : '' }}">
                            <li><a href="{{ route('user.dashboard') }}" class="slide-item {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                                <i class="side-menu__icon fe fe-home"></i> Dashboard
                            </a></li>
                            
                            <!-- Shopping -->
                            <li class="sub-slide {{ request()->routeIs('plants.catalog') || request()->routeIs('cart.*') ? 'is-expanded' : '' }}">
                                <a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="javascript:void(0)">
                                    <i class="side-menu__icon fe fe-shopping-bag"></i> Shopping
                                    <i class="sub-angle fe fe-chevron-right"></i>
                                </a>
                                <ul class="sub-slide-menu">
                                    <li><a href="{{ route('plants.catalog') }}" class="sub-slide-item {{ request()->routeIs('plants.catalog') ? 'active' : '' }}">Plant Catalog</a></li>
                                    <li><a href="{{ route('cart.index') }}" class="sub-slide-item {{ request()->routeIs('cart.*') ? 'active' : '' }}">
                                        Cart
                                        @if(session()->has('cart') && count(session()->get('cart', [])) > 0)
                                            <span class="badge bg-primary">{{ count(session()->get('cart', [])) }}</span>
                                        @endif
                                    </a></li>
                                </ul>
                            </li>

                            <!-- Orders -->
                            <li class="sub-slide {{ request()->routeIs('user.orders.*') ? 'is-expanded' : '' }}">
                                <a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="javascript:void(0)">
                                    <i class="side-menu__icon fe fe-package"></i> Orders
                                    <i class="sub-angle fe fe-chevron-right"></i>
                                </a>
                                <ul class="sub-slide-menu">
                                    <li><a href="{{ route('user.orders.index') }}" class="sub-slide-item {{ request()->routeIs('user.orders.index') ? 'active' : '' }}">All Orders</a></li>
                                    <li><a href="{{ route('user.orders.active') }}" class="sub-slide-item {{ request()->routeIs('user.orders.active') ? 'active' : '' }}">Active Orders</a></li>
                                </ul>
                            </li>

                            <li><a href="{{ route('user.user.reviews') }}" class="slide-item {{ request()->routeIs('user.user.reviews') ? 'active' : '' }}">
                                <i class="side-menu__icon fe fe-star"></i> My Reviews
                            </a></li>
                            
                            <li><a href="{{ route('user.wishlist') }}" class="slide-item {{ request()->routeIs('user.wishlist') ? 'active' : '' }}">
                                <i class="side-menu__icon fe fe-heart"></i> Wishlist
                            </a></li>
                            
                            <li><a href="{{ route('user.profile') }}" class="slide-item {{ request()->routeIs('user.profile') ? 'active' : '' }}">
                                <i class="side-menu__icon fe fe-settings"></i> Profile Settings
                            </a></li>

                            <li><a href="{{ route('plants.my-plants') }}" class="slide-item {{ request()->routeIs('plants.my-plants') ? 'active' : '' }}">
                                <i class="side-menu__icon fe fe-grid"></i> My Plants
                            </a></li>
                        </ul>
                    </li>
                @endrole
            </ul>
            <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
            </svg></div>
        </div>
    </div>
</div>
<!--/APP-SIDEBAR-->
