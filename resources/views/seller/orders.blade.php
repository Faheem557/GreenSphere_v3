@extends('layouts.main')

@section('title')
Seller Orders
@endsection

@section('maincontent')
<div class="main-container container-fluid">
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <h1 class="page-title">My Orders</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Orders</li>
            </ol>
        </div>
    </div>

    <!-- Orders List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Orders</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom" id="orders-table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Items</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                    <th>Order Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ $order->buyer->name }}</td>
                                    <td>
                                        @foreach($order->items as $item)
                                        <div>{{ $item->plant->name }} (x{{ $item->quantity }})</div>
                                        @endforeach
                                    </td>
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
                                    <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('seller.orders.show', $order->id) }}"
                                            class="btn btn-primary btn-sm">
                                            View Details
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No orders found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#orders-table').DataTable({
            order: [
                [0, 'desc']
            ],
            pageLength: 10
        });

        // Handle order status changes
        $('.order-status').change(function() {
            const orderId = $(this).data('order-id');
            const newStatus = $(this).val();
            const select = $(this);

            $.ajax({
                url: `/seller/orders/${orderId}/status`,
                method: 'POST',
                data: {
                    status: newStatus,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    toastr.success('Order status updated successfully');
                    if (newStatus === 'delivered') {
                        select.prop('disabled', true);
                    }
                },
                error: function(xhr) {
                    toastr.error('Error updating order status');
                    // Reset to previous value
                    select.val(select.find('option[selected]').val());
                }
            });
        });

        // Listen for real-time order updates
        const pusher = new Pusher('{{ config('
            broadcasting.connections.pusher.key ') }}', {
                cluster: '{{ config('
                broadcasting.connections.pusher.options.cluster ') }}'
            });

        const channel = pusher.subscribe('seller-{{ auth()->id() }}');

        channel.bind('new-order', function(data) {
            toastr.success('New order received!');
            // Reload the page to show the new order
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        });
    });
</script>
@endpush
@endsection