
/**
 * Sage Coast Realty — Communities Hub JSON-LD (ItemList + FAQPage)
 * Bulletproof gating for: /north-san-diego-county-communities/
 */
add_action('wp_head', function () {

    // ---- Gate: match by exact URL path (most reliable)
    $request_path = trim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
    if ($request_path !== 'north-san-diego-county-communities') {
        return;
    }

    // Optional: also ensure it's a WP page (not strictly required)
    // if (!is_page()) return;

    $page_url = home_url('/north-san-diego-county-communities/');

    $guides = [
        [
            'name'  => 'Living in Escondido, CA',
            'url'   => 'https://sagecoastrealty.com/living-in-escondido/',
            'image' => 'https://sagecoastrealty.com/wp-content/uploads/2025/12/escondido-old.jpg',
        ],
        [
            'name'  => 'Living in Vista, CA',
            'url'   => 'https://sagecoastrealty.com/living-in-vista/',
            'image' => 'https://sagecoastrealty.com/wp-content/uploads/2025/12/VistaDtown101.jpg',
        ],
        [
            'name'  => 'Living in San Marcos, CA',
            'url'   => 'https://sagecoastrealty.com/living-in-san-marcos/',
            'image' => 'https://sagecoastrealty.com/wp-content/uploads/2025/12/SanMarcos.jpg',
        ],
        [
            'name'  => 'Living in Fallbrook, CA',
            'url'   => 'https://sagecoastrealty.com/living-in-fallbrook/',
            'image' => 'https://sagecoastrealty.com/wp-content/uploads/2025/12/fallbrook.png',
        ],
        [
            'name'  => 'Living in Valley Center, CA',
            'url'   => 'https://sagecoastrealty.com/living-in-valley-center/',
            'image' => 'https://sagecoastrealty.com/wp-content/uploads/2025/12/valley-center.png',
        ],
    ];

    $faqs = [
        [
            'q' => 'How do I choose the right North County San Diego community?',
            'a' => 'Start with lifestyle fit: commute rhythm, daily convenience, and how the neighborhood feels at normal hours. Then compare ownership realities like lot size, micro-climates, and (where relevant) land systems. Finally, match market behavior to your timeline so you’re not overpaying for the wrong compromise.',
        ],
        [
            'q' => 'Which areas are best if I want more land, privacy, and quiet?',
            'a' => 'Fallbrook and Valley Center are strong starting points for buyers prioritizing space and privacy. They can feel worlds away day-to-day, while still keeping practical access to Escondido and North County amenities.',
        ],
        [
            'q' => 'Do these guides cover wells, septic, zoning, or other rural considerations?',
            'a' => 'Yes. Where it matters, we call out common semi-rural and rural considerations like water supply, septic systems, access, land use, and long-term operating costs. Those details often matter more than finishes.',
        ],
        [
            'q' => 'Can you set up alerts for listings in a specific community?',
            'a' => 'Absolutely. Share your target community (or two), plus your non-negotiables, and we’ll set up tailored alerts for new listings, price changes, and opportunities that match your buying strategy.',
        ],
        [
            'q' => 'I’m deciding between two areas, can Jill help me compare them?',
            'a' => 'Yes. This is exactly where experience matters. Jill will help you compare the trade-offs that don’t show up in photos: comfort, access, long-term costs, and how the market behaves when conditions change.',
        ],
    ];

    $itemList = [];
    foreach ($guides as $i => $g) {
        $itemList[] = [
            '@type'    => 'ListItem',
            'position' => $i + 1,
            'name'     => $g['name'],
            'url'      => $g['url'],
            'image'    => $g['image'],
        ];
    }

    $mainEntity = [];
    foreach ($faqs as $f) {
        $mainEntity[] = [
            '@type' => 'Question',
            'name'  => $f['q'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text'  => $f['a'],
            ],
        ];
    }

    $schema = [
        '@context' => 'https://schema.org',
        '@graph'   => [
            [
                '@type' => 'ItemList',
                '@id'   => trailingslashit($page_url) . '#community-guides',
                'name'  => 'North County San Diego Community Guides',
                'itemListOrder' => 'https://schema.org/ItemListOrderAscending',
                'numberOfItems' => count($guides),
                'itemListElement' => $itemList,
            ],
            [
                '@type' => 'FAQPage',
                '@id'   => trailingslashit($page_url) . '#faq',
                'mainEntity' => $mainEntity,
            ],
        ],
    ];

    // ✅ Debug marker so you can confirm it’s outputting even if Rich Results ignores it
    echo "\n<!-- SageCoast JSON-LD injected: communities hub -->\n";

    echo '<script type="application/ld+json">' .
        wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) .
        '</script>' . "\n";

}, 999);
