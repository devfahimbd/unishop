@extends('layouts.app')
@section('title', 'Stock Report')
@section('page-title', '<svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg> Stock Report')

@section('content')
<!-- Summary -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card p-3 text-center" style="border-radius:12px;border-left:4px solid #4361ee;">
            <small class="text-muted">Total Products</small>
            <h5 class="mb-0 fw-bold text-primary">{{ number_format($totalProducts) }}</h5>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 text-center" style="border-radius:12px;border-left:4px solid #4cc9f0;">
            <small class="text-muted">Stock Value (Cost)</small>
            <h5 class="mb-0 fw-bold" style="color:#4cc9f0;">{{ formatCurrency($totalStockValue) }}</h5>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 text-center" style="border-radius:12px;border-left:4px solid #f8961e;">
            <small class="text-muted">Selling Value</small>
            <h5 class="mb-0 fw-bold text-warning">{{ formatCurrency($totalSellingValue) }}</h5>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 text-center" style="border-radius:12px;border-left:4px solid #f72585;">
            <small class="text-muted">Out of Stock</small>
            <h5 class="mb-0 fw-bold text-danger">{{ number_format($outOfStock) }}</h5>
        </div>
    </div>
</div>

<!-- Stock Table -->
<div class="card table-card">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr><th>#</th><th>Product</th><th>Category</th><th>Purchase Price</th><th>Selling Price</th><th>Stock</th><th>Stock Value</th><th>Potential Profit</th></tr>
            </thead>
            <tbody>
                @foreach($products as $index => $product)
                    <tr>
                        <td>{{ ($products->currentPage() - 1) * $products->perPage() + $index + 1 }}</td>
                        <td><strong>{{ $product->name }}</strong></td>
                        <td>{{ $product->category->name ?? '-' }}</td>
                        <td>{{ formatCurrency($product->purchase_price) }}</td>
                        <td>{{ formatCurrency($product->selling_price) }}</td>
                        <td>
                            <span class="badge {{ $product->stock_quantity == 0 ? 'bg-danger' : ($product->isLowStock() ? 'bg-warning text-dark' : 'bg-success') }}">
                                {{ $product->stock_quantity }}
                            </span>
                        </td>
                        <td>{{ formatCurrency($product->stock_quantity * $product->purchase_price) }}</td>
                        <td class="fw-semibold text-success">{{ formatCurrency($product->stock_quantity * ($product->selling_price - $product->purchase_price)) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="d-flex justify-content-center mt-3">{{ $products->links() }}</div>
@endsection
