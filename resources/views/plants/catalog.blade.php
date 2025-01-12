@extends('layouts.main')

@section('title', 'Plant Catalog')

@section('maincontent')
<div class="container-fluid">
    <!-- Category Filter -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Categories</h5>
                    <div class="list-group">
                        @foreach(App\Models\Plant::CATEGORIES as $key => $category)
                            <a href="{{ route('plants.catalog', ['category' => $key]) }}" 
                               class="list-group-item list-group-item-action {{ request('category') === $key ? 'active' : '' }}">
                                {{ $category }}
                            </a>
                        @endforeach
                    </div>
                    
                    <!-- Price Filter -->
                    <h5 class="mt-4">Price Range</h5>
                    <input type="range" class="form-range" id="priceRange" min="0" max="1000">
                    <div class="d-flex justify-content-between">
                        <span>₹0</span>
                        <span id="priceValue">₹500</span>
                        <span>₹1000</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Plant Grid -->
        <div class="col-md-9">
            <div class="row row-cards">
                @foreach($plants as $plant)
                <div class="col-sm-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">
                            @if($plant->image)
                                <img src="{{ asset('storage/' . $plant->image) }}"
                                     class="rounded mb-3" 
                                     alt="{{ $plant->name }}"
                                     style="max-height: 200px; width: 100%; object-fit: cover;"
                                     onerror="this.onerror=null; this.src='{{ asset('images/placeholder.jpg') }}'; console.clear(); console.log('Image failed to load: {{ $plant->image }}');">
                            @else
                                <div class="text-center p-3">
                                    <p>No image available</p>
                                </div>
                            @endif
                            <h3 class="card-title">{{ $plant->name }}</h3>
                            <div class="text-muted">{{ Str::limit($plant->description, 100) }}</div>
                            <div class="mt-3">
                                <span class="badge bg-success">{{ $plant->category }}</span>
                                @if($plant->quantity > 0)
                                    <span class="badge bg-info">In Stock</span>
                                @else
                                    <span class="badge bg-danger">Out of Stock</span>
                                @endif
                            </div>
                            <div class="mt-3 d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">₹{{ number_format($plant->price, 2) }}</h4>
                                <div>
                                    <small class="text-muted d-block mb-2">Available: {{ $plant->quantity }}</small>
                                    <button class="btn btn-primary add-to-cart" 
                                            data-plant-id="{{ $plant->id }}"
                                            {{ $plant->quantity <= 0 ? 'disabled' : '' }}>
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-4">
                {{ $plants->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Real-time cart updates using Pusher
const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
    cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}'
});

$(document).ready(function() {
    // Add to cart handler
    $('.add-to-cart').click(function(e) {
        e.preventDefault();
        const button = $(this);
        const plantId = button.data('plant-id');

        button.prop('disabled', true);
        toastr.info('Adding to cart...'); // Loading notification

        $.ajax({
            url: "{{ route('cart.add', ':id') }}".replace(':id', plantId),
            method: 'POST',
            data: {
                quantity: 1,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message || 'Added to cart successfully');
                    updateCartCount(response.cart_count);
                } else {
                    toastr.error(response.message || 'Failed to add to cart');
                }
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Error adding to cart');
            },
            complete: function() {
                button.prop('disabled', false);
            }
        });
    });

    // Cart update notification
    const channel = pusher.subscribe('cart-channel');
    channel.bind('cart-updated', function(data) {
        toastr.info('Cart has been updated');
        updateCartCount(data.count);
    });
});

// Price range filter
$('#priceRange').on('input', function() {
    $('#priceValue').text('₹' + $(this).val());
});
</script>
@endpush
@endsection 