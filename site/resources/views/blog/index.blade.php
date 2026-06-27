<x-layouts.app
    title="Hotel & Travel Blog"
    description="Read the Hotel Benizia blog for tips on booking a hotel in Asaba, room guides, dining, events, and travel advice for Delta State visitors.">

    <x-page-hero
        title="Hotel Benizia Blog"
        subtitle="Guides, tips, and insights for guests visiting Asaba and staying at Hotel Benizia, Delta State."
        :breadcrumbs="[['label' => 'Home', 'url' => route('home')], ['label' => 'Blog']]"
    />

    <x-schema.breadcrumb :items="[
        ['name' => 'Home', 'url' => route('home')],
        ['name' => 'Blog', 'url' => route('blog.index')],
    ]" />

    <section class="py-20 px-4">
        <div class="mx-auto max-w-7xl">
            <x-section-intro
                eyebrow="Articles"
                title="From Our Blog"
                text="Practical guides and stories about staying, dining, and making the most of your time at Hotel Benizia in Asaba."
            />

            <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($posts as $post)
                    <article class="group overflow-hidden rounded-3xl bg-white ring-1 ring-gray-100 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg">
                        @if($post->image)
                            <a href="{{ route('blog.show', $post) }}" class="block h-52 overflow-hidden" aria-label="Read {{ $post->title }}">
                                <img
                                    src="{{ $post->image }}"
                                    alt="{{ $post->title }}"
                                    class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                    loading="lazy"
                                >
                            </a>
                        @endif
                        <div class="p-6">
                            @if($post->category)
                                <span class="inline-block rounded-full bg-benizia-cream px-3 py-1 text-xs font-semibold text-benizia-green mb-3">{{ $post->category }}</span>
                            @endif
                            <h2 class="font-serif text-lg text-benizia-charcoal leading-snug mb-2">
                                <a href="{{ route('blog.show', $post) }}" class="hover:text-benizia-green transition">{{ $post->title }}</a>
                            </h2>
                            @if($post->excerpt)
                                <p class="text-sm text-gray-500 leading-relaxed line-clamp-2">{{ $post->excerpt }}</p>
                            @endif
                            <div class="mt-4 flex items-center justify-between text-xs text-gray-400">
                                <span>{{ $post->author ?? 'Hotel Benizia' }}</span>
                                @if($post->published_at)
                                    <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->format('M d, Y') }}</time>
                                @endif
                            </div>
                            <a href="{{ route('blog.show', $post) }}"
                               class="mt-4 inline-block text-sm font-semibold text-benizia-green hover:text-benizia-charcoal transition">
                                Read More &rarr;
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <x-cta title="Ready to Stay at Hotel Benizia?" text="Book your room today and experience Asaba's premier hotel for yourself." />

</x-layouts.app>
