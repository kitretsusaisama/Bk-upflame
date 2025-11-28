@extends('dashboard.layout')

@section('page-title', 'Roles & Users Management')

@push('styles')
<style>
    :root {
        --primary-purple: #7367F0;
        --text-dark: #444050;
        --text-medium: #6D6B77;
        --text-light: #ACAAB1;
        --border-color: #E6E6E8;
        --bg-white: #FFFFFF;
        --green: #28C76F;
        --orange: #FF9F43;
        --red: #FF4C51;
        --cyan: #00BAD1;
    }

    .roles-page {
        font-family: 'Public Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        font-size: 15px;
        line-height: 1.375;
        color: var(--text-dark);
        background-color: #F8F8F8;
    }

    .roles-page h4 {
        font-size: 24px;
        font-weight: 500;
    }

    .roles-page h5 {
        font-size: 18px;
        font-weight: 500;
    }

    .role-card {
        background: #FFFFFF;
        box-shadow: 0 3px 12px rgba(47, 43, 61, 0.14);
        border-radius: 6px;
        height: 156.625px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .role-card-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 13px;
        color: var(--text-medium);
    }

    .avatar-group {
        display: flex;
        align-items: center;
    }

    .avatar-group img,
    .avatar-group .avatar-more {
        width: 40px;
        height: 40px;
        border-radius: 9999px;
        border: 2px solid #FFFFFF;
        object-fit: cover;
        margin-left: -10px;
        background-color: #FFF;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        color: var(--text-medium);
    }

    .avatar-group img:first-child,
    .avatar-group .avatar-more:first-child {
        margin-left: 0;
    }

    .role-card-bottom {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .role-card-bottom a {
        font-size: 14px;
        color: var(--primary-purple);
        text-decoration: none;
    }

    .role-card-bottom a:hover {
        text-decoration: underline;
    }

    .icon-btn {
        width: 22px;
        height: 22px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #DDD;
        background: #FFF;
        cursor: pointer;
        transition: background 0.15s, transform 0.1s;
    }

    .icon-btn:hover {
        background: #F4F4F8;
        transform: translateY(-1px);
    }

    .add-role-card {
        background: #FFFFFF;
        box-shadow: 0 3px 12px rgba(47, 43, 61, 0.14);
        border-radius: 6px;
        height: 156.625px;
        padding: 20px;
        display: flex;
        gap: 10px;
        overflow: hidden;
    }

    .add-role-illustration {
        flex-basis: 41.67%;
        display: flex;
        align-items: flex-end;
        justify-content: center;
    }

    .add-role-illustration img {
        max-width: 100%;
        height: auto;
        object-fit: contain;
    }

    .add-role-content {
        flex-basis: 58.33%;
        text-align: end;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 6px;
    }

    .add-role-text-title {
        font-size: 16px;
        font-weight: 500;
        color: var(--text-dark);
    }

    .add-role-text-subtitle {
        font-size: 13px;
        color: var(--text-medium);
    }

    .btn-primary {
        background: var(--primary-purple);
        color: #FFFFFF;
        border-radius: 6px;
        padding: 8px 16px;
        font-size: 14px;
        font-weight: 500;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        cursor: pointer;
        transition: background 0.15s, transform 0.1s, box-shadow 0.15s;
    }

    .btn-primary:hover {
        background: #5E54E0;
        box-shadow: 0 4px 10px rgba(115,103,240,0.4);
        transform: translateY(-1px);
    }

    .btn-secondary {
        background: #F3F4F6;
        color: var(--text-dark);
        border-radius: 6px;
        padding: 8px 16px;
        font-size: 14px;
        font-weight: 500;
        border: 1px solid #D1D5DB;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        cursor: pointer;
        transition: background 0.15s, transform 0.1s;
    }

    .btn-secondary:hover {
        background: #E5E7EB;
        transform: translateY(-1px);
    }

    .user-table-card {
        background: #FFFFFF;
        box-shadow: 0 3px 12px rgba(47, 43, 61, 0.08);
        border-radius: 6px;
        border: 1px solid #E5E7EB;
    }

    .user-table-header {
        padding: 20px 24px 0;
    }

    .user-table-header h4 {
        font-size: 18px;
        font-weight: 500;
        margin-bottom: 4px;
    }

    .user-table-header p {
        font-size: 13px;
        color: var(--text-medium);
        margin-bottom: 12px;
    }

    .user-table-controls {
        padding: 12px 24px 20px;
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid var(--border-color);
    }

    .user-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
    }

    .user-table thead tr {
        background: #FAFAFB;
    }

    .user-table th,
    .user-table td {
        padding: 12.512px 20px;
        border-bottom: 1px solid #E6E6E8;
        font-size: 13px;
        text-align: left;
        vertical-align: middle;
        white-space: nowrap;
    }

    .user-table th {
        text-transform: uppercase;
        font-weight: 500;
        color: var(--text-medium);
        font-size: 13px;
    }

    .user-table th.sortable {
        cursor: pointer;
    }

    .user-table th.sortable:hover {
        background: #F2F2F7;
    }

    .user-table tbody tr:hover {
        background: #F9FAFB;
    }

    .user-avatar {
        width: 32px;
        height: 32px;
        border-radius: 9999px;
        object-fit: cover;
        margin-right: 10px;
    }

    .user-name-link {
        font-size: 14px;
        font-weight: 500;
        color: var(--text-dark);
        text-decoration: none;
    }

    .user-name-link:hover {
        color: var(--primary-purple);
    }

    .user-email {
        font-size: 12px;
        color: var(--text-medium);
    }

    .role-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: var(--text-dark);
    }

    .role-pill-icon {
        width: 22px;
        height: 22px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        color: #FFF;
    }

    .role-icon-maintainer {
        background: var(--cyan);
    }

    .role-icon-subscriber {
        background: var(--green);
    }

    .role-icon-editor {
        background: var(--orange);
    }

    .role-icon-author {
        background: var(--red);
    }

    .role-icon-admin {
        background: var(--primary-purple);
    }

    .status-badge {
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .status-active {
        background: #DDF5E7;
        color: #1D9B5A;
    }

    .status-inactive {
        background: #EBF0ED;
        color: #6D6B77;
    }

    .status-pending {
        background: #FFF0E0;
        color: #C97B2B;
    }

    .table-action-btn {
        width: 20.625px;
        height: 20.625px;
        border-radius: 6px;
        border: 1px solid #D1D5DB;
        background: #FFFFFF;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.15s, transform 0.1s;
    }

    .table-action-btn:hover {
        background: #F4F4F8;
        transform: translateY(-1px);
    }

    .table-footer {
        padding: 12px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 13px;
        color: var(--text-medium);
    }

    .pagination {
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .pagination button {
        min-width: 30px;
        height: 30px;
        border-radius: 6px;
        border: 1px solid #D1D5DB;
        background: #FFFFFF;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.15s, color 0.15s, border-color 0.15s;
    }

    .pagination button:hover:not(:disabled) {
        background: #F4F4F8;
    }

    .pagination button:disabled {
        opacity: 0.5;
        cursor: default;
    }

    .pagination .page-current {
        background: var(--primary-purple);
        color: #FFFFFF;
        border-color: var(--primary-purple);
    }

    /* Modal */
    .modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 50;
    }

    .modal {
        background: #FFFFFF;
        max-width: 800px;
        width: 100%;
        border-radius: 10px;
        padding: 48px;
        position: relative;
        box-shadow: 0 20px 45px rgba(15, 23, 42, 0.35);
    }

    @media (max-width: 768px) {
        .modal {
            margin: 16px;
            padding: 24px;
        }
    }

    .modal-close-btn {
        position: absolute;
        top: -18px;
        right: -18px;
        width: 32px;
        height: 32px;
        background: #FFFFFF;
        border-radius: 9999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.25);
        cursor: pointer;
    }

    .modal h4 {
        font-size: 24px;
        font-weight: 500;
    }

    .modal-subtitle {
        margin-top: 4px;
        font-size: 13px;
        color: var(--text-medium);
    }

    .form-label {
        font-size: 13px;
        font-weight: 500;
        color: var(--text-dark);
        margin-bottom: 4px;
    }

    .form-input {
        width: 100%;
        border-radius: 6px;
        border: 1px solid #D1D5DB;
        padding: 8px 10px;
        font-size: 14px;
        outline: none;
        transition: border-color 0.15s, box-shadow 0.15s;
    }

    .form-input:focus {
        border-color: var(--primary-purple);
        box-shadow: 0 0 0 1px rgba(115, 103, 240, 0.3);
    }

    .form-error {
        margin-top: 3px;
        font-size: 12px;
        color: var(--red);
        min-height: 16px;
    }

    .permissions-header {
        margin-top: 28px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
    }

    .permissions-title {
        font-size: 16px;
        font-weight: 500;
    }

    .permissions-subtitle {
        font-size: 12px;
        color: var(--text-medium);
    }

    .tooltip-icon {
        width: 18px;
        height: 18px;
        border-radius: 9999px;
        border: 1px solid #D1D5DB;
        font-size: 11px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--text-medium);
        margin-left: 4px;
    }

    .permissions-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        font-size: 13px;
    }

    .permissions-table th,
    .permissions-table td {
        padding: 8px 4px;
    }

    .permissions-table th {
        font-weight: 500;
        color: var(--text-medium);
    }

    .permissions-actions {
        text-align: center;
        width: 80px;
    }

    .permissions-checkbox {
        width: 16px;
        height: 16px;
    }

    .permissions-gap {
        padding-inline: 24px;
    }

    .modal-footer {
        margin-top: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
    }

    /* Footer bar */
    .page-footer {
        margin-top: 32px;
        padding: 12px 0 4px;
        border-top: 1px solid #E5E7EB;
        font-size: 13px;
        color: var(--text-medium);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 8px;
    }

    .page-footer a {
        color: var(--primary-purple);
        text-decoration: none;
    }

    .page-footer a:hover {
        text-decoration: underline;
    }
