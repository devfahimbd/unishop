@extends('layouts.app')
@section('title', 'Low Stock Report')
@section('page-title', '<svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg> Low Stock Alert')

@section('content')
<div class="alert alert-warning" style="border-radius:12px;">
    <svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>
    Products listed below have stock at or below their alert quantity. Restock them as soon as possible to avoid running out.
</div>

<div class="card table-card">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr><th>#</th><th>Product</th><th>Category</th><th>Current Stock</th><th>Alert Qty</th><th>Status</th><th>Action</th></tr>
            </thead>
            <tbody>
                @foreach($products as $index => $product)
                    <tr class="{{ $product->stock_quantity == 0 ? 'table-danger' : 'table-warning' }}">
                        <td>{{ ($products->currentPage() - 1) * $products->perPage() + $index + 1 }}</td>
                        <td><strong>{{ $product->name }}</strong></td>
                        <td>{{ $product->category->name ?? '-' }}</td>
                        <td>
                            <span class="badge bg-danger">{{ $product->stock_quantity }}</span>
                        </td>
                        <td>{{ $product->alert_quantity }}</td>
                        <td>
                            @if($product->stock_quantity == 0)
                                <span class="badge bg-danger pulse">Out of Stock</span>
                            @else
                                <span class="badge bg-warning text-dark">Low Stock</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('purchases.create') }}" class="btn btn-sm btn-outline-primary"><svg class="me-1" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>Restock</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="d-flex justify-content-center mt-3">{{ $products->links() }}</div>
@endsection
