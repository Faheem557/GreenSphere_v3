@extends('layouts.main')

@section('title', 'Plant Details')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.css">
<style>
    .rating-stars {
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .rating-star {
        color: #e4e5e9;
        font-size: 32px;
        transition: color 0.2s ease;
        cursor: pointer;
    }
    .rating-star:hover {
        transform: scale(1.1);
    }
    .rating-star.active {
        color: #ffd700;  /* Golden yellow for stars */
    }
    .rating-stars:hover .rating-star:hover,
    .rating-stars:hover .rating-star:hover ~ .rating-star,
    .rating-stars:not(:hover) .rating-star.active ~ .rating-star {
        color: #ffd700;
    }
    .review-img {
        width: 100px;
        height: 100px;
        object-fit: cover;
    }
    .rating-stars {
        display: flex;
        flex-direction: row-reverse;
        position: relative;
    }
    .rating-input {
        display: none;
    }
    .rating-label {
        cursor: pointer;
        padding: 0 0.1em;
        color: #ddd;
        transition: color 0.2s;
    }
    .rating-label:hover,
    .rating-label:hover ~ .rating-label,
    .rating-input:checked ~ .rating-label {
        color: #ffc107;
    }
    .rating-feedback {
        text-align: center;
        margin-top: 10px;
    }
    .rating-text {
        font-size: 18px;
        font-weight: 500;
        padding: 5px 15px;
        border-radius: 4px;
    }
    .rating-text.excellent {
        color: #2E7D32;  /* Material Design green 800 */
        background-color: #E8F5E9;  /* Material Design green 50 */
    }
    .rating-text.very-good {
        color: #00695C;  /* Material Design teal 800 */
        background-color: #E0F2F1;  /* Material Design teal 50 */
    }
    .rating-text.good {
        color: #0277BD;  /* Material Design light blue 800 */
        background-color: #E1F5FE;  /* Material Design light blue 50 */
    }
    .rating-text.fair {
        color: #FF8F00;  /* Material Design amber 800 */
        background-color: #FFF8E1;  /* Material Design amber 50 */
    }
    .rating-text.poor {
        color: #C62828;  /* Material Design red 800 */
        background-color: #FFEBEE;  /* Material Design red 50 */
    }
    #image-preview img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 4px;
    }
    .image-preview-item {
        position: relative;
        margin: 5px;
    }
    .image-preview-remove {
        position: absolute;
        top: -6px;
        right: -6px;
        background: #dc3545;
        color: white;
        border-radius: 50%;
        width: 16px;
        height: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        border: 1px solid #fff;
    }
    .image-preview-remove:hover {
        background: #bd2130;
    }
    #image-preview {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 10px;
    }
