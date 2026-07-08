@php
    $btPhone      = config('hotel.phone');
    $btPhoneHref  = config('hotel.phone_href');
    $btName       = config('hotel.name');
    $btSocials    = config('hotel.socials', []);
    $btBookingUrl = route('rooms.index');
@endphp

<header class="site-header" x-data="{ navOpen: false }">
    <div class="site-header__inner">
        <a class="logo" href="#home" aria-label="{{ $btName }} home">
            {{ $btName }}
        </a>

        @if(!empty($btSocials['facebook']) || !empty($btSocials['instagram']))
        <div class="social-links" aria-label="Social links">
            @if(!empty($btSocials['facebook']))
                <a href="{{ $btSocials['facebook'] }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook">f</a>
            @endif
            @if(!empty($btSocials['instagram']))
                <a href="{{ $btSocials['instagram'] }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram">ig</a>
            @endif
        </div>
        @endif

        <nav class="main-nav" :class="{ 'is-open': navOpen }" aria-label="Primary navigation">
            <a href="#home" @click="navOpen = false">Home</a>
            <a href="#about" @click="navOpen = false">About</a>
            <a href="{{ route('rooms.index') }}" @click="navOpen = false">Rooms</a>
            <a href="#contact" @click="navOpen = false">Contact</a>
        </nav>

        <a class="header-contact" href="tel:{{ $btPhoneHref }}">
            <span>Have Question?</span>
            <strong>{{ $btPhone }}</strong>
        </a>

        <a class="book-link" href="{{ $btBookingUrl }}">
            Book Your Stay
        </a>

        <button
            type="button"
            class="mobile-menu"
            @click="navOpen = !navOpen"
            :aria-expanded="navOpen.toString()"
            aria-label="Toggle menu"
        >
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</header>
