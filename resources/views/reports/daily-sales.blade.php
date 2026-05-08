@extends('layouts.app')
@section('title', 'Daily Sales Report')
@section('page-title', '<svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg> Daily Sales Report')

@section('content')
<!-- Filters -->
<div class="card mb-3" style="border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,0.06);">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Select Date</label>
                <input type="date" name="date" class="form-control" value="{{ $date }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg></button>
            </div>
        </form>
    </div>
</div>

<!-- Summary -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card stat-card stat-card-primary">
            <div class="card-body text-center">
                <div class="stat-label">Total Sales</div>
                <div class="stat-value text-primary">{{ formatCurrency($totalSales) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card stat-card-success">
            <div class="card-body text-center">
                <div class="stat-label">Transactions</div>
                <div class="stat-value" style="color:#4cc9f0;">{{ $sales->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card stat-card-warning">
            <div class="card-body text-center">
                <div class="stat-label">Items Sold</div>
                <div class="stat-value" style="color:#f8961e;">{{ number_format($totalItems) }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Sales Table -->
<div class="card table-card">
    <div class="card-header"><h6 class="mb-0 fw-bold"><svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>Sales on {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</h6></div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr><th>Invoice</th><th>Customer</th><th>Items</th><th>Subtotal</th><th>Discount</th><th>VAT</th><th>Total</th><th>Payment</th></tr>
            </thead>
            <tbody>
                @foreach($sales as $sale)
                    <tr>
                        <td><a href="{{ route('pos.invoice', $sale) }}" target="_blank"><strong>{{ $sale->invoice_no }}</strong></a></td>
                        <td>{{ $sale->customer->name ?? 'Walk-in' }}</td>
                        <td>{{ $sale->saleItems->count() }}</td>
                        <td>{{ formatCurrency($sale->subtotal) }}</td>
                        <td class="text-danger">-{{ formatCurrency($sale->discount_amount) }}</td>
                        <td>{{ formatCurrency($sale->vat_amount) }}</td>
                        <td class="fw-semibold">{{ formatCurrency($sale->total_amount) }}</td>
                        <td><span class="badge badge-status bg-success">{{ ucfirst($sale->payment_method) }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
