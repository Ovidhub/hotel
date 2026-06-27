@props([
    'testimonial',
])

@php
    $text   = $testimonial->content ?? $testimonial->text ?? $testimonial['content'] ?? $testimonial['text'] ?? '';
    $name   = $testimonial->name ?? $testimonial->author ?? $testimonial['name'] ?? $testimonial['author'] ?? 'Guest';
    $role   = $testimonial->role ?? $testimonial->title ?? $testimonial['role'] ?? $testimonial['title'] ?? '';
    $rating = $testimonial->rating ?? $testimonial['rating'] ?? 5;
    $avatar = $testimonial->avatar ?? $testimonial['avatar'] ?? null;
@endphp

<article class="flex flex-col rounded-2xl bg-white p-7 shadow-sm ring-1 ring-gray-100 transition hover:shadow-md">

    {{-- Stars --}}
    <div class="mb-4">
        <x-rating-stars :rating="$rating" />
    </div>

    {{-- Quote --}}
    <blockquote class="flex-1">
        <p class="text-sm leading-7 text-gray-600 line-clamp-4">
            &ldquo;{{ $text }}&rdquo;
        </p>
    </blockquote>

    {{-- Author --}}
    <footer class="mt-6 flex items-center gap-4 border-t border-gray-100 pt-5">
        @if($avatar)
            <img
                src="{{ $avatar }}"
                alt="{{ $name }}"
                class="h-10 w-10 rounded-full object-cover"
                loading="lazy"
            >
        @else
            {{-- Initials avatar --}}
            <div class="grid h-10 w-10 place-items-center rounded-full bg-benizia-green text-xs font-bold text-benizia-gold">
                {{ strtoupper(substr($name, 0, 2)) }}
            </div>
        @endif
        <div>
            <p class="text-sm font-semibold text-benizia-charcoal">{{ $name }}</p>
            @if($role)
                <p class="text-xs text-gray-400">{{ $role }}</p>
            @endif
        </div>
    </footer>
</article>
