@extends('layouts.main')

<<<<<<< HEAD
@section('title', 'Plant Catalog')

@section('maincontent')
<div class="container-fluid">
    <div class="py-12">
        <div class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="card">
                        <div class="card-header">
                            <h4>Edit Profile</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <!-- Basic Information -->
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}">
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}">
                                </div>

                                <!-- Gardening Level -->
                                <div class="mb-3">
                                    <label for="gardening_level" class="form-label">Gardening Experience Level</label>
                                    <select class="form-select" id="gardening_level" name="gardening_level">
                                        <option value="beginner" {{ optional($user->profile)->gardening_level === 'beginner' ? 'selected' : '' }}>Beginner</option>
                                        <option value="intermediate" {{ optional($user->profile)->gardening_level === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                        <option value="advanced" {{ optional($user->profile)->gardening_level === 'advanced' ? 'selected' : '' }}>Advanced</option>
                                    </select>
                                </div>

                                <!-- Plant Preferences -->
                                <div class="mb-3">
                                    <label class="form-label">Plant Preferences</label>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="plant_preferences[]" value="indoor" 
                                                    {{ in_array('indoor', optional($user->profile)->plant_preferences ?? []) ? 'checked' : '' }}>
                                                <label class="form-check-label">Indoor Plants</label>
                                            </div>
                                        </div>
                                        <!-- Add more plant preferences -->
                                    </div>
                                </div>

                                <!-- Location -->
                                <div class="mb-3">
                                    <label class="form-label">Location</label>
                                    <div id="map" style="height: 300px;" class="mb-3"></div>
                                    <input type="hidden" name="location[latitude]" id="latitude" value="{{ optional($user->profile)->location_data['latitude'] ?? '' }}">
                                    <input type="hidden" name="location[longitude]" id="longitude" value="{{ optional($user->profile)->location_data['longitude'] ?? '' }}">
                                    <input type="text" class="form-control mb-2" name="location[address]" placeholder="Address" 
                                        value="{{ optional($user->profile)->location_data['address'] ?? '' }}">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" name="location[city]" placeholder="City" 
                                                value="{{ optional($user->profile)->location_data['city'] ?? '' }}">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" name="location[state]" placeholder="State" 
                                                value="{{ optional($user->profile)->location_data['state'] ?? '' }}">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" name="location[zip]" placeholder="ZIP" 
                                                value="{{ optional($user->profile)->location_data['zip'] ?? '' }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Notification Preferences -->
                                <div class="mb-3">
                                    <label class="form-label">Notification Preferences</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="notification_preferences[email]" 
                                            {{ (optional($user->profile)->notification_preferences['email'] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label">Email Notifications</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="notification_preferences[push]" 
                                            {{ (optional($user->profile)->notification_preferences['push'] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label">Push Notifications</label>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">Update Profile</button>
                            </form>
                        </div>
                    </div>
=======
@section('title', 'User Dashboard')

@section('maincontent')
                    <!-- CONTAINER -->
                    <div class="main-container container-fluid">

                        <!-- PAGE-HEADER -->
                        <div class="page-header">
                            <h1 class="page-title">Profile Settings</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        @if(auth()->user()->hasRole('seller'))
                                            <a href="{{ route('seller.dashboard') }}">Dashboard</a>
                                        @else
                                            <a href="{{ route('user.dashboard') }}">Dashboard</a>
                                        @endif
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Profile Settings</li>
                                </ol>
                            </div>
                        </div>
                        <!-- PAGE-HEADER END -->

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- ROW-1 OPEN -->
                        <div class="row">
                            <div class="col-xl-4">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">Profile Photo</div>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-center chat-image mb-5">
                                            <div class="avatar avatar-xxl chat-profile mb-3 brround">
                                                <img src="{{ auth()->user()->profile_photo_url ?? '../assets/images/users/21.jpg' }}" alt="Profile Image" id="preview-image" class="brround">
                                            </div>
                                            <div class="main-chat-msg-name">
                                                <h5 class="mb-1 text-dark fw-semibold">{{ auth()->user()->name }}</h5>
                                                <p class="text-muted mt-0 mb-0 pt-0 fs-13">{{ auth()->user()->email }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="profile_photo" class="form-label">Change Profile Photo</label>
                                            <input type="file" class="form-control" name="profile_photo" id="profile_photo" form="profile-form">
                                            @error('profile_photo')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Edit Profile</h3>
                                    </div>
                                    <div class="card-body">
                                        <form id="profile-form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            
                                            <div class="form-group">
                                                <label for="name" class="form-label">Full Name</label>
                                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                       name="name" value="{{ old('name', auth()->user()->name) }}" required>
                                                @error('name')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="email" class="form-label">Email address</label>
                                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                       name="email" value="{{ old('email', auth()->user()->email) }}" required>
                                                @error('email')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="phone" class="form-label">Phone Number</label>
                                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                                       name="phone" value="{{ old('phone', auth()->user()->phone) }}">
                                                @error('phone')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="bio" class="form-label">Bio</label>
                                                <textarea class="form-control @error('bio') is-invalid @enderror" 
                                                          name="bio" rows="4">{{ old('bio', auth()->user()->bio) }}</textarea>
                                                @error('bio')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="address" class="form-label">Address</label>
                                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                                          name="address" rows="2">{{ old('address', auth()->user()->address) }}</textarea>
                                                @error('address')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-footer mt-4">
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ROW-1 CLOSED -->
                    </div>
                    <!-- CONTAINER CLOSED -->
>>>>>>> 865d8f054825cc550d859cd9305be146439ead36
                </div>
            </div>
            <!--app-content closed-->

@push('scripts')
<script>
<<<<<<< HEAD
    // Initialize map
    function initMap() {
        const map = new google.maps.Map(document.getElementById('map'), {
            zoom: 13,
            center: { 
                lat: {{ optional($user->profile)->location_data['latitude'] ?? 0 }}, 
                lng: {{ optional($user->profile)->location_data['longitude'] ?? 0 }} 
            }
        });
=======
    // Preview image before upload
    document.getElementById('profile_photo').addEventListener('change', function(e) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-image').src = e.target.result;
        }
        reader.readAsDataURL(this.files[0]);
    });
>>>>>>> 865d8f054825cc550d859cd9305be146439ead36

    // Dismiss alerts after 5 seconds
    setTimeout(function() {
        $('.alert').alert('close');
    }, 5000);
</script>
@endpush
<<<<<<< HEAD

@push('styles')
<style>
    .card {
        transition: all 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 25px 0 rgba(0,0,0,.1);
    }
</style>
@endpush
@endsection
=======
@endsection
>>>>>>> 865d8f054825cc550d859cd9305be146439ead36
