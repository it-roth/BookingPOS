@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Edit User: {{ $user->name }}</h3>
                    <a href="{{ route('dashboard.users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>

                <form action="{{ route('dashboard.users.update', $user->id) }}" method="POST" enctype="multipart/form-data" id="user-form">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="user-id" value="{{ $user->id }}">

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

                        <!-- Profile Image Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h6 class="card-title mb-3">
                                            <i class="fas fa-image me-2"></i>Profile Image
                                        </h6>
                                        <div class="position-relative" style="width: 150px; margin: auto;">
                                            <div id="image-preview" class="mb-2">
                                                @if($user->profile_image)
                                                    <img src="{{ asset($user->profile_image) }}"
                                                         alt="Profile"
                                                         class="rounded-circle img-thumbnail shadow"
                                                         style="width: 150px; height: 150px; object-fit: cover;">
                                                @else
                                                    <div class="rounded-circle bg-gradient bg-secondary text-white d-flex align-items-center justify-content-center shadow"
                                                         style="width: 150px; height: 150px;">
                                                        <i class="fas fa-user fa-4x"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="position-absolute bottom-0 end-0">
                                                <label for="profile_image" class="btn btn-sm btn-primary rounded-circle shadow" title="Change Image" onclick="console.log('Camera button clicked')">
                                                    <i class="fas fa-camera"></i>
                                                </label>
                                                <input type="file" id="profile_image" name="profile_image" class="d-none" accept="image/*" onchange="console.log('File input changed directly:', this.files); handleImageChange(this);">
                                            </div>
                                        </div>
                                        <small class="text-muted d-block mt-2">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Optional • Max file size: 2MB • Formats: JPEG, PNG, JPG, GIF
                                        </small>
                                        <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="testImagePreview()">
                                            <i class="fas fa-test-tube"></i> Test Preview
                                        </button>
                                        @error('profile_image')
                                            <div class="text-danger small mt-2">
                                                <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Username -->
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">
                                    <i class="fas fa-user me-1"></i>Username
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-at"></i></span>
                                    <input type="text"
                                           class="form-control @error('username') is-invalid @enderror"
                                           id="username"
                                           name="username"
                                           value="{{ old('username', $user->username) }}"
                                           placeholder="Enter unique username"
                                           required>
                                </div>
                                <div class="form-text">Must be unique across the system</div>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Full Name -->
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">
                                    <i class="fas fa-id-card me-1"></i>Full Name
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user-circle"></i></span>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name', $user->name) }}"
                                           placeholder="Enter full name"
                                           required>
                                </div>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-1"></i>Email Address
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           id="email"
                                           name="email"
                                           value="{{ old('email', $user->email) }}"
                                           placeholder="Enter email address"
                                           required>
                                </div>
                                <div class="form-text">Must be a valid and unique email address</div>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Role -->
                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label">
                                    <i class="fas fa-user-tag me-1"></i>User Role
                                </label>
                                <div class="input-group dropdown-no-scroll">
                                    <span class="input-group-text"><i class="fas fa-shield-alt"></i></span>
                                    <input type="hidden" name="role" id="role" value="{{ old('role', $user->role) }}" required>
                                    <div class="form-control dropdown-trigger @error('role') is-invalid @enderror"
                                         data-dropdown="role-dropdown"
                                         style="cursor: pointer; display: flex; align-items: center; justify-content: space-between;">
                                        <span id="role-display">
                                            @if(old('role', $user->role) === 'user')
                                                <i class="fas fa-user me-2"></i>User
                                            @elseif(old('role', $user->role) === 'admin')
                                                <i class="fas fa-user-shield me-2"></i>Admin
                                            @else
                                                Select Role
                                            @endif
                                        </span>
                                        <i class="fas fa-chevron-down"></i>
                                    </div>
                                    <div class="custom-dropdown-menu" id="role-dropdown">
                                        <div class="custom-dropdown-option" data-value="" data-display="Select Role">
                                            <i class="fas fa-question-circle"></i>
                                            <span>Select Role</span>
                                        </div>
                                        <div class="custom-dropdown-option {{ old('role', $user->role) === 'user' ? 'selected' : '' }}"
                                             data-value="user" data-display="<i class='fas fa-user me-2'></i>User">
                                            <i class="fas fa-user"></i>
                                            <span>User</span>
                                        </div>
                                        <div class="custom-dropdown-option {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}"
                                             data-value="admin" data-display="<i class='fas fa-user-shield me-2'></i>Admin">
                                            <i class="fas fa-user-shield"></i>
                                            <span>Admin</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-text">Admin users have full system access</div>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-md-6 mb-3">
                                <label for="is_active" class="form-label">
                                    <i class="fas fa-toggle-on me-1"></i>Account Status
                                </label>
                                <div class="input-group dropdown-no-scroll">
                                    <span class="input-group-text"><i class="fas fa-power-off"></i></span>
                                    <input type="hidden" name="is_active" id="is_active" value="{{ old('is_active', $user->is_active) }}" required>
                                    <div class="form-control dropdown-trigger @error('is_active') is-invalid @enderror"
                                         data-dropdown="status-dropdown"
                                         style="cursor: pointer; display: flex; align-items: center; justify-content: space-between;">
                                        <span id="status-display">
                                            @if(old('is_active', $user->is_active) == 1)
                                                <i class="fas fa-check-circle text-success me-2"></i>Active - User can login
                                            @else
                                                <i class="fas fa-times-circle text-danger me-2"></i>Inactive - User cannot login
                                            @endif
                                        </span>
                                        <i class="fas fa-chevron-down"></i>
                                    </div>
                                    <div class="custom-dropdown-menu" id="status-dropdown">
                                        <div class="custom-dropdown-option {{ old('is_active', $user->is_active) == 1 ? 'selected' : '' }}"
                                             data-value="1" data-display="<i class='fas fa-check-circle text-success me-2'></i>Active - User can login">
                                            <i class="fas fa-check-circle text-success"></i>
                                            <span>Active - User can login</span>
                                        </div>
                                        <div class="custom-dropdown-option {{ old('is_active', $user->is_active) == 0 ? 'selected' : '' }}"
                                             data-value="0" data-display="<i class='fas fa-times-circle text-danger me-2'></i>Inactive - User cannot login">
                                            <i class="fas fa-times-circle text-danger"></i>
                                            <span>Inactive - User cannot login</span>
                                        </div>
                                    </div>
                                </div>
                                @error('is_active')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Change Password Section -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card border-warning">
                                    <div class="card-header bg-warning bg-opacity-10">
                                        <h6 class="card-title mb-0">
                                            <i class="fas fa-key me-2"></i>Change Password
                                        </h6>
                                        <small class="text-muted">Leave blank to keep current password</small>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="password" class="form-label">
                                                    <i class="fas fa-lock me-1"></i>New Password
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                    <input type="password"
                                                           class="form-control @error('password') is-invalid @enderror"
                                                           id="password"
                                                           name="password"
                                                           placeholder="Enter new password">
                                                    <button class="btn btn-outline-secondary" type="button" id="password-toggle" onclick="togglePasswordVisibility('password', this)">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                                <div class="form-text">Minimum 6 characters required</div>
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="password_confirmation" class="form-label">
                                                    <i class="fas fa-lock me-1"></i>Confirm New Password
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-check"></i></span>
                                                    <input type="password"
                                                           class="form-control"
                                                           id="password_confirmation"
                                                           name="password_confirmation"
                                                           placeholder="Confirm new password">
                                                    <button class="btn btn-outline-secondary" type="button" id="password-confirmation-toggle" onclick="togglePasswordVisibility('password_confirmation', this)">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                                <div class="form-text">Must match the password above</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('dashboard.users.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <div>
                                <a href="{{ route('dashboard.users.show', $user->id) }}" class="btn btn-outline-info me-2">
                                    <i class="fas fa-eye me-1"></i> View Details
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save me-2"></i> Save Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Custom dropdown styles - no scroll, show all options */
.dropdown-no-scroll {
    position: relative;
}

.dropdown-no-scroll .form-select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m1 6 7 7 7-7'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 16px 12px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.dropdown-no-scroll .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    outline: 0;
}

