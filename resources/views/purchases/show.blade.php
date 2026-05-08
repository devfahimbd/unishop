@extends('layouts.app')
@section('title', 'Purchase Details')
@section('page-title', '<svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg> Purchase #{{ $purchase->id }}')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card form-card">
            <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">Purchase Details</h6>
                <a href="{{ route('purchases.index') }}" class="btn btn-sm btn-outline-secondary"><svg class="me-1" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>Back</a>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Supplier:</strong> {{ $purchase->supplier->name ?? 'N/A' }}</p>
                        <p><strong>Date:</strong> {{ $purchase->purchase_date->format('M d, Y') }}</p>
                    </div>
                    <div class="col-md-6 text-end">
                        <p><strong>Status:</strong> <span class="badge {{ $purchase->payment_status === 'paid' ? 'bg-success' : ($purchase->payment_status === 'partial' ? 'bg-warning text-dark' : 'bg-danger') }}">{{ ucfirst($purchase->payment_status) }}</span></p>
                        <p><strong>Total:</strong> {{ formatCurrency($purchase->total_amount) }}</p>
                    </div>
                </div>

                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr><th>Product</th><th>Qty</th><th>Unit Cost</th><th>Total</th></tr>
                    </thead>
                    <tbody>
                        @foreach($purchase->purchaseItems as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ formatCurrency($item->unit_cost) }}</td>
                                <td>{{ formatCurrency($item->total_cost) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold">
                            <td colspan="3" class="text-end">Grand Total:</td>
                            <td>{{ formatCurrency($purchase->total_amount) }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end">Paid:</td>
                            <td class="text-success">{{ formatCurrency($purchase->paid_amount) }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end">Due:</td>
                            <td class="text-danger">{{ formatCurrency($purchase->due_amount) }}</td>
                        </tr>
                    </tfoot>
                </table>

                @if($purchase->notes)
                    <div class="mt-3 p-3" style="background:#f8f9fa;border-radius:8px;">
                        <strong>Notes:</strong> {{ $purchase->notes }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
