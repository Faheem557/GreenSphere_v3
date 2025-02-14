@extends('layouts.main')

@section('content')
<div class="app-content main-content mt-0">
    <div class="side-app">
        <!-- CONTAINER -->
        <div class="main-container container-fluid">
            <!-- PAGE-HEADER -->
            <div class="page-header">
                <div>
                    <h1 class="page-title">My Wishlist</h1>
                </div>
            </div>
            <!-- PAGE-HEADER END -->

            <!-- ROW-1 -->
            <div class="row">
                <div class="col-12">
                    @if($wishlistedPlants->isEmpty())
                        <div class="card custom-card">
                            <div class="card-body text-center p-5">
                                <div class="empty-state">
                                    <!-- Empty State Icon -->
                                    <span class="empty-state-icon bg-light">
                                        <i class="fe fe-heart fs-40 text-muted"></i>
                                    </span>
                                    
                                    <!-- Empty State Title -->
                                    <h3 class="mt-4 mb-2">Your Wishlist is Empty</h3>
                                    
                                    <!-- Empty State Description -->
                                    <p class="text-muted mb-4">
                                        Looks like you haven't added any plants to your wishlist yet. 
                                        Start exploring our collection to find your favorite plants!
                                    </p>
                                    
                                    <!-- Call to Action Button -->
                                    <a href="{{ route('plants.catalog') }}" class="btn btn-primary">
                                        <i class="fe fe-search me-1"></i> Browse Plant Catalog
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            @foreach($wishlistedPlants as $plant)
                                <div class="col-sm-6 col-md-6 col-lg-4 col-xl-4">
                                    <div class="card">
                                        <div class="card-body">
                                            @if($plant->image)
                                                <div class="pro-img-box">
                                                    <img class="w-100" src="{{ Storage::url($plant->image) }}" alt="{{ $plant->name }}">
                                                </div>
                                            @endif
                                            <div class="text-center pt-3">
                                                <h3 class="h6 mb-2 mt-4 font-weight-bold text-uppercase">{{ $plant->name }}</h3>
                                                <span class="tx-15 ms-auto">
                                                    <i class="fe fe-tag fs-16 text-muted"></i>
                                                    ${{ number_format($plant->price, 2) }}
                                                </span>
                                                <p class="text-muted">{{ Str::limit($plant->description, 100) }}</p>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <a href="{{ route('plants.show', $plant) }}" class="btn btn-primary">
                                                    <i class="fe fe-eye"></i> View Details
                                                </a>
                                                <form action="{{ route('user.wishlist.remove', $plant) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="fe fe-trash"></i> Remove
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-4">
                            {{ $wishlistedPlants->links() }}
                        </div>
                    @endif
                </div>
            </div>
            <!-- ROW-1 END -->
        </div>
        <!-- CONTAINER END -->
    </div>
</div>
@endsection

@push('styles')
<style>
    .empty-state {
        padding: 2rem;
    }
    .empty-state-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background-color: rgba(var(--primary-rgb), 0.1);
    }
    .empty-state-icon i {
        color: var(--primary);
    }
    .empty-state h3 {
        color: #1a1630;
        font-weight: 600;
    }
    .empty-state p {
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
    }
</style>
@endpush 