.dropdown-no-scroll .form-select:hover {
    border-color: #86b7fe;
    background-color: #f8f9fa;
}

/* Custom dropdown menu */
.custom-dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    z-index: 1000;
    display: none;
    background-color: #fff;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    max-height: none !important; /* Remove height restriction */
    overflow: visible !important; /* Remove scroll */
    margin-top: 2px;
}

.custom-dropdown-menu.show {
    display: block;
}

.custom-dropdown-option {
    padding: 0.5rem 0.75rem;
    cursor: pointer;
    border-bottom: 1px solid #f8f9fa;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.custom-dropdown-option:last-child {
    border-bottom: none;
}

.custom-dropdown-option:hover {
    background-color: #f8f9fa;
    color: #0d6efd;
}

.custom-dropdown-option.selected {
    background-color: #e7f3ff;
    color: #0d6efd;
    font-weight: 500;
}

.custom-dropdown-option i {
    width: 16px;
    text-align: center;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .custom-dropdown-menu {
        position: fixed;
        left: 1rem;
        right: 1rem;
        top: auto;
        bottom: 1rem;
        border-radius: 0.5rem;
        max-height: 50vh;
        overflow-y: auto;
    }
}
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/users.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize custom dropdowns
    initializeCustomDropdowns();

    function initializeCustomDropdowns() {
        const dropdownTriggers = document.querySelectorAll('.dropdown-trigger');

        dropdownTriggers.forEach(trigger => {
            const dropdownId = trigger.getAttribute('data-dropdown');
            const dropdown = document.getElementById(dropdownId);
            const hiddenInput = trigger.parentElement.querySelector('input[type="hidden"]');
            const displayElement = trigger.querySelector('span');

            if (!dropdown || !hiddenInput || !displayElement) return;

            // Toggle dropdown on click
            trigger.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                // Close other dropdowns
                document.querySelectorAll('.custom-dropdown-menu.show').forEach(menu => {
                    if (menu !== dropdown) {
                        menu.classList.remove('show');
                    }
                });

                // Toggle current dropdown
                dropdown.classList.toggle('show');
            });

            // Handle option selection
            dropdown.querySelectorAll('.custom-dropdown-option').forEach(option => {
                option.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const value = this.getAttribute('data-value');
                    const displayHTML = this.getAttribute('data-display');

                    // Update hidden input
                    hiddenInput.value = value;

                    // Update display
                    displayElement.innerHTML = displayHTML;

                    // Update selected state
                    dropdown.querySelectorAll('.custom-dropdown-option').forEach(opt => {
                        opt.classList.remove('selected');
                    });
                    this.classList.add('selected');

                    // Close dropdown
                    dropdown.classList.remove('show');

                    // Remove validation error styling if present
                    trigger.classList.remove('is-invalid');
                });
            });
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown-no-scroll')) {
                document.querySelectorAll('.custom-dropdown-menu.show').forEach(menu => {
                    menu.classList.remove('show');
                });
            }
        });

        // Close dropdowns on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.custom-dropdown-menu.show').forEach(menu => {
                    menu.classList.remove('show');
                });
            }
        });
    }

    function previewImage(input) {
        const preview = document.getElementById('image-preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Replace the entire content with the new image
                preview.innerHTML = `<img src="${e.target.result}"
                                         alt="Profile Preview"
                                         class="rounded-circle img-thumbnail shadow"
                                         style="width: 150px; height: 150px; object-fit: cover;">`;
                console.log('Image preview updated successfully');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Add event listener to profile image input
    const profileImageInput = document.getElementById('profile_image');
    console.log('Profile image input found:', profileImageInput);

    if (profileImageInput) {
        profileImageInput.addEventListener('change', function() {
            console.log('File input changed, files:', this.files);
            previewImage(this);
        });
        console.log('Event listener added to profile image input');
    } else {
        console.error('Profile image input not found!');
    }

    // Simple and reliable password toggle functionality
    setupPasswordToggle('password-toggle', 'password');
    setupPasswordToggle('password-confirmation-toggle', 'password_confirmation');

    function setupPasswordToggle(buttonId, inputId) {
        const button = document.getElementById(buttonId);
        const input = document.getElementById(inputId);

        console.log(`Setting up toggle for ${buttonId}:`, button);
        console.log(`Input field ${inputId}:`, input);

        if (button && input) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                console.log(`${buttonId} clicked! Current type:`, input.type);

                const icon = this.querySelector('i');

                if (input.type === 'password') {
                    input.type = 'text';
                    if (icon) {
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    }
                    console.log(`${inputId} password shown`);
                } else {
                    input.type = 'password';
                    if (icon) {
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                    console.log(`${inputId} password hidden`);
                }
            });
            console.log(`Event listener added for ${buttonId}`);
        } else {
            console.error(`Button ${buttonId} or input ${inputId} not found!`);
        }
    }
});

