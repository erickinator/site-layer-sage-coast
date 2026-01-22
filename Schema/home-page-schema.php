
/**
 * Sage Coast Realty — Homepage JSON-LD (Entity + Website)
 * Adds RealEstateAgent + Person + WebSite graph on the homepage only.
 * Add via: Code Snippets (PHP) -> Run on Front-end.
 */
add_action('wp_head', function () {

    // Front page only
    if (!is_front_page()) {
        return;
    }

    $home_url = home_url('/');

    // Primary visible contact info from homepage
    $brand_name  = 'Sage Coast Realty';
    $email       = 'info@sagecoastrealty.com';
    $phone       = '+17607058998'; // ✅ cleaner E.164
    $dre         = '01213323';

    // ✅ Required business address for LocalBusiness/RealEstateAgent rich results
    $street_address = '128 E Grand Ave #11';
    $city           = 'Escondido';
    $region         = 'CA';
    $postal_code    = '92025';
    $country        = 'US';

    // Brand promise / specialization pulled from homepage messaging
    $slogan = 'Where Inland Calm Meets Coastal Sophistication';

    // Areas you clearly serve across the site (and reflected in nav/communities)
    $areas_served = [
        'North County San Diego',
        'Escondido, CA',
        'San Marcos, CA',
        'Vista, CA',
        'Fallbrook, CA',
        'Valley Center, CA',
    ];

    $area_nodes = [];
    foreach ($areas_served as $a) {
        $area_nodes[] = [
            '@type' => 'Place',
            'name'  => $a,
        ];
    }

    $schema = [
        '@context' => 'https://schema.org',
        '@graph' => [

            // Website
            [
                '@type' => 'WebSite',
                '@id'   => $home_url . '#website',
                'url'   => $home_url,
                'name'  => $brand_name,
                'description' => $slogan,
                'potentialAction' => [
                    '@type' => 'SearchAction',
                    'target' => [
                        '@type' => 'EntryPoint',
                        // If you use a different search URL, swap this.
                        'urlTemplate' => $home_url . '?s={search_term_string}',
                    ],
                    'query-input' => 'required name=search_term_string',
                ],
            ],

            // Homepage
            [
                '@type' => 'WebPage',
                '@id'   => $home_url . '#webpage',
                'url'   => $home_url,
                'name'  => 'Sage Coast Realty | Jill Magnuson',
                'isPartOf' => [
                    '@id' => $home_url . '#website',
                ],
                'about' => [
                    '@id' => $home_url . '#realestateagent',
                ],
            ],

            // Jill (Person)
            [
                '@type' => 'Person',
                '@id'   => $home_url . '#jill-magnuson',
                'name'  => 'Jill Magnuson',
                'jobTitle' => 'Broker',
                'worksFor' => [
                    '@id' => $home_url . '#realestateagent',
                ],
                'identifier' => [
                    '@type' => 'PropertyValue',
                    'propertyID' => 'CA DRE',
                    'value' => $dre,
                ],
            ],

            // Sage Coast Realty (RealEstateAgent)
            [
                '@type' => 'RealEstateAgent',
                '@id'   => $home_url . '#realestateagent',
                'name'  => $brand_name,
                'url'   => $home_url,
                'slogan' => $slogan,
                'email' => $email,
                'telephone' => $phone,

                // ✅ REQUIRED (fixes "Missing field 'address'")
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => $street_address,
                    'addressLocality' => $city,
                    'addressRegion'   => $region,
                    'postalCode'      => $postal_code,
                    'addressCountry'  => $country,
                ],

                'areaServed' => $area_nodes,
                'founder' => [
                    '@id' => $home_url . '#jill-magnuson',
                ],
                // Optional: if you have an About page for Jill/brand, set sameAs to that URL.
                // 'sameAs' => [],
            ],
        ],
    ];

    // Debug marker
    echo "\n<!-- SageCoast JSON-LD injected: Homepage entity graph -->\n";

    echo '<script type="application/ld+json">' .
        wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) .
        '</script>' . "\n";

}, 999);