</style>
@endpush

@section('content')
<div class="roles-page">
    <div class="mx-auto px-6 py-6 max-w-6xl"
         x-data="{ showAddRoleModal: false }">

        {{-- Header Section --}}
        <header class="mb-6">
            <h4 class="mb-1 text-gray-900">Roles List</h4>
            <p class="text-[13px] text-[var(--text-medium)] whitespace-pre-line">
                A role provides access to predefined menus and features<br/>
                so that depending on the assigned role, an administrator can have access to what they need.
            </p>
        </header>

    {{-- Role Cards Grid - 3x2 MNC-Grade Responsive Layout --}}
    <section class="mb-10">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 lg:gap-6">
            @php
                $roleColors = ['7367f0', '28c76f', 'ff9f43', '00bad1', 'ea5455', '00cfe8'];
            @endphp

            @foreach($roles->take(5) as $index => $role)
                <div class="role-card ">
                    {{-- Top --}}
                    <div class="role-card-top flex justify-between items-center">
                        <span>Total {{ $role->users_count }} {{ Str::plural('user', $role->users_count) }}</span>

                        {{-- Avatar Group --}}
                        <div class="avatar-group flex items-center -space-x-2">
                            @php
                                $roleUsers = $role->users()->limit(3)->get();
                                $remainingCount = max(0, $role->users_count - 3);
                            @endphp

                            @forelse($roleUsers as $uIndex => $user)
                                <img class="w-10 h-10 rounded-full ring-2 ring-white object-cover"
                                    src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background={{ $roleColors[$uIndex % count($roleColors)] }}&color=fff"
                                    alt="{{ $user->name }}">
                            @empty
                                {{-- Default fallback avatars when no users --}}
                                <img class="w-10 h-10 rounded-full ring-2 ring-white object-cover"
                                    src="https://ui-avatars.com/api/?name={{ urlencode($role->name) }}&background={{ $roleColors[0] }}&color=fff">
                            @endforelse

                            @if($remainingCount > 0)
                                <span class="avatar-more w-10 h-10 rounded-full bg-gray-200 text-gray-600 text-xs flex items-center justify-center ring-2 ring-white">
                                    +{{ $remainingCount }}
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Bottom --}}
                    <div class="role-card-bottom flex justify-between items-center pt-2">
                        <div>
                            <h5>{{ $role->name }}</h5>

                            {{-- Prevent Edit only for Super Admin --}}
                            @if(!$role->is_system || $role->name !== 'Super Admin')
                                <a href="{{ route('admin.roles.edit', $role) }}" class="text-[var(--primary-purple)] text-sm hover:underline">
                                    Edit Role
                                </a>
                            @else
                                <span class="text-xs text-gray-500">System Role</span>
                            @endif
                        </div>

                        {{-- Copy Button --}}
                        <button class="icon-btn" title="Copy Role" onclick="alert('Copy role feature coming soon!')">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                class="w-[18px] h-[18px]" fill="none" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                <path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endforeach

            {{-- Add New Role Card (Always Sixth Card) --}}
            <div class="add-role-card flex flex-col sm:flex-row justify-between">
                <div class="add-role-illustration flex items-end justify-center sm:basis-5/12">
                    <img src="https://dummyimage.com/180x140/7367f0/ffffff&text=Role+Illustration" alt="Add Role Illustration">
                </div>

                <div class="add-role-content sm:basis-7/12 text-end space-y-1">
                    <div class="add-role-text-title">Add New Role</div>
                    <div class="add-role-text-subtitle">
                        Create a new role and assign suitable permissions quickly.
                    </div>
                    <a href="{{ route('admin.roles.create') }}" class="btn-primary inline-flex items-center">
                        Add New Role
                    </a>
                </div>
            </div>

        </div>
    </section>


        {{-- User List Table Section --}}
        <section class="user-table-card overflow-hidden">
            <div class="user-table-header">
                <h4>Total users with their roles</h4>
                <p>Find all users with their assigned roles, subscription plan, billing status and current account state.</p>
            </div>

            {{-- Controls --}}
            <div class="user-table-controls">
                <div class="flex items-center gap-2 text-[13px] text-[var(--text-medium)]">
                    <span>Show</span>
                    <select class="border border-[var(--border-color)] rounded-md px-2 py-1 text-[13px] focus:outline-none focus:ring-1 focus:ring-[var(--primary-purple)]">
                        <option>10</option>
                        <option>25</option>
                        <option>50</option>
                        <option>100</option>
                    </select>
                    <span>entries</span>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <div class="relative">
                        <input type="text"
                               placeholder="Search user..."
                               class="border border-[var(--border-color)] rounded-md pl-9 pr-3 py-1.5 text-[13px] focus:outline-none focus:ring-1 focus:ring-[var(--primary-purple)] w-48 md:w-64">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-4 h-4 absolute left-3 top-1.5 text-[var(--text-light)]"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1 0 5.5 5.5a7.5 7.5 0 0 0 11.15 11.15z" />
                        </svg>
                    </div>

                    <button type="button" class="btn-secondary">
                        Export
                    </button>

                    <button type="button"
                            class="btn-primary"
                            @click="showAddRoleModal = true">
                        Add New User
                    </button>
                </div>
            </div>

            {{-- Data Table --}}
            <div class="overflow-x-auto">
                <table class="user-table">
                    <thead>
                        <tr>
                            <th class="w-10">
                                <input type="checkbox"
                                       id="select-all-users"
                                       class="w-4 h-4 border-[var(--border-color)] rounded">
                            </th>
                            <th class="sortable">
                                <div class="flex items-center gap-1">
                                    User
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-[var(--text-light)]" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M5 8l5-5 5 5H5zm0 4h10l-5 5-5-5z" />
                                    </svg>
                                </div>
                            </th>
                            <th class="sortable">
                                <div class="flex items-center gap-1">
                                    Role
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-[var(--text-light)]" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M5 8l5-5 5 5H5zm0 4h10l-5 5-5-5z" />
                                    </svg>
                                </div>
                            </th>
                            <th class="sortable">
                                <div class="flex items-center gap-1">
                                    Tenant
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-[var(--text-light)]" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M5 8l5-5 5 5H5zm0 4h10l-5 5-5-5z" />
                                    </svg>
                                </div>
                            </th>
                            <th class="sortable">
                                <div class="flex items-center gap-1">
                                    Status
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-[var(--text-light)]" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M5 8l5-5 5 5H5zm0 4h10l-5 5-5-5z" />
                                    </svg>
                                </div>
                            </th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            function roleIconClass($roleName) {
                                return match (strtolower($roleName)) {
                                    'maintainer', 'manager' => 'role-icon-maintainer',
                                    'subscriber', 'user' => 'role-icon-subscriber',
                                    'editor' => 'role-icon-editor',
                                    'author' => 'role-icon-author',
                                    'admin', 'super admin', 'tenant admin' => 'role-icon-admin',
                                    default => 'role-icon-admin',
                                };
                            }

                            function statusClasses($status) {
                                // Map user status to UI status classes
                                return match ($status) {
                                    'active'   => 'status-badge status-active',
                                    'inactive' => 'status-badge status-inactive',
                                    'pending'  => 'status-badge status-pending',
                                    default    => 'status-badge status-inactive',
                                };
                            }
                        @endphp

                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <input type="checkbox"
                                           class="user-row-checkbox w-4 h-4 border-[var(--border-color)] rounded">
                                </td>
                                <td>
                                    <div class="flex items-center">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random&color=fff" 
                                             alt="{{ $user->name }}" class="user-avatar">
                                        <div>
                                            <a href="{{ route('admin.users.show', $user) }}" class="user-name-link">
                                                {{ $user->name }}
                                            </a>
                                            <div class="user-email">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $primaryRole = $user->roles->first();
                                    @endphp
                                    @if($primaryRole)
                                        <div class="role-pill">
                                            <div class="role-pill-icon {{ roleIconClass($primaryRole->name) }}">
                                                {{ strtoupper(substr($primaryRole->name, 0, 1)) }}
                                            </div>
                                            <span>{{ $primaryRole->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-500">No Role</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->tenant)
                                        {{ $user->tenant->name }}
                                    @else
                                        <span class="text-xs text-gray-500">Platform</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="{{ statusClasses($user->status ?? 'active') }}">
                                        {{ ucfirst($user->status ?? 'Active') }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <div class="inline-flex items-center gap-2" x-data="{ open: false }">
                                        <button class="table-action-btn" 
                                                title="Delete"
                                                onclick="if(confirm('Are you sure you want to delete this user?')) { alert('Delete functionality to be implemented'); }">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6l-1 14H6L5 6"></path>
                                                <path d="M10 11v6M14 11v6"></path>
                                                <path d="M9 6l1-2h4l1 2"></path>
                                            </svg>
                                        </button>
                                        <a href="{{ route('admin.users.edit', $user) }}" class="table-action-btn" title="View / Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M12 20h9"></path>
                                                <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path>
                                            </svg>
                                        </a>

                                        <div class="relative">
                                            <button class="table-action-btn"
                                                    @click="open = !open"
                                                    @click.outside="open = false"
                                                    title="More">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor">
                                                    <circle cx="12" cy="5" r="1.5"></circle>
                                                    <circle cx="12" cy="12" r="1.5"></circle>
                                                    <circle cx="12" cy="19" r="1.5"></circle>
                                                </svg>
                                            </button>
                                            <div x-show="open"
                                                 x-transition
                                                 class="absolute right-0 mt-2 w-36 bg-white border border-[var(--border-color)] rounded-md shadow-lg z-20">
                                                <a href="{{ route('admin.users.show', $user) }}"
                                                   class="block px-3 py-2 text-[13px] hover:bg-gray-100">
                                                    View Details
                                                </a>
                                                <a href="javascript:void(0)"
                                                   class="block px-3 py-2 text-[13px] hover:bg-gray-100">
                                                    Suspend
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-8 text-gray-500">
                                    No users found
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- Table Footer --}}
            <div class="table-footer">
                <div>
                    Showing <span class="font-medium">1</span> to <span class="font-medium">10</span> of
                    <span class="font-medium">10</span> entries
                </div>
                <div class="pagination">
                    <button disabled>First</button>
                    <button disabled>Prev</button>
                    <button class="page-current">1</button>
                    <button>2</button>
                    <button>3</button>
                    <button>4</button>
                    <button>5</button>
                    <button>Next</button>
                    <button>Last</button>
                </div>
            </div>
        </section>


        {{-- Add Role Modal --}}
        <template x-if="showAddRoleModal">
            <div class="modal-backdrop">
                <div class="modal" @click.stop>
                    <button class="modal-close-btn"
                            @click="showAddRoleModal = false">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>

                    <form id="add-role-form">
                        <div>
                            <h4>Add New Role</h4>
                            <p class="modal-subtitle">Set role permissions</p>
                        </div>

                        <div class="mt-6">
                            <label for="role_name" class="form-label">Role Name</label>
                            <input type="text" id="role_name" name="role_name" class="form-input" placeholder="Enter role name">
                            <div id="role_name_error" class="form-error"></div>
                        </div>

                        <div class="permissions-header">
                            <div>
                                <div class="permissions-title">Role Permissions</div>
                                <div class="permissions-subtitle">
                                    Configure what this role can view, create and manage in the system.
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-[13px] text-[var(--text-medium)] flex items-center">
                                    Administrator Access
                                    <span class="tooltip-icon" title="Allows full access to all permissions.">?</span>
                                </span>
                                <label class="flex items-center gap-1 text-[13px] cursor-pointer">
                                    <input type="checkbox" id="select_all_permissions" class="permissions-checkbox">
                                    <span>Select All</span>
                                </label>
                            </div>
                        </div>

                        <table class="permissions-table">
                            <thead>
                                <tr>
                                    <th class="text-left">Permission</th>
                                    <th class="permissions-actions permissions-gap">Read</th>
                                    <th class="permissions-actions permissions-gap">Write</th>
                                    <th class="permissions-actions permissions-gap">Create</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $permissions = [
                                        'User Management',
                                        'Content Management',
                                        'Disputes Management',
                                        'Database Management',
                                        'Financial Management',
                                        'Reporting',
                                        'API Control',
                                        'Repository Management',
                                        'Payroll',
                                    ];
                                @endphp
                                @foreach($permissions as $index => $permission)
                                    <tr>
                                        <td>{{ $permission }}</td>
                                        <td class="permissions-actions permissions-gap">
                                            <input type="checkbox" class="permissions-checkbox perm-checkbox" data-perm-row="{{ $index }}">
                                        </td>
                                        <td class="permissions-actions permissions-gap">
                                            <input type="checkbox" class="permissions-checkbox perm-checkbox" data-perm-row="{{ $index }}">
                                        </td>
                                        <td class="permissions-actions permissions-gap">
                                            <input type="checkbox" class="permissions-checkbox perm-checkbox" data-perm-row="{{ $index }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="modal-footer">
                            <button type="submit" class="btn-primary">Submit</button>
                            <button type="button"
                                    class="btn-secondary"
                                    @click="showAddRoleModal = false">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Batch user selection
        const selectAllUsers = document.getElementById('select-all-users');
        if (selectAllUsers) {
            selectAllUsers.addEventListener('change', function () {
                document.querySelectorAll('.user-row-checkbox').forEach(cb => {
                    cb.checked = selectAllUsers.checked;
                });
            });
        }

        // Add Role modal form validation
        const addRoleForm = document.getElementById('add-role-form');
        if (addRoleForm) {
            addRoleForm.addEventListener('submit', function (e) {
                const roleNameInput = document.getElementById('role_name');
                const errorElement = document.getElementById('role_name_error');
                let hasError = false;

                if (!roleNameInput.value.trim()) {
                    hasError = true;
                    errorElement.textContent = 'Role name is required.';
                } else {
                    errorElement.textContent = '';
                }

                if (hasError) {
                    e.preventDefault();
                } else {
                    // For now prevent real submit; you can remove this when wiring backend
                    e.preventDefault();
                    alert('Role submitted (demo). Wire this to your backend.');
                }
            });
        }

        // Select all permissions
        const selectAllPerms = document.getElementById('select_all_permissions');
        if (selectAllPerms) {
            selectAllPerms.addEventListener('change', function () {
                document.querySelectorAll('.perm-checkbox').forEach(cb => {
                    cb.checked = selectAllPerms.checked;
                });
            });
        }
    });
</script>
@endpush
