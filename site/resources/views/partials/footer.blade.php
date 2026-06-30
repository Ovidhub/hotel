@php
    $phone     = config('hotel.phone', '+234 813 406 2487');
    $phoneHref = config('hotel.phone_href', '+2348134062487');
    $email     = config('hotel.email', 'booking@hotelbenizia.ng');
    $address   = config('hotel.address', '1 Kingsley Emu Street, Central Area, Asaba 320242, Delta State');
    $socials   = config('hotel.socials', []);
    $nav       = config('hotel.nav', []);
    $tagline   = config('hotel.tagline', 'Luxury in the heart of Asaba');
    $year      = date('Y');
    $bookingUrl = route('rooms.index');
@endphp

<footer class="bg-benizia-green text-white" aria-label="Site footer">

    {{-- ── Main footer grid ── --}}
    <div class="mx-auto grid max-w-7xl gap-12 px-4 py-16 md:grid-cols-2 lg:grid-cols-[1.2fr_0.8fr_1fr_1fr]">

        {{-- Column 1: Brand --}}
        <div>
            <div class="flex items-center gap-3">
                <span class="grid h-12 w-12 place-items-center rounded-full bg-benizia-gold text-sm font-bold text-benizia-green">HB</span>
                <div>
                    <span class="block font-serif text-2xl leading-tight">Hotel Benizia</span>
                    <span class="block text-[10px] uppercase tracking-[0.35em] text-benizia-gold">Luxury Hotel · Asaba</span>
                </div>
            </div>
            <p class="mt-5 max-w-xs text-sm leading-7 text-white/70">
                Luxury hotel and <a href="{{ route('apartments.index') }}" class="text-benizia-gold underline-offset-2 hover:underline">serviced apartments in Asaba</a>, Delta State.
                Premium rooms, short-let apartments, dining, bar, pool, events, 24/7 security, and warm Nigerian hospitality.
            </p>
            <p class="mt-4 text-xs text-white/50 italic">{{ $tagline }}</p>

            {{-- Socials --}}
            <div class="mt-6 flex items-center gap-4">
                @if(!empty($socials['facebook']))
                <a href="{{ $socials['facebook'] }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook"
                   class="grid h-9 w-9 place-items-center rounded-full bg-white/10 transition hover:bg-benizia-gold">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878V14.89h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
                </a>
                @endif
                @if(!empty($socials['instagram']))
                <a href="{{ $socials['instagram'] }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram"
                   class="grid h-9 w-9 place-items-center rounded-full bg-white/10 transition hover:bg-benizia-gold">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"/></svg>
                </a>
                @endif
                @if(!empty($socials['twitter']))
                <a href="{{ $socials['twitter'] }}" target="_blank" rel="noopener noreferrer" aria-label="Twitter / X"
                   class="grid h-9 w-9 place-items-center rounded-full bg-white/10 transition hover:bg-benizia-gold">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </a>
                @endif
                @if(!empty($socials['youtube']))
                <a href="{{ $socials['youtube'] }}" target="_blank" rel="noopener noreferrer" aria-label="YouTube"
                   class="grid h-9 w-9 place-items-center rounded-full bg-white/10 transition hover:bg-benizia-gold">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                </a>
                @endif
                @if(!empty($socials['pinterest']))
                <a href="{{ $socials['pinterest'] }}" target="_blank" rel="noopener noreferrer" aria-label="Pinterest"
                   class="grid h-9 w-9 place-items-center rounded-full bg-white/10 transition hover:bg-benizia-gold">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 0C5.372 0 0 5.372 0 12c0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738a.36.36 0 01.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.632-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0z"/></svg>
                </a>
                @endif
            </div>
        </div>

        {{-- Column 2: Quick Links --}}
        <div>
            <h3 class="font-serif text-xl text-benizia-gold">Quick Links</h3>
            <nav class="mt-5 grid gap-2 text-sm text-white/70" aria-label="Footer navigation">
                @foreach($nav as $item)
                    @php
                        $href = \Illuminate\Support\Facades\Route::has($item['route']) ? route($item['route']) : '#';
                    @endphp
                    <a href="{{ $href }}" class="transition hover:text-benizia-gold hover:pl-1">{{ $item['label'] }}</a>
                @endforeach
            </nav>
        </div>

        {{-- Column 3: Contact Info --}}
        <div>
            <h3 class="font-serif text-xl text-benizia-gold">Contact Us</h3>
            <div class="mt-5 grid gap-4 text-sm leading-6 text-white/70">
                {{-- Address --}}
                <div class="flex gap-3">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-benizia-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                    </svg>
                    <address class="not-italic">{{ $address }}</address>
                </div>
                {{-- Phone --}}
                <a href="tel:{{ $phoneHref }}" class="flex gap-3 transition hover:text-benizia-gold">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-benizia-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                    </svg>
                    <span>{{ $phone }}</span>
                </a>
                {{-- Email --}}
                <a href="mailto:{{ $email }}" class="flex gap-3 transition hover:text-benizia-gold">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-benizia-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                    </svg>
                    <span>{{ $email }}</span>
                </a>
            </div>
        </div>

        {{-- Column 4: Booking note + Newsletter --}}
        <div>
            <h3 class="font-serif text-xl text-benizia-gold">Reservations</h3>
            <div class="mt-5 text-sm text-white/70 space-y-3">
                <p>Secure your stay with a <strong class="text-benizia-gold font-semibold">40% deposit</strong> at booking.</p>
                <p>Free cancellation up to <strong class="text-benizia-gold font-semibold">24 hours</strong> before check-in.</p>
                <p>Balance payable at check-in after confirmation.</p>
            </div>

            <div class="mt-6">
                <h4 class="text-sm font-semibold text-white/80 mb-3">Stay Updated</h4>
                <form class="flex overflow-hidden rounded-full bg-white/10 p-1" onsubmit="event.preventDefault()">
                    <input
                        type="email"
                        placeholder="Your email address"
                        class="min-w-0 flex-1 bg-transparent px-4 text-sm text-white placeholder:text-white/40 outline-none"
                        aria-label="Email address for newsletter"
                    >
                    <button
                        type="submit"
                        class="grid h-10 w-10 place-items-center rounded-full bg-benizia-gold text-white transition hover:bg-white hover:text-benizia-green"
                        aria-label="Subscribe to newsletter"
                    >
                        <svg class="h-4 w-4 -rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3"/>
                        </svg>
                    </button>
                </form>
            </div>

            <a href="{{ $bookingUrl }}"
               class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-full border border-benizia-gold px-6 py-3 text-sm font-bold text-benizia-gold transition hover:bg-benizia-gold hover:text-white">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                </svg>
                Book Your Stay
            </a>
        </div>
    </div>

    {{-- ── Bottom bar ── --}}
    <div class="border-t border-white/15">
        <div class="mx-auto flex max-w-7xl flex-col gap-3 px-4 py-6 text-xs text-white/50 md:flex-row md:items-center md:justify-between">
            <p>&copy; {{ $year }} Hotel Benizia. All rights reserved.</p>
            <p>
                <a href="#" class="hover:text-benizia-gold transition">Privacy Policy</a>
                &nbsp;|&nbsp;
                <a href="#" class="hover:text-benizia-gold transition">Terms &amp; Conditions</a>
                &nbsp;|&nbsp;
                <a href="#" class="hover:text-benizia-gold transition">Cookie Policy</a>
            </p>
        </div>
    </div>
</footer>
