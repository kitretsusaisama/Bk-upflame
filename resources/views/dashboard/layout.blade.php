<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ config('app.name') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; }
    </style>
    
    @stack('styles')
</head>
<body class="h-full" x-data="layout">
    
    <!-- Off-canvas menu for mobile -->
    <div class="relative z-50 lg:hidden" role="dialog" aria-modal="true" x-show="sidebarOpen" x-cloak>
        <div class="fixed inset-0 bg-gray-900/80" x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false"></div>

        <div class="fixed inset-0 flex" x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">
            <div class="relative mr-16 flex w-full max-w-xs flex-1">
                <div class="absolute top-0 left-full flex w-16 justify-center pt-5">
                    <button type="button" class="-m-2.5 p-2.5" @click="sidebarOpen = false">
                        <span class="sr-only">Close sidebar</span>
                        <span class="iconify text-white h-6 w-6" data-icon="heroicons:x-mark"></span>
                    </button>
                </div>
                
                <!-- Mobile Sidebar Content -->
                <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-slate-900 px-6 pb-4 ring-1 ring-white/10">
                    <div class="flex h-16 shrink-0 items-center">
                        <span class="text-white font-bold text-xl tracking-tight">Enterprise SaaS</span>
                    </div>
                    <nav class="flex flex-1 flex-col">
                        @include('dashboard.partials.sidebar')
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Static sidebar for desktop -->
    <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
        <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-slate-900 px-6 pb-4">
            <div class="flex h-16 shrink-0 items-center">
                 <span class="text-white font-bold text-xl tracking-tight">Enterprise SaaS</span>
            </div>
            <nav class="flex flex-1 flex-col">
                @include('dashboard.partials.sidebar')
            </nav>
        </div>
    </div>

    <div class="lg:pl-72">
        <div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
            <button type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden" @click="sidebarOpen = true">
                <span class="sr-only">Open sidebar</span>
                <span class="iconify h-6 w-6" data-icon="heroicons:bars-3"></span>
            </button>

            <!-- Separator -->
            <div class="h-6 w-px bg-gray-200 lg:hidden" aria-hidden="true"></div>

            <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                <div class="flex flex-1 items-center">
                    <h1 class="text-2xl font-semibold text-gray-900">@yield('page-title')</h1>
                </div>
                <div class="flex items-center gap-x-4 lg:gap-x-6">
                    <!-- Profile dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button type="button" class="-m-1.5 flex items-center p-1.5" @click="open = !open" @click.outside="open = false">
                            <span class="sr-only">Open user menu</span>
                            <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 font-bold">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <span class="hidden lg:flex lg:items-center">
                                <span class="ml-4 text-sm font-semibold leading-6 text-gray-900" aria-hidden="true">{{ auth()->user()->name }}</span>
                                <span class="iconify ml-2 h-5 w-5 text-gray-400" data-icon="heroicons:chevron-down"></span>
                            </span>
                        </button>
                        
                        <div class="absolute right-0 z-10 mt-2.5 w-32 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none" x-show="open" x-transition x-cloak>
                            <a href="#" class="block px-3 py-1 text-sm leading-6 text-gray-900 hover:bg-gray-50" @click.prevent="logoutModalOpen = true">Sign out</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <main class="py-10">
            <div class="px-4 sm:px-6 lg:px-8">
                <!-- Impersonation Banner -->
                @if(session('impersonating') || isset($impersonating) && $impersonating)
                <div class="rounded-md bg-yellow-50 p-4 mb-6 border border-yellow-200">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <span class="iconify text-yellow-400 h-5 w-5" data-icon="heroicons:exclamation-triangle"></span>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Impersonation Active</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>You are currently impersonating <strong>{{ auth()->user()->name }}</strong>. <a href="{{ route('impersonate.stop') }}" class="font-medium underline hover:text-yellow-600">Stop Impersonating</a></p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="rounded-md bg-green-50 p-4 mb-6 border border-green-200">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <span class="iconify text-green-400 h-5 w-5" data-icon="heroicons:check-circle"></span>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="rounded-md bg-red-50 p-4 mb-6 border border-red-200">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <span class="iconify text-red-400 h-5 w-5" data-icon="heroicons:x-circle"></span>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Logout Modal -->
    <div class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-show="logoutModalOpen" x-cloak>
        <div class="fixed inset-0 bg-gray-500/75 transition-opacity" x-show="logoutModalOpen" x-transition.opacity></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6" x-show="logoutModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" @click.outside="logoutModalOpen = false">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <span class="iconify text-red-600 h-6 w-6" data-icon="heroicons:exclamation-triangle"></span>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Sign out</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Are you sure you want to sign out of your account?</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <form action="{{ route('logout') }}" method="POST" id="logout-form">
                            @csrf
                            <button type="submit" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">Sign out</button>
                        </form>
                        <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto" @click="logoutModalOpen = false">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Idle Timeout Warning Modal -->
    <div class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-show="idleWarningOpen" x-cloak>
        <div class="fixed inset-0 bg-gray-500/75 transition-opacity" x-show="idleWarningOpen" x-transition.opacity></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6" x-show="idleWarningOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                            <span class="iconify text-yellow-600 h-6 w-6" data-icon="heroicons:clock"></span>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Session Expiring</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">You have been inactive for a while. For your security, you will be automatically logged out soon.</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <button type="button" class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto" @click="resetTimer()">Stay Logged In</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @stack('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('layout', () => ({
                sidebarOpen: false,
                logoutModalOpen: false,
                idleWarningOpen: false,
                idleTime: 0,
                timeout: {{ config('dashboard.idle_timeout', 120) }} * 60 * 1000, // milliseconds
                warningPercent: {{ config('dashboard.inactivity_warning_percent', 90) }},
                interval: null,

                init() {
                    this.startTimer();
                    ['mousemove', 'mousedown', 'keypress', 'DOMMouseScroll', 'mousewheel', 'touchmove', 'MSPointerMove'].forEach(evt => {
                        document.addEventListener(evt, () => this.resetTimer(), false);
                    });
                },

                startTimer() {
                    this.interval = setInterval(() => {
                        this.idleTime += 1000;
                        this.checkTimeout();
                    }, 1000);
                },

                resetTimer() {
                    this.idleTime = 0;
                    this.idleWarningOpen = false;
                },

                checkTimeout() {
                    const warningTime = this.timeout * (this.warningPercent / 100);
                    if (this.idleTime >= this.timeout) {
                        document.getElementById('logout-form').submit();
                    } else if (this.idleTime >= warningTime) {
                        this.idleWarningOpen = true;
                    }
                }
            }))
        })
    </script>
</body>
</html>
