@extends('layouts.app')
@section('title', 'Products')
@section('page-title', '<svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg> Products')

@section('content')
<!-- Action Bar -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <a href="{{ route('products.create') }}" class="btn btn-primary"><svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>Add Product</a>
    <div class="d-flex gap-2">
        <form method="GET" action="{{ route('products.index') }}" class="d-flex">
            <input type="text" name="search" class="form-control" placeholder="Search products..." value="{{ request('search') }}" style="min-width:200px;border-radius:8px 0 0 8px;">
            <button class="btn btn-outline-primary" style="border-radius:0 8px 8px 0;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></button>
        </form>
    </div>
</div>

<!-- Products Table -->
<div class="card table-card">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Barcode</th>
                    <th>Purchase Price</th>
                    <th>Selling Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $index => $product)
                    <tr>
                        <td>{{ ($products->currentPage() - 1) * $products->perPage() + $index + 1 }}</td>
                        <td><strong>{{ $product->name }}</strong></td>
                        <td>{{ $product->category->name ?? '-' }}</td>
                        <td><code>{{ $product->barcode ?? '-' }}</code></td>
                        <td>{{ formatCurrency($product->purchase_price) }}</td>
                        <td class="fw-semibold">{{ formatCurrency($product->selling_price) }}</td>
                        <td>
                            <span class="badge {{ $product->isLowStock() ? ($product->stock_quantity == 0 ? 'bg-danger' : 'bg-warning text-dark') : 'bg-success' }}">
                                {{ $product->stock_quantity }} {{ $product->unit }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-status {{ $product->status ? 'bg-success' : 'bg-secondary' }}">
                                {{ $product->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="action-btns">
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></a>
                            <form method="POST" action="{{ route('products.destroy', $product) }}" style="display:inline;" onsubmit="return confirm('Delete this product?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Delete"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<div class="d-flex justify-content-center mt-3">
    {{ $products->links() }}
</div>
@endsection
