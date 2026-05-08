@extends('layouts.app')
@section('title', 'New Purchase')
@section('page-title', '<svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg> New Purchase')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card form-card">
            <div class="card-header bg-transparent border-bottom">
                <h6 class="mb-0 fw-bold"><svg class="me-2 text-primary" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>Record Purchase</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('purchases.store') }}">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Supplier</label>
                            <select name="supplier_id" class="form-select">
                                <option value="">Select Supplier</option>
                                @foreach($suppliers as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Purchase Date *</label>
                            <input type="date" name="purchase_date" class="form-control" value="{{ old('purchase_date', now()->format('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Paid Amount</label>
                            <input type="number" name="paid_amount" class="form-control" step="0.01" min="0" value="0">
                        </div>
                    </div>

                    <!-- Dynamic Items -->
                    <div class="card mb-3" style="background:#f8f9fa;border-radius:8px;">
                        <div class="card-header bg-transparent py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <strong class="text-sm">Purchase Items</strong>
                                <button type="button" class="btn btn-sm btn-success" onclick="addItem()"><svg class="me-1" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>Add Item</button>
                            </div>
                        </div>
                        <div class="card-body p-2" id="itemsContainer">
                            <div class="row mb-2 item-row" data-index="0">
                                <div class="col-md-5 mb-1">
                                    <select name="items[0][product_id]" class="form-select form-select-sm product-select" required>
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" data-price="{{ $product->purchase_price }}">{{ $product->name }} ({{ $product->barcode ?? 'No barcode' }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 mb-1">
                                    <input type="number" name="items[0][quantity]" class="form-control form-control-sm item-qty" placeholder="Qty" min="1" value="1" required>
                                </div>
                                <div class="col-md-3 mb-1">
                                    <input type="number" name="items[0][unit_cost]" class="form-control form-control-sm item-cost" placeholder="Unit Cost" step="0.01" min="0" required>
                                </div>
                                <div class="col-md-2 mb-1 text-end">
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeItem(this)" disabled><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Optional notes...">{{ old('notes') }}</textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>Record Purchase</button>
                        <a href="{{ route('purchases.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let itemIndex = 1;

function addItem() {
    let container = document.getElementById('itemsContainer');
    let row = document.createElement('div');
    row.className = 'row mb-2 item-row';
    row.dataset.index = itemIndex;
    row.innerHTML = `
        <div class="col-md-5 mb-1">
            <select name="items[${itemIndex}][product_id]" class="form-select form-select-sm product-select" required>
                <option value="">Select Product</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" data-price="{{ $product->purchase_price }}">{{ $product->name }} ({{ $product->barcode ?? 'No barcode' }})</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 mb-1"><input type="number" name="items[${itemIndex}][quantity]" class="form-control form-control-sm item-qty" placeholder="Qty" min="1" value="1" required></div>
        <div class="col-md-3 mb-1"><input type="number" name="items[${itemIndex}][unit_cost]" class="form-control form-control-sm item-cost" placeholder="Unit Cost" step="0.01" min="0" required></div>
        <div class="col-md-2 mb-1 text-end"><button type="button" class="btn btn-sm btn-outline-danger" onclick="removeItem(this)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg></button></div>
    `;
    container.appendChild(row);
    itemIndex++;
    updateRemoveButtons();
}

function removeItem(btn) {
    btn.closest('.item-row').remove();
    updateRemoveButtons();
}

function updateRemoveButtons() {
    let rows = document.querySelectorAll('.item-row');
    rows.forEach((row, i) => {
        let btn = row.querySelector('.btn-outline-danger');
        btn.disabled = rows.length <= 1;
    });
}

// Auto-fill unit cost when product is selected
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('product-select')) {
        let selected = e.target.options[e.target.selectedIndex];
        let price = selected.getAttribute('data-price');
        let costInput = e.target.closest('.item-row').querySelector('.item-cost');
        if (price && !costInput.value) {
            costInput.value = price;
        }
    }
});
</script>
@endpush
