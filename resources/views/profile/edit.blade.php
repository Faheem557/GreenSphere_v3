@extends('layouts.main')

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
                </div>
            </div>
            <!--app-content closed-->

@push('scripts')
<script>
    // Preview image before upload
    document.getElementById('profile_photo').addEventListener('change', function(e) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-image').src = e.target.result;
        }
        reader.readAsDataURL(this.files[0]);
    });

    // Dismiss alerts after 5 seconds
    setTimeout(function() {
        $('.alert').alert('close');
    }, 5000);
</script>
@endpush
@endsection