@extends('layouts.app')
@section('title', 'Purchases')
@section('page-title', '<svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg> Purchases')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <a href="{{ route('purchases.create') }}" class="btn btn-primary"><svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>New Purchase</a>
    <form method="GET" action="{{ route('purchases.index') }}" class="d-flex gap-2">
        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" style="border-radius:8px;">
        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" style="border-radius:8px;">
        <button class="btn btn-outline-primary"><svg class="me-1" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>Filter</button>
    </form>
</div>

<div class="card table-card">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr><th>#</th><th>Date</th><th>Supplier</th><th>Items</th><th>Total</th><th>Paid</th><th>Due</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @foreach($purchases as $index => $purchase)
                    <tr>
                        <td>{{ ($purchases->currentPage() - 1) * $purchases->perPage() + $index + 1 }}</td>
                        <td>{{ $purchase->purchase_date->format('M d, Y') }}</td>
                        <td>{{ $purchase->supplier->name ?? 'N/A' }}</td>
                        <td><span class="badge bg-primary">{{ $purchase->purchaseItems->count() }}</span></td>
                        <td class="fw-semibold">{{ formatCurrency($purchase->total_amount) }}</td>
                        <td>{{ formatCurrency($purchase->paid_amount) }}</td>
                        <td><span class="badge {{ $purchase->due_amount > 0 ? 'bg-danger' : 'bg-success' }}">{{ formatCurrency($purchase->due_amount) }}</span></td>
                        <td>
                            <span class="badge badge-status {{ $purchase->payment_status === 'paid' ? 'bg-success' : ($purchase->payment_status === 'partial' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                {{ ucfirst($purchase->payment_status) }}
                            </span>
                        </td>
                        <td class="action-btns">
                            <form method="POST" action="{{ route('purchases.destroy', $purchase) }}" style="display:inline;" onsubmit="return confirm('Delete this purchase? Stock will be reversed.')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="d-flex justify-content-center mt-3">{{ $purchases->links() }}</div>
@endsection
