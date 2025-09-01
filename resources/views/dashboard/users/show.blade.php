@extends('layouts.app')

@section('title', 'User Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">User Details</h3>
                    <div>
                        <a href="{{ route('dashboard.users.edit', $user->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit User
                        </a>
                        <a href="{{ route('dashboard.users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            <div class="card bg-light h-100">
                                <div class="card-body d-flex flex-column justify-content-center">
                                    @if($user->profile_image)
                                        <img src="{{ asset($user->profile_image) }}"
                                             alt="{{ $user->name }}'s Profile"
                                             class="rounded-circle img-thumbnail mb-3 shadow"
                                             style="width: 200px; height: 200px; object-fit: cover; margin: auto;">
                                    @else
                                        <div class="rounded-circle bg-gradient bg-secondary text-white d-flex align-items-center justify-content-center mx-auto mb-3 shadow"
                                             style="width: 200px; height: 200px;">
                                            <i class="fas fa-user fa-4x"></i>
                                        </div>
                                    @endif
                                    <h4 class="mb-2">{{ $user->name }}</h4>
                                    <div class="mb-2">
                                        <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'info' }} fs-6 px-3 py-2">
                                            <i class="fas fa-{{ $user->role === 'admin' ? 'crown' : 'user' }} me-1"></i>
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="badge bg-{{ $user->is_active ? 'success' : 'warning' }} fs-6 px-3 py-2">
                                            <i class="fas fa-{{ $user->is_active ? 'check-circle' : 'pause-circle' }} me-1"></i>
                                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-info-circle me-2"></i>User Information
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <tbody>
                                                <tr>
                                                    <th width="200" class="bg-light">
                                                        <i class="fas fa-user me-2"></i>Username
                                                    </th>
                                                    <td>
                                                        <code class="bg-light px-2 py-1 rounded">{{ $user->username }}</code>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light">
                                                        <i class="fas fa-id-card me-2"></i>Full Name
                                                    </th>
                                                    <td>{{ $user->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light">
                                                        <i class="fas fa-envelope me-2"></i>Email
                                                    </th>
                                                    <td>
                                                        <a href="mailto:{{ $user->email }}" class="text-decoration-none">
                                                            {{ $user->email }}
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light">
                                                        <i class="fas fa-user-tag me-2"></i>Role
                                                    </th>
                                                    <td>
                                                        <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'info' }} fs-6 px-3 py-2">
                                                            <i class="fas fa-{{ $user->role === 'admin' ? 'crown' : 'user' }} me-1"></i>
                                                            {{ ucfirst($user->role) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light">
                                                        <i class="fas fa-toggle-on me-2"></i>Status
                                                    </th>
                                                    <td>
                                                        <span class="badge bg-{{ $user->is_active ? 'success' : 'warning' }} fs-6 px-3 py-2">
                                                            <i class="fas fa-{{ $user->is_active ? 'check-circle' : 'pause-circle' }} me-1"></i>
                                                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light">
                                                        <i class="fas fa-calendar-plus me-2"></i>Created At
                                                    </th>
                                                    <td>
                                                        {{ $user->created_at->format('F d, Y h:i A') }}
                                                        <small class="text-muted">({{ $user->created_at->diffForHumans() }})</small>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light">
                                                        <i class="fas fa-calendar-edit me-2"></i>Last Updated
                                                    </th>
                                                    <td>
                                                        {{ $user->updated_at->format('F d, Y h:i A') }}
                                                        <small class="text-muted">({{ $user->updated_at->diffForHumans() }})</small>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('dashboard.users.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back to List
                        </a>
                        <div>
                            <a href="{{ route('dashboard.users.edit', $user->id) }}" class="btn btn-primary me-2">
                                <i class="fas fa-edit me-1"></i> Edit User
                            </a>
                            <button type="button"
                                    class="btn btn-danger delete-user"
                                    data-user-id="{{ $user->id }}"
                                    data-username="{{ $user->username }}">
                                <i class="fas fa-trash me-1"></i> Delete User
                            </button>
                        </div>
                    </div>
                    <form id="delete-form-{{ $user->id }}"
                          action="{{ route('dashboard.users.destroy', $user->id) }}"
                          method="POST"
                          class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/users.js') }}"></script>
@endpush 