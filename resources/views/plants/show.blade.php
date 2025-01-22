@extends('layouts.main')

@section('title', 'Plant Details')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.css">
@endpush

@section('maincontent')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            @if($plant->image)
                <img src="{{ asset('storage/' . $plant->image) }}" 
                     class="img-fluid " 
                     alt="{{ $plant->name }}"
                     style="max-height: 400px; width: 100%; object-fit: cover;">
            @endif
        </div>
        <div class="col-md-6">
            <h2>{{ $plant->name }}</h2>
            <div class="mb-3">
                <span class="badge bg-success">{{ $plant->category }}</span>
                @if($plant->quantity > 0)
                    <span class="badge bg-info">In Stock</span>
                @else
                    <span class="badge bg-danger">Out of Stock</span>
                @endif
            </div>
            <h3 class="text-primary mb-3">₹{{ number_format($plant->price, 2) }}</h3>
            <p class="text-muted">{{ $plant->description }}</p>
            
            <!-- Stock and Add to Cart Section -->
            <div class="mt-4">
                <p class="mb-2">Available Quantity: <span class="available-quantity">{{ $plant->quantity }}</span></p>
                @if($plant->quantity > 0)
                    <div class="d-flex align-items-center gap-3">
                        <input type="number" 
                               class="form-control" 
                               id="quantity" 
                               value="1" 
                               min="1" 
                               max="{{ $plant->quantity }}" 
                               style="width: 100px;">
                        <button class="btn btn-primary add-to-cart" 
                                data-plant-id="{{ $plant->id }}">
                            Add to Cart
                        </button>
                    </div>
                @else
                    <button class="btn btn-secondary" disabled>Out of Stock</button>
                @endif
            </div>

            <!-- Seller Info -->
            <div class="mt-4">
                <h5>Seller Information</h5>
                <p>{{ $plant->seller->name }}</p>
            </div>
        </div>
    </div>

    <!-- After the existing plant details section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Detailed Care Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Growing Conditions</h5>
                            <ul class="list-unstyled">
                                <li><strong>Soil Type:</strong> {{ App\Models\Plant::SOIL_TYPES[$plant->soil_type] ?? 'Not specified' }}</li>
                                <li><strong>Temperature:</strong> {{ $plant->temperature_range ?? 'Not specified' }}</li>
                                <li><strong>Humidity:</strong> {{ $plant->humidity_requirements ?? 'Not specified' }}</li>
                                <li><strong>Light Needs:</strong> {{ App\Models\Plant::LIGHT_NEEDS[$plant->light_needs] ?? 'Not specified' }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5>Growth Information</h5>
                            <ul class="list-unstyled">
                                <li><strong>Mature Height:</strong> {{ $plant->mature_height ?? 'Not specified' }}</li>
                                <li><strong>Growth Rate:</strong> {{ App\Models\Plant::GROWTH_RATES[$plant->growth_rate] ?? 'Not specified' }}</li>
                                <li><strong>Blooming Season:</strong> {{ $plant->blooming_season ?? 'Not specified' }}</li>
                                <li><strong>Propagation:</strong> {{ $plant->propagation_method ?? 'Not specified' }}</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5>Care Requirements</h5>
                            <ul class="list-unstyled">
                                <li><strong>Maintenance Level:</strong> {{ App\Models\Plant::MAINTENANCE_LEVELS[$plant->maintenance_level] ?? 'Not specified' }}</li>
                                <li><strong>Water Needs:</strong> {{ App\Models\Plant::WATER_NEEDS[$plant->water_needs] ?? 'Not specified' }}</li>
                                <li><strong>Fertilizer Needs:</strong> {{ $plant->fertilizer_needs ?? 'Not specified' }}</li>
                                <li><strong>Pet Friendly:</strong> <span class="badge {{ $plant->pet_friendly ? 'bg-success' : 'bg-danger' }}">
                                    {{ $plant->pet_friendly ? 'Yes' : 'No' }}</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="card mt-4">
        <div class="card-header">
            <h3>Reviews</h3>
        </div>
        <div class="card-body">
            @if($plant->reviews->count() > 0)
                @foreach($plant->reviews as $review)
                    <div class="review mb-3">
                        <div class="d-flex justify-content-between">
                            <h5>{{ $review->user->name }}</h5>
                            <div class="rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                            </div>
                        </div>
                        <p class="text-muted">{{ $review->comment }}</p>
                        <small class="text-muted">Posted {{ $review->created_at->diffForHumans() }}</small>
                    </div>
                    @if(!$loop->last)
                        <hr>
                    @endif
                @endforeach
            @else
                <p class="text-muted">No reviews yet.</p>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.js"></script>
<script>
$(document).ready(function() {
    // Replace the notif function with Noty
    function showNotification(type, message) {
        new Noty({
            type: type,
            text: message,
            timeout: 3000,
            progressBar: true,
            theme: 'bootstrap-v4'
        }).show();
    }

    function updateCartCount(count) {
        $('.cart-counter').text(count);
    }

    function resetButton(button, enabled = true) {
        button.prop('disabled', !enabled)
              .html(enabled ? 'Add to Cart' : 'Out of Stock');
        
        if (!enabled) {
            button.removeClass('btn-primary').addClass('btn-secondary');
        } else {
            button.removeClass('btn-secondary').addClass('btn-primary');
        }
    }

    $('.add-to-cart').click(function(e) {
        console.clear();    
        e.preventDefault();
        const button = $(this);
        const plantId = button.data('plant-id');
        const quantity = parseInt($('#quantity').val());
        const currentStock = parseInt($('.available-quantity').text());

        // Add debugging logs
        console.log('Current stock before adding to cart:', currentStock);
        console.log('Quantity being added:', quantity);

        if (quantity > currentStock) {
            showNotification('warning', 'Cannot add more items than available in stock');
            return;
        }

        // Show loading state
        button.prop('disabled', true)
              .html('Adding...');

        $.ajax({
            url: "{{ route('cart.add', '') }}/" + plantId,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: {
                quantity: quantity
            },
            success: function(response) {
                if (response.success) {
                    const newQuantity = response.remaining_quantity;
                    // Add debugging log
                    console.log('New quantity after adding to cart:', newQuantity);
                    
                    $('.available-quantity').text(newQuantity);
                    updateCartCount(response.cart_count);
                    $('#quantity').attr('max', newQuantity);
                    
                    if (newQuantity <= 0) {
                        $('.stock-status')
                            .removeClass('bg-info')
                            .addClass('bg-danger')
                            .text('Out of Stock');
                        resetButton(button, false);
                    } else {
                        resetButton(button, true);
                    }

                    showNotification('success', response.message);
                    $('#quantity').val(1);
                } else {
                    showNotification('error', response.message || 'Error adding to cart');
                    resetButton(button, true);
                }
            },
            error: function(xhr) {
                const errorMessage = xhr.responseJSON?.message || 'Error adding to cart';
                showNotification('error', errorMessage);
                resetButton(button, true);
            },
            complete: function() {
                if (button.html() === 'Adding...') {
                    resetButton(button, true);
                }
            }
        });
    });

    // Quantity input validation
    $('#quantity').on('input', function() {
        const max = parseInt($('.available-quantity').text());
        let val = $(this).val();
        
        // Remove leading zeros
        if (val.length > 1 && val[0] === '0') {
            val = val.replace(/^0+/, '');
            $(this).val(val);
        }
        
        // Convert to integer, default to 1 if 0 or empty
        val = parseInt(val) || 0;
        
        if (val === 0) {
            $(this).val(1);
        } else if (val > max) {
            $(this).val(max);
            showNotification('warning', 'Maximum available quantity is ' + max);
        }
    });

    // Handle focus to clear the input
    $('#quantity').on('focus', function() {
        if ($(this).val() === '1') {
            $(this).val('');
        }
    });

    // Handle blur to ensure minimum value
    $('#quantity').on('blur', function() {
        if (!$(this).val()) {
            $(this).val(1);
        }
    });
});
</script>
@endpush
@endsection 