<x-layouts.app
    :title="$post->title"
    :description="$post->excerpt ?? 'Read ' . $post->title . ' on the Hotel Benizia blog — insights on staying, dining, and travelling in Asaba, Delta State.'">

    <x-page-hero
        :title="$post->title"
        :image="$post->image"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Blog', 'url' => route('blog.index')],
            ['label' => $post->title],
        ]"
    />

    <x-schema.breadcrumb :items="[
        ['name' => 'Home', 'url' => route('home')],
        ['name' => 'Blog', 'url' => route('blog.index')],
        ['name' => $post->title, 'url' => route('blog.show', $post)],
    ]" />

    <x-schema.article
        :title="$post->title"
        :description="$post->excerpt ?? $post->title"
        :image="$post->image ?? 'https://hotelbenizia.ng/wp-content/uploads/2025/05/front-page-banner.jpg'"
        :author="$post->author ?? 'Hotel Benizia'"
        :authorType="$post->author && $post->author !== 'Hotel Benizia' ? 'Person' : 'Organization'"
        :datePublished="$post->published_at->toIso8601String()"
        :url="route('blog.show', $post)"
    />

    <section class="py-20 px-4 bg-white">
        <div class="mx-auto max-w-3xl">

            {{-- Meta --}}
            <div class="flex flex-wrap items-center gap-4 mb-8 text-sm text-gray-400">
                @if($post->category)
                    @php
                        $catColor = $post->category_color ? $post->category_color : 'bg-benizia-cream';
                    @endphp
                    <span class="inline-block rounded-full {{ $catColor }} px-3 py-1 text-xs font-semibold text-benizia-green">{{ $post->category }}</span>
                @endif
                <span>By {{ $post->author ?? 'Hotel Benizia' }}</span>
                @if($post->published_at)
                    <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->format('F d, Y') }}</time>
                @endif
            </div>

            {{-- Featured image --}}
            @if($post->image)
                <div class="mb-10 overflow-hidden rounded-3xl">
                    <img
                        src="{{ $post->image }}"
                        alt="{{ $post->title }} — Hotel Benizia Blog"
                        class="w-full h-64 object-cover"
                        loading="lazy"
                    >
                </div>
            @endif

            {{-- Excerpt --}}
            @if($post->excerpt)
                <p class="text-lg text-gray-600 leading-relaxed mb-8 border-l-4 border-benizia-gold pl-4 italic">
                    {{ $post->excerpt }}
                </p>
            @endif

            {{-- Body --}}
            <div class="prose prose-lg max-w-none text-gray-700">
                {!! nl2br(e($post->body)) !!}
            </div>

            {{-- Share links --}}
            <div class="mt-12 pt-8 border-t border-gray-100">
                <p class="text-sm text-gray-400 mb-4">Share this article</p>
                <div class="flex gap-4">
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($post->title) }}&url={{ urlencode(route('blog.show', $post)) }}"
                       target="_blank" rel="noopener noreferrer"
                       class="rounded-full bg-benizia-cream px-4 py-2 text-sm font-semibold text-benizia-green hover:bg-benizia-green hover:text-white transition"
                       aria-label="Share on Twitter">
                        Twitter
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog.show', $post)) }}"
                       target="_blank" rel="noopener noreferrer"
                       class="rounded-full bg-benizia-cream px-4 py-2 text-sm font-semibold text-benizia-green hover:bg-benizia-green hover:text-white transition"
                       aria-label="Share on Facebook">
                        Facebook
                    </a>
                    <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . route('blog.show', $post)) }}"
                       target="_blank" rel="noopener noreferrer"
                       class="rounded-full bg-benizia-cream px-4 py-2 text-sm font-semibold text-benizia-green hover:bg-benizia-green hover:text-white transition"
                       aria-label="Share on WhatsApp">
                        WhatsApp
                    </a>
                </div>
            </div>

            {{-- Back link --}}
            <div class="mt-8">
                <a href="{{ route('blog.index') }}"
                   class="inline-flex items-center gap-2 text-sm font-semibold text-benizia-green hover:text-benizia-charcoal transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                    Back to Blog
                </a>
            </div>
        </div>
    </section>

    {{-- Related posts --}}
    @if($related && $related->count())
        <section class="py-16 px-4 bg-benizia-cream">
            <div class="mx-auto max-w-7xl">
                <h2 class="font-serif text-3xl text-benizia-charcoal text-center mb-10">Related Articles</h2>
                <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($related as $relPost)
                        <article class="group overflow-hidden rounded-3xl bg-white ring-1 ring-gray-100 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                            @if($relPost->image)
                                <a href="{{ route('blog.show', $relPost) }}" class="block h-44 overflow-hidden">
                                    <img
                                        src="{{ $relPost->image }}"
                                        alt="{{ $relPost->title }} — Hotel Benizia Blog"
                                        class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                        loading="lazy"
                                    >
                                </a>
                            @endif
                            <div class="p-5">
                                @if($relPost->category)
                                    <span class="inline-block rounded-full bg-benizia-cream px-3 py-1 text-xs font-semibold text-benizia-green mb-2">{{ $relPost->category }}</span>
                                @endif
                                <h3 class="font-serif text-base text-benizia-charcoal leading-snug mb-2">
                                    <a href="{{ route('blog.show', $relPost) }}" class="hover:text-benizia-green transition">{{ $relPost->title }}</a>
                                </h3>
                                <a href="{{ route('blog.show', $relPost) }}"
                                   class="text-sm font-semibold text-benizia-green hover:text-benizia-charcoal transition">
                                    Read More &rarr;
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <x-cta title="Stay at Hotel Benizia, Asaba" text="Rooms from ₦30,000/night. Breakfast and pool access included with every booking." />

</x-layouts.app>
