@extends('layouts.main')
@section('title', 'My Orders')

@section('maincontent')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">My Orders</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                    <td>₹{{ number_format($order->total_amount, 2) }}</td>
                                    <td>
                                        @if($order->status === 'pending')
                                        <span class="badge bg-{{ $order->status_color }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                        @else
                                        <span class="badge bg-{{ $order->status_color }}">
                                            {{ ucfirst($order->status) }}
                                            @if($order->status === 'shipped' && $order->tracking_number)
                                            <br>
                                            <small>Track: {{ $order->tracking_number }}</small>
                                            @endif
                                        </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($order->status !== 'delivered' && $order->status !== 'cancelled')
                                        <a href="{{ route('user.orders.track', $order->id) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="fas fa-truck me-1"></i> Track Order
                                        </a>
                                        @endif
                                        <a href="{{ route('user.orders.show', $order->id) }}"
                                            class="btn btn-sm btn-info">
                                            <i class="fas fa-eye me-1"></i> View Details
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No orders found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection