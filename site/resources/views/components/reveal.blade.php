@props([
    'delay' => 0,
])

{{--
    Reveal: fades + slides content into view when it enters the viewport.

    Graceful degradation:
    - The element starts VISIBLE (no opacity:0 set in HTML/CSS).
    - Alpine x-init adds a tiny IntersectionObserver that adds `reveal-enter`
      CSS class when the element scrolls into view.
    - If JS is disabled/unavailable, content is fully visible — no animation.
    - prefers-reduced-motion: the reveal-enter CSS animation is disabled,
      content just appears immediately.
--}}

<div
    x-data="{ shown: false }"
    x-init="
        const el = $el;
        const delay = {{ (int)($delay * 1000) }};
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && !shown) {
                        setTimeout(() => {
                            el.classList.add('reveal-enter');
                            shown = true;
                            observer.disconnect();
                        }, delay);
                    }
                });
            },
            { threshold: 0.18 }
        );
        observer.observe(el);
    "
    {{ $attributes }}
>
    {{ $slot }}
</div>
