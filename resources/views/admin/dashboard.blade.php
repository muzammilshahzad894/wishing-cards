@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="row g-4">
    <div class="col-md-6">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-image"></i></div>
            <div class="stat-value">{{ $designsCount }}</div>
            <div class="stat-label">Total designs</div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div class="stat-value">{{ $activeCount }}</div>
            <div class="stat-label">Active (on frontend)</div>
        </div>
    </div>
    <div class="col-12">
        <a href="{{ route('admin.designs.index') }}" class="btn btn-primary">
            <i class="fas fa-image me-2"></i>Manage designs
        </a>
    </div>
</div>
@endsection
