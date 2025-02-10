@extends('layouts.main')

@section('title', 'My Reviews')

@section('maincontent')
<div class="main-container container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">My Reviews</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">My Reviews</li>
            </ol>
        </div>
    </div>

    <!-- Row -->
    <div class="row">
        <div class="col-xl-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    @if($reviews->isEmpty())
                        <div class="text-center p-4">
                            <i class="fe fe-star fs-50 text-muted"></i>
                            <h5 class="mt-4">No Reviews Yet</h5>
                            <p class="text-muted">You haven't written any reviews yet.</p>
                        </div>
                    @else
                        @foreach($reviews as $review)
                            <div class="review-item border-bottom pb-4 mb-4">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5 class="mb-1">{{ $review->plant->name }}</h5>
                                        <p class="text-muted small mb-2">Seller: {{ $review->plant->seller->name }}</p>
                                    </div>
                                    <div class="text-end">
                                        <div class="rating-stars mb-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fe fe-star {{ $i <= $review->rating ? 'active text-warning' : 'text-muted' }}"></i>
                                            @endfor
                                        </div>
                                        <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                                <p class="mb-3">{{ $review->comment }}</p>
                                @if($review->images->count() > 0)
                                    <div class="review-images">
                                        @foreach($review->images as $image)
                                            <img src="{{ Storage::url($image->path) }}" alt="Review Image" class="review-img me-2 rounded">
                                        @endforeach
                                    </div>
                                @endif
                                @if($review->seller_reply)
                                    <div class="seller-reply mt-3 bg-light p-3 rounded">
                                        <p class="mb-1"><strong>Seller Response:</strong></p>
                                        <p class="mb-0">{{ $review->seller_reply }}</p>
                                        <small class="text-muted">{{ $review->replied_at->diffForHumans() }}</small>
                                    </div>
                                @endif
                            </div>
                        @endforeach

                        <!-- Check if the user has purchased the plant -->
                        @if($user->purchases->contains($plant->id))
                            <div class="add-review mt-4">
                                <h5>Add Your Review</h5>
                                <form action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="plant_id" value="{{ $plant->id }}">
                                    
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
                        @endif

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $reviews->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Rating functionality
    const ratingStars = document.querySelectorAll('.rating-star');
    const ratingInput = document.getElementById('selected-rating');
    const ratingFeedback = document.querySelector('.selected-rating');

    ratingStars.forEach(star => {
        star.addEventListener('mouseover', function() {
            const rating = this.dataset.rating;
            updateStars(rating);
        });

        star.addEventListener('click', function() {
            const rating = this.dataset.rating;
            ratingInput.value = rating;
            ratingFeedback.innerHTML = `Rating: ${rating}`;
            updateStars(rating);
        });
    });

    function updateStars(rating) {
        ratingStars.forEach(star => {
            const starRating = parseInt(star.dataset.rating);
            star.classList.toggle('active', starRating <= rating);
        });
    }

    // Image preview functionality
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
                    <span class="image-preview-remove">×</span>
                `;
                previewContainer.appendChild(div);

                // Remove image functionality
                div.querySelector('.image-preview-remove').addEventListener('click', function() {
                    div.remove();
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

@push('styles')
<style>
    .review-img {
        width: 100px;
        height: 100px;
        object-fit: cover;
    }
    .rating-stars .fe-star {
        font-size: 16px;
    }
    .rating-stars .fe-star.active {
        color: #ffc107;
    }
</style>
@endpush 