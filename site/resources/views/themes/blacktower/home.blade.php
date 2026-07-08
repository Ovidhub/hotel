{{-- resources/views/themes/blacktower/home.blade.php --}}
@extends('themes.blacktower.layouts.app')

@section('title', config('hotel.name'))

@section('content')

{{-- ════════════════════════════════════════════════════════════════
     HERO — #home
════════════════════════════════════════════════════════════════ --}}
<section id="home" class="hero-wrap">
    <div class="hero">
        <div class="hero__shade"></div>
        <div class="hero__content">
            <p>Black Tower</p>
            <h1>Experience Comfort</h1>
        </div>
    </div>
</section>

{{-- ════════════════════════════════════════════════════════════════
     SEARCH / BOOKING BAR
════════════════════════════════════════════════════════════════ --}}
<section class="search-panel" aria-label="Room search">
    <form class="search-panel__form" onsubmit="return false">
        <label class="search-panel__keyword">
            <span>Keyword</span>
            <input placeholder="Keyword">
        </label>
        <label>
            <span>Locations</span>
            <select>
                <option>Locations</option>
                <option>Asaba</option>
            </select>
        </label>
        <label>
            <span>Date from</span>
            <input placeholder="Date from" type="text">
        </label>
        <label>
            <span>Start at</span>
            <select>
                <option>12:00 am</option>
                <option>1:00 am</option>
                <option>2:00 am</option>
                <option>3:00 am</option>
            </select>
        </label>
        <label>
            <span>Date to</span>
            <input placeholder="Date to" type="text">
        </label>
        <label>
            <span>Guests</span>
            <select>
                <option>0</option>
                <option>1</option>
                <option>2</option>
                <option>3</option>
            </select>
        </label>
        <button type="button" aria-label="Search availability">&#8981;</button>
    </form>
</section>

{{-- ════════════════════════════════════════════════════════════════
     ABOUT — #about
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
            <h2>Discover True Comfort &amp; Elegance</h2>
            <p>your perfect getaway for relaxation, business, and unforgettable moments. Enjoy premium comfort, world-class service, and a serene environment designed just for you.</p>
        </div>
        <h3>Destination for memorable experiences</h3>
        <a class="btn btn--dark" href="#amenities">Discover more</a>
    </div>
</section>

{{-- ════════════════════════════════════════════════════════════════
     ROOMS — #rooms (wired to $rooms)
════════════════════════════════════════════════════════════════ --}}
<section id="rooms" class="rooms section-pad section-pad--cream">
    <div class="section-title">
        <img src="{{ asset('img/themes/blacktower/logo.png') }}" alt="">
        <span>Featured rooms</span>
        <h2>Discover Our Rooms</h2>
    </div>
    <div class="room-grid">
        @php
            $roomBadges = ['Featured', 'Popular', 'Luxury'];
            $roomFallbackImages = ['tour-6', 'tour-5', 'tour-4'];
        @endphp
        @foreach($rooms->take(3) as $room)
            @php
                $wifiAmenity = collect($room->amenities ?? [])->first(fn ($a) => str_contains(strtolower($a), 'wifi'));
                $thirdMeta = $wifiAmenity ?? ($room->category ?? 'Premium Stay');
                $roomImage = $room->imageUrl() ?? asset('img/themes/blacktower/' . ($roomFallbackImages[$loop->index % 3]) . '.webp');
            @endphp
            <article class="room-card">
                <div class="room-card__image">
                    <img src="{{ $roomImage }}" alt="{{ $room->name }}">
                    <span class="room-card__badge">{{ $roomBadges[$loop->index % 3] }}</span>
                </div>
                <div class="room-card__body">
                    <div class="room-card__rating" aria-label="5 star rating">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                    <h3>{{ $room->name }}</h3>
                    <p>{{ $room->excerpt }}</p>
                    <div class="room-card__meta">
                        <span>{{ $room->guests }} Guests</span>
                        <span>{{ $room->beds }} Bed{{ $room->beds > 1 ? 's' : '' }}</span>
                        <span>{{ $thirdMeta }}</span>
                    </div>
                    <div class="room-card__footer">
                        <div class="room-card__price">
                            <small>from</small>
                            <strong>{{ $room->price_formatted }}</strong>
                            <em>/ night</em>
                        </div>
                        <a class="room-card__button" href="{{ route('rooms.show', $room) }}">Book Now</a>
                    </div>
                </div>
            </article>
        @endforeach
    </div>
</section>

{{-- ════════════════════════════════════════════════════════════════
     WHY CHOOSE US
════════════════════════════════════════════════════════════════ --}}
<section class="why section-pad">
    <div class="why__copy">
        <div class="section-title section-title--left">
            <img src="{{ asset('img/themes/blacktower/logo.png') }}" alt="">
            <span>Why Guests Choose Us</span>
            <h2>Why Choose Us</h2>
            <p>At Black Tower Hotel, we are more than just a place to stay, we are a destination where comfort, service, and unforgettable experiences come together. Whether you&rsquo;re visiting for business, relaxation, or a romantic getaway, we provide everything you need to feel welcomed, valued, and truly at home.</p>
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
            <p>Black Tower Hotel is strategically located close to key attractions, business hubs, shopping centers, and transport routes.</p>
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
     SERVICE BAND
════════════════════════════════════════════════════════════════ --}}
<section class="service-band">
    <div>
        <h2>Discover True Comfort &amp; Elegance</h2>
        <h4>Experience Comfort Exceptional Hospitality</h4>
    </div>
    <div>
        <h2>24/7 Room<br>Services</h2>
        <ul>
            <li>Group / Corporate Bookings</li>
            <li>Honeymoon &amp; Romantic Package</li>
            <li>Get 30% Discount</li>
        </ul>
    </div>
