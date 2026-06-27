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
        let data = $data;
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting && !data.shown) {
                    data.shown = true;
                    $el.classList.add('reveal-enter');
                    observer.disconnect();
                }
            });
        }, { threshold: 0.12 });
        observer.observe($el);
    "
    {{ $attributes }}
>
    {{ $slot }}
</div>
