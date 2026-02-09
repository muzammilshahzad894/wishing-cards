@extends('admin.layout')

@section('title', 'Designs')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0">Card designs</h5>
    <a href="{{ route('admin.designs.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add design
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        @if($designs->isEmpty())
            <div class="empty-state p-5">
                <i class="fas fa-image"></i>
                <p class="mb-3">No designs yet.</p>
                <a href="{{ route('admin.designs.create') }}" class="btn btn-primary">Add your first design</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 80px;">Preview</th>
                            <th>Name</th>
                            <th>Greeting</th>
                            <th>Name placeholder</th>
                            <th>Status</th>
                            <th style="width: 180px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($designs as $d)
                        <tr>
                            <td>
                                <button type="button" class="btn btn-link p-0 border-0 text-decoration-none" data-bs-toggle="modal" data-bs-target="#imagePreviewModal" data-image-src="{{ asset('storage/' . $d->image) }}" data-image-name="{{ $d->name }}" title="View full size">
                                    <img src="{{ asset('storage/' . $d->image) }}" alt="" class="rounded design-thumb" style="width: 56px; height: 56px; object-fit: cover;">
                                </button>
                            </td>
                            <td>{{ $d->name }}</td>
                            <td>{{ $d->greeting_text }}</td>
                            <td>{{ $d->name_placeholder }}</td>
                            <td>
                                <form action="{{ route('admin.designs.toggle-active', $d) }}" method="POST" class="d-inline">
                                    @csrf
                                    @if($d->is_active)
                                        <button type="submit" class="btn btn-sm btn-success">Active</button>
                                    @else
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">Inactive</button>
                                    @endif
                                </form>
                            </td>
                            <td>
                                <a href="{{ route('admin.designs.edit', $d) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                <button type="button" class="btn btn-sm btn-outline-danger" title="Delete" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal" data-design-name="{{ $d->name }}" data-delete-url="{{ route('admin.designs.destroy', $d) }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

{{-- Image preview modal --}}
<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-sm-down">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title" id="imagePreviewModalLabel">Design preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <img src="" alt="" id="imagePreviewModalImg" class="img-fluid rounded shadow-sm" style="max-height: 75vh; width: auto;">
                <p class="text-muted small mt-2 mb-0" id="imagePreviewModalName"></p>
            </div>
        </div>
    </div>
</div>

{{-- Delete confirmation modal --}}
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center p-4">
                <div class="delete-modal-icon mb-3">
                    <i class="fas fa-exclamation-triangle text-danger"></i>
                </div>
                <h5 class="modal-title mb-2" id="deleteConfirmModalLabel">Delete design?</h5>
                <p class="text-muted mb-4">You are about to delete <strong id="deleteDesignName"></strong>. This action cannot be undone.</p>
                <form id="deleteConfirmForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('imagePreviewModal').addEventListener('show.bs.modal', function(e) {
    var btn = e.relatedTarget;
    if (btn && btn.dataset.imageSrc) {
        document.getElementById('imagePreviewModalImg').src = btn.dataset.imageSrc;
        document.getElementById('imagePreviewModalName').textContent = btn.dataset.imageName || '';
    }
});
document.getElementById('deleteConfirmModal').addEventListener('show.bs.modal', function(e) {
    var btn = e.relatedTarget;
    if (btn && btn.dataset.deleteUrl) {
        document.getElementById('deleteConfirmForm').action = btn.dataset.deleteUrl;
        document.getElementById('deleteDesignName').textContent = btn.dataset.designName || 'this design';
    }
});
</script>
@endsection

@section('styles')
<style>
    .design-thumb { cursor: pointer; transition: opacity 0.2s; }
    .design-thumb:hover { opacity: 0.85; }
    .delete-modal-icon { width: 64px; height: 64px; margin: 0 auto; background: #fef2f2; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
    .delete-modal-icon i { font-size: 1.75rem; }
</style>
@endsection
