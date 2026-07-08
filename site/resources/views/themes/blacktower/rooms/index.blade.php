{{-- resources/views/themes/blacktower/rooms/index.blade.php --}}
@extends('themes.blacktower.layouts.app')

@section('title', 'Our Rooms — ' . config('hotel.name'))
@section('description', 'Browse rooms and suites at ' . config('hotel.name') . ' and find your perfect stay.')

@section('content')

{{-- ════════════════════════════════════════════════════════════════
     PAGE HERO
════════════════════════════════════════════════════════════════ --}}
<section class="hero-wrap">
    <div class="hero">
        <div class="hero__shade"></div>
        <div class="hero__content">
            <p>Our Rooms</p>
            <h1>Discover Our Rooms</h1>
        </div>
    </div>
</section>

{{-- ════════════════════════════════════════════════════════════════
     ROOMS — wired to $rooms
════════════════════════════════════════════════════════════════ --}}
<section id="rooms" class="rooms section-pad">
    <div class="section-title">
        <img src="{{ asset('img/themes/blacktower/logo.png') }}" alt="">
        <span>Rooms &amp; suites</span>
        <h2>Find Your Perfect Stay</h2>
    </div>
    <div class="room-grid">
        @php
            $roomBadges = ['Featured', 'Popular', 'Luxury'];
            $roomFallbackImages = ['tour-6', 'tour-5', 'tour-4'];
        @endphp
        @foreach($rooms as $room)
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
                        <a class="room-card__button" href="{{ route('rooms.show', $room) }}">View Room</a>
                    </div>
                </div>
            </article>
        @endforeach
    </div>
</section>

@endsection
