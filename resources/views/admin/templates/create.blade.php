@extends('admin.layout')

@section('title', 'Create Card Template')

@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('admin.templates.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left"></i></a>
    <h5 class="mb-0">Create card template</h5>
</div>
<div class="card">
    <div class="card-body">
        <p class="text-muted mb-4">Upload a background image for your greeting card. After saving, you will define editable zones (photo and text) on the editor page.</p>
        <form action="{{ route('admin.templates.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Template title</label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required maxlength="255" placeholder="e.g. Birthday Greeting">
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4">
                <label for="background_image" class="form-label">Background image</label>
                <input type="file" class="form-control @error('background_image') is-invalid @enderror" id="background_image" name="background_image" accept="image/*" required>
                <div class="form-text">PNG or JPG, max 10 MB. This will be the card background.</div>
                @error('background_image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Create &amp; open editor</button>
            <a href="{{ route('admin.templates.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
        </form>
    </div>
</div>
@endsection
