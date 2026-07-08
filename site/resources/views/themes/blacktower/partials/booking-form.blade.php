{{-- resources/views/themes/blacktower/partials/booking-form.blade.php --}}
<form method="POST" action="{{ route('booking-inquiry.store') }}" class="booking-card">
    @csrf
    <div class="booking-card__eyebrow">Reserve your stay</div>
    <h3>Book Your Stay</h3>

    @if(session('status'))
        <p class="booking-status booking-status--success">{{ session('status') }}</p>
    @endif
    @if($errors->any())
        <p class="booking-status booking-status--error">Please check the form and try again.</p>
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
            <span>Room</span>
            <select name="room" required>
                <option value="">Select a room</option>
                @foreach(($rooms ?? \App\Models\Room::where('is_active', true)->get()) as $r)
                    <option value="{{ $r->name }}" @selected(old('room') === $r->name)>{{ $r->name }}</option>
                @endforeach
            </select>
        </label>
        <label>
            <span>Check-in</span>
            <input type="date" name="check_in" value="{{ old('check_in') }}" required>
        </label>
        <label>
            <span>Check-out</span>
            <input type="date" name="check_out" value="{{ old('check_out') }}" required>
        </label>
        <label class="booking-grid__wide">
            <span>Guests</span>
            <select name="guests">
                <option value="1">1 Guest</option>
                <option value="2" selected>2 Guests</option>
                <option value="3">3 Guests</option>
                <option value="4+">4+ Guests</option>
            </select>
        </label>
        <label class="booking-grid__wide">
            <span>Message</span>
            <textarea name="message" rows="4" placeholder="Special request">{{ old('message') }}</textarea>
        </label>
    </div>
    <button class="btn btn--dark booking-card__button" type="submit">Book Your Stay</button>
</form>
