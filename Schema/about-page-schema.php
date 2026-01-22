
/**
 * Sage Coast Realty — About Jill JSON-LD (AboutPage + Person + RealEstateAgent)
 * One JSON-LD script. Extends AIOSEO without replacing it.
 * Add via: Code Snippets (PHP) -> Run on Front-end.
 */
add_action('wp_head', function () {

    // ---- Bulletproof gate: About Jill page path
    $path = trim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
    $path = rtrim($path, '/');

    if ($path !== 'about-jill-magnuson') {
        return;
    }

    $home_url  = home_url('/');
    $page_url  = home_url('/about-jill-magnuson/');

    // Business / contact
    $brand_name = 'Sage Coast Realty';
    $email      = 'info@sagecoastrealty.com';
    $phone      = '+17607058998';
    $dre        = '01213323';

    // Address (needed for LocalBusiness validation consistency)
    $address = [
        '@type' => 'PostalAddress',
        'streetAddress' => '128 E Grand Ave #11',
        'addressLocality' => 'Escondido',
        'addressRegion'   => 'CA',
        'postalCode'      => '92025',
        'addressCountry'  => 'US',
    ];

    $slogan = 'Where Inland Calm Meets Coastal Sophistication';

    $areas_served = [
        'North County San Diego',
        'Escondido, CA',
        'San Marcos, CA',
        'Vista, CA',
        'Fallbrook, CA',
        'Valley Center, CA',
    ];

    $area_nodes = array_map(function ($a) {
        return ['@type' => 'Place', 'name' => $a];
    }, $areas_served);

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

            // About page
            [
                '@type' => 'AboutPage',
                '@id'   => $page_url . '#aboutpage',
                'url'   => $page_url,
                'name'  => 'About Jill Magnuson - Sage Coast Realty',
                'isPartOf' => [
                    '@id' => $home_url . '#website',
                ],
                'about' => [
                    '@id' => $home_url . '#jill-magnuson',
                ],
                // Keep this only if the image is actually used on the page
                'primaryImageOfPage' => [
                    '@type' => 'ImageObject',
                    'url'   => 'http://sagecoastrealty.com/wp-content/uploads/2025/07/jill-magnuson-sage-coast-realty.jpg',
                ],
            ],

            // Jill (Person)
            [
                '@type' => 'Person',
                '@id'   => $home_url . '#jill-magnuson',
                'name'  => 'Jill Magnuson',
                'jobTitle' => 'Broker & Founder',
                'worksFor' => [
                    '@id' => $home_url . '#realestateagent',
                ],
                'identifier' => [
                    '@type' => 'PropertyValue',
                    'propertyID' => 'CA DRE',
                    'value' => $dre,
                ],
                'hasCredential' => [
                    [
                        '@type' => 'EducationalOccupationalCredential',
                        'name'  => 'Certified Residential Specialist (CRS)',
                    ],
                    [
                        '@type' => 'EducationalOccupationalCredential',
                        'name'  => 'Luxury Home Marketing Specialist',
                    ],
                    [
                        '@type' => 'EducationalOccupationalCredential',
                        'name'  => 'Green Designation',
                    ],
                ],
                'knowsAbout' => [
                    'North County San Diego real estate',
                    'Inland and coastal market transitions',
                    'Rural and semi-rural properties',
                    'Estate-level properties',
                    'Land use, zoning, wells and septic considerations',
                    'Energy efficiency and sustainable home features',
                    'Negotiation strategy and transaction guidance',
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
                'address' => $address,
                'areaServed' => $area_nodes,
                'founder' => [
                    '@id' => $home_url . '#jill-magnuson',
                ],
            ],
        ],
    ];

    echo "\n<!-- SageCoast JSON-LD injected: About Jill -->\n";

    echo '<script type="application/ld+json">' .
        wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) .
        '</script>' . "\n";

}, 999);
