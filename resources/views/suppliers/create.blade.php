@extends('layouts.app')
@section('title', 'Add Supplier')
@section('page-title', '<svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg> Add Supplier')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card form-card">
            <div class="card-body">
                <h6 class="mb-3 fw-bold"><svg class="me-2 text-primary" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>New Supplier</h6>
                <form method="POST" action="{{ route('suppliers.store') }}">
                    @csrf
                    <div class="mb-3"><label class="form-label">Name *</label><input type="text" name="name" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Company Name</label><input type="text" name="company_name" class="form-control"></div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Phone</label><input type="tel" name="phone" class="form-control"></div>
                    </div>
                    <div class="mb-3"><label class="form-label">Address</label><textarea name="address" class="form-control" rows="2"></textarea></div>
                    <div class="mb-3"><label class="form-label">Previous Due</label><input type="number" name="previous_due" class="form-control" step="0.01" min="0" value="0"></div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>Save Supplier</button>
                        <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
