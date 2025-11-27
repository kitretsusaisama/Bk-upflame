@extends('dashboard.layout')

@section('page-title', 'Edit Tenant')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.tenants.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">
            <span class="iconify inline mr-1" data-icon="heroicons:arrow-left"></span> Back to Tenants
        </a>
    </div>

    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2">
        <div class="px-4 py-6 sm:p-8">
            <form action="{{ route('admin.tenants.update', $tenant) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-4">
                        <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Tenant Name</label>
                        <div class="mt-2">
                            <input type="text" name="name" id="name" value="{{ old('name', $tenant->name) }}" required
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 @error('name') ring-red-300 focus:ring-red-600 @enderror">
                        </div>
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-4">
                        <label for="domain" class="block text-sm font-medium leading-6 text-gray-900">Domain</label>
                        <div class="mt-2">
                            <div class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-blue-600 sm:max-w-md">
                                <span class="flex select-none items-center pl-3 text-gray-500 sm:text-sm">https://</span>
                                <input type="text" name="domain" id="domain" value="{{ old('domain', $tenant->domain) }}" required
                                    class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" placeholder="acme.example.com">
                            </div>
                        </div>
                        @error('domain')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="status" class="block text-sm font-medium leading-6 text-gray-900">Status</label>
                        <div class="mt-2">
                            <select id="status" name="status" required
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                <option value="active" {{ old('status', $tenant->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $tenant->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="suspended" {{ old('status', $tenant->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                            </select>
                        </div>
                        @error('status')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="tier" class="block text-sm font-medium leading-6 text-gray-900">Subscription Tier</label>
                        <div class="mt-2">
                            <select id="tier" name="tier" required
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                <option value="free" {{ old('tier', $tenant->tier) == 'free' ? 'selected' : '' }}>Free</option>
                                <option value="standard" {{ old('tier', $tenant->tier) == 'standard' ? 'selected' : '' }}>Standard</option>
                                <option value="premium" {{ old('tier', $tenant->tier) == 'premium' ? 'selected' : '' }}>Premium</option>
                                <option value="enterprise" {{ old('tier', $tenant->tier) == 'enterprise' ? 'selected' : '' }}>Enterprise</option>
                            </select>
                        </div>
                        @error('tier')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-end gap-x-6">
                    <a href="{{ route('admin.tenants.index') }}" class="text-sm font-semibold leading-6 text-gray-900">Cancel</a>
                    <button type="submit" class="rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Update Tenant</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
