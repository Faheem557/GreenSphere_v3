@extends('layouts.main')

@section('title', 'Plant Catalog')

@section('maincontent')
<div class="container-fluid">
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <h1 class="page-title">Plant Catalog</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Plant Catalog</li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- ROW-1 OPEN -->
    <div class="row row-cards">
        <!-- Categories Sidebar -->
        <div class="col-xl-3 col-lg-4">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Categories</div>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($categories ?? [] as $category)
                            <li class="list-group-item border-0 p-0">
                                <a href="{{ route('plants.catalog', ['category' => $category->id]) }}">
                                    <i class="fe fe-chevron-right"></i> {{ $category->name }}
                                </a>
                                <span class="product-label">{{ $category->plants_count }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Price Filter -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Price Range</div>
                </div>
                <div class="card-body">
                    <form id="priceFilterForm" action="{{ route('plants.catalog') }}" method="GET">
                        <!-- Preserve existing search and sort parameters -->
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        @if(request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif

                        <!-- Radio buttons for quick selection -->
                        <label class="custom-control custom-radio mb-0 mt-1">
                            <input type="radio" class="custom-control-input price-range" name="price_range" 
                                   value="0-500" {{ request('price_range') == '0-500' ? 'checked' : '' }}>
                            <span class="custom-control-label">Upto PKR-500</span>
                        </label>
                        <label class="custom-control custom-radio mb-0 mt-1">
                            <input type="radio" class="custom-control-input price-range" name="price_range" 
                                   value="500-1000" {{ request('price_range') == '500-1000' ? 'checked' : '' }}>
                            <span class="custom-control-label">PKR-500 - PKR-1000</span>
                        </label>
                        <label class="custom-control custom-radio mb-0 mt-1">
                            <input type="radio" class="custom-control-input price-range" name="price_range" 
                                   value="1000-1500" {{ request('price_range') == '1000-1500' ? 'checked' : '' }}>
                            <span class="custom-control-label">PKR-1000 - PKR-1500</span>
                        </label>
                        <label class="custom-control custom-radio mb-0 mt-1">
                            <input type="radio" class="custom-control-input price-range" name="price_range" 
                                   value="2000-999999" {{ request('price_range') == '2000-999999' ? 'checked' : '' }}>
                            <span class="custom-control-label">Over PKR-2000</span>
                        </label>

                        <!-- Price range slider -->
                        <div class="price-slider mt-4">
                            <div class="range-slider">
                                <div id="slider-range" class="ui-slider ui-slider-horizontal"></div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-6">
                                    <input type="number" 
                                           id="min-price" 
                                           name="min_price" 
                                           class="form-control" 
                                           placeholder="Min" 
                                           value="{{ request('min_price', 0) }}">
                                </div>
                                <div class="col-6">
                                    <input type="number" 
                                           id="max-price" 
                                           name="max_price" 
                                           class="form-control" 
                                           placeholder="Max" 
                                           value="{{ request('max_price', 5000) }}">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm w-100 mt-2">Apply Range</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-9 col-lg-8">
            <!-- Search and Sort Section -->
            <div class="card p-0">
                <div class="card-body p-4">
                    <form action="{{ route('plants.catalog') }}" method="GET" class="row g-3">
                        <div class="col-xl-5 col-lg-8 col-md-8 col-sm-8">
                            <div class="input-group d-flex w-100 float-start">
                                <input type="text" 
                                       name="search" 
                                       class="form-control border-end-0 my-2" 
                                       placeholder="Search plants..." 
                                       value="{{ request('search') }}">
                                <button class="btn input-group-text bg-transparent border-start-0 text-muted my-2">
                                    <i class="fe fe-search text-muted"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
                            <ul class="nav item2-gl-menu float-end my-2">
                                <li class="border-end">
                                    <a href="#tab-11" class="show active" data-bs-toggle="tab" title="Grid view">
                                        <i class="fa fa-th"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#tab-12" data-bs-toggle="tab" class="" title="List view">
                                        <i class="fa fa-list"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-xl-3 col-lg-12">
                            <select name="sort" class="form-select my-2">
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price (Low to High)</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price (High to Low)</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Products Grid/List View -->
            <div class="tab-content">
                <!-- Grid View -->
                <div class="tab-pane active" id="tab-11">
                    <div class="row">
                        @forelse ($plants as $plant)
                            <div class="col-md-6 col-xl-4 col-sm-6">
                                <div class="card">
                                    <div class="product-grid6">
                                        <div class="product-image6 p-5">
                                            <ul class="icons">
                                                <li>
                                                    <a href="{{ route('plants.show', $plant) }}" class="btn btn-primary">
                                                        <i class="fe fe-eye text-white"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <button type="button" 
                                                            class="btn btn-success add-to-cart" 
                                                            data-plant-id="{{ $plant->id }}"
                                                            data-plant-name="{{ $plant->name }}"
                                                            data-plant-price="{{ $plant->price }}">
                                                        <i class="fe fe-shopping-cart text-white"></i>
                                                    </button>
                                                </li>
                                                <li>
                                                    <button type="button" 
                                                            class="btn btn-danger add-to-wishlist" 
                                                            data-plant-id="{{ $plant->id }}"
                                                            data-plant-name="{{ $plant->name }}">
                                                        <i class="fe {{ in_array($plant->id, $wishlistedPlantIds ?? []) ? 'fe-heart-fill' : 'fe-heart' }} text-white"></i>
                                                    </button>
                                                </li>
                                            </ul>
                                            @if($plant->image)
                                                <img src="{{ asset('storage/' . $plant->image) }}" 
                                                     class="img-fluid br-7 w-100" 
                                                     alt="{{ $plant->name }}">
                                            @else
                                                <div class="br-be-0 br-te-0">
                                                    {{-- resize image --}}
                                                    <img src="{{ asset('storage/noImage.jpg') }}" 
     class="img-fluid br-7" 
     style="width: 150px; height: 100px; object-fit: cover;" 
     alt="No image available">

                                                </div>
                                            @endif
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class="product-content text-center">
                                                <h1 class="title fw-bold fs-20">
                                                    <a href="{{ route('plants.show', $plant) }}">{{ $plant->name }}</a>
                                                </h1>
                                                <div class="mb-2">
                                                    <span class="badge bg-success">{{ $plant->category ?? 'Uncategorized' }}</span>
                                                </div>
                                                <div class="price">PKR-{{ number_format($plant->price, 2) }}</div>
                                                <div class="mt-2">
                                                    <small class="text-muted">Available: {{ $plant->quantity ?? 'N/A' }}</small>
                                                </div>
                                                <div class="mt-3">
                                                    <button type="button" 
                                                            class="btn btn-primary btn-block add-to-cart" 
                                                            data-plant-id="{{ $plant->id }}"
                                                            data-plant-name="{{ $plant->name }}"
                                                            data-plant-price="{{ $plant->price }}">
                                                        <i class="fe fe-shopping-cart me-2"></i>Add to Cart
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body text-center py-5">
                                        <i class="fas fa-leaf fa-3x text-muted mb-3"></i>
                                        <h3 class="text-muted">No plants found</h3>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- List View -->
                <div class="tab-pane" id="tab-12">
                    @foreach($plants as $plant)
                        <div class="card overflow-hidden">
                            <div class="card-body">
                                <div class="row g-0">
                                    <div class="col-xl-3 col-lg-12 col-md-12">
                                        <div class="product-list">
                                            @if($plant->image)
                                                <img src="{{ asset('storage/' . $plant->image) }}" 
                                                     class="cover-image br-7 w-100" 
                                                     alt="{{ $plant->name }}">
                                            @else
                                                <img src="{{ asset('storage/noImage.jpg') }}" 
                                                     class="cover-image br-7 w-100" 
                                                     style="width: 200px; height: 200px; object-fit: cover;" 
                                                     alt="No image available">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-12 col-md-12 border-end">
                                        <div class="card-body">
                                            <h3 class="mb-2">{{ $plant->name }}</h3>
                                            <p class="text-muted">{{ Str::limit($plant->description, 200) }}</p>
                                            <span class="badge bg-success">{{ $plant->category ?? 'Uncategorized' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-12 col-md-12">
                                        <div class="card-body">
                                            <div class="price h3 text-center mb-5 fw-bold">PKR-{{ number_format($plant->price, 2) }}</div>
                                            <button type="button" 
                                                    class="btn btn-primary btn-block add-to-cart mb-2" 
                                                    data-plant-id="{{ $plant->id }}"
                                                    data-plant-name="{{ $plant->name }}"
                                                    data-plant-price="{{ $plant->price }}">
                                                <i class="fe fe-shopping-cart me-2"></i>Add to Cart
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-outline-primary btn-block add-to-wishlist" 
                                                    data-plant-id="{{ $plant->id }}"
                                                    data-plant-name="{{ $plant->name }}">
                                                <i class="fe {{ in_array($plant->id, $wishlistedPlantIds ?? []) ? 'fe-heart-fill' : 'fe-heart' }} me-2"></i>
                                                Add to Wishlist
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Pagination -->
            @if($plants->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $plants->links() }}
                </div>
            @endif
        </div>
    </div>
    <!-- ROW-1 CLOSED -->
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style>
.product-grid6 {
    transition: all 0.3s ease;
}
.product-grid6:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
.product-grid6 .icons {
    padding: 0;
    margin: 0;
    list-style: none;
    position: absolute;
    right: 10px;
    top: 10px;
    z-index: 1;
    opacity: 0;
    transform: translateX(20px);
    transition: all 0.3s ease;
}
.product-grid6:hover .icons {
    opacity: 1;
    transform: translateX(0);
}
.product-grid6 .icons li {
    margin-bottom: 5px;
}
.product-grid6 .icons li button,
.product-grid6 .icons li a {
    width: 40px;
    height: 40px;
    line-height: 40px;
    padding: 0;
    border-radius: 50%;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}
.btn-block {
    width: 100%;
    padding: 0.75rem 1.25rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.btn-primary {
    background: linear-gradient(to right, #0052cc, #0d6efd);
    border: none;
    box-shadow: 0 4px 15px rgba(13, 110, 253, 0.15);
}
.btn-primary:hover {
    background: linear-gradient(to right, #0043a7, #0b5ed7);
    box-shadow: 0 4px 20px rgba(13, 110, 253, 0.25);
}
.btn-success {
    background: linear-gradient(to right, #198754, #28a745);
    border: none;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.15);
}
.btn-success:hover {
    background: linear-gradient(to right, #146c43, #208637);
    box-shadow: 0 4px 20px rgba(40, 167, 69, 0.25);
}
.range-slider {
    margin: 20px 0;
}
.ui-slider-horizontal {
    height: 4px;
    background: #e9ecef;
    border: none;
    margin: 10px 0;
}

.ui-slider .ui-slider-handle {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: none;
    background: #0d6efd;
    top: -6px;
    cursor: pointer;
}

.ui-slider .ui-slider-range {
    background: #0d6efd;
}

.price-slider input[type="number"] {
    text-align: center;
}

.range-slider {
    padding: 5px;
}
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script>
$(document).ready(function() {
    // Price range slider initialization
    $("#slider-range").slider({
        range: true,
        min: 0,
        max: 5000,
        values: [{{ request('min_price', 0) }}, {{ request('max_price', 5000) }}],
        slide: function(event, ui) {
            $("#min-price").val(ui.values[0]);
            $("#max-price").val(ui.values[1]);
            // Uncheck radio buttons when slider is used
            $('.price-range').prop('checked', false);
        }
    });

    // Sync number inputs with slider
    $("#min-price, #max-price").on('change', function() {
        var min = parseInt($("#min-price").val());
        var max = parseInt($("#max-price").val());
        
        if (min >= 0 && max > min) {
            $("#slider-range").slider("values", [min, max]);
            // Uncheck radio buttons when inputs are changed
            $('.price-range').prop('checked', false);
        }
    });

    // Handle radio button changes
    $('.price-range').change(function() {
        if ($(this).is(':checked')) {
            // Clear custom range inputs when radio is selected
            var range = $(this).val().split('-');
            $("#min-price").val(range[0]);
            $("#max-price").val(range[1]);
            $("#slider-range").slider("values", [range[0], range[1]]);
        }
        $('#priceFilterForm').submit();
    });

    // Add to Wishlist functionality
    $('.add-to-wishlist').click(function() {
        var button = $(this);
        var plantId = button.data('plant-id');
        var plantName = button.data('plant-name');
        var icon = button.find('i');
        
        $.ajax({
            url: "{{ route('user.wishlist.add', '') }}/" + plantId,
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}"
            },
            beforeSend: function() {
                button.prop('disabled', true);
            },
            success: function(response) {
                if(response.success) {
                    // Toggle heart icon
                    if (response.is_wishlisted) {
                        icon.removeClass('fe-heart').addClass('fe-heart-fill text-danger');
                    } else {
                        icon.removeClass('fe-heart-fill text-danger').addClass('fe-heart');
                    }
                    toastr.success(response.message);
                }
            },
            error: function(xhr) {
                let errorMessage = 'Failed to add to wishlist';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                toastr.error(errorMessage);
            },
            complete: function() {
                button.prop('disabled', false);
            }
        });
    });

    // Add to Cart functionality
    $('.add-to-cart').click(function() {
        var button = $(this);
        var plantId = button.data('plant-id');
        var plantName = button.data('plant-name');
        var originalHtml = button.html();
        
        $.ajax({
            url: "{{ route('cart.add', '') }}/" + plantId,
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                quantity: 1
            },
            beforeSend: function() {
                button.prop('disabled', true);
                button.html('<i class="fa fa-spinner fa-spin me-2"></i>Adding...');
            },
            success: function(response) {
                if(response.success) {
                    // Update cart count in header if exists
                    if($('.cart-count').length) {
                        $('.cart-count').text(response.cart_count);
                    }
                    
                    // Show toastr success notification
                    toastr.success(`${plantName} added to cart successfully!`);
                }
            },
            error: function(xhr) {
                let errorMessage = 'Failed to add item to cart';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                toastr.error(errorMessage);
            },
            complete: function() {
                button.prop('disabled', false);
                button.html(originalHtml);
            }
        });
    });
});
</script>
@endpush 