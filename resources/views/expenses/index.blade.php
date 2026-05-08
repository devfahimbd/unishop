@extends('layouts.app')
@section('title', 'Expenses')
@section('page-title', '<svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg> Expenses')

@section('content')
<div class="row">
    <!-- Expense List -->
    <div class="col-lg-8">
        <div class="card table-card">
            <div class="card-header">
                <h6 class="mb-0 fw-bold"><svg class="me-2 text-primary" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>All Expenses</h6>
                <a href="{{ route('expenses.report') }}" class="btn btn-sm btn-outline-info"><svg class="me-1" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>Report</a>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr><th>#</th><th>Category</th><th>Amount</th><th>Date</th><th>Description</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        @foreach($expenses as $index => $expense)
                            <tr>
                                <td>{{ ($expenses->currentPage() - 1) * $expenses->perPage() + $index + 1 }}</td>
                                <td><span class="badge bg-primary">{{ $expense->category }}</span></td>
                                <td class="fw-semibold text-danger">{{ formatCurrency($expense->amount) }}</td>
                                <td>{{ $expense->date->format('M d, Y') }}</td>
                                <td>{{ Str::limit($expense->description, 40) ?? '-' }}</td>
                                <td class="action-btns">
                                    <button class="btn btn-sm btn-outline-primary" onclick="editExpense({{ $expense->toJson() }})"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg></button>
                                    <form method="POST" action="{{ route('expenses.destroy', $expense) }}" style="display:inline;" onsubmit="return confirm('Delete?')">
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
        <div class="d-flex justify-content-center mt-3">{{ $expenses->links() }}</div>
    </div>

    <!-- Add Expense Form -->
    <div class="col-lg-4">
        <div class="card form-card">
            <div class="card-header bg-transparent border-bottom">
                <h6 class="mb-0 fw-bold"><svg class="me-2 text-success" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>Add Expense</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('expenses.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Category *</label>
                        <input type="text" name="category" class="form-control" placeholder="e.g., Rent, Salary, Transport" required list="expenseCategories">
                        <datalist id="expenseCategories">
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount *</label>
                        <input type="number" name="amount" class="form-control" step="0.01" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date *</label>
                        <input type="date" name="date" class="form-control" value="{{ old('date', now()->format('Y-m-d')) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>Save Expense</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:12px;">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Edit Expense</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="editForm">
                @csrf @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="editId">
                    <div class="mb-3"><label class="form-label">Category *</label><input type="text" name="category" class="form-control" id="editCategory" required></div>
                    <div class="mb-3"><label class="form-label">Amount *</label><input type="number" name="amount" class="form-control" id="editAmount" step="0.01" min="0" required></div>
                    <div class="mb-3"><label class="form-label">Date *</label><input type="date" name="date" class="form-control" id="editDate" required></div>
                    <div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control" id="editDescription" rows="2"></textarea></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function editExpense(expense) {
    document.getElementById('editId').value = expense.id;
    document.getElementById('editCategory').value = expense.category;
    document.getElementById('editAmount').value = expense.amount;
    document.getElementById('editDate').value = expense.date;
    document.getElementById('editDescription').value = expense.description || '';
    document.getElementById('editForm').action = '/expenses/' + expense.id;
    new bootstrap.Modal(document.getElementById('editModal')).show();
}
</script>
@endpush
