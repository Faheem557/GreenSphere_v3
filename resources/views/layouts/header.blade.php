@php
use Illuminate\Support\Facades\Schema;
@endphp

<!-- app-Header -->
<div class="app-header header sticky">
    <div class="container-fluid main-container">
        <div class="d-flex">
            <a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-bs-toggle="sidebar" href="javascript:void(0)"></a>
            <!-- sidebar-toggle-->
            <a class="logo-horizontal" href="{{ url('/') }}">
                <img src="{{ URL::asset('assets/images/brand/logo-white.png') }}" class="header-brand-img desktop-logo" alt="logo">
                <img src="{{ URL::asset('assets/images/brand/logo-dark.png') }}" class="header-brand-img light-logo1" alt="logo">
            </a>

            <!-- Search -->
            <div class="main-header-center ms-3 d-none d-lg-block">
                <input type="text" class="form-control" id="typehead" placeholder="Search for plants...">
                <button class="btn px-0 pt-2"><i class="fe fe-search" aria-hidden="true"></i></button>
            </div>

            <div class="d-flex order-lg-2 ms-auto header-right-icons">
                <div class="navbar navbar-collapse responsive-navbar p-0">
                    <div class="collapse navbar-collapse" id="navbarSupportedContent-4">
                        <div class="d-flex order-lg-2">
                            <!-- Theme Toggle -->
                            <div class="d-flex">
                                <a class="nav-link icon theme-layout nav-link-bg layout-setting">
                                    <span class="dark-layout"><i class="fe fe-moon"></i></span>
                                    <span class="light-layout"><i class="fe fe-sun"></i></span>
                                </a>
                            </div>

                            <!-- Cart Icon (Only for Users) -->
                            @role('user')
                            <div class="dropdown d-flex">
                                <a class="nav-link icon text-center" href="{{ route('cart.index') }}">
                                    <i class="fe fe-shopping-cart"></i>
                                    @if(session()->has('cart') && count(session()->get('cart', [])) > 0)
                                    <span class="badge bg-primary header-badge cart-count">
                                        {{ count(session()->get('cart', [])) }}
                                    </span>
                                    @endif
                                </a>
                            </div>
                            @endrole

                            <!-- Orders Badge (Only for Sellers) -->
                            @role('seller')
                            <div class="dropdown d-flex">
                                <a class="nav-link icon text-center" href="{{ route('seller.orders.index') }}">
                                    <i class="fe fe-shopping-bag"></i>
                                    @php
                                    $pendingOrdersCount = auth()->user()->orders()
                                    ->where('status', 'pending')
                                    ->where('is_read', false)
                                    ->count();
                                    @endphp
                                    @if($pendingOrdersCount > 0)
                                    <span class="badge bg-warning header-badge">{{ $pendingOrdersCount }}</span>
                                    @endif
                                </a>
                            </div>
                            @endrole

                            <!-- Notifications -->
                            <div class="dropdown d-flex notifications">
                                <a class="nav-link icon" data-bs-toggle="dropdown">
                                    <i class="fe fe-bell"></i>
                                    @if(auth()->user()->unreadNotifications->count() > 0)
                                    <span class="pulse"></span>
                                    @endif
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <div class="drop-heading border-bottom">
                                        <div class="d-flex">
                                            <h6 class="mt-1 mb-0 fs-16 fw-semibold text-dark">Notifications</h6>
                                        </div>
                                    </div>
                                    <div class="notifications-menu">
                                        @forelse(auth()->user()->notifications()->latest()->take(5)->get() as $notification)
                                        <a class="dropdown-item d-flex" href="#">
                                            <div class="me-3 notifyimg bg-{{ $notification->data['type'] ?? 'primary' }} brround box-shadow-primary">
                                                <i class="fe fe-{{ $notification->data['icon'] ?? 'bell' }}"></i>
                                            </div>
                                            <div class="mt-1 wd-80p">
                                                <h5 class="notification-label mb-1">{{ $notification->data['message'] ?? 'New Notification' }}</h5>
                                                <span class="notification-subtext">{{ $notification->created_at->diffForHumans() }}</span>
                                            </div>
                                        </a>
                                        @empty
                                        <a class="dropdown-item d-flex" href="#">
                                            <div class="mt-1 wd-80p">
                                                <span>No notifications</span>
                                            </div>
                                        </a>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                            <!-- User Profile -->
                            <div class="dropdown d-flex profile-1">
                                <a href="javascript:void(0)" data-bs-toggle="dropdown" class="nav-link leading-none d-flex">
                                    <img src="{{ auth()->user()->profile_photo_url ?? URL::asset('assets/images/users/default.jpg') }}"
                                        alt="profile-user"
                                        class="avatar profile-user brround cover-image">
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <div class="drop-heading">
                                        <div class="text-center">
                                            <h5 class="text-dark mb-0 fs-14 fw-semibold">{{ auth()->user()->name }}</h5>
                                            <small class="text-muted">{{ auth()->user()->roles->first()->name ?? 'User' }}</small>
                                        </div>
                                    </div>
                                    <div class="dropdown-divider m-0"></div>

                                    <!-- Profile Management -->
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="dropdown-icon fe fe-user"></i> Profile
                                    </a>

                                    <!-- Authentication -->
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /app-Header -->

@push('scripts')
<script>
    // Function to update cart count
    function updateCartCount(count) {
        const cartBadge = $('.cart-count');
        if (count > 0) {
            if (cartBadge.length) {
                cartBadge.text(count);
            } else {
                $('.fe-shopping-cart').after(`<span class="badge bg-primary header-badge cart-count">${count}</span>`);
            }
        } else {
            cartBadge.remove();
        }
    }

    // Listen for cart updates via Pusher
    const pusher = new Pusher('{{ config('
        broadcasting.connections.pusher.key ') }}', {
            cluster: '{{ config('
            broadcasting.connections.pusher.options.cluster ') }}'
        });

    const channel = pusher.subscribe('cart-channel');
    channel.bind('cart-updated', function(data) {
        updateCartCount(data.count);
    });
</script>
@endpush

<div class="cart-icon-wrapper">
    <i class="fe fe-shopping-cart"></i>
    <span class="cart-counter badge bg-primary">
        {{ array_sum(array_column(session()->get('cart', []), 'quantity')) }}
    </span>
</div>