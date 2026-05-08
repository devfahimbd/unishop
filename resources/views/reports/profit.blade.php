@extends('layouts.app')
@section('title', 'Profit Report')
@section('page-title', '<svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg> Profit Report')

@section('content')
<!-- Filters -->
<div class="card mb-3" style="border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,0.06);">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold small">From</label>
                <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small">To</label>
                <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg></button>
            </div>
        </form>
    </div>
</div>

<!-- Profit Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card p-4 text-center" style="border-radius:12px;border-left:4px solid #4361ee;">
            <small class="text-muted">Sales Revenue</small>
            <h4 class="mb-0 fw-bold text-primary">{{ formatCurrency($salesRevenue) }}</h4>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-4 text-center" style="border-radius:12px;border-left:4px solid #f72585;">
            <small class="text-muted">Cost of Goods</small>
            <h4 class="mb-0 fw-bold text-danger">{{ formatCurrency($costOfGoods) }}</h4>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-4 text-center" style="border-radius:12px;border-left:4px solid #f8961e;">
            <small class="text-muted">Expenses</small>
            <h4 class="mb-0 fw-bold text-warning">{{ formatCurrency($totalExpenses) }}</h4>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card p-4 text-center" style="border-radius:12px;background:linear-gradient(135deg, rgba(67,97,238,0.05), rgba(86,11,173,0.05));">
            <small class="text-muted">Gross Profit</small>
            <h3 class="mb-0 fw-bold {{ $grossProfit >= 0 ? 'text-primary' : 'text-danger' }}">{{ formatCurrency($grossProfit) }}</h3>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-4 text-center" style="border-radius:12px;background:linear-gradient(135deg, {{ $netProfit >= 0 ? 'rgba(76,201,240,0.05), rgba(67,97,238,0.05)' : 'rgba(247,37,133,0.05), rgba(248,150,30,0.05)' }});">
            <small class="text-muted">Net Profit</small>
            <h3 class="mb-0 fw-bold {{ $netProfit >= 0 ? 'text-success' : 'text-danger' }}">{{ formatCurrency($netProfit) }}</h3>
            <small class="text-muted">Margin: <strong>{{ number_format($profitMargin, 2) }}%</strong></small>
        </div>
    </div>
</div>

<!-- Profit Breakdown Chart -->
<div class="card" style="border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,0.06);">
    <div class="card-header bg-transparent border-bottom">
        <h6 class="mb-0 fw-bold"><svg class="me-2 text-primary" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>Revenue Breakdown</h6>
    </div>
    <div class="card-body p-3">
        <canvas id="profitChart" height="100"></canvas>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('profitChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Cost of Goods', 'Expenses', 'Net Profit'],
            datasets: [{
                data: [
                    {{ floatval($costOfGoods) }},
                    {{ floatval($totalExpenses) }},
                    {{ floatval($netProfit) > 0 ? floatval($netProfit) : 0 }}
                ],
                backgroundColor: ['#f72585', '#f8961e', '#4cc9f0'],
                borderWidth: 0,
                borderRadius: 5,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: { callbacks: { label: ctx => ctx.label + ': ' + formatCurrency(ctx.raw) } }
            }
        }
    });
});
</script>
@endpush
