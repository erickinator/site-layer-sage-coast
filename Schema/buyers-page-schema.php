
/**
 * Sage Coast Realty — Buyer’s Guide JSON-LD (HowTo + Service + VideoObject + WebPage)
 * One JSON-LD script. Extends AIOSEO without replacing it.
 * Add via: Code Snippets (PHP) -> Run on Front-end.
 */
add_action('wp_head', function () {

    // ---- Gate: buyer guide page path (add variants if your slug differs)
    $path = trim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
    $path = rtrim($path, '/');

    $allowed = [
        'buyers-guide',
        'buyer-guide',
        'your-north-county-buying-journey',
        'north-county-buying-journey',
        'buying-journey',
        'buyers',
    ];

    if (!in_array($path, $allowed, true)) {
        return;
    }

    $home_url = home_url('/');

    // Canonical URL for the Buyer’s Guide page (update this to your real slug if needed)
    $page_url = home_url('/buyers-guide/');

    $brand_name = 'Sage Coast Realty';
    $slogan     = 'Where Inland Calm Meets Coastal Sophistication';

    $phone = '+17607058998';
    $email = 'info@sagecoastrealty.com';

    // Areas referenced on the page
    $areas = [
        'Vista, CA',
        'San Marcos, CA',
        'Escondido, CA',
        'Valley Center, CA',
        'Fallbrook, CA',
    ];

    $area_nodes = array_map(function ($a) {
        return ['@type' => 'Place', 'name' => $a];
    }, $areas);

    // Video in hero (from your page)
    $youtube_id = 'wcX-zXn4PN8';

    $schema = [
        '@context' => 'https://schema.org',
        '@graph' => [

            // Website anchor
            [
                '@type' => 'WebSite',
                '@id'   => $home_url . '#website',
                'url'   => $home_url,
                'name'  => $brand_name,
                'description' => $slogan,
            ],

            // WebPage node
            [
                '@type' => 'WebPage',
                '@id'   => $page_url . '#webpage',
                'url'   => $page_url,
                'name'  => 'Your North County Buying Journey | Sage Coast Realty',
                'description' => 'Calm, experienced guidance for buyers in Vista, San Marcos, Escondido, Valley Center, and Fallbrook.',
                'isPartOf' => [
                    '@id' => $home_url . '#website',
                ],
                'about' => [
                    '@id' => $home_url . '#realestateagent',
                ],
                'primaryImageOfPage' => [
                    '@type' => 'ImageObject',
                    'url'   => $home_url . 'wp-content/uploads/2025/07/sagecoast-gold-nobk-scaled.png',
                ],
                'subjectOf' => [
                    '@id' => $page_url . '#video',
                ],
            ],

            // VideoObject (hero background video)
            [
                '@type' => 'VideoObject',
                '@id'   => $page_url . '#video',
                'name'  => 'North County Home Buying Journey',
                'description' => 'A calm, strategic overview of buying a home in North County San Diego with Sage Coast Realty.',
                'thumbnailUrl' => [
                    'https://img.youtube.com/vi/' . $youtube_id . '/maxresdefault.jpg',
                ],
                'embedUrl' => 'https://www.youtube.com/embed/' . $youtube_id,
                'uploadDate' => '2025-01-01',
            ],

            // Service (buyer representation)
            [
                '@type' => 'Service',
                '@id'   => $page_url . '#service',
                'name'  => 'Buyer Representation in North County San Diego',
                'serviceType' => 'Residential buyer representation',
                'provider' => [
                    '@id' => $home_url . '#realestateagent',
                ],
                'areaServed' => $area_nodes,
                'audience' => [
                    '@type' => 'Audience',
                    'audienceType' => 'Home buyers',
                ],
                'availableChannel' => [
                    '@type' => 'ServiceChannel',
                    'servicePhone' => [
                        '@type' => 'ContactPoint',
                        'telephone' => $phone,
                        'contactType' => 'sales',
                        'email' => $email,
                    ],
                ],
            ],

            // HowTo (the 5-step journey on the page)
            [
                '@type' => 'HowTo',
                '@id'   => $page_url . '#howto',
                'name'  => 'How to Buy a Home in North County San Diego',
                'description' => 'A structured 5-step process designed to reduce uncertainty and strengthen your position at every step.',
                'inLanguage' => 'en-US',
                'provider' => [
                    '@id' => $home_url . '#realestateagent',
                ],
                'step' => [
                    [
                        '@type' => 'HowToStep',
                        'position' => 1,
                        'name' => 'Initial Consultation & Pre-Approval',
                        'text' => 'Align goals, timeline, and budget. Get strong pre-approval with trusted local lenders to improve leverage and keep the search focused.',
                    ],
                    [
                        '@type' => 'HowToStep',
                        'position' => 2,
                        'name' => 'Strategic Home Search',
                        'text' => 'Run a focused search across Vista, San Marcos, Escondido, Valley Center, and Fallbrook with clear guidance on value, condition, and long-term fit.',
                    ],
                    [
                        '@type' => 'HowToStep',
                        'position' => 3,
                        'name' => 'Making Your Offer',
                        'text' => 'Build an offer strategy around comps, terms, timing, and risk. Win without overpaying while keeping protections where they matter.',
                    ],
                    [
                        '@type' => 'HowToStep',
                        'position' => 4,
                        'name' => 'Due Diligence & Inspections',
                        'text' => 'Coordinate inspections and review disclosures with clarity. Understand what is real, what is negotiable, and what changes the decision.',
                    ],
                    [
                        '@type' => 'HowToStep',
                        'position' => 5,
                        'name' => 'Closing & Beyond',
                        'text' => 'Manage details through closing and remain a long-term resource after keys, from planning to future moves.',
                    ],
                ],
            ],
        ],
    ];

    echo "\n<!-- SageCoast JSON-LD injected: Buyers Guide -->\n";

    echo '<script type="application/ld+json">' .
        wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) .
        '</script>' . "\n";

}, 999);
