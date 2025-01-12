@extends('layouts.main')
@section('title')
Order Details #{{ $order->id }}
@endsection

@section('maincontent')
<div class="main-container container-fluid">
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <h1 class="page-title">Order Details #{{ $order->id }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('seller.orders.index') }}">Orders</a></li>
                <li class="breadcrumb-item active" aria-current="page">Order Details</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Order Information</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Plant</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Subtotal</th>
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
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Order Status</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('seller.orders.update-status', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Current Status</label>
                            <select name="status" class="form-select" {{ $order->status === 'delivered' ? 'disabled' : '' }}>
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <div class="mb-3" id="tracking_number_field" style="{{ $order->status !== 'shipped' ? 'display: none;' : '' }}">
                            <label class="form-label">Tracking Number</label>
                            <input type="text" name="tracking_number" class="form-control" value="{{ $order->tracking_number }}" placeholder="Enter tracking number">
                        </div>

                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">Customer Details</h3>
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $order->buyer->name ?? 'N/A' }}</p>
                    <p><strong>Email:</strong> {{ $order->buyer->email ?? 'N/A' }}</p>
                    <p><strong>Phone:</strong> {{ $order->buyer->phone ?? 'N/A' }}</p>
                    <hr>
                    <h5>Shipping Address</h5>
                    <address>
                        @if($order->location)
                        {{ $order->location['address'] ?? 'N/A' }}<br>
                        {{ $order->location['city'] ?? 'N/A' }}, {{ $order->location['state'] ?? 'N/A' }}<br>
                        {{ $order->location['zip'] ?? 'N/A' }}
                        @else
                        No shipping address provided
                        @endif
                    </address>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('select[name="status"]').change(function() {
            const status = $(this).val();
            const trackingField = $('#tracking_number_field');

            if (status === 'shipped') {
                trackingField.slideDown();
            } else {
                trackingField.slideUp();
            }
        });
    });
</script>
@endpush
@endsection