// Global function for password visibility toggle (works with onclick)
function togglePasswordVisibility(inputId, button) {
    console.log('togglePasswordVisibility called for:', inputId);

    const input = document.getElementById(inputId);
    const icon = button.querySelector('i');

    console.log('Input found:', input);
    console.log('Icon found:', icon);
    console.log('Current type:', input ? input.type : 'input not found');

    if (input && icon) {
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
            console.log('Password shown for:', inputId);
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
            console.log('Password hidden for:', inputId);
        }
    } else {
        console.error('Input or icon not found for:', inputId);
    }
}

// Legacy function for backward compatibility
function togglePassword(fieldId) {
    console.log('togglePassword called for field:', fieldId);
    const passwordInput = document.getElementById(fieldId);
    const toggleButton = passwordInput.nextElementSibling; // Get the button next to the input
    const icon = toggleButton.querySelector('i');

    console.log('Password input found:', passwordInput);
    console.log('Toggle button found:', toggleButton);
    console.log('Icon found:', icon);

    if (passwordInput && icon) {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
            console.log('Password shown');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
            console.log('Password hidden');
        }
    } else {
        console.error('Password input or icon not found for field:', fieldId);
    }
}

// Global test function (outside DOMContentLoaded so it's accessible from onclick)
function testImagePreview() {
    console.log('Testing image preview...');
    const preview = document.getElementById('image-preview');
    console.log('Preview element:', preview);
    console.log('Current preview content:', preview.innerHTML);

    if (preview) {
        // Clear existing content first
        preview.innerHTML = '';

        // Create test image element
        const testImg = document.createElement('img');
        testImg.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTUwIiBoZWlnaHQ9IjE1MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZmY2NjAwIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtc2l6ZT0iMTgiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIiBmaWxsPSJ3aGl0ZSI+VEVTVDWVNUPC90ZXh0Pjwvc3ZnPg==';
        testImg.alt = 'Test Preview';
        testImg.className = 'rounded-circle img-thumbnail shadow';
        testImg.style.cssText = 'width: 150px; height: 150px; object-fit: cover;';

        preview.appendChild(testImg);

        // Force repaint
        preview.style.display = 'none';
        preview.offsetHeight;
        preview.style.display = 'block';

        console.log('Test image added:', preview.innerHTML);
        alert('Preview updated with test image. Check if it changed!');
    } else {
        alert('Preview element not found!');
        console.error('Preview element not found!');
    }
}

