<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $title ?? 'Admin Panel' }} - {{ \App\Models\Setting::where('key', 'meta_title')->value('value') ?: (\App\Models\Setting::where('key', 'site_title')->value('value') ?: 'WeLiveCrypto') }}</title>
        <meta name="description" content="{{ \App\Models\Setting::where('key', 'meta_description')->value('value') ?: '' }}">
        
        <!-- Tailwind CSS via CDN for Quick Dev -->

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        @if(isset($settings['font_url']) && $settings['font_url'])
            <link href="{{ $settings['font_url'] }}" rel="stylesheet">
        @elseif(isset($settings['font_family']))
            <link href="https://fonts.googleapis.com/css2?family={{ str_replace(' ', '+', $settings['font_family']) }}&display=swap" rel="stylesheet">
        @else
            <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
        @endif
        @stack('styles')
    </head>
    <body class="bg-gray-50 text-gray-900 font-sans antialiased" style="background-color: {{ $settings['background_color'] ?? '#f9fafb' }}; font-family: '{{ $settings['font_family'] ?? 'Inter' }}', sans-serif; font-size: {{ $settings['base_font_size'] ?? '16px' }};">
        @props([
            'title' => 'Admin',
            'subtitle' => null,
        ])

        @php
            $navBase = 'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium';
            $navActive = 'bg-gray-100 text-gray-900';
            $navIdle = 'text-gray-700 hover:bg-gray-50 hover:text-gray-900';
        @endphp

        <div class="min-h-screen bg-gray-50">
            <!-- COPIED TOP NAV FROM app.blade.php -->
            <nav class="bg-white shadow-sm border-b border-gray-200 z-50 relative">
                <div class="container mx-auto px-4">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <div class="shrink-0 flex items-center">
                                <a href="{{ route('home') }}" class="font-bold text-xl text-blue-600">
                                    @if(isset($settings['logo']) && $settings['logo'])
                                        <img src="{{ asset('storage/' . $settings['logo']) }}" alt="WeLiveCrypto" class="h-8 w-auto">
                                    @else
                                        {{ \App\Models\Setting::where('key', 'site_title')->value('value') ?: config('app.name', 'WeLiveCrypto') }}
                                    @endif
                                </a>
                            </div>
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <a href="{{ route('home') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                    Catalog
                                </a>
                                <a href="{{ rtrim((string) config('smf.url', 'http://localhost/wlc/forum'), '/') }}/" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                    Forum
                                </a>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            @auth
                                <a href="{{ route('projects.submit') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                                    + Submit Project
                                </a>
                                
                                @if(Auth::user()->isModerator())
                                    <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-red-600 hover:text-red-800">
                                        Admin Dashboard
                                    </a>
                                @endif
 
                                <div class="text-sm text-gray-500">
                                    Hello, {{ Auth::user()->name }}
                                </div>
 
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="text-sm text-gray-600 hover:text-gray-900">
                                        Logout
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">Login / Register</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </nav>
            <!-- END TOP NAV -->
 
            <input id="admin-sidebar-toggle" type="checkbox" class="peer sr-only" />
 
            <!-- Mobile overlay (tap to close) -->
            <label for="admin-sidebar-toggle" class="fixed inset-0 z-40 hidden bg-black/40 peer-checked:block lg:hidden" aria-hidden="true"></label>
 
            <!-- Mobile top bar -->
            <div class="sticky top-0 z-30 border-b border-gray-200 bg-white lg:hidden">
                <div class="mx-auto flex max-w-7xl items-center gap-3 px-4 py-3">
                    <label for="admin-sidebar-toggle" class="inline-flex items-center justify-center rounded-md border border-gray-200 bg-white p-2 text-gray-700 shadow-sm hover:bg-gray-50">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M3 5h14a1 1 0 110 2H3a1 1 0 110-2zm0 4h14a1 1 0 110 2H3a1 1 0 110-2zm0 4h14a1 1 0 110 2H3a1 1 0 110-2z" clip-rule="evenodd" />
                        </svg>
                    </label>
                    <div class="min-w-0 flex-1">
                        <div class="truncate text-base font-semibold text-gray-900">{{ $title }}</div>
                        @if($subtitle)
                            <div class="truncate text-xs text-gray-500">{{ $subtitle }}</div>
                        @endif
                    </div>
                </div>
            </div>
 
            <div class="mx-auto flex max-w-7xl gap-6 px-4 py-6 lg:px-6">
                <!-- Sidebar -->
                <aside
                    class="fixed inset-y-0 left-0 z-50 w-72 overflow-y-auto border-r border-gray-200 bg-white px-4 py-6 shadow-lg transition-transform duration-200 ease-out -translate-x-full peer-checked:translate-x-0 lg:static lg:translate-x-0 lg:shadow-none"
                    aria-label="Admin sidebar"
                >
                    <div class="mb-6 flex items-center justify-between">
                        <div>
                            <div class="text-lg font-bold text-gray-900">Admin</div>
                            <div class="text-xs text-gray-500">
                                @auth
                                    {{ Auth::user()->name }} • {{ Auth::user()->role }}
                                @else
                                    Guest
                                @endauth
                            </div>
                        </div>
                        <label for="admin-sidebar-toggle" class="lg:hidden inline-flex items-center justify-center rounded-md border border-gray-200 bg-white p-2 text-gray-700 shadow-sm hover:bg-gray-50">
                            <span class="sr-only">Close sidebar</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </label>
                    </div>
 
                    <nav class="space-y-1">
                        <a href="{{ route('admin.dashboard') }}" class="{{ $navBase }} {{ request()->routeIs('admin.dashboard') ? $navActive : $navIdle }}">
                            <span class="h-2.5 w-2.5 rounded-full bg-blue-500"></span>
                            Dashboard
                        </a>
 
                        <a href="{{ route('admin.users') }}" class="{{ $navBase }} {{ request()->routeIs('admin.users') ? $navActive : $navIdle }}">
                            <span class="h-2.5 w-2.5 rounded-full bg-indigo-500"></span>
                            User settings
                        </a>
 
                        <a href="{{ route('admin.settings') }}" class="{{ $navBase }} {{ request()->routeIs('admin.settings') ? $navActive : $navIdle }}">
                            <span class="h-2.5 w-2.5 rounded-full bg-purple-500"></span>
                            Settings
                        </a>
 
                        <a href="{{ route('admin.permissions') }}" class="{{ $navBase }} {{ request()->routeIs('admin.permissions') ? $navActive : $navIdle }}">
                            <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                            Permissions
                        </a>
 
                        <a href="{{ route('admin.categories') }}" class="{{ $navBase }} {{ request()->routeIs('admin.categories') ? $navActive : $navIdle }}">
                            <span class="h-2.5 w-2.5 rounded-full bg-yellow-500"></span>
                            Categories
                        </a>
 
                        <a href="{{ route('admin.fields') }}" class="{{ $navBase }} {{ request()->routeIs('admin.fields') ? $navActive : $navIdle }}">
                            <span class="h-2.5 w-2.5 rounded-full bg-pink-500"></span>
                            Fields
                        </a>
 
                        <a href="{{ route('admin.reputation-privacy') }}" class="{{ $navBase }} {{ request()->routeIs('admin.reputation-privacy') ? $navActive : $navIdle }}">
                            <span class="h-2.5 w-2.5 rounded-full bg-cyan-500"></span>
                            Reputation & Privacy
                        </a>
 
                        <a href="{{ route('admin.projects') }}" class="{{ $navBase }} {{ request()->routeIs('admin.projects*') ? $navActive : $navIdle }}">
                            <span class="h-2.5 w-2.5 rounded-full bg-orange-500"></span>
                            Projects
                        </a>

                        <a href="{{ route('admin.reports') }}" class="{{ $navBase }} {{ request()->routeIs('admin.reports*') ? $navActive : $navIdle }} flex justify-between items-center">
                            <span class="flex items-center gap-3">
                                <span class="h-2.5 w-2.5 rounded-full bg-red-500"></span>
                                Reports
                            </span>
                            @php $pendingReportsCount = \App\Models\ProjectReport::where('status', 'pending')->count(); @endphp
                            @if($pendingReportsCount > 0)
                                <span class="inline-flex items-center justify-center px-2 py-0.5 text-[10px] font-black leading-none bg-red-100 text-red-800 border border-red-200 rounded-full animate-pulse">
                                    {{ $pendingReportsCount }}
                                </span>
                            @endif
                        </a>
 
                        <a href="{{ route('admin.pages') }}" class="{{ $navBase }} {{ request()->routeIs('admin.pages*') ? $navActive : $navIdle }}">
                            <span class="h-2.5 w-2.5 rounded-full bg-gray-500"></span>
                            Pages
                        </a>
 
                        @if(request()->routeIs('admin.dashboard'))
                            <div class="pt-4">
                                <div class="px-3 pb-2 text-xs font-semibold uppercase tracking-wider text-gray-500">Projects</div>
                                <a href="{{ route('admin.projects') }}" class="{{ $navBase }} {{ request()->routeIs('admin.projects*') ? $navActive : $navIdle }}">
                                    Projects List
                                </a>
                            </div>
                        @endif
 
                        <div class="pt-4">
                            <div class="px-3 pb-2 text-xs font-semibold uppercase tracking-wider text-gray-500">Quick links</div>
                            <a href="{{ route('admin.clear-cache') }}" class="{{ $navBase }} {{ $navIdle }} text-red-600 hover:text-red-700">
                                <span class="h-2.5 w-2.5 rounded-full bg-red-500"></span>
                                Clear All Cache
                            </a>
                            <a href="{{ route('projects.submit') }}" class="{{ $navBase }} {{ $navIdle }}">
                                Submit project
                            </a>
                            <a href="{{ route('home') }}" class="{{ $navBase }} {{ $navIdle }}">
                                Back to catalog
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                                @csrf
                                <button type="submit" class="{{ $navBase }} w-full {{ $navIdle }}">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </nav>
                </aside>
 
                <!-- Content -->
                <div class="min-w-0 flex-1 relative">
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6 lg:p-8 h-full">
                        <div class="mb-6 hidden lg:block">
                            <h1 class="text-3xl font-bold text-gray-800">{{ $title }}</h1>
                            @if($subtitle)
                                <p class="mt-1 text-sm text-gray-500">{{ $subtitle }}</p>
                            @endif
                        </div>
 
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
        </div>
        <script src="{{ asset('vendor/livewire/livewire.js') }}" data-csrf="{{ csrf_token() }}" data-update-uri="{{ url('/livewire/update') }}" data-navigate-once="true"></script>
        @stack('scripts')
    </body>
</html>
