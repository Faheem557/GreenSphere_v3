@extends('layouts.main')

@section('title', 'Plant Catalog')

@section('maincontent')
<div class="app-content main-content">
    <div class="side-app">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-leftheader">
                <h4 class="page-title mb-0">Plant Catalog</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><i class="fe fe-home"></i> Home</a></li>
                    <li class="breadcrumb-item active">Plant Catalog</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <!-- Category Filter -->
            <div class="col-xl-3 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Filters</h3>
                    </div>
                    <div class="card-body">
                        <form id="filter-form" action="{{ route('plants.catalog') }}" method="GET">
                            <!-- Categories -->
                            <div class="custom-controls-stacked">
                                <h4 class="mb-3">Categories</h4>
                                @foreach($categories as $key => $category)
                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input filter-control" 
                                               name="categories[]" value="{{ $key }}" 
                                               {{ in_array($key, request('categories', [])) ? 'checked' : '' }}>
                                        <span class="custom-control-label">{{ $category }}</span>
                                    </label>
                                @endforeach
                            </div>

                            <!-- Sub Categories (Dynamic based on selected category) -->
                            <div class="mt-4" id="subCategoriesContainer" style="display: none;">
                                <h4 class="mb-3">Sub Categories</h4>
                                <div id="subCategoriesContent">
                                    <!-- Populated via JavaScript -->
                                </div>
                            </div>

                            <!-- Care Level -->
                            <div class="mt-4">
                                <h4 class="mb-3">Care Level</h4>
                                @foreach($careLevels as $key => $level)
                                    <label class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input filter-control" 
                                               name="care_level" value="{{ $key }}" 
                                               {{ request('care_level') == $key ? 'checked' : '' }}>
                                        <span class="custom-control-label">{{ $level }}</span>
                                    </label>
                                @endforeach
                            </div>

                            <!-- Water Needs -->
                            <div class="mt-4">
                                <h4 class="mb-3">Water Needs</h4>
                                @foreach($waterNeeds as $key => $need)
                                    <label class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input filter-control" 
                                               name="water_needs" value="{{ $key }}" 
                                               {{ request('water_needs') == $key ? 'checked' : '' }}>
                                        <span class="custom-control-label">{{ $need }}</span>
                                    </label>
                                @endforeach
                            </div>

                            <!-- Light Needs -->
                            <div class="mt-4">
                                <h4 class="mb-3">Light Requirements</h4>
                                @foreach($lightNeeds as $key => $need)
                                    <label class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input filter-control" 
                                               name="light_needs" value="{{ $key }}" 
                                               {{ request('light_needs') == $key ? 'checked' : '' }}>
                                        <span class="custom-control-label">{{ $need }}</span>
                                    </label>
                                @endforeach
                            </div>

                            <!-- Price Range -->
                            <div class="mt-4">
                                <h4 class="mb-3">Price Range</h4>
                                <div class="range-slider">
                                    <input type="range" class="form-range filter-control" id="minPrice" 
                                           name="min_price" min="0" max="1000" 
                                           value="{{ request('min_price', 0) }}">
                                    <input type="range" class="form-range filter-control" id="maxPrice" 
                                           name="max_price" min="0" max="1000" 
                                           value="{{ request('max_price', 1000) }}">
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>₹<span id="minPriceValue">{{ request('min_price', 0) }}</span></span>
                                    <span>₹<span id="maxPriceValue">{{ request('max_price', 1000) }}</span></span>
                                </div>
                            </div>

                            <!-- Sort Options -->
                            <div class="mt-4">
                                <h4 class="mb-3">Sort By</h4>
                                <select name="sort_by" id="sort-select" class="form-select filter-control">
                                    <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Newest First</option>
                                    <option value="price" {{ request('sort_by') == 'price' ? 'selected' : '' }}>Price</option>
                                    <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Name</option>
                                </select>
                                <select name="sort_order" class="form-select mt-2 filter-control">
                                    <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descending</option>
                                    <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                                </select>
                            </div>

                            <!-- Apply Filters Button -->
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Plant Grid -->
            <div class="col-xl-9 col-lg-8">
                <div class="row">
                    @foreach($plants as $plant)
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <div class="card overflow-hidden">
                            <div class="card-body pd-0">
                                <div class="product-grid">
                                    <div class="product-image">
                                        @if($plant->image)
                                            <a href="shop-description.html" class="image">
                                                <img class="pic-1" src="{{ asset('storage/' . $plant->image) }}" 
                                                     alt="{{ $plant->name }}"
                                                     onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                                            </a>
                                        @else
                                            <div class="image">
                                                <img class="pic-1" src="{{ asset('images/placeholder.jpg') }}" 
                                                     alt="No Image Available">
                                            </div>
                                        @endif
                                        <div class="product-discount-label">
                                            @if($plant->quantity > 0)
                                                <span class="badge bg-success">In Stock</span>
                                            @else
                                                <span class="badge bg-danger">Out of Stock</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <h3 class="title fw-bold fs-20">{{ $plant->name }}</h3>
                                        <div class="mb-2 text-muted">{{ Str::limit($plant->description, 100) }}</div>
                                        
                                        <!-- Add care requirements -->
                                        <div class="care-requirements mb-2">
                                            <span class="badge bg-info">{{ ucfirst($plant->care_level) }}</span>
                                            <span class="badge bg-light text-dark">Water: {{ $plant->water_needs }}</span>
                                            <span class="badge bg-light text-dark">Light: {{ $plant->light_needs }}</span>
                                        </div>
                                        
                                        <div class="price mb-2">
                                            <span class="fs-18 fw-bold">₹{{ number_format($plant->price, 2) }}</span>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="text-muted fs-12">Available: {{ $plant->quantity }}</span>
                                            <div class="btn-group">
                                                <a href="{{ route('plants.show', $plant->id) }}" class="btn btn-secondary">
                                                    <i class="fe fe-eye me-1"></i>View Details e
                                                </a>
                                                <button class="btn btn-info quick-add-to-cart" 
                                                        data-plant-id="{{ $plant->id }}"
                                                        {{ $plant->quantity <= 0 ? 'disabled' : '' }}>
                                                    <i class="fe fe-shopping-cart"></i>
                                                </button>
                                                <button class="btn btn-primary add-to-cart-one" 
                                                        data-plant-id="{{ $plant->id }}"
                                                        {{ $plant->quantity <= 0 ? 'disabled' : '' }}>
                                                    Cart
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $plants->links('vendor.pagination.custom') }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.product-grid {
    font-family: 'Poppins', sans-serif;
    text-align: center;
}
.product-grid .product-image {
    overflow: hidden;
    position: relative;
    border-radius: 7px 7px 0 0;
}
.product-grid .product-image a.image { display: block; }
.product-grid .product-image img {
    width: 100%;
    height: 250px;
    object-fit: cover;
}
.product-content {
    padding: 15px;
    background: #fff;
    display: flex;
    flex-direction: column;
}
.product-content .d-flex {
    flex-direction: column;
}
.product-grid .price {
    color: #000;
    font-size: 17px;
    font-weight: 700;
    margin: 0 0 10px;
}
.btn-group {
    display: flex;
    gap: 5px;
    margin-top: 10px;
    width: 100%;
}

