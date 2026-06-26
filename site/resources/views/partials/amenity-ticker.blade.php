@php
    $items = config('hotel.ticker', []);
    // Triple the items so the seamless loop works: we animate the first 1/3
    $repeated = array_merge($items, $items, $items);
@endphp

<div class="overflow-hidden bg-benizia-green py-3 text-white" aria-label="Hotel amenities">
    <div class="marquee-track flex w-max items-center gap-8 whitespace-nowrap">
        @foreach($repeated as $item)
            <div class="flex items-center gap-6">
                <span class="font-serif text-base tracking-wide">{{ $item }}</span>
                {{-- Gold diamond separator --}}
                <span class="h-2 w-2 rotate-45 bg-benizia-gold inline-block" aria-hidden="true"></span>
            </div>
        @endforeach
    </div>
</div>
