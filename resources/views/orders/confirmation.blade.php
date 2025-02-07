@extends('layouts.main')

@section('title', 'Order Confirmation')

@section('maincontent')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fe fe-check-circle text-success" style="font-size: 48px;"></i>
                    <h2 class="mt-3">Thank You for Your Order!</h2>
                    <p class="lead">Order #{{ $order->id }} has been placed successfully.</p>
                    
                    <div class="alert alert-info">
                        <p class="mb-0">We'll send you an email confirmation with order details shortly.</p>
                    </div>

                    <div class="order-details mt-4">
                        <h4>Order Summary</h4>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td>{{ $item->plant->name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>Rs{{ number_format($item->price * $item->quantity, 2) }}</td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="2" class="text-end"><strong>Total:</strong></td>
                                        <td><strong>Rs{{ number_format($order->total, 2) }}</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="shipping-details mt-4">
                        <h4>Shipping Details</h4>
                        @php $address = json_decode($order->shipping_address, true); @endphp
                        <p>
                            {{ $address['name'] }}<br>
                            {{ $address['address'] }}<br>
                            {{ $address['zip'] }}<br>
                            {{ $address['email'] }}
                        </p>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('orders.index') }}" class="btn btn-primary">View My Orders</a>
                        <a href="{{ route('plants.catalog') }}" class="btn btn-outline-primary">Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
