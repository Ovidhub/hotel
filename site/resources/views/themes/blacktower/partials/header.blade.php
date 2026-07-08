@php
    $btPhone      = config('hotel.phone');
    $btPhoneHref  = config('hotel.phone_href');
    $btName       = config('hotel.name');
    $btSocials    = config('hotel.socials', []);
    $btBookingUrl = route('rooms.index');
@endphp

<header class="site-header">
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

        <nav class="main-nav" aria-label="Primary navigation">
            <a href="#home">Home</a>
            <a href="#about">About</a>
            <a href="{{ route('rooms.index') }}">Rooms</a>
            <a href="#contact">Contact</a>
        </nav>

        <a class="header-contact" href="tel:{{ $btPhoneHref }}">
            <span>Have Question?</span>
            <strong>{{ $btPhone }}</strong>
        </a>

        <a class="book-link" href="{{ $btBookingUrl }}">
            Book Your Stay
        </a>

        <button type="button" class="mobile-menu" aria-label="Open menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</header>
