@extends('layouts.main')
@section('title')
    Edit Plant
@endsection

@section('maincontent')
    <div class="main-container container-fluid">
        <div class="page-header">
            <h1 class="page-title">Edit Plant</h1>
            <div>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('seller.inventory') }}">Inventory</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Plant</li>
                </ol>
            </div>
        </div>

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

                        <form action="{{ route('seller.plants.update', $plant->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Plant Name</label>
                                        <input type="text" class="form-control" name="name" value="{{ old('name', $plant->name) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Category</label>
                                        <select name="category" class="form-control" required>
                                            <option value="indoor" {{ $plant->category === 'indoor' ? 'selected' : '' }}>Indoor</option>
                                            <option value="outdoor" {{ $plant->category === 'outdoor' ? 'selected' : '' }}>Outdoor</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Price</label>
                                        <input type="number" class="form-control" name="price" value="{{ old('price', $plant->price) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Stock Quantity</label>
                                        <input type="number" class="form-control" name="quantity" value="{{ old('quantity', $plant->quantity) }}" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" name="description" rows="4">{{ old('description', $plant->description) }}</textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label">Current Image</label>
                                        @if($plant->image)
                                            <div class="mb-2">
                                                <img src="{{ Storage::url($plant->image) }}" 
                                                     alt="{{ $plant->name }}" 
                                                     class="img-fluid" 
                                                     style="max-height: 200px;">
                                            </div>
                                        @endif
                                        <label class="form-label">Update Image</label>
                                        <input type="file" 
                                               class="form-control @error('image') is-invalid @enderror" 
                                               name="image" 
                                               accept="image/*">
                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">
                                            Leave empty to keep current image. Accepted formats: JPEG, PNG, JPG, GIF. Maximum size: 2MB
                                        </small>
                                    </div>
                                    <div class="mt-2" id="imagePreview" style="display: none;">
                                        <img src="" alt="Preview" class="img-fluid" style="max-height: 200px;">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <button type="submit" class="btn btn-primary">Update Plant</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Add form submission handling
        document.querySelector('form').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...';
        });

        // Image preview functionality
        document.querySelector('input[name="image"]').addEventListener('change', function(e) {
            const preview = document.getElementById('imagePreview');
            const file = e.target.files[0];
            
            if (file) {
                // Validate file size
                if (file.size > 2 * 1024 * 1024) { // 2MB
                    alert('File size must be less than 2MB');
                    this.value = '';
                    return;
                }
                
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
    </script>
    @endpush
@endsection 