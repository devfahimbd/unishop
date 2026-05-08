@extends('layouts.app')
@section('title', 'Edit Supplier')
@section('page-title', '<svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg> Edit Supplier')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card form-card">
            <div class="card-body">
                <h6 class="mb-3 fw-bold"><svg class="me-2 text-primary" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>Edit Supplier</h6>
                <form method="POST" action="{{ route('suppliers.update', $supplier) }}">
                    @csrf @method('PUT')
                    <div class="mb-3"><label class="form-label">Name *</label><input type="text" name="name" class="form-control" value="{{ old('name', $supplier->name) }}" required></div>
                    <div class="mb-3"><label class="form-label">Company Name</label><input type="text" name="company_name" class="form-control" value="{{ old('company_name', $supplier->company_name) }}"></div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email', $supplier->email) }}"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Phone</label><input type="tel" name="phone" class="form-control" value="{{ old('phone', $supplier->phone) }}"></div>
                    </div>
                    <div class="mb-3"><label class="form-label">Address</label><textarea name="address" class="form-control" rows="2">{{ old('address', $supplier->address) }}</textarea></div>
                    <div class="mb-3"><label class="form-label">Previous Due</label><input type="number" name="previous_due" class="form-control" step="0.01" min="0" value="{{ old('previous_due', $supplier->previous_due) }}"></div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>Update Supplier</button>
                        <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
