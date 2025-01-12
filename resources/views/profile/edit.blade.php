@extends('layouts.main')

@section('title')
    Edit Profile
@endsection

@section('maincontent')
<div class="main-container container-fluid">
    <div class="page-header">
        <h1 class="page-title">Edit Profile</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Address</label>
                    <input type="text" name="location[address]" class="form-control @error('location.address') is-invalid @enderror" 
                           value="{{ old('location.address', $user->location['address'] ?? '') }}" required>
                    @error('location.address')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label">City</label>
                            <input type="text" name="location[city]" class="form-control @error('location.city') is-invalid @enderror" 
                                   value="{{ old('location.city', $user->location['city'] ?? '') }}" required>
                            @error('location.city')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label">State</label>
                            <input type="text" name="location[state]" class="form-control @error('location.state') is-invalid @enderror" 
                                   value="{{ old('location.state', $user->location['state'] ?? '') }}" required>
                            @error('location.state')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label">ZIP Code</label>
                            <input type="text" name="location[zip]" class="form-control @error('location.zip') is-invalid @enderror" 
                                   value="{{ old('location.zip', $user->location['zip'] ?? '') }}" required>
                            @error('location.zip')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr>

                <h4>Change Password</h4>
                <div class="form-group mb-3">
                    <label class="form-label">Current Password</label>
                    <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror">
                    @error('current_password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">New Password</label>
                    <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror">
                    @error('new_password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="new_password_confirmation" class="form-control">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
