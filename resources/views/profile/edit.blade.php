@extends('layouts.app')

@section('content')
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
                                <option value="beginner" {{ $user->profile->gardening_level === 'beginner' ? 'selected' : '' }}>Beginner</option>
                                <option value="intermediate" {{ $user->profile->gardening_level === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                <option value="advanced" {{ $user->profile->gardening_level === 'advanced' ? 'selected' : '' }}>Advanced</option>
                            </select>
                        </div>

                        <!-- Plant Preferences -->
                        <div class="mb-3">
                            <label class="form-label">Plant Preferences</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="plant_preferences[]" value="indoor" 
                                            {{ in_array('indoor', $user->profile->plant_preferences ?? []) ? 'checked' : '' }}>
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
                            <input type="hidden" name="location[latitude]" id="latitude">
                            <input type="hidden" name="location[longitude]" id="longitude">
                            <input type="text" class="form-control mb-2" name="location[address]" placeholder="Address" 
                                value="{{ $user->profile->location_data['address'] ?? '' }}">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="location[city]" placeholder="City" 
                                        value="{{ $user->profile->location_data['city'] ?? '' }}">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="location[state]" placeholder="State" 
                                        value="{{ $user->profile->location_data['state'] ?? '' }}">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="location[zip]" placeholder="ZIP" 
                                        value="{{ $user->profile->location_data['zip'] ?? '' }}">
                                </div>
                            </div>
                        </div>

                        <!-- Notification Preferences -->
                        <div class="mb-3">
                            <label class="form-label">Notification Preferences</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="notification_preferences[email]" 
                                    {{ ($user->profile->notification_preferences['email'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label">Email Notifications</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="notification_preferences[push]" 
                                    {{ ($user->profile->notification_preferences['push'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label">Push Notifications</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=places"></script>
<script>
    // Initialize map
    function initMap() {
        const map = new google.maps.Map(document.getElementById('map'), {
            zoom: 13,
            center: { 
                lat: {{ $user->profile->location_data['latitude'] ?? 0 }}, 
                lng: {{ $user->profile->location_data['longitude'] ?? 0 }} 
            }
        });

        const marker = new google.maps.Marker({
            map: map,
            draggable: true
        });

        // Update coordinates when marker is dragged
        marker.addListener('dragend', function() {
            const position = marker.getPosition();
            document.getElementById('latitude').value = position.lat();
            document.getElementById('longitude').value = position.lng();
        });

        // Initialize Places Autocomplete
        const input = document.querySelector('input[name="location[address]"]');
        const autocomplete = new google.maps.places.Autocomplete(input);

        autocomplete.addListener('place_changed', function() {
            const place = autocomplete.getPlace();
            if (place.geometry) {
                map.setCenter(place.geometry.location);
                marker.setPosition(place.geometry.location);
                
                document.getElementById('latitude').value = place.geometry.location.lat();
                document.getElementById('longitude').value = place.geometry.location.lng();
            }
        });
    }

    // Load map when page is ready
    document.addEventListener('DOMContentLoaded', initMap);
</script>
@endpush
@endsection