// Global function to trigger file input (for the camera button)
function triggerFileInput() {
    document.getElementById('profile_image').click();
}

// Direct handler for file input change (as backup)
function handleImageChange(input) {
    console.log('handleImageChange called with:', input);
    console.log('Files:', input.files);

    const preview = document.getElementById('image-preview');
    console.log('Preview element found:', preview);
    console.log('Current preview HTML before change:', preview.innerHTML);

    if (input.files && input.files[0] && preview) {
        const reader = new FileReader();
        reader.onload = function(e) {
            console.log('FileReader loaded, updating preview...');
            console.log('New image data URL length:', e.target.result.length);

            // Clear existing content first
            preview.innerHTML = '';

            // Add new image
            const newImg = document.createElement('img');
            newImg.src = e.target.result;
            newImg.alt = 'Profile Preview';
            newImg.className = 'rounded-circle img-thumbnail shadow';
            newImg.style.cssText = 'width: 150px; height: 150px; object-fit: cover;';

            preview.appendChild(newImg);

            console.log('Preview updated successfully!');
            console.log('New preview HTML:', preview.innerHTML);

            // Force a repaint
            preview.style.display = 'none';
            preview.offsetHeight; // Trigger reflow
            preview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        console.error('Missing files or preview element:', {
            files: input.files,
            preview: preview
        });
    }
}
</script>
@endpush