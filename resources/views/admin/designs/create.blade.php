@extends('admin.layout')

@section('title', 'Add design')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="fas fa-plus me-2"></i>Add design
    </div>
    <div class="card-body">
        <form action="{{ route('admin.designs.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.designs.form')
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Save design</button>
                <a href="{{ route('admin.designs.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
