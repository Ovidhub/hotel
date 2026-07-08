@props([
    'eyebrow'  => null,
    'title',
    'text'     => null,
    'align'    => 'center',
    'rawTitle' => false,
])

@php
    $isLeft = $align === 'left';
    $wrapClass = $isLeft
        ? 'max-w-2xl text-left'
        : 'mx-auto max-w-2xl text-center';
@endphp

<div
    class="{{ $wrapClass }}"
    x-data="{}"
    x-init="
        const io = new IntersectionObserver((entries) => {
            entries.forEach((e) => { if (e.isIntersecting) { $el.classList.add('reveal-enter'); io.disconnect(); } });
        }, { threshold: 0.15 });
        io.observe($el);
    "
>
    {{-- Gold eyebrow label --}}
    @if($eyebrow)
        <p class="text-sm font-bold uppercase tracking-[0.15em] text-benizia-gold">
            {{ $eyebrow }}
        </p>
    @endif

    {{-- Section heading --}}
    <h2 class="font-serif mt-3 text-3xl leading-tight text-benizia-charcoal md:text-5xl">
        {!! $rawTitle ? $title : e($title) !!}
    </h2>

    {{-- Supporting text --}}
    @if($text)
        <p class="mt-5 text-sm leading-7 text-gray-500 md:text-base {{ $isLeft ? 'max-w-xl' : 'mx-auto max-w-xl' }}">
            {{ $text }}
        </p>
    @endif
</div>
