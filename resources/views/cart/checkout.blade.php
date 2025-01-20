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
                    <form id="checkout-form" action="{{ route('orders.checkout') }}" method="POST" class="needs-validation" novalidate>
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

                        <div class="form-group">
                            <label for="delivery_option_id" class="form-label">Delivery Option</label>
                            <select name="delivery_option_id" id="delivery_option_id" 
                                    class="form-control @error('delivery_option_id') is-invalid @enderror" required>
                                <option value="">Select Delivery Option</option>
                                @foreach(App\Models\Plant::DELIVERY_OPTIONS as $key => $option)
                                    <option value="{{ $key }}" 
                                            {{ old('delivery_option_id') == $key ? 'selected' : '' }}>
                                        {{ $option }}
                                    </option>
                                @endforeach
                            </select>
                            @error('delivery_option_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="shipping_address" class="form-label">Shipping Address</label>
                            <textarea name="shipping_address" id="shipping_address" 
                                      class="form-control @error('shipping_address') is-invalid @enderror" 
                                      required>{{ old('shipping_address') }}</textarea>
                            @error('shipping_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" name="phone" id="phone" 
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   value="{{ old('phone') }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="payment_method" class="form-label">Payment Method</label>
                            <select name="payment_method" id="payment_method" 
                                    class="form-control @error('payment_method') is-invalid @enderror" required>
                                <option value="">Select Payment Method</option>
                                <option value="cod" {{ old('payment_method') == 'cod' ? 'selected' : '' }}>Cash on Delivery</option>
                                <option value="online" {{ old('payment_method') == 'online' ? 'selected' : '' }}>Online Payment</option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <div class="card mt-4">
                            <div class="card-header">
                                <h4 class="mb-0">Delivery Options</h4>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Preferred Delivery Date</label>
                                        <input type="date" class="form-control" id="delivery_date" name="delivery_date" 
                                               min="{{ date('Y-m-d', strtotime('+1 day')) }}" 
                                               required>
                                    </div>
                                    
                                    <div class="col-12">
                                        <label class="form-label">Delivery Time Slot</label>
                                        <select class="form-control" id="delivery_slot" name="delivery_slot" required>
                                            <option value="">Choose a time slot...</option>
                                            <option value="morning">Morning (9 AM - 12 PM)</option>
                                            <option value="afternoon">Afternoon (12 PM - 3 PM)</option>
                                            <option value="evening">Evening (3 PM - 6 PM)</option>
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Delivery Instructions (Optional)</label>
                                        <textarea class="form-control" id="delivery_instructions" name="delivery_instructions" 
                                                  rows="2" placeholder="Any special instructions for delivery..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

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
            delivery: {
                date: $('#delivery_date').val(),
                slot: $('#delivery_slot').val(),
                instructions: $('#delivery_instructions').val()
            },
            payment_method: $('#payment_method').val(),
            delivery_option_id: $('#delivery_option_id').val(),
            shipping_address: $('#shipping_address').val(),
            phone: $('#phone').val()
        };

        // Submit order
        $.ajax({
            url: '{{ route("orders.checkout") }}',
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    toastr.success('Order placed successfully!');
                    window.location.href = response.redirect;
                } else {
                    toastr.error(response.message || 'Error processing order');
                }
            },
            error: function(xhr, status, error) {
                let errorMessage = 'Error processing order';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    errorMessage = Object.values(errors).flat().join('\n');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                toastr.error(errorMessage);
                
                console.error('Order submission error:', {
                    status: status,
                    error: error,
                    response: xhr.responseJSON,
                    formData: formData
                });
            }
        });
    });

    // Add client-side validation
    $('#checkout-form').on('submit', function(e) {
        const deliveryOption = $('#delivery_option_id').val();
        if (!deliveryOption) {
            e.preventDefault();
            toastr.error('Please select a delivery option');
            return false;
        }
    });
});
</script>
@endpush 