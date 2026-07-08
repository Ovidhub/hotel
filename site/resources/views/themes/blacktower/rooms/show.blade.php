{{-- resources/views/themes/blacktower/rooms/show.blade.php --}}
@extends('themes.blacktower.layouts.app')

@section('title', $room->name . ' — ' . config('hotel.name'))
@section('description', $room->excerpt)

@section('content')

@php $galleryUrls = $room->galleryUrls(); @endphp

{{-- ════════════════════════════════════════════════════════════════
     PAGE HERO
════════════════════════════════════════════════════════════════ --}}
<section class="hero-wrap">
    <div class="hero">
        <div class="hero__shade"></div>
        <div class="hero__content">
            <p>Room</p>
            <h1>{{ $room->name }}</h1>
        </div>
    </div>
</section>

{{-- ════════════════════════════════════════════════════════════════
     ROOM DETAILS + BOOKING FORM
════════════════════════════════════════════════════════════════ --}}
<section class="amenities section-pad">
    <div class="amenities__image-card">
        <img src="{{ $galleryUrls[0] ?? asset('img/themes/blacktower/tour-9.webp') }}" alt="{{ $room->name }}">
        <div>
            <h2>{{ $room->price_formatted }}</h2>
            <p>per night</p>
        </div>
    </div>
    <div class="amenities__copy">
        <div class="section-title section-title--left">
            <img src="{{ asset('img/themes/blacktower/logo.png') }}" alt="">
            <span>Room details</span>
            <h2>{{ $room->name }}</h2>
        </div>
        <article>
            <h3>About this room</h3>
            <p>{{ $room->description }}</p>
        </article>
        @if(!empty($room->amenities))
        <article>
            <h3>Amenities</h3>
            <p>{{ collect($room->amenities)->implode(', ') }}</p>
        </article>
        @endif
    </div>
    <div id="booking" class="amenities__booking">
        @include('themes.blacktower.partials.booking-form')
    </div>
</section>

{{-- ════════════════════════════════════════════════════════════════
     ROOM GALLERY
════════════════════════════════════════════════════════════════ --}}
@if(count($galleryUrls) > 1)
<section class="gallery section-pad section-pad--cream">
    <div class="section-title">
        <img src="{{ asset('img/themes/blacktower/logo.png') }}" alt="">
        <h2>Room Gallery</h2>
    </div>
    <div class="gallery-track">
        @foreach($galleryUrls as $index => $url)
            <img src="{{ $url }}" alt="{{ $room->name }} photo {{ $index + 1 }}">
        @endforeach
    </div>
</section>
@endif

{{-- ════════════════════════════════════════════════════════════════
     SIMILAR ROOMS — wired to $similar
════════════════════════════════════════════════════════════════ --}}
@if($similar->isNotEmpty())
<section class="rooms section-pad">
    <div class="section-title">
        <img src="{{ asset('img/themes/blacktower/logo.png') }}" alt="">
        <span>You may also like</span>
        <h2>Similar Rooms</h2>
    </div>
    <div class="room-grid">
        @foreach($similar as $s)
            <article class="room-card">
                <div class="room-card__image">
                    <img src="{{ $s->imageUrl() ?? asset('img/themes/blacktower/tour-4.webp') }}" alt="{{ $s->name }}">
                </div>
                <div class="room-card__body">
                    <h3>{{ $s->name }}</h3>
                    <p>{{ $s->excerpt }}</p>
                    <div class="room-card__footer">
                        <div class="room-card__price">
                            <small>from</small>
                            <strong>{{ $s->price_formatted }}</strong>
                            <em>/ night</em>
                        </div>
                        <a class="room-card__button" href="{{ route('rooms.show', $s) }}">View Room</a>
                    </div>
                </div>
            </article>
        @endforeach
    </div>
</section>
@endif

@endsection
