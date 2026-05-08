@extends('layouts.app')
@section('title', 'Add Product')
@section('page-title', '<svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Add Product')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card form-card">
            <div class="card-header bg-transparent border-bottom">
                <h6 class="mb-0 fw-bold"><svg class="me-2 text-primary" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>New Product</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('products.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Product Name *</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Category</label>
                            <select name="category_id" class="form-select">
                                <option value="">Select Category</option>
                                @foreach($categories as $id => $name)
                                    <option value="{{ $id }}" {{ old('category_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Barcode</label>
                            <input type="text" name="barcode" class="form-control" value="{{ old('barcode') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Unit</label>
                            <input type="text" name="unit" class="form-control" value="{{ old('unit', 'piece') }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status</label>
                            <div class="form-check form-switch mt-2">
                                <input type="checkbox" name="status" class="form-check-input" id="status" checked>
                                <label class="form-check-label" for="status">Active</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Purchase Price *</label>
                            <input type="number" name="purchase_price" class="form-control" step="0.01" min="0" value="{{ old('purchase_price') }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Selling Price *</label>
                            <input type="number" name="selling_price" class="form-control" step="0.01" min="0" value="{{ old('selling_price') }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Profit Margin</label>
                            <input type="text" class="form-control" id="profitMargin" readonly style="background:#f8f9fa;">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stock Quantity *</label>
                            <input type="number" name="stock_quantity" class="form-control" min="0" value="{{ old('stock_quantity', 0) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Alert Quantity *</label>
                            <input type="number" name="alert_quantity" class="form-control" min="0" value="{{ old('alert_quantity', 10) }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>Save Product</button>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$('input[name="purchase_price"], input[name="selling_price"]').on('input', function() {
    let pp = parseFloat($('input[name="purchase_price"]').val()) || 0;
    let sp = parseFloat($('input[name="selling_price"]').val()) || 0;
    if (pp > 0) {
        let margin = ((sp - pp) / pp * 100).toFixed(2);
        $('#profitMargin').val(margin + '%');
    } else {
        $('#profitMargin').val('');
    }
});
</script>
@endpush
