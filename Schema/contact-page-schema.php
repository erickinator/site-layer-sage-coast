
/**
 * Sage Coast Realty — Contact Page JSON-LD (ContactPage + ContactPoint + OpeningHours + Map)
 * One JSON-LD script. Extends AIOSEO without replacing it.
 * Add via: Code Snippets (PHP) -> Run on Front-end.
 */
add_action('wp_head', function () {

    // ---- Gate: Contact page path (adjust if your slug differs)
    $path = trim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
    $path = rtrim($path, '/');

    $allowed = [
        'contact',
        'contact-us',
        'get-in-touch',
    ];

    if (!in_array($path, $allowed, true)) {
        return;
    }

    $home_url = home_url('/');
    $page_url = home_url('/contact/');

    $brand_name = 'Sage Coast Realty';
    $slogan     = 'Where Inland Calm Meets Coastal Sophistication';

    $email = 'info@sagecoastrealty.com';
    $phone = '+17607058998';
    $dre   = '01213323';

    $address = [
        '@type' => 'PostalAddress',
        'streetAddress'   => '128 E Grand Ave #11',
        'addressLocality' => 'Escondido',
        'addressRegion'   => 'CA',
        'postalCode'      => '92025',
        'addressCountry'  => 'US',
    ];

    // Hours from your page
    $opening_hours = [
        'Mo-Fr 09:00-18:00',
        'Sa 10:00-16:00',
    ];

    // Optional coordinates from your map embed (parsed from your iframe: 33.1222524, -117.0830393)
    $geo = [
        '@type' => 'GeoCoordinates',
        'latitude'  => 33.1222524,
        'longitude' => -117.0830393,
    ];

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

            // Contact page
            [
                '@type' => 'ContactPage',
                '@id'   => $page_url . '#contactpage',
                'url'   => $page_url,
                'name'  => 'Contact Us - Sage Coast Realty',
                'description' => 'Contact Sage Coast Realty for buying and selling guidance in North County San Diego, including Escondido, Vista, San Marcos, Fallbrook, and Valley Center.',
                'isPartOf' => [
                    '@id' => $home_url . '#website',
                ],
                'about' => [
                    '@id' => $home_url . '#realestateagent',
                ],
                'primaryImageOfPage' => [
                    '@type' => 'ImageObject',
                    'url'   => 'http://sagecoastrealty.com/wp-content/uploads/2025/07/sage-coast-home-001.jpg',
                ],
            ],

            // ContactPoint (shows how to reach you)
            [
                '@type' => 'ContactPoint',
                '@id'   => $page_url . '#contactpoint',
                'contactType' => 'sales',
                'telephone' => $phone,
                'email' => $email,
                'availableLanguage' => ['en'],
                'areaServed' => 'US-CA',
            ],

            // Business entity (safe to repeat @id; merges with homepage graph)
            [
                '@type' => 'RealEstateAgent',
                '@id'   => $home_url . '#realestateagent',
                'name'  => $brand_name,
                'url'   => $home_url,
                'slogan' => $slogan,
                'email' => $email,
                'telephone' => $phone,
                'address' => $address,
                'geo' => $geo,
                'openingHoursSpecification' => [
                    [
                        '@type' => 'OpeningHoursSpecification',
                        'dayOfWeek' => ['Monday','Tuesday','Wednesday','Thursday','Friday'],
                        'opens' => '09:00',
                        'closes' => '18:00',
                    ],
                    [
                        '@type' => 'OpeningHoursSpecification',
                        'dayOfWeek' => 'Saturday',
                        'opens' => '10:00',
                        'closes' => '16:00',
                    ],
                    [
                        '@type' => 'OpeningHoursSpecification',
                        'dayOfWeek' => 'Sunday',
                        'opens' => '00:00',
                        'closes' => '00:00',
                        'description' => 'By appointment',
                    ],
                ],
                'contactPoint' => [
                    '@id' => $page_url . '#contactpoint',
                ],
                'identifier' => [
                    '@type' => 'PropertyValue',
                    'propertyID' => 'CA DRE',
                    'value' => $dre,
                ],
            ],
        ],
    ];

    echo "\n<!-- SageCoast JSON-LD injected: Contact page -->\n";

    echo '<script type="application/ld+json">' .
        wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) .
        '</script>' . "\n";

}, 999);
