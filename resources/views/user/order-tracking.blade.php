@extends('layouts.main')
@section('title')
Track Order #{{ $order->id }}
@endsection

@section('maincontent')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Track Order #{{ $order->id }}</h4>
                </div>
                <div class="card-body">
                    <!-- Order Status Timeline -->
                    <div class="timeline mb-4">
                        <div class="status-line mb-4">
                            <div class="d-flex align-items-center">
                                <div class="status-icon @if($order->status >= 'pending') bg-success @else bg-secondary @endif">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="status-content ms-3">
                                    <h6 class="mb-0">Order Received</h6>
                                    <small class="text-muted">{{ $order->created_at->format('M d, Y H:i') }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="status-line mb-4">
                            <div class="d-flex align-items-center">
                                <div class="status-icon @if($order->status >= 'processing') bg-success @else bg-secondary @endif">
                                    <i class="fas fa-box"></i>
                                </div>
                                <div class="status-content ms-3">
                                    <h6 class="mb-0">Processing</h6>
                                    <small class="text-muted">Your order is being prepared</small>
                                </div>
                            </div>
                        </div>

                        <div class="status-line mb-4">
                            <div class="d-flex align-items-center">
                                <div class="status-icon @if($order->status >= 'shipped') bg-success @else bg-secondary @endif">
                                    <i class="fas fa-truck"></i>
                                </div>
                                <div class="status-content ms-3">
                                    <h6 class="mb-0">Shipped</h6>
                                    @if($order->tracking_number)
                                    <small class="text-muted">Tracking Number: {{ $order->tracking_number }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="status-line">
                            <div class="d-flex align-items-center">
                                <div class="status-icon @if($order->status == 'delivered') bg-success @else bg-secondary @endif">
                                    <i class="fas fa-home"></i>
                                </div>
                                <div class="status-content ms-3">
                                    <h6 class="mb-0">Delivered</h6>
                                    @if($order->status == 'delivered')
                                    <small class="text-muted">{{ $order->updated_at->format('M d, Y H:i') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Details -->
                    <div class="mt-5">
                        <h5>Order Details</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td>{{ $item->plant->name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>₹{{ number_format($item->price, 2) }}</td>
                                        <td>₹{{ number_format($item->price * $item->quantity, 2) }}</td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                        <td><strong>₹{{ number_format($order->total_amount, 2) }}</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Shipping Details -->
                    <div class="mt-4">
                        <h5>Shipping Details</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Delivery Address:</strong><br>
                                    {{ $order->location['address'] }}<br>
                                    {{ $order->location['city'] }}, {{ $order->location['state'] }} {{ $order->location['zip'] }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Contact Information:</strong><br>
                                    {{ $order->buyer->name }}<br>
                                    {{ $order->buyer->email }}<br>
                                    {{ $order->buyer->phone }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .timeline {
        position: relative;
        padding: 20px 0;
    }

    .status-line {
        position: relative;
    }

    .status-line:not(:last-child):before {
        content: '';
        position: absolute;
        left: 20px;
        top: 40px;
        bottom: -20px;
        width: 2px;
        background-color: #dee2e6;
    }

    .status-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .bg-success {
        background-color: #28a745;
    }

    .bg-secondary {
        background-color: #6c757d;
    }
</style>
@endsection