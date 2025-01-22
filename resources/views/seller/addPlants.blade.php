@extends('layouts.main')
@section('title')
    Add Plants
@endsection

@section('maincontent')
    <!-- CONTAINER -->
    <div class="main-container container-fluid">
        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">Add New Plant</h1>
            <div>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add Plant</li>
                </ol>
            </div>
        </div>

        <!-- ROW -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Plant Details</h3>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('seller.plants.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Plant Name</label>
                                        <input type="text" class="form-control" name="name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Category</label>
                                        <select name="category" class="form-control" required>
                                            <option value="indoor">Indoor</option>
                                            <option value="outdoor">Outdoor</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Price</label>
                                        <input type="number" class="form-control" name="price" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Stock Quantity</label>
                                        <input type="number" class="form-control" name="quantity" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" name="description" rows="4"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label">Plant Image <span class="text-danger">*</span></label>
                                        <input type="file" 
                                               class="form-control @error('image') is-invalid @enderror" 
                                               name="image" 
                                               accept="image/*"
                                               required>
                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">
                                            Accepted formats: JPEG, PNG, JPG, GIF. Maximum size: 2MB
                                        </small>
                                    </div>
                                    <div class="mt-2" id="imagePreview" style="display: none;">
                                        <img src="" alt="Preview" class="img-fluid" style="max-height: 200px;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Soil Type</label>
                                        <select name="soil_type" class="form-control">
                                            @foreach(App\Models\Plant::SOIL_TYPES as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Temperature Range</label>
                                        <input type="text" class="form-control" name="temperature_range" 
                                               placeholder="e.g., 18-24°C">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Humidity Requirements</label>
                                        <input type="text" class="form-control" name="humidity_requirements" 
                                               placeholder="e.g., 40-60%">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Fertilizer Needs</label>
                                        <input type="text" class="form-control" name="fertilizer_needs" 
                                               placeholder="e.g., Monthly during growing season">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Blooming Season</label>
                                        <input type="text" class="form-control" name="blooming_season" 
                                               placeholder="e.g., Spring-Summer">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Mature Height</label>
                                        <input type="text" class="form-control" name="mature_height" 
                                               placeholder="e.g., 30-40 cm">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Growth Rate</label>
                                        <select name="growth_rate" class="form-control">
                                            @foreach(App\Models\Plant::GROWTH_RATES as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Maintenance Level</label>
                                        <select name="maintenance_level" class="form-control">
                                            @foreach(App\Models\Plant::MAINTENANCE_LEVELS as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Pet Friendly</label>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" name="pet_friendly" id="pet_friendly">
                                            <label class="custom-control-label" for="pet_friendly">Safe for pets</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Propagation Method</label>
                                        <input type="text" class="form-control" name="propagation_method" 
                                               placeholder="e.g., Stem cuttings, Division">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <button type="submit" class="btn btn-primary">Add Plant</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Plant Care Information Section -->
    <div class="card mt-4">
        <div class="card-header">
            <h4 class="card-title">Plant Care Information</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Basic Care Requirements -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Care Level</label>
                        <select name="care_level" class="form-control" required>
                            @foreach(App\Models\Plant::CARE_LEVELS as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Water Needs</label>
                        <select name="water_needs" class="form-control" required>
                            @foreach(App\Models\Plant::WATER_NEEDS as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Light Requirements</label>
                        <select name="light_needs" class="form-control" required>
                            @foreach(App\Models\Plant::LIGHT_NEEDS as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Detailed Care Instructions -->
                <div class="col-12">
                    <h5 class="mt-4 mb-3">Detailed Care Instructions</h5>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Watering Instructions</label>
                        <textarea class="form-control" name="care_instructions[watering]" rows="3" 
                                  placeholder="Detailed watering instructions..."></textarea>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Sunlight Requirements</label>
                        <textarea class="form-control" name="care_instructions[sunlight]" rows="3" 
                                  placeholder="Detailed sunlight requirements..."></textarea>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Temperature Care</label>
                        <textarea class="form-control" name="care_instructions[temperature]" rows="3" 
                                  placeholder="Temperature requirements and care..."></textarea>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Soil Care</label>
                        <textarea class="form-control" name="care_instructions[soil]" rows="3" 
                                  placeholder="Soil maintenance and care..."></textarea>
                    </div>
                </div>

                <!-- Maintenance Schedule -->
                <div class="col-12">
                    <h5 class="mt-4 mb-3">Maintenance Schedule</h5>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Watering Schedule</label>
                        <select name="maintenance[watering_schedule][frequency]" class="form-control">
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Watering Amount</label>
                        <select name="maintenance[watering_schedule][amount]" class="form-control">
                            <option value="light">Light</option>
                            <option value="moderate">Moderate</option>
                            <option value="heavy">Heavy</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Fertilizing Schedule</label>
                        <input type="text" class="form-control" name="maintenance[fertilizing_schedule][frequency]" 
                               placeholder="e.g., Every 4 weeks">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Fertilizer Type</label>
                        <select name="maintenance[fertilizing_schedule][type]" class="form-control">
                            <option value="NPK">NPK</option>
                            <option value="Organic">Organic</option>
                            <option value="Liquid">Liquid</option>
                        </select>
                    </div>
                </div>

                <!-- Common Problems -->
                <div class="col-12">
                    <h5 class="mt-4 mb-3">Common Problems</h5>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Common Pests</label>
                        <select name="care_guide[common_problems][pest_issues][]" class="form-control" multiple>
                            <option value="Spider mites">Spider mites</option>
                            <option value="Mealybugs">Mealybugs</option>
                            <option value="Scale insects">Scale insects</option>
                            <option value="Fungus gnats">Fungus gnats</option>
                        </select>
                        <small class="form-text text-muted">Hold Ctrl/Cmd to select multiple</small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Common Diseases</label>
                        <select name="care_guide[common_problems][diseases][]" class="form-control" multiple>
                            <option value="Root rot">Root rot</option>
                            <option value="Leaf spot">Leaf spot</option>
                            <option value="Powdery mildew">Powdery mildew</option>
                        </select>
                        <small class="form-text text-muted">Hold Ctrl/Cmd to select multiple</small>
                    </div>
                </div>

                <!-- Seasonal Care -->
                <div class="col-12">
                    <h5 class="mt-4 mb-3">Seasonal Care</h5>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Spring Care</label>
                        <textarea class="form-control" name="care_guide[seasonal_care][spring]" rows="3" 
                                  placeholder="Spring care instructions..."></textarea>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Summer Care</label>
                        <textarea class="form-control" name="care_guide[seasonal_care][summer]" rows="3" 
                                  placeholder="Summer care instructions..."></textarea>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Fall Care</label>
                        <textarea class="form-control" name="care_guide[seasonal_care][fall]" rows="3" 
                                  placeholder="Fall care instructions..."></textarea>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Winter Care</label>
                        <textarea class="form-control" name="care_guide[seasonal_care][winter]" rows="3" 
                                  placeholder="Winter care instructions..."></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Image preview functionality
        document.querySelector('input[name="image"]').addEventListener('change', function(e) {
            const preview = document.getElementById('imagePreview');
            const file = e.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.querySelector('img').src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        });

        $(document).ready(function() {
            // Initialize select2 for multiple select boxes
            $('select[multiple]').select2({
                placeholder: "Select options",
                allowClear: true,
                theme: "bootstrap-5"
            });

            // Add validation for required fields
            $('form').on('submit', function(e) {
                let valid = true;
                
                // Check required fields
                $(this).find('[required]').each(function() {
                    if (!$(this).val()) {
                        valid = false;
                        $(this).addClass('is-invalid');
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });

                if (!valid) {
                    e.preventDefault();
                    showNotification('error', 'Please fill in all required fields');
                }
            });
        });
    </script>
    @endpush
@endsection
