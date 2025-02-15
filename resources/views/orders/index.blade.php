@extends('layouts.main')

@section('title', 'Order History')

@section('maincontent')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">{{ __('Order History') }}</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">{{ __('Dashboard') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('Orders') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            @if($orders->isEmpty())
                                <div class="text-center py-4">
                                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                    <h4 class="text-muted">{{ __('No completed orders found') }}</h4>
                                    <p class="text-muted mb-3">{{ __('Looks like you haven\'t placed any orders yet.') }}</p>
                                    <a href="{{ route('plants.catalog') }}" class="btn btn-primary">
                                        <i class="fas fa-shopping-cart mr-2"></i>{{ __('Browse Plants') }}
                                    </a>
                                </div>
                            @else
                                <table class="table table-centered table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>{{ __('Order ID') }}</th>
                                            <th>{{ __('Date') }}</th>
                                            <th>{{ __('Items') }}</th>
                                            <th>{{ __('Total') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th class="text-center">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($orders as $order)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('user.orders.show', $order) }}" 
                                                       class="text-body fw-bold">
                                                        #{{ $order->id }}
                                                    </a>
                                                </td>
                                                <td>
                                                    {{ $order->created_at->format('M d, Y') }}
                                                    <small class="text-muted">
                                                        {{ $order->created_at->format('h:i A') }}
                                                    </small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-soft-info">
                                                        {{ $order->items->count() }} {{ __('items') }}
                                                    </span>
                                                </td>
                                                <td>
                                                    ${{ number_format($order->total, 2) }}
                                                </td>
                                                <td>
                                                    <span class="badge bg-soft-{{ $order->status_color }} text-{{ $order->status_color }}">
                                                        {{ ucfirst($order->status) }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex justify-content-center gap-2">
                                                        <a href="{{ route('user.orders.show', $order) }}" 
                                                           class="btn btn-sm btn-primary">
                                                            <i class="fas fa-eye"></i>
                                                            {{ __('View') }}
                                                        </a>
                                                        @if($order->status !== 'delivered' && $order->status !== 'cancelled')
                                                            <a href="{{ route('user.orders.track', $order) }}" 
                                                               class="btn btn-sm btn-info">
                                                                <i class="fas fa-truck"></i>
                                                                {{ __('Track') }}
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <div class="mt-4">
                                    {{ $orders->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Initialize any plugins or event handlers here
        });
    </script>
@endsection 