</style>
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
            <h3 class="text-primary mb-3">PKR-{{ number_format($plant->price, 2) }}</h3>
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

    @auth
    @role('user')
    <!-- Review Form -->
    <div class="row mt-4">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Write a Review</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('reviews.store', $plant) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Rating Section -->
                        <div class="form-group mb-4">
                            <label class="form-label mb-3">Your Rating <span class="text-danger">*</span></label>
                            <div class="d-flex flex-column align-items-center">
                                <div class="rating-stars">
                                    <input type="hidden" name="rating" id="selected-rating" required>
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star-fill rating-star" data-rating="{{ $i }}"></i>
                                    @endfor
                                </div>
                                <div class="rating-feedback">
                                    <span class="selected-rating text-muted">Click to rate</span>
                                </div>
                            </div>
                            @error('rating')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Review Text Section -->
                        <div class="form-group mb-4">
                            <label class="form-label">Your Review <span class="text-danger">*</span></label>
                            <textarea name="comment" 
                                      rows="4" 
                                      class="form-control @error('comment') is-invalid @enderror" 
                                      placeholder="Share your experience with this plant..."
                                      required>{{ old('comment') }}</textarea>
                            <div class="form-text text-muted">
                                Minimum 10 characters, maximum 500 characters
                            </div>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Image Upload Section -->
                        <div class="form-group mb-4">
                            <label class="form-label">
                                Add Photos
                                <span class="badge bg-info ms-2">Optional</span>
                            </label>
                            <div class="input-group">
                                <input type="file" 
                                       name="images[]" 
                                       class="form-control @error('images.*') is-invalid @enderror" 
                                       multiple 
                                       accept="image/*"
                                       id="review-images">
                                <label class="input-group-text" for="review-images">
                                    <i class="fe fe-image"></i>
                                </label>
                            </div>
                            <div class="form-text text-muted">
                                <i class="fe fe-info me-1"></i>
                                You can upload up to 5 images (max 2MB each)
                            </div>
                            <div id="image-preview" class="mt-2 d-flex flex-wrap gap-2"></div>
                            @error('images.*')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fe fe-send me-2"></i>Submit Review
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endrole
    @endauth

    <!-- Existing Reviews Section -->
    <div class="card mt-4">
        <div class="card-header">
            <h3 class="card-title">Customer Reviews</h3>
        </div>
        <div class="card-body">
            @forelse($plant->reviews as $review)
                <div class="review-item {{ !$loop->last ? 'border-bottom mb-4 pb-4' : '' }}">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="mb-1">{{ $review->user->name }}</h5>
                            <div class="rating-stars mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fe fe-star {{ $i <= $review->rating ? 'active text-warning' : 'text-muted' }}"></i>
                                @endfor
                            </div>
                        </div>
                        <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                    </div>
                    
                    <p class="mb-3">{{ $review->comment }}</p>

                    @if($review->images->count() > 0)
                        <div class="review-images mb-3">
                            @foreach($review->images as $image)
                                <img src="{{ Storage::url($image->path) }}" alt="Review Image" 
                                     class="review-img me-2 rounded">
                            @endforeach
                        </div>
                    @endif

                    @if($review->seller_reply)
                        <div class="seller-reply bg-light p-3 rounded">
                            <p class="mb-1"><strong>Seller Response:</strong></p>
                            <p class="mb-0">{{ $review->seller_reply }}</p>
                            <small class="text-muted">{{ $review->replied_at->diffForHumans() }}</small>
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center p-4">
                    <i class="fe fe-star fs-50 text-muted"></i>
                    <h5 class="mt-4">No Reviews Yet</h5>
                    <p class="text-muted">Be the first to review this plant!</p>
                </div>
            @endforelse
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ratingStars = document.querySelectorAll('.rating-star');
    const ratingInput = document.getElementById('selected-rating');
    const ratingFeedback = document.querySelector('.selected-rating');
    const ratingTexts = {
        5: { text: 'Excellent!', class: 'text-success' },
        4: { text: 'Very Good', class: 'text-primary' },
        3: { text: 'Good', class: 'text-info' },
        2: { text: 'Fair', class: 'text-warning' },
        1: { text: 'Poor', class: 'text-danger' }
    };

    function updateStars(rating) {
        ratingStars.forEach(star => {
            const starRating = parseInt(star.dataset.rating);
            if (starRating <= rating) {
                star.classList.add('active');
            } else {
                star.classList.remove('active');
            }
        });
    }

    ratingStars.forEach(star => {
        star.addEventListener('mouseover', function() {
            const rating = this.dataset.rating;
            updateStars(rating);
        });

        star.addEventListener('click', function() {
            const rating = this.dataset.rating;
            const ratingInfo = ratingTexts[rating];
            ratingInput.value = rating;
            ratingFeedback.innerHTML = `<span class="${ratingInfo.class} fw-bold">${ratingInfo.text}</span>`;
            updateStars(rating);
        });
    });

    const ratingContainer = document.querySelector('.rating-stars');
    ratingContainer.addEventListener('mouseleave', function() {
        const currentRating = ratingInput.value;
        if (currentRating) {
            updateStars(currentRating);
        } else {
            updateStars(0);
            ratingFeedback.innerHTML = '<span class="text-muted">Click to rate</span>';
        }
    });

    // Image preview
    const imageInput = document.getElementById('review-images');
    const previewContainer = document.getElementById('image-preview');

    imageInput.addEventListener('change', function() {
        previewContainer.innerHTML = '';
        const files = Array.from(this.files);

        files.forEach(file => {
            if (files.length > 5) {
                alert('You can only upload up to 5 images');
                this.value = '';
                previewContainer.innerHTML = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'image-preview-item';
                div.innerHTML = `
                    <img src="${e.target.result}" class="shadow-sm">
                    <span class="image-preview-remove">
                        ×
                    </span>
                `;
                previewContainer.appendChild(div);

                // Remove image functionality
                div.querySelector('.image-preview-remove').addEventListener('click', function() {
                    div.remove();
                    // Reset file input if all previews are removed
                    if (previewContainer.children.length === 0) {
                        imageInput.value = '';
                    }
                });
            };
            reader.readAsDataURL(file);
        });
    });
});
</script>
@endpush
@endsection 