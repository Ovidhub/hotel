{{-- resources/views/themes/blacktower/about.blade.php --}}
@extends('themes.blacktower.layouts.app')

@section('title', 'About Us — ' . config('hotel.name'))
@section('description', 'Learn about ' . config('hotel.name') . ' — a destination for premium comfort, world-class service, and unforgettable moments.')

@section('content')

{{-- ════════════════════════════════════════════════════════════════
     PAGE HERO
════════════════════════════════════════════════════════════════ --}}
<section class="hero-wrap">
    <div class="hero">
        <div class="hero__shade"></div>
        <div class="hero__content">
            <p>About Us</p>
            <h1>Discover True Comfort</h1>
        </div>
    </div>
</section>

{{-- ════════════════════════════════════════════════════════════════
     ABOUT
════════════════════════════════════════════════════════════════ --}}
<section id="about" class="about section-pad">
    <div class="about__media">
        <div class="about__single">
            <div class="about__primary">
                <div class="about__image-inner">
                    <img src="{{ asset('img/themes/blacktower/tour-2.webp') }}" alt="{{ config('hotel.name') }} lounge">
                </div>
                <div class="about__logo">
                    <img src="{{ asset('img/themes/blacktower/logo.png') }}" alt="{{ config('hotel.name') }} emblem">
                </div>
            </div>
            <div class="about__secondary">
                <div class="about__circle-inner">
                    <img src="{{ asset('img/themes/blacktower/tour-3.webp') }}" alt="{{ config('hotel.name') }} guest room">
                </div>
            </div>
        </div>
    </div>
    <div class="about__copy">
        <div class="section-title section-title--left">
            <img src="{{ asset('img/themes/blacktower/logo.png') }}" alt="">
            <span>Welcome to {{ config('hotel.name') }}</span>
            <h2>Our Story</h2>
            <p>{{ config('hotel.name') }} is your destination for premium comfort, world-class service, and unforgettable moments. Since opening our doors, we have welcomed guests visiting for business, leisure, and everything in between, offering a serene environment designed just for you.</p>
        </div>
        <h3>Destination for memorable experiences</h3>
        <a class="btn btn--dark" href="{{ route('rooms.index') }}">View our rooms</a>
    </div>
</section>

{{-- ════════════════════════════════════════════════════════════════
     WHY CHOOSE US
════════════════════════════════════════════════════════════════ --}}
<section class="why section-pad section-pad--cream">
    <div class="why__copy">
        <div class="section-title section-title--left">
            <img src="{{ asset('img/themes/blacktower/logo.png') }}" alt="">
            <span>Why Guests Choose Us</span>
            <h2>What Sets Us Apart</h2>
            <p>At {{ config('hotel.name') }}, we are more than just a place to stay, we are a destination where comfort, service, and unforgettable experiences come together. Whether you&rsquo;re visiting for business, relaxation, or a romantic getaway, we provide everything you need to feel welcomed, valued, and truly at home.</p>
        </div>
        <div class="video-pill">
            <span>&#9658;</span>
            <div>
                <strong>Watch Hotel Video</strong>
                <small>2 Minutes Video</small>
            </div>
        </div>
    </div>
    <div class="why__cards">
        <article>
            <span>01</span>
            <h3>Exceptional Hospitality &amp; Professional Staff</h3>
            <p>We believe true luxury comes from genuine care. Our trained team is friendly, attentive, and committed to personalized service.</p>
        </article>
        <article>
            <span>02</span>
            <h3>Prime Location for Convenience</h3>
            <p>{{ config('hotel.name') }} is strategically located close to key attractions, business hubs, shopping centers, and transport routes.</p>
        </article>
        <article>
            <span>03</span>
            <h3>Peaceful Ambience Perfect for Rest &amp; Relaxation</h3>
            <p>Whether you are winding down after a long trip or escaping the noise of the city, our serene environment offers calm comfort.</p>
        </article>
        <article>
            <span>04</span>
            <h3>Premium Comfort &amp; Quality Room Service</h3>
            <p>Enjoy clean rooms, soft bedding, reliable Wi-Fi, delicious meals, and responsive service throughout your stay.</p>
        </article>
    </div>
</section>

{{-- ════════════════════════════════════════════════════════════════
     HOTEL HIGHLIGHTS
════════════════════════════════════════════════════════════════ --}}
<section class="highlights section-pad">
    <div class="highlights__copy">
        <div class="section-title section-title--left">
            <img src="{{ asset('img/themes/blacktower/logo.png') }}" alt="">
            <span>our benefits</span>
            <h2>Hotel Highlights</h2>
            <p>At {{ config('hotel.name') }}, we combine comfort, convenience, and exceptional service to give you a memorable stay.</p>
        </div>
        <ul>
            <li>Comfortable Rooms</li>
            <li>Prime Location</li>
            <li>Safe Pick &amp; Drop</li>
            <li>Quality Room Service</li>
        </ul>
    </div>
    <div class="highlights__image">
        <p>Whether you are here for business or leisure, our hotel provides everything you need for a relaxing experience.</p>
        <img src="{{ asset('img/themes/blacktower/tour-11.webp') }}" alt="Quality room service">
    </div>
</section>

{{-- ════════════════════════════════════════════════════════════════
     COUNTER BAND
════════════════════════════════════════════════════════════════ --}}
<section class="counter-band">
    <strong>500<span>+</span></strong>
    <p>Guests visit our hotel every month</p>
</section>

{{-- ════════════════════════════════════════════════════════════════
     TESTIMONIALS — wired to $testimonials
════════════════════════════════════════════════════════════════ --}}
<section class="testimonials section-pad section-pad--cream">
    <div class="section-title">
        <img src="{{ asset('img/themes/blacktower/logo.png') }}" alt="">
        <span>Customers reviews</span>
        <h2>What They&rsquo;re Saying?</h2>
    </div>
    <div class="testimonial-grid">
        @foreach($testimonials as $testimonial)
            <article>
                <div class="stars">{{ str_repeat('★', max(1, min(5, (int) $testimonial->rating))) }}</div>
                <p>{{ $testimonial->text }}</p>
                <h4>{{ $testimonial->name }}</h4>
            </article>
        @endforeach
    </div>
</section>

@endsection
