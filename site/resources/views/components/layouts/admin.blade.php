@props(['title' => 'Admin'])
<!doctype html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $title }} — Hotel Benizia Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">

    @if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="h-full font-sans antialiased" x-data="{ sidebarOpen: false }">

<div class="flex h-full min-h-screen">

    {{-- ── Mobile sidebar overlay ── --}}
    <div x-show="sidebarOpen"
         x-cloak
         class="fixed inset-0 z-40 bg-gray-900/60 lg:hidden"
         @click="sidebarOpen = false">
    </div>

    {{-- ── Sidebar ── --}}
    <aside id="admin-sidebar"
           class="fixed inset-y-0 left-0 z-50 w-64 flex flex-col bg-[#7C0E52] text-white shadow-xl
                  transform transition-transform duration-300 ease-in-out
                  lg:static lg:translate-x-0"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

        {{-- Logo / Brand --}}
        <div class="flex h-16 items-center gap-3 border-b border-white/20 px-6">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#C79A46]">
                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a8 8 0 100 16A8 8 0 0010 2zM8 12V8l4 2-4 2z"/>
                </svg>
            </div>
            <span class="text-sm font-semibold tracking-wide">Benizia Admin</span>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto py-4 px-3">
            <ul class="space-y-1">

                <li>
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium
                              {{ request()->routeIs('admin.dashboard') ? 'bg-white/20 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }}
                              transition-colors duration-150">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>
                </li>

                @if(Route::has('admin.rooms.index'))
                <li>
                    <a href="{{ route('admin.rooms.index') }}"
                       class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium
                              {{ request()->routeIs('admin.rooms.*') ? 'bg-white/20 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }}
                              transition-colors duration-150">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                        </svg>
                        Rooms
                    </a>
                </li>
                @else
                <li>
                    <span class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-white/40 cursor-not-allowed">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                        </svg>
                        Rooms
                    </span>
                </li>
                @endif

                @if(Route::has('admin.apartments.index'))
                <li>
                    <a href="{{ route('admin.apartments.index') }}"
                       class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium
                              {{ request()->routeIs('admin.apartments.*') ? 'bg-white/20 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }}
                              transition-colors duration-150">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Apartments
                    </a>
                </li>
                @else
                <li>
                    <span class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-white/40 cursor-not-allowed">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Apartments
                    </span>
                </li>
                @endif

                @if(Route::has('admin.availability.index'))
                <li>
                    <a href="{{ route('admin.availability.index') }}"
                       class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium
                              {{ request()->routeIs('admin.availability.*') ? 'bg-white/20 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }}
                              transition-colors duration-150">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Availability
                    </a>
                </li>
                @endif

                @if(Route::has('admin.bookings.index'))
                <li>
                    <a href="{{ route('admin.bookings.index') }}"
                       class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium
                              {{ request()->routeIs('admin.bookings.*') ? 'bg-white/20 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }}
                              transition-colors duration-150">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Bookings
                    </a>
                </li>
                @else
                <li>
                    <span class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-white/40 cursor-not-allowed">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Bookings
                    </span>
                </li>
                @endif

                @if(Route::has('admin.payment-methods.index'))
                <li>
                    <a href="{{ route('admin.payment-methods.index') }}"
                       class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium
                              {{ request()->routeIs('admin.payment-methods.*') ? 'bg-white/20 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }}
                              transition-colors duration-150">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        Payment Methods
                    </a>
                </li>
                @else
                <li>
                    <span class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-white/40 cursor-not-allowed">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        Payment Methods
                    </span>
                </li>
                @endif

                @if(Route::has('admin.blog.index'))
                <li>
                    <a href="{{ route('admin.blog.index') }}"
                       class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium
                              {{ request()->routeIs('admin.blog.*') ? 'bg-white/20 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }}
                              transition-colors duration-150">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                        Blog
                    </a>
                </li>
                @else
                <li>
                    <span class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-white/40 cursor-not-allowed">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                        Blog
                    </span>
                </li>
                @endif

                @if(Route::has('admin.testimonials.index'))
                <li>
                    <a href="{{ route('admin.testimonials.index') }}"
                       class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium
                              {{ request()->routeIs('admin.testimonials.*') ? 'bg-white/20 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }}
                              transition-colors duration-150">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        Testimonials
                    </a>
                </li>
                @else
                <li>
                    <span class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-white/40 cursor-not-allowed">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        Testimonials
                    </span>
                </li>
                @endif

                @if(Route::has('admin.faqs.index'))
                <li>
                    <a href="{{ route('admin.faqs.index') }}"
                       class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium
                              {{ request()->routeIs('admin.faqs.*') ? 'bg-white/20 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }}
                              transition-colors duration-150">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        FAQs
                    </a>
                </li>
                @else
                <li>
                    <span class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-white/40 cursor-not-allowed">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        FAQs
                    </span>
                </li>
                @endif

                @if(Route::has('admin.messages.index'))
                <li>
                    <a href="{{ route('admin.messages.index') }}"
                       class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium
                              {{ request()->routeIs('admin.messages.*') ? 'bg-white/20 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }}
                              transition-colors duration-150">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Messages
                    </a>
                </li>
                @else
                <li>
                    <span class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-white/40 cursor-not-allowed">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Messages
                    </span>
                </li>
                @endif

                <li>
                    <a href="{{ route('admin.account') }}"
                       class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium
                              {{ request()->routeIs('admin.account') ? 'bg-white/20 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }}
                              transition-colors duration-150">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Account
                    </a>
                </li>

            </ul>
        </nav>

        {{-- Bottom: Public site link --}}
        <div class="border-t border-white/20 p-4">
            <a href="{{ route('home') }}"
               target="_blank"
               class="flex items-center gap-2 text-xs text-white/60 hover:text-white transition-colors">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                View Public Site
            </a>
        </div>
    </aside>

    {{-- ── Main content area ── --}}
    <div class="flex flex-1 flex-col min-w-0 overflow-hidden">

        {{-- Top bar --}}
        <header class="flex h-16 items-center justify-between border-b border-gray-200 bg-white px-4 lg:px-6 shadow-sm">

            {{-- Mobile menu button --}}
            <button @click="sidebarOpen = !sidebarOpen"
                    class="lg:hidden rounded-md p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- Page title --}}
            <h1 class="text-lg font-semibold text-gray-800 lg:ml-0 ml-3">{{ $title }}</h1>

            {{-- Right: admin name + logout --}}
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.account') }}"
                   class="hidden sm:block text-sm text-gray-600 hover:text-[#7C0E52] transition-colors">
                    {{ auth()->user()?->name ?? 'Admin' }}
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 rounded-lg bg-[#7C0E52] px-3 py-1.5 text-xs font-medium text-white hover:bg-[#560A3A] transition-colors">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </header>

        {{-- Page content --}}
        <main class="flex-1 overflow-y-auto p-4 lg:p-6">
            @if(session('status'))
                <div class="mb-4 rounded-lg bg-emerald-50 px-4 py-3 text-sm text-emerald-700 ring-1 ring-emerald-200">
                    {{ session('status') }}
                </div>
            @endif
            {{ $slot }}
        </main>

    </div>
</div>

</body>
</html>
