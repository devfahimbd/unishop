@extends('layouts.app')
@section('title', 'Profile')
@section('page-title', '<svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg> Profile Settings')

@section('content')
<div class="row g-4">
    <!-- Profile Info -->
    <div class="col-lg-8">
        <div class="card form-card">
            <div class="card-header bg-transparent border-bottom">
                <h6 class="mb-0 fw-bold"><svg class="me-2 text-primary" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>Profile Information</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Shop Name</label>
                            <input type="text" name="shop_name" class="form-control" value="{{ old('shop_name', $user->shop_name) }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="3">{{ old('address', $user->address) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Profile Photo</label>
                        <input type="file" name="photo" class="form-control" accept="image/*">
                        @if($user->photo)
                            <div class="mt-2">
                                <img src="{{ asset($user->photo) }}" alt="Profile" class="rounded-circle" style="width:60px;height:60px;object-fit:cover;">
                            </div>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>Update Profile
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Password Change -->
    <div class="col-lg-4">
        <div class="card form-card">
            <div class="card-header bg-transparent border-bottom">
                <h6 class="mb-0 fw-bold"><svg class="me-2 text-warning" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>Change Password</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-warning w-100">
                        <svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>Change Password
                    </button>
                </form>
            </div>
        </div>

        <!-- Account Info Card -->
        <div class="card form-card mt-3">
            <div class="card-header bg-transparent border-bottom">
                <h6 class="mb-0 fw-bold"><svg class="me-2 text-info" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>Account Info</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <small class="text-muted">Member Since</small>
                    <small class="fw-semibold">{{ $user->created_at->format('M d, Y') }}</small>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <small class="text-muted">Email</small>
                    <small class="fw-semibold">{{ $user->email }}</small>
                </div>
                <div class="d-flex justify-content-between">
                    <small class="text-muted">Shop</small>
                    <small class="fw-semibold">{{ $user->shop_name }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
