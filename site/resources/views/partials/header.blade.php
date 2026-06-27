@php
    $phone     = config('hotel.phone', '+234 813 406 2487');
    $phoneHref = config('hotel.phone_href', '+2348134062487');
    $email     = config('hotel.email', 'booking@hotelbenizia.ng');
    $address   = config('hotel.address', '1 Kingsley Emu Street, Central Area, Asaba 320242, Delta State');
    $socials   = config('hotel.socials', []);
    $nav       = config('hotel.nav', []);
    $bookingUrl = route('rooms.index');
@endphp

<header
    class="fixed inset-x-0 top-0 z-50"
    x-data="{ open: false, scrolled: false }"
    x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 8 })"
>
    {{-- ── Top utility bar ── --}}
    <div class="bg-benizia-green text-xs text-white/85">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-3 px-4 py-2">
            <div class="flex items-center gap-5">
                {{-- Phone --}}
                <a href="tel:{{ $phoneHref }}"
                   class="flex items-center gap-2 transition hover:text-white"
                   aria-label="Call us">
                    <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                    </svg>
                    <span class="font-medium">{{ $phone }}</span>
                </a>

                {{-- Email --}}
                <a href="mailto:{{ $email }}"
                   class="hidden items-center gap-2 transition hover:text-white sm:flex"
                   aria-label="Email us">
                    <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                    </svg>
                    <span>{{ $email }}</span>
                </a>
            </div>

            {{-- Address (desktop only) --}}
            <div class="hidden items-center gap-2 md:flex text-white/70">
                <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                </svg>
                <span>{{ $address }}</span>
            </div>

            {{-- Socials --}}
            <div class="hidden items-center gap-3 lg:flex">
                @if(!empty($socials['facebook']))
                <a href="{{ $socials['facebook'] }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook" class="transition hover:text-benizia-gold">
                    <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878V14.89h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
                </a>
                @endif
                @if(!empty($socials['instagram']))
                <a href="{{ $socials['instagram'] }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram" class="transition hover:text-benizia-gold">
                    <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"/></svg>
                </a>
                @endif
                @if(!empty($socials['twitter']))
                <a href="{{ $socials['twitter'] }}" target="_blank" rel="noopener noreferrer" aria-label="Twitter / X" class="transition hover:text-benizia-gold">
                    <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </a>
                @endif
                @if(!empty($socials['youtube']))
                <a href="{{ $socials['youtube'] }}" target="_blank" rel="noopener noreferrer" aria-label="YouTube" class="transition hover:text-benizia-gold">
                    <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                </a>
                @endif
            </div>
        </div>
    </div>

    {{-- ── Main nav bar ── --}}
    <div
        class="border-b border-white/70 bg-white/95 backdrop-blur-xl transition-shadow duration-300"
        :class="scrolled ? 'shadow-md' : ''"
    >
        <div class="mx-auto flex h-20 max-w-7xl items-center justify-between px-4">

            {{-- Logo / Wordmark --}}
            <a href="{{ \Illuminate\Support\Facades\Route::has('home') ? route('home') : '/' }}"
               class="flex items-center gap-3 group"
               aria-label="Hotel Benizia — Home">
                <span class="grid h-11 w-11 place-items-center rounded-full bg-benizia-green text-sm font-bold text-benizia-gold transition group-hover:bg-benizia-charcoal">
                    HB
                </span>
                <span>
                    <span class="block font-serif text-2xl leading-none text-benizia-charcoal">Hotel Benizia</span>
                    <span class="block text-[10px] uppercase tracking-[0.35em] text-benizia-gold">Luxury Hotel · Asaba</span>
                </span>
            </a>

            {{-- Desktop Navigation --}}
            <nav class="hidden items-center gap-6 text-sm font-medium text-benizia-charcoal lg:flex" aria-label="Primary navigation">
                @foreach($nav as $item)
                    @php
                        $href = \Illuminate\Support\Facades\Route::has($item['route']) ? route($item['route']) : '#';
                        $isActive = \Illuminate\Support\Facades\Route::has($item['route']) && request()->routeIs($item['route']);
                    @endphp
                    <a href="{{ $href }}"
                       class="relative transition hover:text-benizia-green {{ $isActive ? 'text-benizia-green font-semibold' : '' }} group">
                        {{ $item['label'] }}
                        <span class="absolute -bottom-1 left-0 h-0.5 w-0 bg-benizia-gold transition-all duration-300 group-hover:w-full {{ $isActive ? 'w-full' : '' }}"></span>
                    </a>
                @endforeach
            </nav>

            {{-- Desktop Book Now CTA --}}
            <a href="{{ $bookingUrl }}"
               class="hidden rounded-full bg-benizia-gold px-6 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-benizia-green hover:shadow-md lg:inline-flex items-center gap-2">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                </svg>
                Book Now
            </a>

            {{-- Mobile Hamburger --}}
            <button
                @click="open = !open"
                :aria-expanded="open"
                aria-label="Toggle navigation menu"
                class="grid h-11 w-11 place-items-center rounded-full border border-benizia-gold/30 text-benizia-green transition hover:bg-benizia-green hover:text-white lg:hidden"
            >
                <svg x-show="!open" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                </svg>
                <svg x-show="open" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true" style="display:none">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- ── Mobile Menu Drawer ── --}}
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="border-t border-benizia-gold/20 bg-white px-4 py-5 lg:hidden"
            style="display:none"
            aria-label="Mobile navigation"
        >
            <div class="mx-auto grid max-w-7xl gap-1">
                @foreach($nav as $item)
                    @php
                        $href = \Illuminate\Support\Facades\Route::has($item['route']) ? route($item['route']) : '#';
                        $isActive = \Illuminate\Support\Facades\Route::has($item['route']) && request()->routeIs($item['route']);
                    @endphp
                    <a href="{{ $href }}"
                       @click="open = false"
                       class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium text-benizia-charcoal transition hover:bg-benizia-cream hover:text-benizia-green {{ $isActive ? 'bg-benizia-cream text-benizia-green font-semibold' : '' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
                <div class="mt-3 border-t border-benizia-gold/20 pt-4">
                    <a href="{{ $bookingUrl }}"
                       @click="open = false"
                       class="block rounded-full bg-benizia-gold py-3 text-center text-sm font-bold text-white transition hover:bg-benizia-green">
                        Book Now
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

{{-- Spacer to prevent content from hiding behind the fixed header (utility bar ~34px + nav bar 80px) --}}
<div class="h-[114px]" aria-hidden="true"></div>
