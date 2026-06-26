<x-layouts.app
    title="Contact Us"
    description="Contact Hotel Benizia in Asaba, Delta State. Reach our reservations team by phone, email, or visit us on Summit Road, Asaba. Open 24 hours.">

    <x-page-hero
        title="Contact Us"
        subtitle="Our team is available 24 hours to assist with reservations, events, and enquiries. Reach us by phone, email, or visit us in Asaba."
        :breadcrumbs="[['label' => 'Home', 'url' => route('home')], ['label' => 'Contact Us']]"
    />

    <section class="py-20 px-4 bg-white">
        <div class="mx-auto max-w-7xl">
            <div class="grid gap-16 lg:grid-cols-2">

                {{-- Contact Info --}}
                <div>
                    <x-section-intro
                        eyebrow="Get in Touch"
                        title="We're Here 24 Hours"
                        text="Whether you need to make a reservation, enquire about events, or simply have a question — our team is ready to help."
                        align="left"
                    />

                    <div class="mt-10 space-y-6">
                        {{-- Address --}}
                        <div class="flex gap-4 rounded-2xl bg-benizia-cream p-6">
                            <div class="flex-shrink-0 h-12 w-12 rounded-full bg-benizia-green flex items-center justify-center">
                                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="font-semibold text-benizia-charcoal mb-1">Address</h2>
                                <address class="not-italic text-gray-600 text-sm leading-relaxed">
                                    Hotel Benizia<br>
                                    1 Kingsley Emu Street, Summit Road<br>
                                    Asaba, Delta State, Nigeria
                                </address>
                            </div>
                        </div>

                        {{-- Phone --}}
                        <div class="flex gap-4 rounded-2xl bg-benizia-cream p-6">
                            <div class="flex-shrink-0 h-12 w-12 rounded-full bg-benizia-green flex items-center justify-center">
                                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="font-semibold text-benizia-charcoal mb-1">Phone</h2>
                                <a href="tel:+2348134062487" class="text-benizia-green font-semibold hover:underline text-sm">+234 813 406 2487</a>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="flex gap-4 rounded-2xl bg-benizia-cream p-6">
                            <div class="flex-shrink-0 h-12 w-12 rounded-full bg-benizia-green flex items-center justify-center">
                                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="font-semibold text-benizia-charcoal mb-1">Email</h2>
                                <a href="mailto:booking@hotelbenizia.ng" class="text-benizia-green font-semibold hover:underline text-sm">booking@hotelbenizia.ng</a>
                            </div>
                        </div>

                        {{-- Hours --}}
                        <div class="flex gap-4 rounded-2xl bg-benizia-cream p-6">
                            <div class="flex-shrink-0 h-12 w-12 rounded-full bg-benizia-green flex items-center justify-center">
                                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="font-semibold text-benizia-charcoal mb-1">Hours</h2>
                                <p class="text-gray-600 text-sm">Reception: 24 Hours · 7 Days<br>Restaurant &amp; Bar: 24 Hours</p>
                            </div>
                        </div>
                    </div>

                    {{-- Google Maps iframe --}}
                    <div class="mt-8 overflow-hidden rounded-3xl h-56">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3963.9540297278433!2d6.728879!3d6.196800!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1040d32b3a0827ab%3A0xa6c3e8c6bcd5e6e8!2sHotel%20Benizia!5e0!3m2!1sen!2sng!4v1620000000000!5m2!1sen!2sng"
                            width="100%"
                            height="100%"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Hotel Benizia location on Google Maps — Summit Road, Asaba, Delta State"
                            aria-label="Map showing Hotel Benizia location in Asaba, Delta State"
                        ></iframe>
                    </div>
                </div>

                {{-- Contact Form --}}
                <div>
                    <div class="rounded-3xl bg-benizia-cream p-8 lg:p-10">
                        <h2 class="font-serif text-2xl text-benizia-charcoal mb-2">Send Us a Message</h2>
                        <p class="text-sm text-gray-500 mb-8">Fill in the form below and our team will get back to you as soon as possible.</p>

                        <form
                            method="POST"
                            action="{{ Route::has('contact.store') ? route('contact.store') : '#' }}"
                            class="space-y-6"
                        >
                            @csrf

                            {{-- Name --}}
                            <div>
                                <label for="contact_name" class="block text-sm font-semibold text-benizia-charcoal mb-2">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="contact_name"
                                    name="name"
                                    value="{{ old('name') }}"
                                    placeholder="Your full name"
                                    required
                                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-benizia-charcoal placeholder-gray-400 focus:border-benizia-green focus:outline-none focus:ring-2 focus:ring-benizia-green/20 transition"
                                >
                                @error('name')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label for="contact_email" class="block text-sm font-semibold text-benizia-charcoal mb-2">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="email"
                                    id="contact_email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="your@email.com"
                                    required
                                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-benizia-charcoal placeholder-gray-400 focus:border-benizia-green focus:outline-none focus:ring-2 focus:ring-benizia-green/20 transition"
                                >
                                @error('email')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Phone --}}
                            <div>
                                <label for="contact_phone" class="block text-sm font-semibold text-benizia-charcoal mb-2">
                                    Phone Number
                                </label>
                                <input
                                    type="tel"
                                    id="contact_phone"
                                    name="phone"
                                    value="{{ old('phone') }}"
                                    placeholder="+234 800 000 0000"
                                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-benizia-charcoal placeholder-gray-400 focus:border-benizia-green focus:outline-none focus:ring-2 focus:ring-benizia-green/20 transition"
                                >
                                @error('phone')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Subject --}}
                            <div>
                                <label for="contact_subject" class="block text-sm font-semibold text-benizia-charcoal mb-2">
                                    Subject
                                </label>
                                <input
                                    type="text"
                                    id="contact_subject"
                                    name="subject"
                                    value="{{ old('subject') }}"
                                    placeholder="Reservation enquiry, event booking, etc."
                                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-benizia-charcoal placeholder-gray-400 focus:border-benizia-green focus:outline-none focus:ring-2 focus:ring-benizia-green/20 transition"
                                >
                                @error('subject')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Message --}}
                            <div>
                                <label for="contact_message" class="block text-sm font-semibold text-benizia-charcoal mb-2">
                                    Message <span class="text-red-500">*</span>
                                </label>
                                <textarea
                                    id="contact_message"
                                    name="message"
                                    rows="5"
                                    placeholder="Tell us how we can help you..."
                                    required
                                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-benizia-charcoal placeholder-gray-400 focus:border-benizia-green focus:outline-none focus:ring-2 focus:ring-benizia-green/20 transition resize-none"
                                >{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Submit --}}
                            <button
                                type="submit"
                                class="w-full rounded-full bg-benizia-green py-4 font-bold text-white transition hover:bg-benizia-charcoal text-sm"
                            >
                                Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <x-cta title="Book Your Stay Today" text="Call or email our reservations team to secure your room or apartment at Hotel Benizia, Asaba." />

</x-layouts.app>