.btn-group .btn {
    flex: 1;
    padding: 8px;
    font-size: 14px;
    white-space: nowrap;
}

.btn-group .quick-add-to-cart {
    width: 46px;
    padding: 8px;
    flex: 0 0 auto;
}

.btn-group .btn:not(.quick-add-to-cart) {
    flex: 1;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize Pusher
    const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
        cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}'
    });

    // Add to cart functionality
    $('.add-to-cart').click(function(e) {
        e.preventDefault();
        const button = $(this);
        const plantId = button.data('plant-id');

        button.prop('disabled', true).html('<i class="fe fe-loader"></i> Adding...');

        $.ajax({
            url: "{{ route('cart.add', ':id') }}".replace(':id', plantId),
            method: 'POST',
            data: {
                quantity: 1,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    notif({
                        type: 'success',
                        msg: response.message || 'Added to cart successfully',
                        position: 'right',
                        fade: true
                    });
                    updateCartCount(response.cart_count);
                } else {
                    notif({
                        type: 'error',
                        msg: response.message || 'Failed to add to cart',
                        position: 'right',
                        fade: true
                    });
                }
            },
            error: function(xhr) {
                notif({
                    type: 'error',
                    msg: xhr.responseJSON?.message || 'Error adding to cart',
                    position: 'right',
                    fade: true
                });
            },
            complete: function() {
                button.prop('disabled', false).html('<i class="fe fe-shopping-cart me-2"></i>Add to Cart');
            }
        });
    });

    // Price range handler
    $('#priceRange').on('input', function() {
        $('#priceValue').text('₹' + $(this).val());
        // Add debounce to avoid too many requests
        clearTimeout(window.priceTimeout);
        window.priceTimeout = setTimeout(() => {
            let params = new URLSearchParams(window.location.search);
            params.set('max_price', $(this).val());
            window.location.href = "{{ route('plants.catalog') }}?" + params.toString();
        }, 500);
    });

    // Category filter handler
    $('input[name="category"], input[name="care_level"]').change(function() {
        let params = new URLSearchParams(window.location.search);
        params.set($(this).attr('name'), $(this).val());
        window.location.href = "{{ route('plants.catalog') }}?" + params.toString();
    });

    // Cart update notification
    const channel = pusher.subscribe('cart-channel');
    channel.bind('cart-updated', function(data) {
        notif({
            type: 'info',
            msg: 'Cart has been updated',
            position: 'right',
            fade: true
        });
        updateCartCount(data.count);
    });

    // Quick Add to cart functionality
    $('.quick-add-to-cart').click(function(e) {
        e.preventDefault();
        const button = $(this);
        const plantId = button.data('plant-id');

        button.prop('disabled', true).html('<i class="fe fe-loader"></i>');

        $.ajax({
            url: "{{ route('cart.add', ':id') }}".replace(':id', plantId),
            method: 'POST',
            data: {
                quantity: 1,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    notif({
                        type: 'success',
                        msg: response.message || 'Added to cart successfully',
                        position: 'right',
                        fade: true
                    });
                    updateCartCount(response.cart_count);
                } else {
                    notif({
                        type: 'error',
                        msg: response.message || 'Failed to add to cart',
                        position: 'right',
                        fade: true
                    });
                }
            },
            error: function(xhr) {
                notif({
                    type: 'error',
                    msg: xhr.responseJSON?.message || 'Error adding to cart',
                    position: 'right',
                    fade: true
                });
            },
            complete: function() {
                button.prop('disabled', false).html('<i class="fe fe-shopping-cart"></i>');
            }
        });
    });

    // New Cart button functionality
    $('.add-to-cart-one').click(function(e) {
        e.preventDefault();
        const button = $(this);
        const plantId = button.data('plant-id');

        button.prop('disabled', true).html('<i class="fe fe-loader"></i>');

        $.ajax({
            url: "{{ route('cart.add', ':id') }}".replace(':id', plantId),
            method: 'POST',
            data: {
                quantity: 1,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    notif({
                        type: 'success',
                        msg: response.message || 'Added to cart successfully',
                        position: 'right',
                        fade: true
                    });
                    updateCartCount(response.cart_count);
                } else {
                    notif({
                        type: 'error',
                        msg: response.message || 'Failed to add to cart',
                        position: 'right',
                        fade: true
                    });
                }
            },
            error: function(xhr) {
                notif({
                    type: 'error',
                    msg: xhr.responseJSON?.message || 'Error adding to cart',
                    position: 'right',
                    fade: true
                });
            },
            complete: function() {
                button.prop('disabled', false).html('Cart');
            }
        });
    });

    // Price range slider
    const minPriceInput = $('#minPrice');
    const maxPriceInput = $('#maxPrice');
    const minPriceValue = $('#minPriceValue');
    const maxPriceValue = $('#maxPriceValue');

    function updatePriceRange() {
        minPriceValue.text(minPriceInput.val());
        maxPriceValue.text(maxPriceInput.val());
    }

    minPriceInput.on('input', updatePriceRange);
    maxPriceInput.on('input', updatePriceRange);

    // Dynamic filtering
    let filterTimeout;
    $('.filter-control').on('change', function() {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(function() {
            $('#filter-form').submit();
        }, 500);
    });

    // Sort functionality
    $('#sort-select').on('change', function() {
        $('#filter-form').submit();
    });
});
</script>
@endpush
@endsection 