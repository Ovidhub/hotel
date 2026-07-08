{{-- resources/views/themes/blacktower/contact.blade.php --}}
@extends('themes.blacktower.layouts.app')

@section('title', 'Contact Us — ' . config('hotel.name'))
@section('description', 'Get in touch with ' . config('hotel.name') . ' — visit us, call us, or send a message.')

@section('content')

{{-- ════════════════════════════════════════════════════════════════
     PAGE HERO
════════════════════════════════════════════════════════════════ --}}
<section class="hero-wrap">
    <div class="hero">
        <div class="hero__shade"></div>
        <div class="hero__content">
            <p>Contact Us</p>
            <h1>Get In Touch</h1>
        </div>
    </div>
</section>

{{-- ════════════════════════════════════════════════════════════════
     CONTACT DETAILS
════════════════════════════════════════════════════════════════ --}}
<section class="amenities section-pad">
    <div class="amenities__image-card">
        <img src="{{ asset('img/themes/blacktower/tour-9.webp') }}" alt="{{ config('hotel.name') }}">
        <div>
            <h2>We&rsquo;re Here to Help</h2>
            <p>Reach out any time</p>
        </div>
    </div>
    <div class="amenities__copy">
        <div class="section-title section-title--left">
            <img src="{{ asset('img/themes/blacktower/icon-heading.png') }}" alt="">
            <span>Get in touch</span>
            <h2>We&rsquo;d Love to Hear From You</h2>
        </div>
        <article>
            <h3>Visit Us</h3>
            <p>{{ config('hotel.address') }}</p>
        </article>
        <article>
            <h3>Call Us</h3>
            <p><a href="tel:{{ config('hotel.phone_href') }}">{{ config('hotel.phone') }}</a></p>
        </article>
        <article>
            <h3>Email Us</h3>
            <p><a href="mailto:{{ config('hotel.email') }}">{{ config('hotel.email') }}</a></p>
        </article>
    </div>
    <div class="amenities__booking">
        <form method="POST" action="{{ route('contact.store') }}" class="booking-card">
            @csrf
            <div class="booking-card__eyebrow">Send a message</div>
            <h3>Contact Us</h3>

            @if(session('status'))
                <p class="booking-status booking-status--success">{{ session('status') }}</p>
            @endif
            @if($errors->any())
                <p class="booking-status booking-status--error">{{ $errors->first() }}</p>
            @endif

            <div class="booking-grid">
                <label>
                    <span>Name</span>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Your name" required>
                </label>
                <label>
                    <span>Email</span>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Email address" required>
                </label>
                <label>
                    <span>Phone</span>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Phone number">
                </label>
                <label>
                    <span>Subject</span>
                    <input type="text" name="subject" value="{{ old('subject') }}" placeholder="Subject">
                </label>
                <label class="booking-grid__wide">
                    <span>Message</span>
                    <textarea name="message" rows="4" placeholder="Your message" required>{{ old('message') }}</textarea>
                </label>
            </div>
            <button class="btn btn--dark booking-card__button" type="submit">Send Message</button>
        </form>
    </div>
</section>

{{-- ════════════════════════════════════════════════════════════════
     BOOKING-INQUIRY FORM
════════════════════════════════════════════════════════════════ --}}
<section id="booking" class="rooms section-pad section-pad--cream">
    <div class="section-title">
        <img src="{{ asset('img/themes/blacktower/icon-heading.png') }}" alt="">
        <span>Reserve your stay</span>
        <h2>Ready For Your Stay?</h2>
    </div>
    @include('themes.blacktower.partials.booking-form')
</section>

@endsection
