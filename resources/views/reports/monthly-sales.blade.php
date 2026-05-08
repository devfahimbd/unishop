@extends('layouts.app')
@section('title', 'Monthly Sales Report')
@section('page-title', '<svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg> Monthly Sales Report')

@section('content')
<!-- Filters -->
<div class="card mb-3" style="border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,0.06);">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Month</label>
                <select name="month" class="form-select">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Year</label>
                <select name="year" class="form-select">
                    @for($y = now()->year; $y >= now()->year - 5; $y--)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg></button>
            </div>
        </form>
    </div>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card p-3 text-center" style="border-radius:12px;border-left:4px solid #4361ee;">
            <small class="text-muted">Total Sales</small>
            <h5 class="mb-0 fw-bold text-primary">{{ formatCurrency($totalSales) }}</h5>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 text-center" style="border-radius:12px;border-left:4px solid #f72585;">
            <small class="text-muted">Total Discount</small>
            <h5 class="mb-0 fw-bold text-danger">{{ formatCurrency($totalDiscount) }}</h5>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 text-center" style="border-radius:12px;border-left:4px solid #560bad;">
            <small class="text-muted">Total VAT</small>
            <h5 class="mb-0 fw-bold" style="color:#560bad;">{{ formatCurrency($totalVat) }}</h5>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 text-center" style="border-radius:12px;border-left:4px solid #f8961e;">
            <small class="text-muted">Total Due</small>
            <h5 class="mb-0 fw-bold text-warning">{{ formatCurrency($totalDue) }}</h5>
        </div>
    </div>
</div>

<!-- Daily Breakdown Chart -->
<div class="card mb-4" style="border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,0.06);">
    <div class="card-header bg-transparent border-bottom">
        <h6 class="mb-0 fw-bold"><svg class="me-2 text-primary" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>Daily Breakdown</h6>
    </div>
    <div class="card-body p-3">
        <canvas id="monthlyChart" height="100"></canvas>
    </div>
</div>

<!-- Sales Table -->
<div class="card table-card">
    <div class="card-header"><h6 class="mb-0 fw-bold"><svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>All Sales</h6></div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr><th>Invoice</th><th>Customer</th><th>Total</th><th>Payment</th><th>Status</th><th>Date</th></tr>
            </thead>
            <tbody>
                @foreach($sales as $sale)
                    <tr>
                        <td><a href="{{ route('pos.invoice', $sale) }}" target="_blank">{{ $sale->invoice_no }}</a></td>
                        <td>{{ $sale->customer->name ?? 'Walk-in' }}</td>
                        <td class="fw-semibold">{{ formatCurrency($sale->total_amount) }}</td>
                        <td><span class="badge badge-status bg-success">{{ ucfirst($sale->payment_method) }}</span></td>
                        <td><span class="badge badge-status {{ $sale->payment_status === 'paid' ? 'bg-success' : 'bg-danger' }}">{{ ucfirst($sale->payment_status) }}</span></td>
                        <td>{{ $sale->sale_date->format('M d') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const data = @json($dailyBreakdown);
    const ctx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(d => new Date(d.date).toLocaleDateString('en-US', {month:'short', day:'numeric'})),
            datasets: [{
                label: 'Sales',
                data: data.map(d => parseFloat(d.total)),
                borderColor: '#4361ee',
                backgroundColor: 'rgba(67,97,238,0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: '#4361ee',
            }]
        },
        options: {
            responsive: true,
            plugins: {
                tooltip: { callbacks: { label: ctx => formatCurrency(ctx.raw) } }
            },
            scales: {
                y: { beginAtZero: true, ticks: { callback: v => formatCurrency(v) }, grid: {color:'rgba(0,0,0,0.05)'} },
                x: { grid: {display: false} }
            }
        }
    });
});
</script>
@endpush
