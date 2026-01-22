/**
 * Sage Coast Realty — Valley Center JSON-LD (Place + ItemList + FAQPage)
 * One JSON-LD script. Extends AIOSEO without replacing it.
 */
add_action('wp_head', function () {

    // ---- Bulletproof gate: match common Valley Center page paths
    $path = trim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
    $path = rtrim($path, '/');

    $allowed = [
        'living-in-valley-center',
        'community/featured-areas/valley-center',
    ];

    if (!in_array($path, $allowed, true)) {
        return;
    }

    // ---- Canonical URL for schema IDs (choose ONE that matches your live canonical)
    // If your live page is /community/featured-areas/valley-center/ then change this line.
    $page_url = home_url('/living-in-valley-center/');

    // ---- Popular Searches (2 links per card = 8 total) — matches your page
    $popular_searches = [
        [
            'name' => 'Open Houses in Valley Center (This Weekend)',
            'url'  => home_url('/listing-results?data-filter=bb&mls_id=ca45&city=Valley%20Center&openhouse_dt=weekend'),
        ],
        [
            'name' => 'Open Houses in Valley Center (Next Weekend)',
            'url'  => home_url('/listing-results?data-filter=bb&mls_id=ca45&city=Valley%20Center&openhouse_dt=next-weekend'),
        ],
        [
            'name' => 'Recently Listed Homes in Valley Center (Last 7 Days)',
            'url'  => home_url('/listing-results?data-filter=bb&mls_id=ca45&city=Valley%20Center&create_dt=7'),
        ],
        [
            'name' => 'Recently Listed Homes in Valley Center (Last 30 Days)',
            'url'  => home_url('/listing-results?data-filter=bb&mls_id=ca45&city=Valley%20Center&create_dt=30'),
        ],
        [
            'name' => 'Price Reductions in Valley Center (Last 7 Days)',
            'url'  => home_url('/listing-results?data-filter=bb&mls_id=ca45&city=Valley%20Center&price_drop=7'),
        ],
        [
            'name' => 'Price Reductions in Valley Center (Last 30 Days)',
            'url'  => home_url('/listing-results?data-filter=bb&mls_id=ca45&city=Valley%20Center&price_drop=30'),
        ],
        [
            'name' => 'Motivated Sellers in Valley Center (90+ Days on Market)',
            'url'  => home_url('/listing-results?data-filter=bb&mls_id=ca45&city=Valley%20Center&create_dt_min=90'),
        ],
        [
            'name' => 'Motivated Sellers in Valley Center (180+ Days on Market)',
            'url'  => home_url('/listing-results?data-filter=bb&mls_id=ca45&city=Valley%20Center&create_dt_min=180'),
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

    // ---- FAQPage (exact Q/A from your Valley Center HTML)
    $faqs = [
        [
            'q' => 'Is Valley Center a good place to live if I want more space and privacy?',
            'a' => 'Yes — that’s one of Valley Center’s strongest draws. Many neighborhoods and semi-rural pockets offer larger lots, more separation between homes, and a quieter pace than the coast. The trade-off is that road feel, services, and commute times vary more, so choosing the right pocket matters.',
        ],
        [
            'q' => 'What should I know about wells, septic, and agricultural zoning in Valley Center?',
            'a' => 'Some Valley Center properties rely on well and septic systems and may fall under agricultural zoning. That can impact inspections, maintenance routines, water considerations, and future use of the property. If you’re comparing larger lots, we’ll help you understand what’s typical for the area and what you should confirm during due diligence.',
        ],
        [
            'q' => 'How does Valley Center compare to Oceanside, Vista, or San Marcos?',
            'a' => 'Valley Center generally offers more land and a calmer, more rural-leaning lifestyle. Nearby cities can be closer to coastal amenities and may feel more uniform street-to-street. Valley Center’s advantage is variety — from village living to hillside views — and the key is matching the neighborhood to your daily routine.',
        ],
        [
            'q' => 'Are there planned communities in Valley Center, or is it mostly rural?',
            'a' => 'It’s a mix. Valley Center includes established neighborhoods and planned communities as well as larger-lot areas that feel more agricultural or equestrian. If you want lower-maintenance living, we can focus your search on neighborhoods with more predictable roads and services. If you want acreage, we’ll prioritize access, utilities, and long-term upkeep.',
        ],
        [
            'q' => 'What’s the smartest way to buy in Valley Center without overpaying?',
            'a' => 'In Valley Center, value is often tied to fundamentals that photos don’t show: access, terrain, usability of land, and the true “feel” of the location. A smart approach is to compare like-for-like properties, verify the ownership details that matter (water, septic, grading, road maintenance), and negotiate based on what the property will realistically take to own and maintain.',
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
                'name'  => 'Valley Center, CA',
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressLocality' => 'Valley Center',
                    'addressRegion'   => 'CA',
                    'addressCountry'  => 'US',
                ],
            ],
            [
                '@type' => 'ItemList',
                '@id'   => trailingslashit($page_url) . '#popular-searches',
                'name'  => 'Popular Searches in Valley Center, CA',
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
    echo "\n<!-- SageCoast JSON-LD injected: Valley Center -->\n";

    echo '<script type="application/ld+json">' .
        wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) .
        '</script>' . "\n";

}, 999);
