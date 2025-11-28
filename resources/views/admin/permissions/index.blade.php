@extends('dashboard.layout')

@section('page-title', 'Permissions')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Permissions Directory</h2>
                <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Roles
                </a>
            </div>
            <p class="text-muted mt-2">All available permissions in the system, grouped by resource</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            @foreach($permissions as $resource => $perms)
                <div class="card mb-3">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-cube"></i> {{ ucfirst(str_replace('_', ' ', $resource)) }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($perms as $permission)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="border-left border-primary pl-3">
                                        <strong>{{ $permission->name }}</strong>
                                        @if($permission->description)
                                            <br><small class="text-muted">{{ $permission->description }}</small>
                                        @endif
                                        <br>
                                        <small class="badge badge-sm badge-light">
                                            {{ $permission->action }}
                                        </small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="alert alert-info">
                <h5><i class="fas fa-info-circle"></i> Permission Naming Convention</h5>
                <p class="mb-2">Permissions follow the pattern: <code>scope.resource.action</code></p>
                <ul class="mb-0">
                    <li><strong>platform.*</strong> - Super Admin permissions (cross-tenant)</li>
                    <li><strong>tenant.*</strong> - Tenant Admin permissions (tenant-scoped)</li>
                    <li><strong>Other resources</strong> - Domain-specific permissions</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.border-left {
    border-left-width: 3px !important;
}
</style>
@endpush
