@extends('layouts.app')
@section('title', 'Suppliers')
@section('page-title', '<svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg> Suppliers')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <a href="{{ route('suppliers.create') }}" class="btn btn-primary"><svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>Add Supplier</a>
    <form method="GET" action="{{ route('suppliers.index') }}" class="d-flex">
        <input type="text" name="search" class="form-control" placeholder="Search suppliers..." value="{{ request('search') }}" style="min-width:200px;border-radius:8px 0 0 8px;">
        <button class="btn btn-outline-primary" style="border-radius:0 8px 8px 0;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg></button>
    </form>
</div>

<div class="card table-card">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr><th>#</th><th>Name</th><th>Company</th><th>Email</th><th>Phone</th><th>Due</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @foreach($suppliers as $index => $supplier)
                    <tr>
                        <td>{{ ($suppliers->currentPage() - 1) * $suppliers->perPage() + $index + 1 }}</td>
                        <td><strong>{{ $supplier->name }}</strong></td>
                        <td>{{ $supplier->company_name ?? '-' }}</td>
                        <td>{{ $supplier->email ?? '-' }}</td>
                        <td>{{ $supplier->phone ?? '-' }}</td>
                        <td><span class="badge {{ $supplier->previous_due > 0 ? 'bg-danger' : 'bg-success' }}">{{ formatCurrency($supplier->previous_due) }}</span></td>
                        <td class="action-btns">
                            <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-sm btn-outline-primary"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg></a>
                            <form method="POST" action="{{ route('suppliers.destroy', $supplier) }}" style="display:inline;" onsubmit="return confirm('Delete?')">
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
<div class="d-flex justify-content-center mt-3">{{ $suppliers->links() }}</div>
@endsection
