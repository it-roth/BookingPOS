@extends('layouts.app')

@section('title', 'Admin Profile')

@section('content')
<div class="container d-flex justify-content-center mt-5">
    <div class="card profile-card p-4 text-center">
        <div class="profile-header position-relative">
            <div class="profile-image-wrapper mx-auto">
                @if($admin->profile_image)
                    <img src="{{ asset($admin->profile_image) }}" class="profile-image" alt="Profile Image">
                @else
                    <div class="default-profile-image">
                        <i class="fas fa-user"></i>
                    </div>
                @endif
            
                <label for="profile-image-input" class="change-photo-overlay">
                    <i class="fas fa-camera"></i>
                </label>
            
                <form action="{{ route('admin.profile.update.image') }}" method="POST" enctype="multipart/form-data" class="d-none">
                    @csrf
                    <input type="file" name="profile_image" id="profile-image-input" onchange="this.form.submit()">
                </form>
            </div>
            
            
        </div>

        <div class="card-body">
            <h4 class="text-white mb-1">{{ $admin->name }}</h4>

            <div class="profile-info text-start mx-auto" style="max-width: 300px;">
                <div class="info-item mb-3">
                    <i class="fas fa-envelope me-2 text-primary"></i>
                    <span>{{ $admin->email }}</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-user me-2 text-info"></i>
                    <span>{{ $admin->username }}</span>
                </div>
            </div>

            <a href="{{ route('admin.profile.settings') }}" class="btn btn-white mt-4 shadow-sm">
                <i class="fas fa-cog me-2"></i> Edit Profile Settings
            </a>
        </div>
    </div>
</div>

<style>
    body {
        background: #f5f7fa;
    }

    .profile-card {
        background: linear-gradient(to bottom right, #2c3e50, #1c2833);
        border-radius: 25px;
        width: 100%;
        max-width: 500px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.2);
        overflow: hidden;
    }

    .profile-header {
        padding-top: 2rem;
        padding-bottom: 1rem;
    }

    .profile-image-wrapper {
        position: relative;
        width: 140px;
        height: 140px;
    }

    .profile-image,
    .default-profile-image {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        object-fit: cover;
        border: 5px solid #1c2833;
        box-shadow: 0 6px 12px rgba(0,0,0,0.3);
        background-color: #34495E;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .default-profile-image i {
        font-size: 3.5rem;
        color: #fff;
    }

    .change-photo-overlay {
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(255, 255, 255, 0.95);
        border-radius: 15px;
        padding: 8px 16px;
        cursor: pointer;
        font-size: 0.9rem;
        font-weight: 500;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .change-photo-overlay:hover {
        background: #ffffff;
    }

    .profile-info {
        color: #ecf0f1;
        font-size: 1rem;
    }

    .btn-white {
        background: white;
        color: #2c3e50;
        padding: 10px 25px;
        border-radius: 30px;
        font-weight: 600;
        transition: 0.3s ease;
        border: none;
    }

    .btn-white:hover {
        background: #f1f1f1;
        transform: translateY(-2px);
    }
    .profile-image-wrapper {
    position: relative;
    width: 150px;
    height: 150px;
    }

    .profile-image,
    .default-profile-image {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #1c2833;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        background-color: #34495E;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .default-profile-image i {
        font-size: 3.5rem;
        color: #fff;
    }

    /* Overlay appears only on hover */
    .change-photo-overlay {
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 50px;
        height: 50px;
        background-color: white;
        border-radius: 50%;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: opacity 0.3s ease;
        opacity: 0;
        cursor: pointer;
    }

    .profile-image-wrapper:hover .change-photo-overlay {
        opacity: 1;
    }

    .change-photo-overlay i {
        font-size: 1rem;
        color: #2c3e50;
    }

</style>
@endsection
