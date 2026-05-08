@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

<!-- Quick Stats Row -->
<div class="row g-3 mb-4">
    <!-- Today's Sales - Primary Card (Wider) -->
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="stat-card stat-card-primary">
            <div class="stat-card-body">
                <div class="stat-card-icon" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                </div>
                <div class="stat-card-info">
                    <div class="stat-card-label">Today's Sales</div>
                    <div class="stat-card-value">{{ formatCurrency($todaySales) }}</div>
                    <div class="stat-card-meta">
                        <span class="stat-card-badge bg-primary-soft text-primary">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="vertical-align:-1px;"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                            {{ $todaySalesCount }} transactions
                        </span>
                    </div>
                </div>
            </div>
            <div class="stat-card-footer">
                <svg width="60" height="30" viewBox="0 0 60 30" style="opacity:0.15; position:absolute; bottom:0; right:10px;">
                    <polyline points="0,25 10,20 20,22 30,10 40,15 50,5 60,8" fill="none" stroke="currentColor" stroke-width="2"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Total Products -->
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="stat-card stat-card-products">
            <div class="stat-card-body">
                <div class="stat-card-icon" style="background: linear-gradient(135deg, #06d6a0 0%, #059669 100%);">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                </div>
                <div class="stat-card-info">
                    <div class="stat-card-label">Total Products</div>
                    <div class="stat-card-value">{{ number_format($totalProducts) }}</div>
                    <div class="stat-card-meta">
                        <span class="stat-card-badge bg-success-soft text-success">Active items</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Customers -->
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="stat-card stat-card-customers">
            <div class="stat-card-body">
                <div class="stat-card-icon" style="background: linear-gradient(135deg, #f8961e 0%, #e85d04 100%);">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                </div>
                <div class="stat-card-info">
                    <div class="stat-card-label">Total Customers</div>
                    <div class="stat-card-value">{{ number_format($totalCustomers) }}</div>
                    <div class="stat-card-meta">
                        <span class="stat-card-badge bg-warning-soft text-warning">Registered</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Value -->
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="stat-card stat-card-stock">
            <div class="stat-card-body">
                <div class="stat-card-icon" style="background: linear-gradient(135deg, #7b2cbf 0%, #560bad 100%);">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                </div>
                <div class="stat-card-info">
                    <div class="stat-card-label">Stock Value</div>
                    <div class="stat-card-value">{{ formatCurrency($totalStockValue) }}</div>
                    <div class="stat-card-meta">
                        <span class="stat-card-badge bg-purple-soft text-purple">Inventory</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts & Alerts Row -->
