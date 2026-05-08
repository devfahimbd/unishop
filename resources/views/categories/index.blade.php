@extends('layouts.app')
@section('title', 'Categories')
@section('page-title', '<svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg> Categories')

@section('content')
<div class="row">
    <!-- Category List -->
    <div class="col-lg-8">
        <div class="card table-card">
            <div class="card-header">
                <h6 class="mb-0 fw-bold"><svg class="me-2 text-primary" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>All Categories</h6>
                <form method="GET" action="{{ route('categories.index') }}" class="d-flex">
                    <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}" style="min-width:150px;border-radius:8px 0 0 8px;">
                    <button class="btn btn-outline-primary" style="border-radius:0 8px 8px 0;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></button>
                </form>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Products</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $index => $category)
                            <tr>
                                <td>{{ ($categories->currentPage() - 1) * $categories->perPage() + $index + 1 }}</td>
                                <td><strong>{{ $category->name }}</strong></td>
                                <td>{{ Str::limit($category->description, 50) ?? '-' }}</td>
                                <td><span class="badge bg-primary">{{ $category->products->count() }}</span></td>
                                <td>
                                    <span class="badge badge-status {{ $category->status ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $category->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="action-btns">
                                    <button class="btn btn-sm btn-outline-primary" onclick="editCategory({{ $category }})" data-bs-toggle="tooltip" title="Edit"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                                    <form method="POST" action="{{ route('categories.destroy', $category) }}" style="display:inline;" onsubmit="return confirm('Delete this category?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Delete"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="d-flex justify-content-center mt-3">{{ $categories->links() }}</div>
    </div>

    <!-- Add Category Form -->
    <div class="col-lg-4">
        <div class="card form-card">
            <div class="card-header bg-transparent border-bottom">
                <h6 class="mb-0 fw-bold"><svg class="me-2 text-success" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>Add Category</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('categories.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Category Name *</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" name="status" class="form-check-input" id="catStatus" checked>
                            <label class="form-check-label" for="catStatus">Active</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>Save Category</button>
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
                <h5 class="modal-title">Edit Category</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="editForm">
                @csrf @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" id="editId">
                    <div class="mb-3">
                        <label class="form-label">Category Name *</label>
                        <input type="text" name="name" class="form-control" id="editName" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" id="editDescription" rows="3"></textarea>
                    </div>
                    <div class="form-check form-switch">
                        <input type="checkbox" name="status" class="form-check-input" id="editStatus">
                        <label class="form-check-label" for="editStatus">Active</label>
                    </div>
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
function editCategory(cat) {
    document.getElementById('editId').value = cat.id;
    document.getElementById('editName').value = cat.name;
    document.getElementById('editDescription').value = cat.description || '';
    document.getElementById('editStatus').checked = cat.status;
    document.getElementById('editForm').action = '/categories/' + cat.id;
    new bootstrap.Modal(document.getElementById('editModal')).show();
}
</script>
@endpush
