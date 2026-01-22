/**
 * Sage Coast Realty — Escondido JSON-LD (Place + ItemList + FAQPage)
 * Outputs ONE JSON-LD script on the Escondido community page.
 */
add_action('wp_head', function () {

    // ---- Bulletproof gate: match common Escondido page paths
    $path = trim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');

    $allowed = [
        'living-in-escondido',
        'community/featured-areas/escondido',
        'community/featured-areas/escondido/', // harmless redundancy
    ];

    // normalize trailing slash variants
    $path = rtrim($path, '/');

    if (!in_array($path, array_map(fn($p) => rtrim(trim($p,'/'),'/'), $allowed), true)) {
        return;
    }

    // Canonical URL you want represented in schema (pick ONE)
    // If your live page is /community/featured-areas/escondido/ then change this to that.
    $page_url = home_url('/living-in-escondido/');

    // ---- ItemList: Popular Searches (from your page CTA cards)
    $popular_searches = [
        [
            'name' => 'Open Houses in Escondido (This Weekend)',
            'url'  => home_url('/listing-results?data-filter=bb&mls_id=ca45&city=Escondido&openhouse_dt=weekend'),
        ],
        [
            'name' => 'Recently Listed Homes in Escondido (Last 7 Days)',
            'url'  => home_url('/listing-results?data-filter=bb&mls_id=ca45&city=Escondido&create_dt=7'),
        ],
        [
            'name' => 'Price Reductions in Escondido (Last 7 Days)',
            'url'  => home_url('/listing-results?data-filter=bb&mls_id=ca45&city=Escondido&price_drop=7'),
        ],
        [
            'name' => 'Motivated Sellers in Escondido (90+ Days on Market)',
            'url'  => home_url('/listing-results?data-filter=bb&mls_id=ca45&city=Escondido&create_dt_min=90'),
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

    // ---- FAQPage: matches your Escondido FAQ section (same Qs + same meaning)
    $faqs = [
        [
            'q' => 'Is Escondido a good place to live for families?',
            'a' => 'For many families, Escondido is a strong fit because you can often get more space and more neighborhood variety than the immediate coast, while staying close to shopping, parks, and freeway access. The best pocket depends on your daily routine, school preferences, and whether you want a more walkable feel or a quieter residential setting.',
        ],
        [
            'q' => 'What’s the biggest difference between Escondido and nearby coastal cities?',
            'a' => 'Escondido tends to offer more housing styles, more lot-size variation, and a slightly calmer day-to-day pace, but with warmer afternoons in summer and more neighborhood-to-neighborhood change. Coastal cities can feel more uniform and temperate, while Escondido offers range and trade-offs that are worth comparing carefully.',
        ],
        [
            'q' => 'How should I compare neighborhoods within Escondido?',
            'a' => 'Don’t just compare homes. Compare how the area lives. Look at drive-time patterns at the hours you’ll actually commute, pay attention to micro-climates, and watch for things photos won’t reveal such as road noise, grading/slope, drainage, and long-term landscaping demands. A second visit at a different time of day is often more revealing than more online browsing.',
        ],
        [
            'q' => 'What costs do buyers overlook when owning a home in Escondido?',
            'a' => 'Beyond the mortgage, the most common surprises are utilities during hot stretches, irrigation or landscape maintenance on larger lots, and ongoing property realities like tree care, drainage upkeep, and exterior wear. For hillside or larger-lot homes, it is also smart to consider slope management and ongoing land stewardship so the home stays easy to own.',
        ],
        [
            'q' => 'What’s a smart way to buy in Escondido without overpaying?',
            'a' => 'Start by separating home value from home presentation. In Escondido, opportunities can appear when a home is solid but not perfectly styled, or when a layout trade-off turns away casual browsers. The right strategy is neighborhood-specific: compare recent sales, current competing inventory, and the property’s functional strengths so you can bid confidently and avoid paying a premium for features that won’t matter long-term.',
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

    // ---- Place entity (lightweight, clean, and helps AI/Google contextualize)
    $schema = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'Place',
                '@id'   => trailingslashit($page_url) . '#place',
                'name'  => 'Escondido, CA',
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressLocality' => 'Escondido',
                    'addressRegion'   => 'CA',
                    'addressCountry'  => 'US',
                ],
            ],
            [
                '@type' => 'ItemList',
                '@id'   => trailingslashit($page_url) . '#popular-searches',
                'name'  => 'Popular Searches in Escondido, CA',
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

    // Debug marker (view-source search for this)
    echo "\n<!-- SageCoast JSON-LD injected: Escondido -->\n";

    echo '<script type="application/ld+json">' .
        wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) .
        '</script>' . "\n";

}, 999);