</section>

{{-- ════════════════════════════════════════════════════════════════
     GALLERY
════════════════════════════════════════════════════════════════ --}}
<section class="gallery section-pad">
    <div class="section-title">
        <img src="{{ asset('img/themes/blacktower/logo.png') }}" alt="">
        <h2>Our Gallery</h2>
    </div>
    <div class="gallery-track">
        @foreach(['tour-6','tour-5','tour-4','tour-3','tour-2','tour-1','tour-7','tour-8','tour-11','tour-10'] as $index => $image)
            <img src="{{ asset('img/themes/blacktower/' . $image . '.webp') }}" alt="Black Tower gallery {{ $index + 1 }}">
        @endforeach
    </div>
</section>

{{-- ════════════════════════════════════════════════════════════════
     HOTEL HIGHLIGHTS
════════════════════════════════════════════════════════════════ --}}
<section class="highlights section-pad section-pad--cream">
    <div class="highlights__copy">
        <div class="section-title section-title--left">
            <img src="{{ asset('img/themes/blacktower/logo.png') }}" alt="">
            <span>our benefits</span>
            <h2>Hotel Highlights</h2>
            <p>At Black Tower Hotel, we combine comfort, convenience, and exceptional service to give you a memorable stay.</p>
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
<section class="testimonials section-pad">
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

{{-- ════════════════════════════════════════════════════════════════
     AMENITIES — #amenities + BOOKING FORM — #booking / #contact
════════════════════════════════════════════════════════════════ --}}
<section id="amenities" class="amenities section-pad">
    <div class="amenities__image-card">
        <img src="{{ asset('img/themes/blacktower/tour-9.webp') }}" alt="Beautiful Black Tower hotel moment">
        <div>
            <h2>Beautiful Moments You will Never Forget</h2>
            <p>A refined luxury hotel</p>
        </div>
    </div>
    <div class="amenities__copy">
        <div class="section-title section-title--left">
            <img src="{{ asset('img/themes/blacktower/logo.png') }}" alt="">
            <span>Hotel Amenities</span>
            <h2>Inspired Incentives</h2>
        </div>
        <article>
            <h3>Airport Pickup</h3>
            <p>Enjoy smooth and stress-free arrivals with our reliable airport pickup service.</p>
        </article>
        <article>
            <h3>Free Parking</h3>
            <p>Stay with peace of mind &mdash; we offer secure and convenient parking at no extra cost.</p>
        </article>
        <article>
            <h3>Outdoor Pool</h3>
            <p>Relax and unwind in our refreshing outdoor pool, perfect for leisure and family time.</p>
        </article>
    </div>
    <div id="booking" class="amenities__booking">
        @include('themes.blacktower.partials.booking-form')
    </div>
</section>

@endsection
