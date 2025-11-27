@extends('dashboard.layout')

@section('page-title', 'Tenant Details')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.tenants.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">
            <span class="iconify inline mr-1" data-icon="heroicons:arrow-left"></span> Back to Tenants
        </a>
        <a href="{{ route('admin.tenants.edit', $tenant) }}" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
            Edit Tenant
        </a>
    </div>

    <!-- Tenant Details -->
    <div class="overflow-hidden bg-white shadow sm:rounded-lg mb-8">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-base font-semibold leading-6 text-gray-900">Tenant Information</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Details and subscription status.</p>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Tenant Name</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $tenant->name }}</dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Domain</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $tenant->domain }}</dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                        <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $tenant->status === 'active' ? 'bg-green-50 text-green-700 ring-green-600/20' : 'bg-gray-50 text-gray-600 ring-gray-500/10' }}">
                            {{ ucfirst($tenant->status) }}
                        </span>
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Subscription Tier</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ ucfirst($tenant->tier) }}</dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Created At</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $tenant->created_at->format('F j, Y, g:i a') }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Tenant Users -->
    <div class="sm:flex sm:items-center mb-4">
        <div class="sm:flex-auto">
            <h3 class="text-base font-semibold leading-6 text-gray-900">Tenant Users</h3>
            <p class="mt-2 text-sm text-gray-700">Users associated with this tenant.</p>
        </div>
    </div>

    <div class="flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Name</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Email</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Role</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($tenant->users as $user)
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ $user->name }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $user->email }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    {{ $user->roles->pluck('name')->join(', ') ?: 'User' }}
                                </td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                    <form action="{{ route('impersonate.start', $user) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="text-blue-600 hover:text-blue-900">Impersonate</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-3 py-4 text-sm text-gray-500 text-center">No users found for this tenant.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
