@extends('layouts.app')
@section('title', 'Edit Customer')
@section('page-title', '<svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg> Edit Customer')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card form-card">
            <div class="card-header bg-transparent border-bottom">
                <h6 class="mb-0 fw-bold"><svg class="me-2 text-primary" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>Edit Customer</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('customers.update', $customer) }}">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Customer Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $customer->name) }}" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $customer->email) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="tel" name="phone" class="form-control" value="{{ old('phone', $customer->phone) }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="2">{{ old('address', $customer->address) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Previous Due</label>
                        <input type="number" name="previous_due" class="form-control" step="0.01" min="0" value="{{ old('previous_due', $customer->previous_due) }}">
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>Update Customer</button>
                        <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