<div class="row g-3 mb-4">
    <!-- Sales Chart -->
    <div class="col-xl-8 col-lg-7">
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <div class="dashboard-card-title">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#4361ee" stroke-width="2" stroke-linecap="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                    <span>Sales Overview</span>
                </div>
                <div class="dashboard-card-subtitle">Last 7 days performance</div>
            </div>
            <div class="dashboard-card-body" style="padding: 20px;">
                <canvas id="salesChart" height="130"></canvas>
            </div>
        </div>
    </div>

    <!-- Low Stock Alert -->
    <div class="col-xl-4 col-lg-5">
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <div class="dashboard-card-title">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2" stroke-linecap="round"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    <span>Low Stock Alert</span>
                </div>
                <span class="low-stock-count">{{ $lowStockProducts->count() }} items</span>
            </div>
            <div class="dashboard-card-body p-0 low-stock-list">
                @forelse($lowStockProducts as $product)
                    <div class="low-stock-item {{ $product->stock_quantity == 0 ? 'out-of-stock' : '' }}">
                        <div class="low-stock-item-info">
                            <div class="low-stock-item-name">{{ $product->name }}</div>
                            <div class="low-stock-item-cat">{{ $product->category->name ?? 'Uncategorized' }}</div>
                        </div>
                        <span class="low-stock-badge {{ $product->stock_quantity == 0 ? 'badge-danger' : 'badge-warning' }}">
                            {{ $product->stock_quantity }} / {{ $product->alert_quantity }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-5 text-muted">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="1.5" style="margin-bottom:12px;"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        <p class="mb-0 fw-medium">All stock levels are healthy</p>
                        <small>No alerts at this time</small>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Recent Sales & Top Products -->
<div class="row g-3">
    <!-- Recent Sales -->
    <div class="col-xl-7 col-lg-6">
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <div class="dashboard-card-title">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#4361ee" stroke-width="2" stroke-linecap="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    <span>Recent Sales</span>
                </div>
                <a href="{{ route('reports.daily-sales') }}" class="view-all-btn">
                    View All
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                </a>
            </div>
            <div class="dashboard-card-body p-0">
                <div class="table-responsive">
                    <table class="table dashboard-table">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Payment</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentSales as $sale)
                                <tr>
                                    <td><strong class="invoice-link">{{ $sale->invoice_no }}</strong></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="customer-avatar-sm">{{ strtoupper(substr($sale->customer->name ?? 'W', 0, 1)) }}</div>
                                            {{ $sale->customer->name ?? 'Walk-in' }}
                                        </div>
                                    </td>
                                    <td class="fw-semibold amount-text">{{ formatCurrency($sale->total_amount) }}</td>
                                    <td>
                                        <span class="payment-badge {{ $sale->payment_method === 'cash' ? 'badge-cash' : ($sale->payment_method === 'bkash' ? 'badge-bkash' : 'badge-other') }}">
                                            {{ ucfirst($sale->payment_method) }}
                                        </span>
                                    </td>
                                    <td class="text-muted">{{ $sale->sale_date->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-5">
                                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#ccc" stroke-width="1.5" style="margin-bottom:8px;"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                                        <div class="fw-medium">No sales yet</div>
                                        <small>Start selling from POS to see data here</small>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Products -->
    <div class="col-xl-5 col-lg-6">
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <div class="dashboard-card-title">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/></svg>
                    <span>Top Products</span>
                </div>
                <span class="top-products-period">Last 30 days</span>
            </div>
            <div class="dashboard-card-body p-0">
                @forelse($topProducts as $index => $topProduct)
                    <div class="top-product-item">
                        <div class="top-product-rank {{ $index < 3 ? 'rank-gold' : '' }}">
                            {{ $index + 1 }}
                        </div>
                        <div class="top-product-info">
                            <div class="top-product-name">{{ $topProduct->product->name }}</div>
                            <div class="top-product-meta">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:-1px;"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
                                {{ number_format($topProduct->total_sold) }} sold
                            </div>
                        </div>
                        <div class="top-product-revenue">{{ formatCurrency($topProduct->total_revenue) }}</div>
                    </div>
                @empty
                    <div class="text-center py-5 text-muted">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#ccc" stroke-width="1.5" style="margin-bottom:12px;"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                        <div class="fw-medium">No sales data yet</div>
                        <small>Top products will appear here</small>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fetch sales chart data
    fetch('{{ route('dashboard.sales-chart') }}')
        .then(response => response.json())
        .then(data => {
            const ctx = document.getElementById('salesChart').getContext('2d');

            // Gradient for bars
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(67, 97, 238, 0.85)');
            gradient.addColorStop(1, 'rgba(67, 97, 238, 0.25)');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.map(item => item.date),
                    datasets: [{
                        label: 'Sales',
                        data: data.map(item => item.sales),
                        backgroundColor: gradient,
                        borderColor: '#4361ee',
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false,
                        barPercentage: 0.6,
                        categoryPercentage: 0.7,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleFont: { size: 13, weight: '600' },
                            bodyFont: { size: 13 },
                            padding: 12,
                            cornerRadius: 8,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return 'Sales: ' + formatCurrency(context.raw);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) { return formatCurrency(value); },
                                font: { size: 11 },
                                color: '#94a3b8',
                                maxTicksLimit: 6,
                            },
                            grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false },
                            border: { display: false },
                        },
                        x: {
                            grid: { display: false },
                            border: { display: false },
                            ticks: { font: { size: 12, weight: '500' }, color: '#64748b' }
                        }
                    }
                }
            });
        });
});
</script>
@endpush
