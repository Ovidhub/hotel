@php
    $btName       = config('hotel.name');
    $btAddress    = config('hotel.address');
    $btPhone      = config('hotel.phone');
    $btPhoneHref  = config('hotel.phone_href');
    $btEmail      = config('hotel.email');
    $btBookingUrl = route('rooms.index');
    $btYear       = date('Y');
@endphp

<footer id="contact" class="footer">
    <div class="footer__grid">
        <div>
            <p class="footer__logo">{{ $btName }}</p>
            <p>{{ $btName }} is your destination for premium comfort, world-class service, and unforgettable moments.</p>
            <a class="btn btn--light" href="{{ $btBookingUrl }}">Book Your Stay</a>
        </div>

        <div>
            <h3>Links</h3>
            <a href="{{ route('rooms.index') }}">Our Rooms</a>
            <a href="#about">About us</a>
            <a href="#contact">Contact us</a>
            <a href="#home">Home</a>
        </div>

        <div>
            <h3>Contact</h3>
            <p><strong>Location</strong><br>{{ $btAddress }}</p>
            <p><strong>Send Email</strong><br><a href="mailto:{{ $btEmail }}">{{ $btEmail }}</a></p>
            <p><strong>Call Anytime</strong><br><a href="tel:{{ $btPhoneHref }}">{{ $btPhone }}</a></p>
        </div>

        <div>
            <h3>Newsletter</h3>
            <p>Sign up now for updates.</p>
            <form class="newsletter" onsubmit="return false">
                <input type="email" placeholder="Email address" aria-label="Email address">
                <button type="button">Subscribe</button>
            </form>
            <small>I agree to all terms and policies.</small>
        </div>
    </div>

    <div class="footer__bottom">
        <span>&copy; {{ $btYear }} Copyrights by {{ $btName }}</span>
        <nav>
            <a href="#home">Home</a>
            <a href="#about">About</a>
            <a href="{{ route('rooms.index') }}">Rooms</a>
            <a href="#contact">Contact</a>
        </nav>
    </div>
</footer>
