@props([
    'title',
    'subtitle'    => null,
    'image'       => null,
    'breadcrumbs' => [],
    'rawTitle'    => false,
])

@php
    $defaultImage = 'https://hotelbenizia.ng/img/property/hotel-exterior.webp';
    $bgImage = $image ?? $defaultImage;
@endphp

<section
    class="relative flex min-h-[480px] items-end overflow-hidden bg-cover bg-center px-4 pb-16 pt-36 text-white"
    style="background-image: linear-gradient(90deg, rgba(12,41,39,0.90), rgba(12,41,39,0.60), rgba(12,41,39,0.25)), url('{{ $bgImage }}');"
    aria-label="Page hero"
>
    <div class="mx-auto w-full max-w-7xl">

        {{-- Breadcrumbs --}}
        @if(!empty($breadcrumbs))
            <nav aria-label="Breadcrumb" class="mb-6">
                <ol class="flex flex-wrap items-center gap-2 text-xs text-white/60">
                    @foreach($breadcrumbs as $crumb)
                        @if(!$loop->last)
                            <li>
                                <a href="{{ $crumb['url'] ?? '#' }}" class="hover:text-benizia-gold transition">{{ $crumb['label'] }}</a>
                            </li>
                            <li aria-hidden="true">
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                            </li>
                        @else
                            <li class="text-white/90" aria-current="page">{{ $crumb['label'] }}</li>
                        @endif
                    @endforeach
                </ol>
            </nav>
        @endif

        {{-- Title --}}
        <h1 class="font-serif text-5xl leading-tight text-white md:text-7xl">
            {!! $rawTitle ? $title : e($title) !!}
        </h1>

        {{-- Subtitle --}}
        @if($subtitle)
            <p class="mt-5 max-w-2xl text-base leading-8 text-white/80 md:text-lg">
                {{ $subtitle }}
            </p>
        @endif

        {{-- Gold accent line --}}
        <div class="mt-8 h-0.5 w-16 bg-benizia-gold"></div>
    </div>
</section>
