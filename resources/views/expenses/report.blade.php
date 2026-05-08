@extends('layouts.app')
@section('title', 'Expense Report')
@section('page-title', '<svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg> Expense Report')

@section('content')
<div class="mb-3">
    <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary"><svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>Back to Expenses</a>
</div>

<!-- Filters -->
<div class="card mb-3" style="border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,0.06);">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('expenses.report') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold small">From Date</label>
                <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small">To Date</label>
                <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100"><svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>Generate Report</button>
            </div>
        </form>
    </div>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card p-3 text-center" style="border-radius:12px;border-left:4px solid #f72585;">
            <small class="text-muted">Total Expenses</small>
            <h4 class="mb-0 fw-bold text-danger">{{ formatCurrency($totalExpenses) }}</h4>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3 text-center" style="border-radius:12px;border-left:4px solid #4361ee;">
            <small class="text-muted">Categories</small>
            <h4 class="mb-0 fw-bold text-primary">{{ $categoryBreakdown->count() }}</h4>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3 text-center" style="border-radius:12px;border-left:4px solid #f8961e;">
            <small class="text-muted">Total Transactions</small>
            <h4 class="mb-0 fw-bold text-warning">{{ $expenses->count() }}</h4>
        </div>
    </div>
</div>

<!-- Category Breakdown -->
<div class="card table-card">
    <div class="card-header">
        <h6 class="mb-0 fw-bold"><svg class="me-2 text-primary" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>Category Breakdown</h6>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr><th>Category</th><th>Count</th><th>Total Amount</th><th>% of Total</th></tr>
            </thead>
            <tbody>
                @foreach($categoryBreakdown->sortByDesc('total') as $category => $data)
                    <tr>
                        <td><span class="badge bg-primary">{{ $category }}</span></td>
                        <td>{{ $data['count'] }}</td>
                        <td class="fw-semibold text-danger">{{ formatCurrency($data['total']) }}</td>
                        <td>
                            <div class="progress" style="height:20px;border-radius:10px;">
                                <div class="progress-bar bg-primary" style="width:{{ $totalExpenses > 0 ? ($data['total'] / $totalExpenses * 100) : 0 }}%">{{ $totalExpenses > 0 ? number_format($data['total'] / $totalExpenses * 100, 1) : 0 }}%</div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
