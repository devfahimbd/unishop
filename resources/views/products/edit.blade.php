@extends('layouts.app')
@section('title', 'Edit Product')
@section('page-title', '<svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg> Edit Product')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card form-card">
            <div class="card-header bg-transparent border-bottom">
                <h6 class="mb-0 fw-bold"><svg class="me-2 text-primary" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>Edit Product</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('products.update', $product) }}">
                    @csrf @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Product Name *</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Category</label>
                            <select name="category_id" class="form-select">
                                <option value="">Select Category</option>
                                @foreach($categories as $id => $name)
                                    <option value="{{ $id }}" {{ old('category_id', $product->category_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Barcode</label>
                            <input type="text" name="barcode" class="form-control" value="{{ old('barcode', $product->barcode) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Unit</label>
                            <input type="text" name="unit" class="form-control" value="{{ old('unit', $product->unit) }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status</label>
                            <div class="form-check form-switch mt-2">
                                <input type="checkbox" name="status" class="form-check-input" id="status" {{ $product->status ? 'checked' : '' }}>
                                <label class="form-check-label" for="status">Active</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Purchase Price *</label>
                            <input type="number" name="purchase_price" class="form-control" step="0.01" min="0" value="{{ old('purchase_price', $product->purchase_price) }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Selling Price *</label>
                            <input type="number" name="selling_price" class="form-control" step="0.01" min="0" value="{{ old('selling_price', $product->selling_price) }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Profit Margin</label>
                            <input type="text" class="form-control" id="profitMargin" readonly style="background:#f8f9fa;">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stock Quantity *</label>
                            <input type="number" name="stock_quantity" class="form-control" min="0" value="{{ old('stock_quantity', $product->stock_quantity) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Alert Quantity *</label>
                            <input type="number" name="alert_quantity" class="form-control" min="0" value="{{ old('alert_quantity', $product->alert_quantity) }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $product->description) }}</textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>Update Product</button>
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
$(document).ready(function() {
    function calcMargin() {
        let pp = parseFloat($('input[name="purchase_price"]').val()) || 0;
        let sp = parseFloat($('input[name="selling_price"]').val()) || 0;
        if (pp > 0) { $('#profitMargin').val(((sp - pp) / pp * 100).toFixed(2) + '%'); }
        else { $('#profitMargin').val(''); }
    }
    calcMargin();
    $('input[name="purchase_price"], input[name="selling_price"]').on('input', calcMargin);
});
</script>
@endpush
