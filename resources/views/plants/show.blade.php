@extends('layouts.main')

@section('title', 'Plant Details')

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
                <p class="mb-2">Available Quantity: {{ $plant->quantity }}</p>
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
<script>
$(document).ready(function() {
    $('.add-to-cart').click(function(e) {
        e.preventDefault();
        const button = $(this);
        const plantId = button.data('plant-id');
        const quantity = $('#quantity').val();

        button.prop('disabled', true);
        toastr.info('Adding to cart...'); // Loading notification

        $.ajax({
            url: "{{ route('cart.add', ':id') }}".replace(':id', plantId),
            method: 'POST',
            data: {
                quantity: quantity,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message || 'Added to cart successfully');
                    updateCartCount(response.cart_count);
                    
                    // Update available quantity
                    const newQuantity = {{ $plant->quantity }} - quantity;
                    $('.available-quantity').text(newQuantity);
                    
                    // Disable button if no more stock
                    if (newQuantity <= 0) {
                        button.prop('disabled', true).text('Out of Stock');
                    }
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

    // Quantity input validation
    $('#quantity').on('input', function() {
        const max = {{ $plant->quantity }};
        const val = parseInt($(this).val());
        if (val > max) {
            $(this).val(max);
            toastr.warning('Maximum available quantity is ' + max);
        }
    });
});
</script>
@endpush
@endsection 