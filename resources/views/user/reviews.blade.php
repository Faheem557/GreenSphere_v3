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