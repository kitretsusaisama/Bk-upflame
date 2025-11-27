@extends('dashboard.layout')

@section('page-title', 'Users')

@section('content')
<div class="sm:flex sm:items-center">
    <div class="sm:flex-auto">
        <p class="mt-2 text-sm text-gray-700">A list of all users in your organization including their name, email, and role.</p>
    </div>
    <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
        <a href="{{ route('tenant.users.create') }}" class="block rounded-md bg-blue-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
            <span class="iconify inline mr-1" data-icon="heroicons:plus"></span> New User
        </a>
    </div>
</div>

<div class="mt-8 flow-root">
    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Name</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Email</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Role</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Created</th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($users as $user)
                        <tr>
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ $user->name }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $user->email }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                {{ $user->roles->pluck('name')->join(', ') ?: 'User' }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                <div x-data="{ open: false }" class="relative inline-block text-left">
                                    <button @click="open = !open" @click.outside="open = false" type="button" class="text-gray-400 hover:text-gray-600">
                                        <span class="iconify h-5 w-5" data-icon="heroicons:ellipsis-vertical"></span>
                                    </button>
                                    
                                    <div x-show="open" x-transition x-cloak class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                        <div class="py-1">
                                            <a href="{{ route('tenant.users.edit', $user) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit</a>
                                            
                                            @can('impersonate.user')
                                                <form action="{{ route('impersonate.start', $user) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Impersonate</button>
                                                </form>
                                            @endcan
                                            
                                            <form action="{{ route('tenant.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-gray-100">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
