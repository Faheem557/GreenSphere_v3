@extends('layouts.main')

@section('title', 'Order Status')

@section('maincontent')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Order #{{ $order->id }} Status</h4>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="h5">Thank you for your order!</div>
                        <p class="text-muted">We'll update you on your order status</p>
                    </div>

                    <div class="timeline">
                        <div class="status-line mb-4">
                            <div class="d-flex align-items-center">
                                <div class="status-icon @if($order->status >= 'pending') bg-success @else bg-secondary @endif">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="status-content ms-3">
                                    <h6 class="mb-0">Order Received</h6>
                                    <small class="text-muted">We have received your order</small>
                                </div>
                            </div>
                        </div>

                        <div class="status-line mb-4">
                            <div class="d-flex align-items-center">
                                <div class="status-icon @if($order->status >= 'processing') bg-success @else bg-secondary @endif">
                                    <i class="fas fa-box"></i>
                                </div>
                                <div class="status-content ms-3">
                                    <h6 class="mb-0">Processing Order</h6>
                                    <small class="text-muted">Your order is being processed</small>
                                </div>
                            </div>
                        </div>

                        <div class="status-line mb-4">
                            <div class="d-flex align-items-center">
                                <div class="status-icon @if($order->status >= 'shipped') bg-success @else bg-secondary @endif">
                                    <i class="fas fa-truck"></i>
                                </div>
                                <div class="status-content ms-3">
                                    <h6 class="mb-0">Out for Delivery</h6>
                                    <small class="text-muted">Your order is on its way</small>
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
                                    <small class="text-muted">Order has been delivered</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="order-details mt-5">
                        <h5 class="mb-3">Order Details</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Delivery Address:</strong><br>
                                    {{ $order->location['address'] }}<br>
                                    {{ $order->location['city'] }}, {{ $order->location['state'] }} {{ $order->location['zip'] }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Payment Method:</strong><br>
                                    {{ ucfirst($order->payment_method) }}</p>
                                <p><strong>Order Total:</strong><br>
                                    Rs{{ $order->total }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.status-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
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
</style>
@endsection 