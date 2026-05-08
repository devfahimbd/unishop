@extends('layouts.app')
@section('title', 'POS - Point of Sale')
@section('page-title', '<svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg> Point of Sale')

@section('content')
<div class="pos-container">
    <!-- Products Side -->
    <div class="pos-products">
        <!-- Search & Barcode -->
        <div class="card mb-3" style="border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,0.06);">
            <div class="card-body py-2 px-3">
                <div class="row g-2">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg></span>
                            <input type="text" id="barcodeInput" class="form-control" placeholder="Scan barcode..." style="border-radius:0 8px 8px 0;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg></span>
                            <input type="text" id="productSearch" class="form-control" placeholder="Search product..." style="border-radius:0 8px 8px 0;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="row g-2" id="productsGrid">
            @foreach($products as $product)
                <div class="col-xl-4 col-md-6 product-item" data-name="{{ strtolower($product->name) }}" data-barcode="{{ $product->barcode ?? '' }}">
                    <div class="pos-product-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-semibold text-sm">{{ $product->name }}</div>
                                <small class="text-muted">{{ $product->category->name ?? 'Uncategorized' }}</small>
                            </div>
                            <span class="badge {{ $product->stock_quantity <= $product->alert_quantity ? 'bg-danger' : 'bg-success' }}" style="font-size:0.7rem;">
                                {{ $product->stock_quantity }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <strong class="text-primary">{{ formatCurrency($product->selling_price) }}</strong>
                            <button class="btn btn-sm btn-primary" onclick="quickAdd({{ $product->id }}, 1)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg> Add</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Cart Side -->
    <div class="pos-cart">
        <div class="p-3 border-bottom" style="background:linear-gradient(135deg,#4361ee,#3f37c9);border-radius:12px 12px 0 0;">
            <div class="d-flex justify-content-between align-items-center text-white">
                <h6 class="mb-0"><svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>Cart <span class="badge bg-light text-dark" id="cartCount">0</span></h6>
                <button class="btn btn-sm btn-outline-light" onclick="clearCart()"><svg class="me-1" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>Clear</button>
            </div>
        </div>

        <!-- Cart Items -->
        <div class="pos-cart-items p-2" id="cartItems">
            <div class="text-center text-muted py-5" id="emptyCart">
                <svg class="mb-3 opacity-25" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>
                <p class="mb-0">Cart is empty</p>
                <small>Scan or click products to add</small>
            </div>
        </div>

        <!-- Cart Summary & Checkout -->
        <div class="p-3 border-top mt-auto" style="background:#f8f9fa;">
            <!-- Discount & VAT -->
            <div class="row g-2 mb-2">
                <div class="col-4">
                    <input type="number" id="discount" class="form-control form-control-sm" placeholder="Discount" value="0" min="0" style="border-radius:6px;">
                </div>
                <div class="col-3">
                    <select id="discountType" class="form-select form-select-sm" style="border-radius:6px;">
                        <option value="fixed">Fixed</option>
                        <option value="percentage">%</option>
                    </select>
                </div>
                <div class="col-5">
                    <div class="input-group input-group-sm">
                        <input type="number" id="vatPercent" class="form-control" placeholder="VAT %" value="0" min="0" max="100" style="border-radius:6px 0 0 6px;">
                        <span class="input-group-text" style="border-radius:0 6px 6px 0;">%</span>
                    </div>
                </div>
            </div>

            <!-- Summary -->
            <div class="d-flex justify-content-between mb-1">
                <small>Subtotal:</small>
                <small class="fw-semibold" id="subtotalDisplay">$0.00</small>
            </div>
            <div class="d-flex justify-content-between mb-1 text-danger">
                <small>Discount:</small>
                <small class="fw-semibold" id="discountDisplay">-$0.00</small>
            </div>
            <div class="d-flex justify-content-between mb-1 text-info">
                <small>VAT:</small>
                <small class="fw-semibold" id="vatDisplay">$0.00</small>
            </div>
            <hr class="my-2">
            <div class="d-flex justify-content-between mb-3">
                <strong class="fs-5">Total:</strong>
                <strong class="fs-5 text-primary" id="totalDisplay">$0.00</strong>
            </div>

            <!-- Customer -->
            <div class="mb-2">
                <select id="customerId" class="form-select form-select-sm" style="border-radius:6px;">
                    <option value="">Walk-in Customer</option>
                    @foreach($customers as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Payment -->
            <div class="row g-2 mb-2">
                <div class="col-6">
                    <select id="paymentMethod" class="form-select form-select-sm" style="border-radius:6px;">
                        <option value="cash">Cash</option>
                        <option value="bkash">Bkash</option>
                        <option value="card">Card</option>
                    </select>
                </div>
                <div class="col-6">
                    <input type="number" id="paidAmount" class="form-control form-control-sm" placeholder="Paid Amount" style="border-radius:6px;" min="0">
                </div>
            </div>

            <!-- Notes -->
            <div class="mb-3">
                <input type="text" id="saleNotes" class="form-control form-control-sm" placeholder="Notes (optional)" style="border-radius:6px;">
            </div>

            <button class="btn btn-primary w-100 fw-bold" id="checkoutBtn" onclick="checkout()" style="border-radius:8px;padding:10px;">
                <svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>Complete Sale
            </button>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;">
            <div class="modal-body text-center p-5">
                <div class="mb-3">
                    <svg class="text-success" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>
                </div>
                <h4 class="fw-bold text-success">Sale Completed!</h4>
                <p class="mb-2">Invoice: <strong id="invoiceNo"></strong></p>
                <div class="d-flex justify-content-center gap-2 mt-4">
                    <a href="#" id="printInvoiceBtn" target="_blank" class="btn btn-primary"><svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>Print Invoice</a>
                    <button class="btn btn-outline-success" data-bs-dismiss="modal">New Sale</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let cart = {};

// Format currency
function formatMoney(amount) {
    return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(amount);
}

// Quick add product
function quickAdd(productId, qty) {
    addToCart(productId, qty);
}

// Add to cart via AJAX
function addToCart(productId, qty) {
    $.ajax({
        url: '{{ route('pos.add-to-cart') }}',
        method: 'POST',
        data: { product_id: productId, quantity: qty, _token: '{{ csrf_token() }}' },
        success: function(response) {
            if (response.success) {
                cart = response.cart;
                renderCart(response.cart_total);
            } else {
                alert(response.message);
            }
        },
        error: function() { alert('Error adding product.'); }
    });
}

// Update cart quantity
function updateCartQty(productId, qty) {
    $.ajax({
        url: '{{ route('pos.update-cart') }}',
        method: 'POST',
        data: { product_id: productId, quantity: qty, _token: '{{ csrf_token() }}' },
        success: function(response) {
            if (response.success) {
                cart = response.cart;
                renderCart(response.cart_total);
            } else {
                alert(response.message);
            }
        }
    });
}

// Remove from cart
function removeFromCart(productId) {
    $.ajax({
        url: '{{ route('pos.remove-cart') }}',
        method: 'POST',
        data: { product_id: productId, _token: '{{ csrf_token() }}' },
        success: function(response) {
            cart = response.cart;
            renderCart(response.cart_total);
        }
    });
}

// Clear cart
function clearCart() {
    $.ajax({
        url: '{{ route('pos.clear-cart') }}',
        method: 'POST',
        data: { _token: '{{ csrf_token() }}' },
        success: function(response) {
            cart = {};
            renderCart(0);
        }
    });
}

// Render cart
function renderCart(total) {
    let container = document.getElementById('cartItems');
    let items = Object.values(cart);
    document.getElementById('cartCount').textContent = items.length;

    if (items.length === 0) {
        container.innerHTML = '<div class="text-center text-muted py-5" id="emptyCart"><svg class="mb-3 opacity-25" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg><p class="mb-0">Cart is empty</p><small>Scan or click products to add</small></div>';
        updateTotals(0);
        return;
    }

    let html = '';
    items.forEach(item => {
        html += `<div class="d-flex justify-content-between align-items-center p-2 mb-1" style="background:#f8f9fa;border-radius:8px;">
            <div class="flex-grow-1 me-2">
                <div class="fw-semibold" style="font-size:0.85rem;">${item.name}</div>
                <small class="text-muted">${formatMoney(item.price)} x ${item.quantity}</small>
            </div>
            <div class="d-flex align-items-center gap-1">
                <button class="btn btn-sm btn-outline-secondary" style="padding:2px 6px;" onclick="updateCartQty(${item.product_id}, ${item.quantity - 1})">-</button>
                <span class="fw-semibold" style="min-width:20px;text-align:center;">${item.quantity}</span>
                <button class="btn btn-sm btn-outline-secondary" style="padding:2px 6px;" onclick="updateCartQty(${item.product_id}, ${item.quantity + 1})">+</button>
                <span class="fw-semibold text-primary ms-2" style="min-width:60px;text-align:right;">${formatMoney(item.total)}</span>
                <button class="btn btn-sm btn-outline-danger ms-1" style="padding:2px 6px;" onclick="removeFromCart(${item.product_id})"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg></button>
            </div>
        </div>`;
    });
    container.innerHTML = html;
    updateTotals(total);
}

// Update totals
function updateTotals(subtotal) {
    let discount = parseFloat(document.getElementById('discount').value) || 0;
    let discountType = document.getElementById('discountType').value;
    let vatPercent = parseFloat(document.getElementById('vatPercent').value) || 0;

    let discountAmount = discountType === 'percentage' ? (subtotal * discount / 100) : discount;
    discountAmount = Math.min(discountAmount, subtotal);
    let afterDiscount = subtotal - discountAmount;
    let vatAmount = (afterDiscount * vatPercent) / 100;
    let total = afterDiscount + vatAmount;

    document.getElementById('subtotalDisplay').textContent = formatMoney(subtotal);
    document.getElementById('discountDisplay').textContent = '-' + formatMoney(discountAmount);
    document.getElementById('vatDisplay').textContent = formatMoney(vatAmount);
    document.getElementById('totalDisplay').textContent = formatMoney(total);

    document.getElementById('paidAmount').value = total.toFixed(2);
}

// Recalculate on change
['discount', 'discountType', 'vatPercent'].forEach(id => {
    document.getElementById(id).addEventListener('input', function() {
        let subtotal = Object.values(cart).reduce((sum, item) => sum + item.total, 0);
        updateTotals(subtotal);
    });
});

// Barcode scan
document.getElementById('barcodeInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        let barcode = this.value.trim();
        if (!barcode) return;
        $.ajax({
            url: '{{ route('products.barcode-search') }}',
            method: 'GET',
            data: { barcode: barcode },
            success: function(response) {
                if (response.success) {
                    addToCart(response.product.id, 1);
                } else {
                    alert('Product not found: ' + barcode);
                }
            }
        });
        this.value = '';
    }
});

