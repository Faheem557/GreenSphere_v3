@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Order Successful</div>

                <div class="card-body">
                    <div class="text-center">
                        <h2 class="text-success">Thank you for your order!</h2>
                        <p>Your order has been successfully placed.</p>
                        <a href="{{ route('home') }}" class="btn btn-primary">Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 