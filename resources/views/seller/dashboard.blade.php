@extends('layouts.main')   
@section('title')
    Seller Dashboard
@endsection

@section('maincontent')     
    <!-- CONTAINER -->
    <div class="main-container container-fluid">
        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">Seller Dashboard</h1>
            <div>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                    <li class="umb-item active" aria-current="page">Dashboard</li>
                </ol>
            </div>
        </div>

        <!-- ROW-1 -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                        <div class="card overflow-hidden">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="mt-2">
                                        <h6 class="">Total Plants</h6>
                                        <h2 class="mb-0 number-font">{{ $stats['total_plants'] ?? 0 }}</h2>
                                    </div>
                                    <div class="ms-auto">
                                        <div class="chart-wrapper mt-1">
                                            <i class="fe fe-shopping-bag fs-35 text-primary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                        <div class="card overflow-hidden">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="mt-2">
                                        <h6 class="">Active Plants</h6>
                                        <h2 class="mb-0 number-font">{{ $stats['active_plants'] ?? 0 }}</h2>
                                    </div>
                                    <div class="ms-auto">
                                        <div class="chart-wrapper mt-1">
                                            <i class="fe fe-check-circle fs-35 text-success"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                        <div class="card overflow-hidden">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="mt-2">
                                        <h6 class="">Out of Stock</h6>
                                        <h2 class="mb-0 number-font">{{ $stats['out_of_stock'] ?? 0 }}</h2>
                                    </div>
                                    <div class="ms-auto">
                                        <div class="chart-wrapper mt-1">
                                            <i class="fe fe-alert-circle fs-35 text-danger"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                        <div class="card overflow-hidden">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="mt-2">
                                        <h6 class="">Pending Orders</h6>
                                        <h2 class="mb-0 number-font">{{ $stats['pending_orders'] ?? 0 }}</h2>
                                    </div>
                                    <div class="ms-auto">
                                        <div class="chart-wrapper mt-1">
                                            <i class="fe fe-clock fs-35 text-warning"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Recent Orders</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered text-nowrap border-bottom">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Total Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($stats['recent_orders'] ?? [] as $order)
                                        <tr>
                                            <td>#{{ $order->id }}</td>
                                            <td>{{ $order->buyer->name }}</td>
                                            <td>PKR-{{ number_format($order->total_amount, 2) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $order->status === 'pending' ? 'warning' : 'success' }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('seller.orders.show', $order->id) }}" 
                                                   class="btn btn-sm btn-primary">
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No recent orders</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 

@push('scripts')
<script>
$(document).ready(function() {
    // Listen for new orders
    window.Echo.private('orders')
        .listenForWhisper('newOrder', (e) => {
            // Update order count badge
            const currentCount = parseInt($('#order-count').text() || 0);
            $('#order-count').text(currentCount + 1);
            
            // Show notification
            toastr.success('New order received!', 'Order Update');
            
            // Add new order to the recent orders table
            if (e.order) {
                const newRow = `
                    <tr>
                        <td>${e.order.id}</td>
                        <td>${e.order.customer_name}</td>
                        <td>PKR-${e.order.total}</td>
                        <td><span class="badge bg-warning">New</span></td>
                        <td>
                            <a href="/seller/orders/${e.order.id}" class="btn btn-sm btn-primary">View</a>
                        </td>
                    </tr>
                `;
                $('#recent-orders tbody').prepend(newRow);
            }
        });
});
</script>
@endpush 