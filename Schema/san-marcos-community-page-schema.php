
/**
 * Sage Coast Realty — San Marcos JSON-LD (Place + ItemList + FAQPage)
 * One JSON-LD script. Extends AIOSEO without replacing it.
 */
add_action('wp_head', function () {

    // ---- Bulletproof gate: match common San Marcos page paths
    $path = trim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
    $path = rtrim($path, '/');

    $allowed = [
        'living-in-san-marcos',
        'community/featured-areas/san-marcos',
    ];

    if (!in_array($path, $allowed, true)) {
        return;
    }

    // ---- Canonical URL for schema IDs (choose ONE that matches your live canonical)
    // If your live page is /community/featured-areas/san-marcos/ then change this line.
    $page_url = home_url('/living-in-san-marcos/');

    // ---- Popular Searches (2 links per card = 8 total) — matches your page
    $popular_searches = [
        [
            'name' => 'Open Houses in San Marcos (This Weekend)',
            'url'  => home_url('/listing-results?data-filter=bb&mls_id=ca45&city=San Marcos&openhouse_dt=weekend'),
        ],
        [
            'name' => 'Open Houses in San Marcos (Next Weekend)',
            'url'  => home_url('/listing-results?data-filter=bb&mls_id=ca45&city=San Marcos&openhouse_dt=next-weekend'),
        ],
        [
            'name' => 'Recently Listed Homes in San Marcos (Last 7 Days)',
            'url'  => home_url('/listing-results?data-filter=bb&mls_id=ca45&city=San Marcos&create_dt=7'),
        ],
        [
            'name' => 'Recently Listed Homes in San Marcos (Last 30 Days)',
            'url'  => home_url('/listing-results?data-filter=bb&mls_id=ca45&city=San Marcos&create_dt=30'),
        ],
        [
            'name' => 'Price Reductions in San Marcos (Last 7 Days)',
            'url'  => home_url('/listing-results?data-filter=bb&mls_id=ca45&city=San Marcos&price_drop=7'),
        ],
        [
            'name' => 'Price Reductions in San Marcos (Last 30 Days)',
            'url'  => home_url('/listing-results?data-filter=bb&mls_id=ca45&city=San Marcos&price_drop=30'),
        ],
        [
            'name' => 'Motivated Sellers in San Marcos (90+ Days on Market)',
            'url'  => home_url('/listing-results?data-filter=bb&mls_id=ca45&city=San Marcos&create_dt_min=90'),
        ],
        [
            'name' => 'Motivated Sellers in San Marcos (180+ Days on Market)',
            'url'  => home_url('/listing-results?data-filter=bb&mls_id=ca45&city=San Marcos&create_dt_min=180'),
        ],
    ];

    $itemList = [];
    foreach ($popular_searches as $i => $s) {
        $itemList[] = [
            '@type' => 'ListItem',
            'position' => $i + 1,
            'name' => $s['name'],
            'url'  => $s['url'],
        ];
    }

    // ---- FAQPage (San Marcos FAQs from the page)
    $faqs = [
        [
            'q' => 'Is San Marcos a good place to live if I work in North County or commute?',
            'a' => 'For many buyers, yes. San Marcos sits close to major routes and employment corridors, so you can often get strong day-to-day convenience without paying a pure coastal premium. The key is choosing a pocket that matches your commute pattern. Two homes can be the same distance on a map but feel very different at peak traffic hours.',
        ],
        [
            'q' => 'What’s the real difference between San Marcos neighborhoods like San Elijo Hills, Lake San Marcos, and the “in-town” pockets?',
            'a' => 'San Marcos is a city of micro-lifestyles. Some areas lean “planned community” with trails and amenities, others feel more established and central, and some offer lake or golf-adjacent living. When we narrow options, we compare not just the home, but walkability, slope/elevation, HOA structure, and the day-to-day rhythm you want.',
        ],
        [
            'q' => 'Are HOAs common in San Marcos, and what should I pay attention to?',
            'a' => 'Many San Marcos communities include HOAs, especially where amenities, landscaping standards, or shared facilities are part of the lifestyle. Dues are only the starting point. We review what’s included, reserve health, restrictions that matter to you (parking, rentals, exterior changes), and whether the rules fit how you want to live.',
        ],
        [
            'q' => 'What costs do buyers overlook when owning a home in San Marcos?',
            'a' => 'The surprises are usually practical: utilities during warmer stretches, landscaping or slope maintenance on certain lots, and the long-term “upkeep reality” of outdoor spaces. If a home sits on a hill or near open space, we also look at drainage, erosion control, and defensible space so the home stays easy to own over time.',
        ],
        [
            'q' => 'How do I buy confidently in San Marcos without overpaying?',
            'a' => 'Start by separating “presentation” from “value.” In San Marcos, the best opportunities often appear when a home is solid but not perfectly styled, or when a layout trade-off turns away casual browsers. We’ll anchor your offer using true neighborhood comps, current competition, and the property’s functional strengths so you can bid with confidence and avoid paying a premium for features that won’t matter to you long-term.',
        ],
    ];

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

    // ---- Schema graph: Place + ItemList + FAQPage
    $schema = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'Place',
                '@id'   => trailingslashit($page_url) . '#place',
                'name'  => 'San Marcos, CA',
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressLocality' => 'San Marcos',
                    'addressRegion'   => 'CA',
                    'addressCountry'  => 'US',
                ],
            ],
            [
                '@type' => 'ItemList',
                '@id'   => trailingslashit($page_url) . '#popular-searches',
                'name'  => 'Popular Searches in San Marcos, CA',
                'itemListOrder' => 'https://schema.org/ItemListOrderAscending',
                'numberOfItems' => count($itemList),
                'itemListElement' => $itemList,
            ],
            [
                '@type' => 'FAQPage',
                '@id'   => trailingslashit($page_url) . '#faq',
                'mainEntity' => $mainEntity,
            ],
        ],
    ];

    // Debug marker (View Source -> search this)
    echo "\n<!-- SageCoast JSON-LD injected: San Marcos -->\n";

    echo '<script type="application/ld+json">' .
        wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) .
        '</script>' . "\n";

}, 999);
