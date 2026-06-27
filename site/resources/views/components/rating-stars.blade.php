@props([
    'rating' => 5,
    'max'    => 5,
])

@php
    $rating = (float) $rating;
    $fullStars  = (int) floor($rating);
    $halfStar   = ($rating - $fullStars) >= 0.5;
    $emptyStars = $max - $fullStars - ($halfStar ? 1 : 0);
@endphp

<span
    class="inline-flex items-center gap-0.5"
    role="img"
    aria-label="Rating: {{ number_format($rating, 1) }} out of {{ $max }} stars"
>
    {{-- Full stars --}}
    @for($i = 0; $i < $fullStars; $i++)
        <svg class="h-4 w-4 text-benizia-gold" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
        </svg>
    @endfor

    {{-- Half star --}}
    @if($halfStar)
        <svg class="h-4 w-4" viewBox="0 0 20 20" aria-hidden="true">
            <defs>
                <linearGradient id="halfGrad{{ $rating }}">
                    <stop offset="50%" stop-color="#C9A96E"/>
                    <stop offset="50%" stop-color="#d1d5db"/>
                </linearGradient>
            </defs>
            <path fill="url(#halfGrad{{ $rating }})" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
        </svg>
    @endif

    {{-- Empty stars --}}
    @for($i = 0; $i < $emptyStars; $i++)
        <svg class="h-4 w-4 text-gray-300" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
        </svg>
    @endfor
</span>
