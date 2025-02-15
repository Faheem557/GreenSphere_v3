<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __('Delete Account') }}</h3>
    </div>
    <div class="card-body">
        <div class="text-muted mb-4">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </div>

        <button class="btn btn-danger" 
                data-bs-toggle="modal" 
                data-bs-target="#deleteAccountModal">
            {{ __('Delete Account') }}
        </button>

        <!-- Delete Account Modal -->
        <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" action="{{ route('profile.destroy') }}">
                        @csrf
                        @method('delete')
                        
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteAccountModalLabel">
                                {{ __('Are you sure you want to delete your account?') }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <p class="text-muted">
                                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                            </p>

                            <div class="form-group">
                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                <input type="password" 
                                       class="form-control @error('password', 'userDeletion') is-invalid @enderror" 
                                       id="password"
                                       name="password"
                                       placeholder="{{ __('Password') }}"
                                       required>
                                
                                @error('password', 'userDeletion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                {{ __('Cancel') }}
                            </button>
                            <button type="submit" class="btn btn-danger">
                                {{ __('Delete Account') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
