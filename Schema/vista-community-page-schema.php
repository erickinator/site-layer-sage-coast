
/**
 * Sage Coast Realty — Vista JSON-LD (Place + ItemList + FAQPage)
 * One JSON-LD script. Extends AIOSEO without replacing it.
 */
add_action('wp_head', function () {

    // ---- Bulletproof gate: match common Vista page paths
    $path = trim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
    $path = rtrim($path, '/');

    $allowed = [
        'living-in-vista',
        'community/featured-areas/vista',
    ];

    if (!in_array($path, $allowed, true)) {
        return;
    }

    // ---- Canonical URL for schema IDs (choose ONE that matches your live canonical)
    // If your live page is /community/featured-areas/vista/ then change this line.
    $page_url = home_url('/living-in-vista/');

    // ---- Popular Searches (2 links per card = 8 total) — matches your Vista HTML buttons
    $popular_searches = [
        [
            'name' => 'Open Houses in Vista (This Weekend)',
            'url'  => home_url('/listing-results?data-filter=bb&mls_id=ca45&city=Vista&openhouse_dt=weekend'),
        ],
        [
            'name' => 'Open Houses in Vista (Next Weekend)',
            'url'  => home_url('/listing-results?data-filter=bb&mls_id=ca45&city=Vista&openhouse_dt=next-weekend'),
        ],
        [
            'name' => 'Recently Listed Homes in Vista (Last 7 Days)',
            'url'  => home_url('/listing-results?data-filter=bb&mls_id=ca45&city=Vista&create_dt=7'),
        ],
        [
            'name' => 'Recently Listed Homes in Vista (Last 30 Days)',
            'url'  => home_url('/listing-results?data-filter=bb&mls_id=ca45&city=Vista&create_dt=30'),
        ],
        [
            'name' => 'Price Reductions in Vista (Last 7 Days)',
            'url'  => home_url('/listing-results?data-filter=bb&mls_id=ca45&city=Vista&price_drop=7'),
        ],
        [
            'name' => 'Price Reductions in Vista (Last 30 Days)',
            'url'  => home_url('/listing-results?data-filter=bb&mls_id=ca45&city=Vista&price_drop=30'),
        ],
        [
            'name' => 'Motivated Sellers in Vista (90+ Days on Market)',
            'url'  => home_url('/listing-results?data-filter=bb&mls_id=ca45&city=Vista&create_dt_min=90'),
        ],
        [
            'name' => 'Motivated Sellers in Vista (180+ Days on Market)',
            'url'  => home_url('/listing-results?data-filter=bb&mls_id=ca45&city=Vista&create_dt_min=180'),
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

    // ---- FAQPage (exact Q/A from your Vista HTML)
    $faqs = [
        [
            'q' => 'Is Vista a good place to live if I still want coastal access?',
            'a' => 'For many buyers, yes — Vista is close enough for easy beach days and coastal dining, but far enough inland to offer more housing variety and, often, more space for the money. The experience depends on which pocket you choose: some areas feel more “in-town and connected,” while others feel quieter and more removed.',
        ],
        [
            'q' => 'How do I compare neighborhoods within Vista without guessing?',
            'a' => 'Compare how the neighborhood lives, not just how the home photographs. Pay attention to elevation (heat and evening cooling can shift), road noise, traffic patterns at the times you’ll actually drive, and whether the area feels walkable vs. more “car-first.” A short second visit at a different time of day is often the most revealing step.',
        ],
        [
            'q' => 'What’s the biggest difference between Vista and nearby cities like Oceanside or Carlsbad?',
            'a' => 'The coast tends to feel more temperate and more uniform, while Vista offers more range — in lot sizes, home styles, and “pocket-to-pocket” lifestyle. You’re usually trading a bit of coastal climate consistency for more flexibility in space, price points, and neighborhood character.',
        ],
        [
            'q' => 'What ownership costs do Vista buyers commonly overlook?',
            'a' => 'Beyond the mortgage, it’s often utilities during warmer stretches, irrigation and landscape upkeep, and long-term exterior maintenance. In some newer pockets, HOA fees or special assessments can also be part of the picture. We’ll help you understand what’s typical for the specific neighborhood and property type you’re considering.',
        ],
        [
            'q' => 'What should I watch for with larger lots or semi-rural edges of Vista?',
            'a' => 'Larger lots can be wonderful — and they come with details that don’t show up in listing photos: drainage and grading, mature tree maintenance, defensible space and wildfire considerations, and the practical cost of keeping land “easy” over time. We’ll flag the items that matter early so you can make a confident decision.',
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
                'name'  => 'Vista, CA',
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressLocality' => 'Vista',
                    'addressRegion'   => 'CA',
                    'addressCountry'  => 'US',
                ],
            ],
            [
                '@type' => 'ItemList',
                '@id'   => trailingslashit($page_url) . '#popular-searches',
                'name'  => 'Popular Searches in Vista, CA',
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
    echo "\n<!-- SageCoast JSON-LD injected: Vista -->\n";

    echo '<script type="application/ld+json">' .
        wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) .
        '</script>' . "\n";

}, 999);
