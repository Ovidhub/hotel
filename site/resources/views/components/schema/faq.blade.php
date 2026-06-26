@props(['faqs'])

@php
    $mainEntity = [];
    foreach ($faqs as $faq) {
        $mainEntity[] = [
            '@type'          => 'Question',
            'name'           => $faq['question'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text'  => $faq['answer'],
            ],
        ];
    }

    $data = [
        '@context'   => 'https://schema.org',
        '@type'      => 'FAQPage',
        'mainEntity' => $mainEntity,
    ];
@endphp
<script type="application/ld+json">{!! json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
