@extends('layouts.main')
@section('title')
    Inventory Management
@endsection

@section('maincontent')
    <!-- CONTAINER -->
    <div class="main-container container-fluid">
        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">Inventory Management</h1>
            <div>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Inventory</li>
                </ol>
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- ROW -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">My Plants</h3>
                        <div class="card-options">
                            <a href="{{ route('seller.plants.add') }}" class="btn btn-primary">Add New Plant</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="inventory-table" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Plant Name</th>
                                        <th>Stock</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($plants as $plant)
                                    <tr>
                                        <td>{{ $plant->name }}</td>
                                        <td>
                                            <input type="number" 
                                                   class="form-control stock-input" 
                                                   value="{{ $plant->quantity }}" 
                                                   min="0" 
                                                   data-plant-id="{{ $plant->id }}"
                                                   data-original-value="{{ $plant->quantity }}">
                                            <span class="badge {{ $plant->quantity > 0 ? 'badge-success' : 'badge-danger' }} stock-status">
                                                {{ $plant->quantity > 0 ? 'In Stock' : 'Out of Stock' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input type="checkbox" 
                                                       class="form-check-input status-toggle" 
                                                       {{ $plant->is_active ? 'checked' : '' }}
                                                       data-plant-id="{{ $plant->id }}"
                                                       id="status_{{ $plant->id }}">
                                                <label class="form-check-label" for="status_{{ $plant->id }}">
                                                    {{ $plant->is_active ? 'Active' : 'Inactive' }}
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('seller.plants.edit', $plant->id) }}" 
                                               class="btn btn-sm btn-primary">
                                                Edit
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this plant? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Toggle Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Confirm Status Change</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to change the status of this plant?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmStatus">Update Status</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof toastr !== 'undefined') {
                toastr.success('Inventory page loaded successfully', 'Welcome');
            } else {
                console.error('Toastr is not loaded');
            }
        });
    </script>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    console.log('Inventory script loaded'); // Debug line

    // Initialize toastr options
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "3000"
    };

    // Setup CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Initialize DataTable with debug
    try {
        $('#inventory-table').DataTable();
        console.log('DataTable initialized');
    } catch (e) {
        console.error('DataTable initialization error:', e);
    }

    // Helper function for notifications
    function showNotification(type, message, title = '') {
        if (typeof toastr === 'undefined') {
            console.error('Toastr is not loaded');
            return;
        }
        
        switch(type) {
            case 'success':
                toastr.success(message, title);
                break;
            case 'error':
                toastr.error(message, title);
                break;
            case 'info':
                toastr.info(message, title);
                break;
            case 'warning':
                toastr.warning(message, title);
                break;
        }
    }

    // Stock update handler
    $(document).on('change', '.stock-input', function() {
        const input = $(this);
        const plantId = input.data('plant-id');
        const newQuantity = input.val();
        const originalValue = input.data('original-value');

        showNotification('info', 'Updating stock...', 'Please wait');

        $.ajax({
            url: "{{ route('seller.plants.update-stock', ':id') }}".replace(':id', plantId),
            method: 'POST',
            data: {
                quantity: newQuantity,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    showNotification('success', 'Stock updated successfully');
                    updateStockStatus(input, newQuantity);
                } else {
                    showNotification('error', response.message || 'Failed to update stock');
                    input.val(originalValue);
                }
            },
            error: function(xhr) {
                showNotification('error', xhr.responseJSON?.message || 'Error updating stock');
                input.val(originalValue);
            }
        });
    });

    // Status toggle handler
    $(document).on('change', '.status-toggle', function() {
        const checkbox = $(this);
        const plantId = checkbox.data('plant-id');
        const isActive = checkbox.prop('checked');

        toastr.info('Updating status...'); // Loading notification

        $.ajax({
            url: "{{ route('seller.plants.toggle-status', ':id') }}".replace(':id', plantId),
            method: 'POST',
            data: {
                is_active: isActive,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(`Plant ${isActive ? 'activated' : 'deactivated'} successfully`);
                } else {
                    toastr.error(response.message || 'Failed to update status');
                    checkbox.prop('checked', !isActive);
                }
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Error updating status');
                checkbox.prop('checked', !isActive);
            }
        });
    });

    // Helper function to update stock status
    function updateStockStatus(input, quantity) {
        const row = input.closest('tr');
        const statusBadge = row.find('.stock-status');
        
        if (parseInt(quantity) <= 0) {
            statusBadge.removeClass('badge-success').addClass('badge-danger').text('Out of Stock');
        } else {
            statusBadge.removeClass('badge-danger').addClass('badge-success').text('In Stock');
        }
    }
});
</script>
@endpush