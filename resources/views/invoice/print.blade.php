<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $sale->invoice_no }} - UniShop Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f0f2f5; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .invoice-container { max-width: 800px; margin: 20px auto; background: #fff; padding: 40px; border-radius: 12px; box-shadow: 0 2px 15px rgba(0,0,0,0.08); }
        .invoice-header { border-bottom: 3px solid #4361ee; padding-bottom: 20px; margin-bottom: 20px; }
        .invoice-header h2 { color: #4361ee; margin: 0; }
        .invoice-header .shop-name { font-size: 1.2rem; font-weight: 700; }
        .invoice-table th { background-color: #4361ee; color: #fff; font-size: 0.85rem; }
        .invoice-table td { vertical-align: middle; }
        .invoice-footer { border-top: 2px solid #eee; padding-top: 15px; margin-top: 20px; }
        .watermark { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-30deg); font-size: 5rem; color: rgba(67,97,238,0.05); font-weight: bold; pointer-events: none; }
        @media print {
            body { background: #fff; }
            .invoice-container { box-shadow: none; margin: 0; padding: 20px; max-width: 100%; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="invoice-container" style="position:relative;">
        <div class="watermark">UniShop</div>

        <!-- Print Button -->
        <div class="text-end no-print mb-3">
            <button class="btn btn-primary" onclick="window.print()"><svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>Print Invoice</button>
        </div>

        <!-- Header -->
        <div class="invoice-header">
            <div class="row align-items-center">
                <div class="col-6">
                    <h2><svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>UniShop Manager</h2>
                    <div class="shop-name">{{ $shop->shop_name }}</div>
                    @if($shop->address)
                        <small class="text-muted">{{ $shop->address }}</small>
                    @endif
                    @if($shop->phone)
                        <div><small class="text-muted"><svg class="me-1" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>{{ $shop->phone }}</small></div>
                    @endif
                </div>
                <div class="col-6 text-end">
                    <h4 class="text-primary mb-1">INVOICE</h4>
                    <div class="mb-1"><strong>{{ $sale->invoice_no }}</strong></div>
                    <div><small class="text-muted">Date: {{ $sale->sale_date->format('M d, Y') }}</small></div>
                </div>
            </div>
        </div>

        <!-- Customer Info -->
        @if($sale->customer)
            <div class="mb-3 p-3" style="background:#f8f9fa;border-radius:8px;">
                <strong><svg class="me-1" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg> Customer:</strong> {{ $sale->customer->name }}
                @if($sale->customer->phone)
                    <span class="text-muted ms-2"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg> {{ $sale->customer->phone }}</span>
                @endif
            </div>
        @endif

        <!-- Items Table -->
        <table class="table table-bordered invoice-table" style="border-radius:8px;overflow:hidden;">
            <thead>
                <tr>
                    <th style="width:5%;">#</th>
                    <th>Product</th>
                    <th style="width:12%;">Qty</th>
                    <th style="width:18%;">Unit Price</th>
                    <th style="width:18%;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->saleItems as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->product->name }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-end">{{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-end fw-semibold">{{ number_format($item->total_price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="row mt-3">
            <div class="col-7">
                @if($sale->notes)
                    <div class="p-3" style="background:#f8f9fa;border-radius:8px;">
                        <strong>Notes:</strong> {{ $sale->notes }}
                    </div>
                @endif
            </div>
            <div class="col-5">
                <table class="table table-borderless" style="font-size:0.9rem;">
                    <tr>
                        <td class="text-muted">Subtotal:</td>
                        <td class="text-end fw-semibold">{{ number_format($sale->subtotal, 2) }}</td>
                    </tr>
                    @if($sale->discount_amount > 0)
                    <tr>
                        <td class="text-muted">Discount ({{ $sale->discount_type == 'percentage' ? $sale->discount_amount . '%' : '' }}):</td>
                        <td class="text-end text-danger">-{{ number_format($sale->discount_amount, 2) }}</td>
                    </tr>
                    @endif
                    @if($sale->vat_amount > 0)
                    <tr>
                        <td class="text-muted">VAT ({{ $sale->vat_percentage }}%):</td>
                        <td class="text-end">{{ number_format($sale->vat_amount, 2) }}</td>
                    </tr>
                    @endif
                    <tr style="border-top:2px solid #4361ee;">
                        <td class="fw-bold fs-5 text-primary">Total:</td>
                        <td class="text-end fw-bold fs-5 text-primary">{{ number_format($sale->total_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Paid:</td>
                        <td class="text-end text-success">{{ number_format($sale->paid_amount, 2) }}</td>
                    </tr>
                    @if($sale->due_amount > 0)
                    <tr>
                        <td class="text-muted">Due:</td>
                        <td class="text-end text-danger fw-semibold">{{ number_format($sale->due_amount, 2) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="text-muted">Payment:</td>
                        <td class="text-end">
                            <span class="badge bg-success text-uppercase">{{ $sale->payment_method }}</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Footer -->
        <div class="invoice-footer text-center">
            <p class="mb-1 text-muted">Thank you for your purchase!</p>
            <small class="text-muted"><svg class="me-1" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>Powered by UniShop Manager</small>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
