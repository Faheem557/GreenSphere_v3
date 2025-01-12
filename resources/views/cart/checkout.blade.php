@extends('layouts.main')

@section('title', 'Checkout')

@section('maincontent')
<div class="container py-5">
    <div class="row">
        <!-- Order Summary -->
        <div class="col-md-4 order-md-2 mb-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="d-flex justify-content-between align-items-center mb-0">
                        Order Summary
                        <span class="badge bg-primary rounded-pill">{{ count($cart) }}</span>
                    </h4>
                </div>
                <div class="card-body">
                    <ul class="list-group mb-3">
                        @foreach($cart as $id => $details)
                        <li class="list-group-item d-flex justify-content-between lh-sm">
                            <div>
                                <h6 class="my-0">{{ $details['name'] }}</h6>
                                <small class="text-muted">Quantity: {{ $details['quantity'] }}</small>
                            </div>
                            <span class="text-muted">Rs{{ $details['price'] * $details['quantity'] }}</span>
                        </li>
                        @endforeach
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Total (PKR)</strong>
                            <strong>Rs{{ $total }}</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Checkout Form -->
        <div class="col-md-8 order-md-1">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Billing Details</h4>
                </div>
                <div class="card-body">
                    <form id="checkout-form" class="needs-validation" novalidate>
                        @csrf
                        <!-- Billing Address -->
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" 
                                {{-- {{ dd(auth()->user()->name) }}  --}}
                                    value="{{ auth()->user()->name }}" required readonly>
                                <div class="invalid-feedback">
                                    Valid name is required.
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" 
                                    value="{{ auth()->user()->email }}" required readonly>
                                <div class="invalid-feedback">
                                    Please enter a valid email address.
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="address" class="form-label">Street Address</label>
                                <textarea class="form-control" id="address" name="address" rows="2" required>{{ json_decode(auth()->user()->location)->address ?? '' }}</textarea>
                                <div class="invalid-feedback">
                                    Please enter your street address.
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control" id="city" name="city" 
                                        value="{{ json_decode(auth()->user()->location)->city ?? '' }}" required>
                                    <div class="invalid-feedback">
                                        Please enter your city.
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label for="state" class="form-label">State</label>
                                    <input type="text" class="form-control" id="state" name="state" 
                                        value="{{ json_decode(auth()->user()->location)->state ?? '' }}" required>
                                    <div class="invalid-feedback">
                                        Please enter your state.
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <label for="zip" class="form-label">ZIP Code</label>
                                    <input type="text" class="form-control" id="zip" name="zip" 
                                        value="{{ json_decode(auth()->user()->location)->zip ?? '' }}" required>
                                    <div class="invalid-feedback">
                                        Please enter your ZIP code.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="payment-method">
                            <h4 class="mb-3">Payment Method</h4>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="cod" name="payment_method" value="cod" checked>
                                <label class="form-check-label" for="cod">Cash on Delivery</label>
                            </div>
                        </div>

                        <hr class="my-4">

                        <button class="w-100 btn btn-primary btn-lg" type="submit">Place Order</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const form = document.getElementById('checkout-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        
        if (!form.checkValidity()) {
            event.stopPropagation();
            form.classList.add('was-validated');
            return;
        }

        // Show processing message
        toastr.info('Processing your order...', 'Please wait');

        // Get the form data
        const formData = {
            _token: '{{ csrf_token() }}',
            name: $('#name').val(),
            email: $('#email').val(),
            location: {
                address: $('#address').val(),
                city: $('#city').val(),
                state: $('#state').val(),
                zip: $('#zip').val()
            },
            payment_method: 'cod'
        };

        // Submit order
        $.ajax({
            url: '{{ route("orders.store") }}',
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    // Broadcast order created event
                    window.Echo.private('orders')
                        .whisper('newOrder', {
                            order: response.order
                        });
                    
                    toastr.success('Order placed successfully!');
                    // Redirect to order status page
                    window.location.href = `/orders/${response.order.id}/status`;
                } else {
                    toastr.error(response.message || 'Error processing order');
                    console.error('Order processing failed:', response);
                }
            },
            error: function(xhr, status, error) {
                const errorMessage = xhr.responseJSON?.message || 'Error processing order';
                toastr.error(errorMessage);
                console.log(errorMessage);
                console.error('Order submission error:', {
                    status: status,
                    error: error,
                    response: xhr.responseJSON,
                    formData: formData
                });
            }
        });
    });
});
</script>
@endpush 