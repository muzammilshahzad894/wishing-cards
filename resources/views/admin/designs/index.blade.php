@extends('admin.layout')

@section('title', 'Designs')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0">Card designs</h5>
</div>
<p class="text-muted mb-4">Designs are built in code (see <code>resources/views/cards/templates/</code>). Toggle active to show or hide on the frontend.</p>
<div class="card">
    <div class="card-body p-0">
        @if($designs->isEmpty())
            <div class="empty-state p-5 text-center">
                <i class="fas fa-image text-muted fa-3x mb-3"></i>
                <p class="mb-0 text-muted">No designs yet. Add template keys to <code>config/cards.php</code> and run <code>php artisan db:seed --class=DesignSeeder</code>.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Template</th>
                            <th>Status</th>
                            <th style="width: 180px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($designs as $d)
                        <tr>
                            <td>{{ $d->name }}</td>
                            <td><code>{{ $d->template_key }}</code></td>
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
                                <button type="button" class="btn btn-sm btn-outline-primary" title="Preview" data-bs-toggle="modal" data-bs-target="#previewModal" data-preview-url="{{ route('admin.designs.preview', $d) }}" data-design-name="{{ $d->name }}">
                                    <i class="fas fa-external-link-alt"></i> Preview
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

{{-- Preview modal (iframe) --}}
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-fullscreen-md-down">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title" id="previewModalLabel">Design preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" style="min-height: 560px;">
                <iframe id="previewIframe" src="about:blank" title="Preview" style="width:100%; height: 560px; border: 0;"></iframe>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('previewModal').addEventListener('show.bs.modal', function(e) {
    var btn = e.relatedTarget;
    var iframe = document.getElementById('previewIframe');
    if (btn && btn.dataset.previewUrl && iframe) {
        document.getElementById('previewModalLabel').textContent = 'Preview: ' + (btn.dataset.designName || '');
        iframe.src = btn.dataset.previewUrl;
    }
});
document.getElementById('previewModal').addEventListener('hidden.bs.modal', function() {
    var iframe = document.getElementById('previewIframe');
    if (iframe) iframe.src = 'about:blank';
});
</script>
@endsection
