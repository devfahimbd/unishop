@extends('layouts.app')
@section('title', 'Customer Purchase History')
@section('page-title', '<svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg> {{ $customer->name }} - Purchase History')

@section('content')
<div class="mb-3">
    <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary"><svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>Back to Customers</a>
</div>

<div class="row mb-3">
    <div class="col-md-3">
        <div class="card p-3 text-center">
            <small class="text-muted">Total Purchases</small>
            <h5 class="mb-0 fw-bold">{{ $sales->total() }}</h5>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 text-center">
            <small class="text-muted">Total Spent</small>
            <h5 class="mb-0 fw-bold text-primary">{{ formatCurrency($sales->sum('total_amount')) }}</h5>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 text-center">
            <small class="text-muted">Total Due</small>
            <h5 class="mb-0 fw-bold text-danger">{{ formatCurrency($sales->sum('due_amount')) }}</h5>
        </div>
    </div>
</div>

<div class="card table-card">
    <div class="card-header">
        <h6 class="mb-0 fw-bold"><svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>Purchase History</h6>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr><th>Invoice</th><th>Date</th><th>Items</th><th>Total</th><th>Paid</th><th>Due</th><th>Payment</th></tr>
            </thead>
            <tbody>
                @foreach($sales as $sale)
                    <tr>
                        <td><a href="{{ route('pos.invoice', $sale) }}" target="_blank"><strong>{{ $sale->invoice_no }}</strong></a></td>
                        <td>{{ $sale->sale_date->format('M d, Y') }}</td>
                        <td>{{ $sale->saleItems->count() }} items</td>
                        <td>{{ formatCurrency($sale->total_amount) }}</td>
                        <td>{{ formatCurrency($sale->paid_amount) }}</td>
                        <td><span class="badge {{ $sale->due_amount > 0 ? 'bg-danger' : 'bg-success' }}">{{ formatCurrency($sale->due_amount) }}</span></td>
                        <td><span class="badge badge-status bg-success">{{ ucfirst($sale->payment_method) }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="d-flex justify-content-center mt-3">{{ $sales->links() }}</div>
@endsection
