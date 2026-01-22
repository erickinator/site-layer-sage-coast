
/**
 * Sage Coast Realty — Why List With Us JSON-LD (Service + HowTo + WebPage)
 * One JSON-LD script. Extends AIOSEO without replacing it.
 * Add via: Code Snippets (PHP) -> Run on Front-end.
 */
add_action('wp_head', function () {

    // ---- Gate: update to your actual slug when published
    // Common slugs might be: why-list-with-us, sellers, sellers-guide, why-list, listing-services
    $path = trim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
    $path = rtrim($path, '/');

    $allowed = [
        'why-list-with-us',
        'why-list-with-sage-coast-realty',
        'sellers',
        'sellers-guide',
        'listing-services',
    ];

    if (!in_array($path, $allowed, true)) {
        return;
    }

    $home_url = home_url('/');
    // Canonical for this page (update if your actual slug differs)
    $page_url = home_url('/why-list-with-us/');

    $brand_name = 'Sage Coast Realty';
    $slogan     = 'Where Inland Calm Meets Coastal Sophistication';

    $email = 'info@sagecoastrealty.com';
    $phone = '+17607058998';

    // Address (keeps your LocalBusiness entity consistent everywhere)
    $address = [
        '@type' => 'PostalAddress',
        'streetAddress' => '128 E Grand Ave #11',
        'addressLocality' => 'Escondido',
        'addressRegion'   => 'CA',
        'postalCode'      => '92025',
        'addressCountry'  => 'US',
    ];

    // Areas mentioned throughout the site and aligned to your communities focus
    $areas = [
        'North County San Diego',
        'Escondido, CA',
        'San Marcos, CA',
        'Vista, CA',
        'Fallbrook, CA',
        'Valley Center, CA',
        'Rancho Bernardo, CA',
    ];

    $area_nodes = array_map(function ($a) {
        return ['@type' => 'Place', 'name' => $a];
    }, $areas);

    // 4-step process timeline from the page
    $howto_steps = [
        [
            'name' => 'Comprehensive Market Analysis',
            'text' => 'Analyze local micro-market conditions, recent sales, and active competition to determine an optimal pricing and positioning strategy.',
        ],
        [
            'name' => 'Strategic Property Preparation',
            'text' => 'Prepare the home thoughtfully with staging guidance and targeted improvements that protect value and improve buyer perception.',
        ],
        [
            'name' => 'Premium Marketing Launch',
            'text' => 'Launch coordinated marketing across professional visuals, distribution, and follow-up systems designed to reach informed buyers.',
        ],
        [
            'name' => 'Expert Negotiation & Closing',
            'text' => 'Negotiate with experience and calm guidance, manage paperwork and timelines, coordinate inspections, and drive a clean closing.',
        ],
    ];

    $howto_step_nodes = [];
    foreach ($howto_steps as $i => $s) {
        $howto_step_nodes[] = [
            '@type' => 'HowToStep',
            'position' => $i + 1,
            'name' => $s['name'],
            'text' => $s['text'],
        ];
    }

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

            // WebPage
            [
                '@type' => 'WebPage',
                '@id'   => $page_url . '#webpage',
                'url'   => $page_url,
                'name'  => 'Why List With Sage Coast Realty | North County San Diego',
                'description' => 'A seller-first listing strategy built on positioning, preparation, marketing systems, and experience-led negotiation across North County San Diego.',
                'isPartOf' => [
                    '@id' => $home_url . '#website',
                ],
                'about' => [
                    '@id' => $home_url . '#realestateagent',
                ],
                'mainEntity' => [
                    '@id' => $page_url . '#service',
                ],
            ],

            // Service (Seller representation)
            [
                '@type' => 'Service',
                '@id'   => $page_url . '#service',
                'name'  => 'Listing Representation in North County San Diego',
                'serviceType' => 'Residential listing representation',
                'provider' => [
                    '@id' => $home_url . '#realestateagent',
                ],
                'areaServed' => $area_nodes,
                'audience' => [
                    '@type' => 'Audience',
                    'audienceType' => 'Home sellers',
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

            // HowTo (Proven Selling Process)
            [
                '@type' => 'HowTo',
                '@id'   => $page_url . '#howto',
                'name'  => 'Our Proven Selling Process',
                'description' => 'A clear 4-step approach designed to protect value, attract aligned buyers, and negotiate from a position of strength.',
                'inLanguage' => 'en-US',
                'provider' => [
                    '@id' => $home_url . '#realestateagent',
                ],
                'step' => $howto_step_nodes,
            ],

            // Optional: include the business entity node here too, if you haven't already globally.
            // Safe even if homepage snippet exists (same @id).
            [
                '@type' => 'RealEstateAgent',
                '@id'   => $home_url . '#realestateagent',
                'name'  => $brand_name,
                'url'   => $home_url,
                'slogan' => $slogan,
                'email' => $email,
                'telephone' => $phone,
                'address' => $address,
                'areaServed' => $area_nodes,
                'founder' => [
                    '@id' => $home_url . '#jill-magnuson',
                ],
            ],
        ],
    ];

    echo "\n<!-- SageCoast JSON-LD injected: Why List With Us -->\n";

    echo '<script type="application/ld+json">' .
        wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) .
        '</script>' . "\n";

}, 999);
