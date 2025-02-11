@extends('layouts.main')

@section('title', 'Plant Reviews')

@section('maincontent')
<div class="main-container container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Plant Reviews</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Reviews</li>
            </ol>
        </div>
    </div>

    <!-- Row -->
    <div class="row">
        <div class="col-xl-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">All Reviews</h3>
                </div>
                <div class="card-body">
                    @if($reviews->isEmpty())
                        <div class="text-center p-4">
                            <i class="fe fe-star fs-50 text-muted"></i>
                            <h5 class="mt-4">No Reviews Yet</h5>
                            <p class="text-muted">Your plants haven't received any reviews yet.</p>
                        </div>
                    @else
                        @foreach($reviews as $review)
                            <div class="review-item border-bottom pb-4 mb-4">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5 class="mb-1">{{ $review->plant->name }}</h5>
                                        <p class="text-muted small mb-2">By: {{ $review->user->name }}</p>
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
                                    <div class="review-images mb-3">
                                        @foreach($review->images as $image)
                                            <img src="{{ Storage::url($image->path) }}" alt="Review Image" class="review-img me-2 rounded">
                                        @endforeach
                                    </div>
                                @endif

                                @if($review->seller_reply)
                                    <div class="seller-reply bg-light p-3 rounded mb-3">
                                        <p class="mb-1"><strong>Your Response:</strong></p>
                                        <p class="mb-0">{{ $review->seller_reply }}</p>
                                        <small class="text-muted">{{ $review->replied_at->diffForHumans() }}</small>
                                    </div>
                                @else
                                    <button class="btn btn-primary btn-sm" onclick="showReplyForm('{{ $review->id }}')">
                                        <i class="fe fe-message-circle me-1"></i> Reply
                                    </button>
                                    <form id="replyForm{{ $review->id }}" style="display: none;" class="mt-3" 
                                          action="{{ route('seller.reviews.reply', $review) }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <textarea class="form-control" name="reply" rows="3" required 
                                                      placeholder="Write your response..."></textarea>
                                        </div>
                                        <div class="mt-2">
                                            <button type="submit" class="btn btn-primary btn-sm">Submit Reply</button>
                                            <button type="button" class="btn btn-light btn-sm" 
                                                    onclick="hideReplyForm('{{ $review->id }}')">Cancel</button>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        @endforeach

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

@push('scripts')
<script>
    function showReplyForm(reviewId) {
        document.getElementById('replyForm' + reviewId).style.display = 'block';
    }

    function hideReplyForm(reviewId) {
        document.getElementById('replyForm' + reviewId).style.display = 'none';
    }
</script>
@endpush 