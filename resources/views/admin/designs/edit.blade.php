@extends('admin.layout')

@section('title', 'Edit design')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="fas fa-edit me-2"></i>Edit design
    </div>
    <div class="card-body">
        <form action="{{ route('admin.designs.update', $design) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.designs.form', ['design' => $design])
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Update design</button>
                <a href="{{ route('admin.designs.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
