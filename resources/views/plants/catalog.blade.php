@extends('layouts.main')

@section('title', 'Plant Catalog')

@section('maincontent')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Plant Catalog</p>
                                <h5 class="font-weight-bolder">Browse All Plants</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                <i class="fas fa-leaf text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('plants.catalog') }}" method="GET" class="row g-3 align-items-center">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-transparent">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" 
                                       name="search" 
                                       placeholder="Search plants..." 
                                       value="{{ request('search') }}"
                                       class="form-control">
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <select name="sort" class="form-select">
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price (Low to High)</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price (High to Low)</option>
                            </select>
                        </div>
                        
                        <div class="col-md-2">
                            <button type="submit" class="btn bg-gradient-primary w-100">
                                <i class="fas fa-filter me-2"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Plants Grid -->
    <div class="row">
        @forelse ($plants as $plant)
            <div class="col-sm-6 col-lg-3 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        @if($plant->image)
                            <img src="{{ asset('storage/' . $plant->image) }}" 
                                 class="img-fluid rounded mb-3" 
                                 alt="{{ $plant->name }}"
                                 style="height: 200px; width: 100%; object-fit: cover;">
                        @else
                            <div class="rounded bg-light mb-3 d-flex align-items-center justify-content-center" 
                                 style="height: 200px;">
                                <i class="fas fa-image text-muted" style="font-size: 3rem;"></i>
                            </div>
                        @endif
                        <h5 class="card-title">{{ $plant->name }}</h5>
                        <p class="card-text text-muted">{{ Str::limit($plant->description, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-success">{{ $plant->category ?? 'Uncategorized' }}</span>
                            <span class="text-primary">₹{{ number_format($plant->price, 2) }}</span>
                        </div>
                        <div class="mt-3">
                            <small class="text-muted">Available: {{ $plant->quantity ?? 'N/A' }}</small>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('plants.show', $plant) }}" 
                               class="btn bg-gradient-primary btn-sm">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-leaf mb-3 text-muted" style="font-size: 2rem;"></i>
                        <p class="text-muted mb-0">No plants found.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($plants->hasPages())
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body d-flex justify-content-center">
                        {{ $plants->links() }}
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .card {
        transition: all 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 25px 0 rgba(0,0,0,.1);
    }
</style>
@endpush 