@extends('admin.layout')

@section('title', 'Card Templates')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
    <h5 class="mb-0">Card templates (Fabric.js)</h5>
    <a href="{{ route('admin.templates.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus me-1"></i> New Template
    </a>
</div>
<p class="text-muted mb-4">Create templates with a background image and define editable zones (photo + text) for the user card generator.</p>
<div class="card">
    <div class="card-body p-0">
        @if($templates->isEmpty())
            <div class="empty-state p-5 text-center">
                <i class="fas fa-th-large text-muted fa-3x mb-3"></i>
                <p class="mb-0 text-muted">No templates yet. Create one to upload a background and define zones.</p>
                <a href="{{ route('admin.templates.create') }}" class="btn btn-primary mt-3">Create Template</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Zones</th>
                            <th>Created</th>
                            <th style="width: 200px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($templates as $t)
                        <tr>
                            <td>{{ $t->title }}</td>
                            <td>{{ $t->zones_count }} zone(s)</td>
                            <td>{{ $t->created_at->format('M j, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.templates.editor', $t) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit me-1"></i> Edit Zones
                                </a>
                                <a href="{{ route('cards.template.show', $t) }}" class="btn btn-sm btn-outline-secondary" target="_blank" rel="noopener">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
