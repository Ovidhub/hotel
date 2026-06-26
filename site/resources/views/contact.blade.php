<x-layouts.app
    title="Contact Hotel Benizia — Asaba, Delta State"
    description="Contact Hotel Benizia in Asaba, Delta State. Reach our reservations team by phone, email, or visit us on Summit Road, Asaba.">

    <x-page-hero
        title="Contact Us"
        subtitle="Our team is available 24 hours to assist with reservations, events, and enquiries. Reach us by phone, email, or visit us in Asaba."
        :breadcrumbs="[['label' => 'Home', 'url' => route('home')], ['label' => 'Contact']]"
    />

    <section class="py-20 px-4">
        <div class="mx-auto max-w-5xl">
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-gray-100">
                    <h2 class="font-serif text-xl text-benizia-charcoal mb-3">Phone</h2>
                    <a href="tel:+2348134062487" class="text-benizia-green font-semibold hover:underline">+234 813 406 2487</a>
                </div>
                <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-gray-100">
                    <h2 class="font-serif text-xl text-benizia-charcoal mb-3">Email</h2>
                    <a href="mailto:booking@hotelbenizia.ng" class="text-benizia-green font-semibold hover:underline">booking@hotelbenizia.ng</a>
                </div>
                <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-gray-100">
                    <h2 class="font-serif text-xl text-benizia-charcoal mb-3">Address</h2>
                    <address class="not-italic text-gray-600 text-sm leading-relaxed">
                        Hotel Benizia<br>
                        Summit Road, Asaba<br>
                        Delta State, Nigeria
                    </address>
                </div>
            </div>

            <div class="mt-12 rounded-3xl bg-benizia-cream p-8">
                <h2 class="font-serif text-2xl text-benizia-charcoal mb-4">Make a Reservation</h2>
                <p class="text-gray-600 leading-relaxed mb-6">To book a room or apartment, call or email our reservations team. A 40% commitment fee is required to secure your booking while availability is confirmed.</p>
                <a href="tel:+2348134062487"
                   class="inline-block rounded-full bg-benizia-green px-8 py-3 font-bold text-white transition hover:bg-benizia-charcoal">
                    Call to Book
                </a>
            </div>
        </div>
    </section>

    <x-cta title="Book Your Stay Today" text="Call or email our reservations team to secure your room or apartment at Hotel Benizia." />

</x-layouts.app>
