@extends('layouts.app')
@section('title', 'Customers')
@section('page-title', '<svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg> Customers')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <a href="{{ route('customers.create') }}" class="btn btn-primary"><svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>Add Customer</a>
    <form method="GET" action="{{ route('customers.index') }}" class="d-flex">
        <input type="text" name="search" class="form-control" placeholder="Search customers..." value="{{ request('search') }}" style="min-width:200px;border-radius:8px 0 0 8px;">
        <button class="btn btn-outline-primary" style="border-radius:0 8px 8px 0;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></button>
    </form>
</div>

<div class="card table-card">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr><th>#</th><th>Name</th><th>Email</th><th>Phone</th><th>Address</th><th>Due</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @foreach($customers as $index => $customer)
                    <tr>
                        <td>{{ ($customers->currentPage() - 1) * $customers->perPage() + $index + 1 }}</td>
                        <td><strong>{{ $customer->name }}</strong></td>
                        <td>{{ $customer->email ?? '-' }}</td>
                        <td>{{ $customer->phone ?? '-' }}</td>
                        <td>{{ Str::limit($customer->address, 30) ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $customer->previous_due > 0 ? 'bg-danger' : 'bg-success' }}">
                                {{ formatCurrency($customer->previous_due) }}
                            </span>
                        </td>
                        <td class="action-btns">
                            <a href="{{ route('customers.purchase-history', $customer) }}" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="History"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 102.13-9.36L1 10"/></svg></a>
                            <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></a>
                            <form method="POST" action="{{ route('customers.destroy', $customer) }}" style="display:inline;" onsubmit="return confirm('Delete?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="d-flex justify-content-center mt-3">{{ $customers->links() }}</div>
@endsection
