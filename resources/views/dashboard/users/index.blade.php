@extends('layouts.app')

@section('title', 'Users')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Users</h3>
                    <div>
                        <a href="{{ route('dashboard.users.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New User
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Search and Filter Section -->
                    <div class="search-filter-section fade-in">
                        <div class="row">
                            <div class="col-md-12">
                                <h6 class="mb-3">
                                    <i class="fas fa-search me-2"></i>Search & Filter Users
                                </h6>
                                <form method="GET" action="{{ route('dashboard.users.index') }}" class="row g-3">
                                <div class="col-md-4">
                                    <label for="search" class="form-label">Search Users</label>
                                    <input type="text"
                                           class="form-control"
                                           id="search"
                                           name="search"
                                           value="{{ request('search') }}"
                                           placeholder="Search by username, name, or email...">
                                </div>
                                <div class="col-md-3">
                                    <label for="role" class="form-label">Filter by Role</label>
                                    <select class="form-select" id="role" name="role">
                                        <option value="">All Roles</option>
                                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="status" class="form-label">Filter by Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="">All Status</option>
                                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fas fa-search"></i> Search
                                    </button>
                                    <a href="{{ route('dashboard.users.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i> Clear
                                    </a>
                                </div>
                            </form>
                            </div>
                        </div>
                    </div>

                    <!-- Results Summary -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <span class="text-muted">
                                Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }}
                                of {{ $users->total() }} users
                            </span>
                        </div>
                        @if(request()->hasAny(['search', 'role', 'status']))
                            <div>
                                <span class="badge bg-info">
                                    <i class="fas fa-filter"></i> Filtered Results
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Bulk Actions -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div id="bulk-actions" class="d-none">
                            <button type="button" class="btn btn-outline-warning btn-sm" onclick="bulkToggleStatus()">
                                <i class="fas fa-toggle-on me-1"></i> Toggle Status
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="bulkDelete()">
                                <i class="fas fa-trash me-1"></i> Delete Selected
                            </button>
                            <span class="text-muted ms-2" id="selected-count">0 selected</span>
                        </div>
                        <div class="ms-auto">
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="selectAll()">
                                <i class="fas fa-check-square me-1"></i> Select All
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th width="50" class="text-center">
                                        <input type="checkbox" id="select-all-checkbox" class="form-check-input">
                                    </th>
                                    <th width="80" class="text-center">Avatar</th>
                                    <th>Username</th>
                                    <th>Name</th>
                                    <th class="d-none d-md-table-cell">Email</th>
                                    <th class="text-center">Role</th>
                                    <th class="text-center">Status</th>
                                    <th width="200" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr class="align-middle slide-in">
                                        <td class="text-center">
                                            <input type="checkbox" class="form-check-input user-checkbox" value="{{ $user->id }}">
                                        </td>
                                        <td class="text-center">
                                            <div class="profile-image-container">
                                                @if($user->profile_image)
                                                    <img src="{{ asset($user->profile_image) }}"
                                                         alt="Profile"
                                                         class="user-avatar rounded-circle border border-2 border-light shadow-sm"
                                                         width="45"
                                                         height="45"
                                                         style="object-fit: cover;">
                                                @else
                                                    <div class="user-avatar rounded-circle bg-gradient bg-secondary text-white d-flex align-items-center justify-content-center shadow-sm"
                                                         style="width: 45px; height: 45px;">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <strong>{{ $user->username }}</strong>
                                            <br>
                                            <small class="text-muted">ID: {{ $user->id }}</small>
                                        </td>
                                        <td>
                                            <div>{{ $user->name }}</div>
                                            <small class="text-muted">
                                                Created: {{ $user->created_at->format('M d, Y') }}
                                            </small>
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                            <div>{{ $user->email }}</div>
                                            <small class="text-muted">
                                                Updated: {{ $user->updated_at->format('M d, Y') }}
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'info' }} fs-6 px-3 py-2">
                                                <i class="fas fa-{{ $user->role === 'admin' ? 'crown' : 'user' }} me-1"></i>
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $user->is_active ? 'success' : 'warning' }} fs-6 px-3 py-2">
                                                <i class="fas fa-{{ $user->is_active ? 'check-circle' : 'pause-circle' }} me-1"></i>
                                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('dashboard.users.show', $user->id) }}"
                                                   class="btn btn-outline-info btn-sm"
                                                   title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('dashboard.users.edit', $user->id) }}"
                                                   class="btn btn-outline-primary btn-sm"
                                                   title="Edit User">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button"
                                                        class="btn btn-outline-danger btn-sm delete-user"
                                                        data-user-id="{{ $user->id }}"
                                                        data-username="{{ $user->username }}"
                                                        title="Delete User"
                                                        onclick="console.log('Direct onclick triggered for user {{ $user->id }}'); if(confirm('Delete {{ $user->username }}?')) { document.getElementById('delete-form-{{ $user->id }}').submit(); }">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                            <form id="delete-form-{{ $user->id }}"
                                                  action="{{ route('dashboard.users.destroy', $user->id) }}"
                                                  method="POST"
                                                  class="d-none">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fas fa-users fa-3x mb-3"></i>
                                                <h5>No users found</h5>
                                                <p>{{ request()->hasAny(['search', 'role', 'status']) ? 'Try adjusting your search criteria.' : 'Start by creating your first user.' }}</p>
                                                @if(!request()->hasAny(['search', 'role', 'status']))
                                                    <a href="{{ route('dashboard.users.create') }}" class="btn btn-primary">
                                                        <i class="fas fa-plus"></i> Create First User
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($users->hasPages())
                        <div class="mt-4">
                            {{ $users->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/users.js') }}"></script>
<!-- <script>
// Additional debugging and fallback
document.addEventListener('DOMContentLoaded', function() {
    console.log('Inline script loaded');
    console.log('SweetAlert2 available:', typeof Swal !== 'undefined');

    // Fallback event listener if the main one doesn't work
    setTimeout(function() {
        const deleteButtons = document.querySelectorAll('.delete-user');
        console.log('Fallback check - Delete buttons found:', deleteButtons.length);

        deleteButtons.forEach(function(button, index) {
            console.log(`Button ${index}:`, button);

            // Remove any existing listeners and add a new one
            button.removeEventListener('click', handleDeleteClick);
            button.addEventListener('click', handleDeleteClick);
        });
    }, 1000);

    function handleDeleteClick(e) {
        e.preventDefault();
        console.log('Delete button clicked!', this);

        const userId = this.dataset.userId;
        const username = this.dataset.username;

        console.log('User ID:', userId, 'Username:', username);

        if (confirm(`Are you sure you want to delete user "${username}"?`)) {
            const form = document.getElementById(`delete-form-${userId}`);
            if (form) {
                console.log('Submitting form:', form);
                form.submit();
            } else {
                console.error('Form not found!');
                alert('Error: Could not find delete form');
            }
        }
    }
});
</script> -->
@endpush