@extends('layouts.main')

@section('title', 'Checkout')

@push('styles')

<style>
    .checkout-container {
        background-color: #f8f9fa;
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(0,0,0,0.05);
        min-height: 100vh;
    }
    .sticky-summary {
        position: -webkit-sticky;
        position: sticky;
        top: 80px; /* Adjust this value based on your header height */
    }
    .order-summary-card {
        background: white;
        border: none;
        box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        border-radius: 12px;
        height: auto;
        max-height: calc(100vh - 120px); /* Adjust based on your needs */
        overflow-y: auto;
    }
    .billing-card {
        background: white;
        border: none;
        box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        border-radius: 12px;
    }
    .form-control, .form-select {
        border-radius: 8px;
        padding: 12px;
        border: 1px solid #e0e0e0;
        transition: all 0.3s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
    }
    .btn-checkout {
        padding: 12px 24px;
        font-weight: 600;
        border-radius: 8px;
        background: linear-gradient(45deg, #6366f1, #8b5cf6);
        border: none;
        transition: all 0.3s ease;
    }
    .btn-checkout:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
    }
    .item-card {
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    .item-card:hover {
        transform: translateY(-2px);
    }
    .badge-custom {
        background: linear-gradient(45deg, #6366f1, #8b5cf6);
        padding: 8px 12px;
        font-size: 0.9rem;
    }
    .section-divider {
        height: 1px;
        background: linear-gradient(to right, transparent, #e0e0e0, transparent);
        margin: 2rem 0;
    }
</style>
@endpush

@section('maincontent')
<div class="container py-5">
    <div class="checkout-container p-4">
        <div class="row g-4">
            <!-- Order Summary -->
            <div class="col-lg-4 order-lg-2">
                <div class="sticky-summary">
                    <div class="order-summary-card p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="fw-bold m-0">Order Summary</h4>
                            <span class="badge badge-custom rounded-pill">{{ count($cart) }} items</span>
                        </div>
                        
                        <div class="items-container">
                            @foreach($cart as $id => $details)
                            <div class="item-card p-3 mb-3 bg-light rounded">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="fw-bold mb-1">{{ $details['name'] }}</h6>
                                        <span class="text-muted small">Qty: {{ $details['quantity'] }}</span>
                                    </div>
                                    <span class="fw-bold text-primary">Rs{{ number_format($details['price'] * $details['quantity'], 2) }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="section-divider"></div>

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-bold">Rs{{ number_format($total, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Delivery Fee</span>
                            <span class="fw-bold text-success">Free</span>
                        </div>
                        <div class="section-divider"></div>
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold">Total</h5>
                            <h5 class="fw-bold text-primary">Rs{{ number_format($total, 2) }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Checkout Form -->
            <div class="col-lg-8 order-lg-1">
                <div class="billing-card p-4">
                    <h4 class="fw-bold mb-4">Billing Details</h4>
                    <form id="checkout-form" action="{{ route('orders.checkout') }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        <div class="row g-4">
                            <!-- Personal Information -->
                            <div class="col-12">
                                <div class="bg-light p-4 rounded-3 mb-4">
                                    <h5 class="fw-bold mb-3">Personal Information</h5>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Full Name</label>
                                            <input type="text" class="form-control" id="name" value="{{ auth()->user()->name }}" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" value="{{ auth()->user()->email }}" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Phone <span class="text-danger">*</span></label>
                                            <input type="tel" 
                                                   class="form-control" 
                                                   id="phone" 
                                                   name="phone" 
                                                   value="{{ auth()->user()->phone }}" 
                                                   pattern="[0-9]{10,}"
                                                   title="Please enter a valid phone number"
                                                   required>
                                            <div class="invalid-feedback">
                                                Please provide a valid phone number
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Delivery Address -->
                            <div class="col-12">
                                <div class="bg-light p-4 rounded-3 mb-4">
                                    <h5 class="fw-bold mb-3">Delivery Address</h5>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label">Street Address <span class="text-danger">*</span></label>
                                            <textarea class="form-control" id="address" name="address" 
                                                      rows="2" required>{{ json_decode(auth()->user()->location)->address ?? '' }}</textarea>
                                            <div class="invalid-feedback">
                                                Please provide your shipping address
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label">City</label>
                                            <input type="text" class="form-control" id="city" name="city" value="{{ json_decode(auth()->user()->location)->city ?? '' }}" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">State</label>
                                            <input type="text" class="form-control" id="state" name="state" value="{{ json_decode(auth()->user()->location)->state ?? '' }}" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">ZIP Code</label>
                                            <input type="text" class="form-control" id="zip" name="zip" value="{{ json_decode(auth()->user()->location)->zip ?? '' }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Delivery Options -->
                            <div class="col-12">
                                <div class="bg-light p-4 rounded-3 mb-4">
                                    <h5 class="fw-bold mb-3">Delivery Preferences</h5>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Delivery Option</label>
                                            <select name="delivery_option_id" id="delivery_option_id" class="form-select" required>
                                                <option value="">Select Delivery Option</option>
                                                @foreach(App\Models\Plant::DELIVERY_OPTIONS as $key => $option)
                                                    <option value="{{ $key }}">{{ $option }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Preferred Date</label>
                                            <input type="date" class="form-control" id="delivery_date" name="delivery_date" 
                                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Time Slot</label>
                                            <select class="form-select" id="delivery_slot" name="delivery_slot" required>
                                                <option value="">Choose a time slot...</option>
                                                <option value="morning">Morning (9 AM - 12 PM)</option>
                                                <option value="afternoon">Afternoon (12 PM - 3 PM)</option>
                                                <option value="evening">Evening (3 PM - 6 PM)</option>
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Special Instructions</label>
                                            <textarea class="form-control" id="delivery_instructions" name="delivery_instructions" 
                                                      rows="2" placeholder="Any special instructions for delivery..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Method -->
                            <div class="col-12">
                                <div class="bg-light p-4 rounded-3 mb-4">
                                    <h5 class="fw-bold mb-3">Payment Method</h5>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <select name="payment_method" id="payment_method" class="form-select" required>
                                                <option value="">Select Payment Method</option>
                                                <option value="cod">Cash on Delivery</option>
                                                <option value="online">Online Payment</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <button class="btn btn-checkout w-100 btn-primary" type="submit">
                                    <i class="fas fa-lock me-2"></i>Place Order Securely
                                </button>
                            </div>
                        </div>
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
        
        // Validate phone
        const phone = $('#phone').val().replace(/\D/g, '');
        if (phone.length < 10) {
            toastr.error('Please enter a valid phone number');
            $('#phone').focus();
            return;
        }

        // Validate address
        if (!$('#address').val().trim()) {
            toastr.error('Please enter your shipping address');
            $('#address').focus();
            return;
        }

        // Validate delivery date
        const deliveryDate = new Date($('#delivery_date').val());
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        if (deliveryDate < tomorrow) {
            toastr.error('Delivery date must be at least tomorrow');
            $('#delivery_date').focus();
            return;
        }

        // Show processing message
        toastr.info('Processing your order...', 'Please wait');

        // Get the form data
        const formData = {
            _token: '{{ csrf_token() }}',
            name: $('#name').val(),
            email: $('#email').val(),
            phone: $('#phone').val(),
            shipping_address: JSON.stringify({
                address: $('#address').val(),
                city: $('#city').val(),
                state: $('#state').val(),
                zip: $('#zip').val()
            }),
            delivery_date: $('#delivery_date').val(),
            delivery_slot: $('#delivery_slot').val(),
            delivery_instructions: $('#delivery_instructions').val(),
            payment_method: $('#payment_method').val(),
            delivery_option_id: $('#delivery_option_id').val()
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