// Product search filter
document.getElementById('productSearch').addEventListener('input', function() {
    let term = this.value.toLowerCase();
    document.querySelectorAll('.product-item').forEach(el => {
        let name = el.dataset.name;
        let barcode = el.dataset.barcode;
        el.style.display = (name.includes(term) || barcode.includes(term)) ? '' : 'none';
    });
});

// Checkout
function checkout() {
    if (Object.keys(cart).length === 0) { alert('Cart is empty!'); return; }

    let subtotal = Object.values(cart).reduce((sum, item) => sum + item.total, 0);
    let discount = document.getElementById('discount').value;
    let discountType = document.getElementById('discountType').value;
    let vatPercent = document.getElementById('vatPercent').value;
    let paidAmount = document.getElementById('paidAmount').value;
    let customerId = document.getElementById('customerId').value;
    let paymentMethod = document.getElementById('paymentMethod').value;
    let notes = document.getElementById('saleNotes').value;

    $.ajax({
        url: '{{ route('pos.checkout') }}',
        method: 'POST',
        data: {
            customer_id: customerId,
            discount_amount: discount,
            discount_type: discountType,
            vat_percentage: vatPercent,
            payment_method: paymentMethod,
            paid_amount: paidAmount,
            notes: notes,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                document.getElementById('invoiceNo').textContent = response.invoice_no;
                document.getElementById('printInvoiceBtn').href = response.invoice_url;
                cart = {};
                renderCart(0);
                document.getElementById('discount').value = 0;
                document.getElementById('vatPercent').value = 0;
                document.getElementById('saleNotes').value = '';
                new bootstrap.Modal(document.getElementById('successModal')).show();
            } else {
                alert(response.message);
            }
        },
        error: function(xhr) {
            let errors = xhr.responseJSON?.errors;
            if (errors) {
                alert(Object.values(errors).flat().join('\n'));
            } else {
                alert('Checkout failed. Please try again.');
            }
        }
    });
}
</script>
@endpush
