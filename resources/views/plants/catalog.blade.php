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
                        <div class="custom-controls-stacked">
                            <h4 class="mb-3">Categories</h4>
                            @foreach(App\Models\Plant::CATEGORIES as $key => $category)
                                <label class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" name="category" 
                                           value="{{ $key }}" {{ request('category') === $key ? 'checked' : '' }}>
                                    <span class="custom-control-label">{{ $category }}</span>
                                </label>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            <h4 class="mb-3">Price Range</h4>
                            <input type="range" class="form-range" id="priceRange" min="0" max="1000">
                            <div class="d-flex justify-content-between mt-2">
                                <span class="font-weight-bold">₹0</span>
                                <span class="font-weight-bold" id="priceValue">₹500</span>
                                <span class="font-weight-bold">₹1000</span>
                            </div>
                        </div>
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
                                        <div class="price mb-2">
                                            <span class="fs-18 fw-bold">₹{{ number_format($plant->price, 2) }}</span>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="text-muted fs-12">Available: {{ $plant->quantity }}</span>
                                            <button class="btn btn-primary add-to-cart" 
                                                    data-plant-id="{{ $plant->id }}"
                                                    {{ $plant->quantity <= 0 ? 'disabled' : '' }}>
                                                <i class="fe fe-shopping-cart me-2"></i>Add to Cart
                                            </button>
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
}
.product-grid .price {
    color: #000;
    font-size: 17px;
    font-weight: 700;
    margin: 0 0 10px;
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
    });

    // Category filter handler
    $('input[name="category"]').change(function() {
        window.location.href = "{{ route('plants.catalog') }}?category=" + $(this).val();
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
});
</script>
@endpush
@endsection 