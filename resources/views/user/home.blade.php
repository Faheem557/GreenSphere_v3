@extends('layouts.main')

@section('title', 'User Dashboard')

@section('maincontent')
<div class="container-fluid">
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Plants</p>
                                <h5 class="font-weight-bolder">{{ $stats['total_plants'] }}</h5>
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
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Available Plants</p>
                                <h5 class="font-weight-bolder">{{ $stats['available_plants'] }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                <i class="fas fa-check text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Categories</p>
                                <h5 class="font-weight-bolder">{{ $stats['categories'] }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-info shadow-info text-center rounded-circle">
                                <i class="fas fa-list text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Cart Items</p>
                                <h5 class="font-weight-bolder">{{ $stats['cart_count'] }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                                <i class="fas fa-shopping-cart text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest Plants -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Latest Available Plants</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="row p-3">
                        @foreach($stats['latest_plants'] as $plant)
                        <div class="col-sm-6 col-lg-3 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    @if($plant->image)
                                        <img src="{{ asset('storage/' . $plant->image) }}" 
                                             class="img-fluid rounded mb-3" 
                                             alt="{{ $plant->name }}"
                                             style="height: 200px; width: 100%; object-fit: cover;">
                                    @endif
                                    <h5 class="card-title">{{ $plant->name }}</h5>
                                    <p class="card-text text-muted">{{ Str::limit($plant->description, 100) }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-success">{{ $plant->category }}</span>
                                        <span class="text-primary">PKR-{{ number_format($plant->price, 2) }}</span>
                                    </div>
                                    <div class="mt-3">
                                        <small class="text-muted">Available: {{ $plant->quantity }}</small>
                                    </div>
                                    <div class="mt-3">
                                        <a href="{{ route('plants.show', $plant) }}" 
                                           class="btn btn-primary btn-sm">View Details</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection