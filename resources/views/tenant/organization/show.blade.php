@extends('dashboard.layout')

@section('page-title', 'Organization Profile')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2">
        <div class="px-4 py-6 sm:p-8">
            <form action="{{ route('tenant.organization.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-4">
                        <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Organization Name</label>
                        <div class="mt-2">
                            <input type="text" name="name" id="name" value="{{ old('name', $tenant->name) }}" required
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 @error('name') ring-red-300 focus:ring-red-600 @enderror">
                        </div>
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-4">
                        <label class="block text-sm font-medium leading-6 text-gray-900">Domain</label>
                        <div class="mt-2">
                            <input type="text" value="{{ $tenant->domain }}" disabled
                                class="block w-full rounded-md border-0 py-1.5 text-gray-500 bg-gray-50 shadow-sm ring-1 ring-inset ring-gray-300 sm:text-sm sm:leading-6 cursor-not-allowed">
                        </div>
                        <p class="mt-2 text-sm text-gray-500">Domain cannot be changed. Contact support for assistance.</p>
                    </div>

                    <div class="sm:col-span-3">
                        <label class="block text-sm font-medium leading-6 text-gray-900">Status</label>
                        <div class="mt-2">
                            <input type="text" value="{{ ucfirst($tenant->status) }}" disabled
                                class="block w-full rounded-md border-0 py-1.5 text-gray-500 bg-gray-50 shadow-sm ring-1 ring-inset ring-gray-300 sm:text-sm sm:leading-6 cursor-not-allowed">
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label class="block text-sm font-medium leading-6 text-gray-900">Subscription Tier</label>
                        <div class="mt-2">
                            <input type="text" value="{{ ucfirst($tenant->tier) }}" disabled
                                class="block w-full rounded-md border-0 py-1.5 text-gray-500 bg-gray-50 shadow-sm ring-1 ring-inset ring-gray-300 sm:text-sm sm:leading-6 cursor-not-allowed">
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-end gap-x-6">
                    <button type="submit" class="rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Update Profile</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
