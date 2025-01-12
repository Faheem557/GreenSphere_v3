@extends('layouts.main')

@section('title')
    Shopping Cart
@endsection

@section('maincontent')
<div class="main-container container-fluid">
    <div class="page-header">
        <h1 class="page-title">Shopping Cart</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Cart</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Cart Items</h3>
                </div>
                <div class="card-body">
                    @if(count($cart) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Subtotal</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart as $id => $details)
                                        <tr data-id="{{ $id }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($details['image'])
                                                        <img src="{{ Storage::url($details['image']) }}" 
                                                             alt="{{ $details['name'] }}"
                                                             class="rounded me-3"
                                                             style="width: 60px; height: 60px; object-fit: cover;">
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-0">{{ $details['name'] }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Rs{{ number_format($details['price'], 2) }}</td>
                                            <td>
                                                <input type="number" 
                                                       class="form-control quantity update-cart" 
                                                       value="{{ $details['quantity'] }}"
                                                       min="1"
                                                       style="width: 100px">
                                            </td>
                                            <td>Rs{{ number_format($details['price'] * $details['quantity'], 2) }}</td>
                                            <td>
                                                <button class="btn btn-danger btn-sm remove-from-cart">
                                                    <i class="fe fe-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                        <td colspan="2"><strong>Rs{{ number_format($total, 2) }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="text-end mt-3">
                            <a href="{{ route('plants.catalog') }}" class="btn btn-secondary">
                                Continue Shopping
                            </a>
                            <a href="{{ route('cart.checkout') }}" class="btn btn-primary">
                                Proceed to Checkout
                            </a>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <h4>Your cart is empty</h4>
                            <a href="{{ route('plants.catalog') }}" class="btn btn-primary mt-3">
                                Continue Shopping
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Update cart quantity
    $('.update-cart').change(function(e) {
        e.preventDefault();
        const ele = $(this);
        const quantity = ele.val();

        $.ajax({
            url: '{{ route('cart.update') }}',
            method: "PATCH",
            data: {
                _token: '{{ csrf_token() }}',
                id: ele.parents("tr").attr("data-id"),
                quantity: quantity
            },
            success: function (response) {
                window.location.reload();
            },
            error: function (response) {
                toastr.error('Error updating cart');
            }
        });
    });

    // Remove from cart
    $('.remove-from-cart').click(function(e) {
        e.preventDefault();
        const ele = $(this);

        if(confirm("Are you sure you want to remove this item?")) {
            $.ajax({
                url: '{{ route('cart.remove') }}',
                method: "DELETE",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: ele.parents("tr").attr("data-id")
                },
                success: function (response) {
                    window.location.reload();
                },
                error: function (response) {
                    toastr.error('Error removing item');
                }
            });
        }
    });

    // Listen for cart updates via Pusher
    const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
        cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}'
    });

    const channel = pusher.subscribe('cart-channel');
    channel.bind('cart-updated', function(data) {
        updateCartCount(data.count);
    });
});
</script>
@endpush
@endsection 