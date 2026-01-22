/**
 * Sage Coast Realty — Meet Our Team JSON-LD (CollectionPage + Agent Roster)
 * Extends AIOSEO. Add via: Code Snippets -> Run on Front-end.
 * Updates:
 * - Fixed 'Missing Address' error for Google Validator.
 * - Updated Atifa Rashan's image.
 */
add_action('wp_head', function () {

    // 1. GATE KEEPER: Only run on the specific slug
    $path = trim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
    if ($path !== 'meet-our-team') {
        return;
    }

    // 2. GLOBAL CONSTANTS
    $home_url   = home_url('/');
    $page_url   = home_url('/meet-our-team/');
    $org_id     = $home_url . '#realestateagent'; // The ID for Sage Coast Realty HQ
    $brand_name = 'Sage Coast Realty';
    $main_phone = '+17607058998'; 

    // DEFINITION: Shared Office Address (Required by Google for EVERY agent node)
    $office_address = [
        '@type'           => 'PostalAddress',
        'streetAddress'   => '128 E Grand Ave #11',
        'addressLocality' => 'Escondido',
        'addressRegion'   => 'CA',
        'postalCode'      => '92025',
        'addressCountry'  => 'US',
    ];

    // 3. AGENT ROSTER DATA
    $agents_data = [
        [
            'name'  => 'Jill Magnuson',
            'title' => 'Broker & Founder',
            'dre'   => '01213323',
            'phone' => '+17607058998',
            'email' => 'jill@jillmagnuson.com',
            'image' => 'https://sagecoastrealty.com/wp-content/uploads/2026/01/jill-magnuson-sage-coast-realty.png',
            'url'   => $home_url . 'team/JillMagnuson/'
        ],
        [
            'name'  => 'Atifa Rashan',
            'title' => 'Realtor®',
            'dre'   => '01443905',
            'phone' => '+18583532196',
            'email' => 'atifa67@gmail.com',
            'image' => 'https://sagecoastrealty.com/wp-content/uploads/2026/01/atifa-rashan-sage-coast-realty.jpg', // <--- UPDATED
            'url'   => $home_url . 'team/AtifaRashan/'
        ],
        [
            'name'  => 'Zehra Rizvi',
            'title' => 'Realtor®',
            'dre'   => '01977495',
            'phone' => '+16197391496',
            'email' => 'zehra@3zllc.com',
            'image' => 'https://sagecoastrealty.com/wp-content/uploads/2026/01/agent-placeholder-woman.jpg',
            'url'   => $home_url . 'team/ZehraRizvi/'
        ],
        [
            'name'  => 'Sharron Saidi',
            'title' => 'Realtor®',
            'dre'   => '01787523',
            'phone' => '+17608079176',
            'email' => 'sharronre@gmail.com',
            'image' => 'https://sagecoastrealty.com/wp-content/uploads/2026/01/agent-placeholder-woman.jpg',
            'url'   => $home_url . 'team/SharronSaidi/'
        ],
        [
            'name'  => 'Jerome Stehly',
            'title' => 'Realtor®',
            'dre'   => '01011431',
            'phone' => '+17606383443',
            'email' => 'stehlyent@aol.com',
            'image' => 'https://sagecoastrealty.com/wp-content/uploads/2026/01/jerome-stehly-sage-coast-realty.png',
            'url'   => $home_url . 'team/JeromeStehly/'
        ],
        [
            'name'  => 'Art Felix',
            'title' => 'Realtor®',
            'dre'   => '02018411',
            'phone' => $main_phone,
            'email' => 'art.felix617@att.net',
            'image' => 'https://sagecoastrealty.com/wp-content/uploads/2026/01/art-felix-sage-coast-realty.jpg',
            'url'   => $home_url . 'team/ArtFelix/'
        ],
        [
            'name'  => 'Brianna Bentley',
            'title' => 'Realtor®',
            'dre'   => '02249886',
            'phone' => $main_phone,
            'email' => 'breelbentley@gmail.com',
            'image' => 'https://sagecoastrealty.com/wp-content/uploads/2026/01/bree-bentley-sage-coast-realty.jpg',
            'url'   => $home_url . 'team/BriannaBentley/'
        ],
        [
            'name'  => 'Faith Camarata',
            'title' => 'Support Staff',
            'dre'   => '02090905',
            'phone' => $main_phone,
            'email' => 'faith@faithcamarata.com',
            'image' => 'https://sagecoastrealty.com/wp-content/uploads/2026/01/faith-camarata-sage-coast-realty.jpg',
            'url'   => $home_url . 'team/FaithCamarata/'
        ],
        [
            'name'  => 'Marcia Rambaud',
            'title' => 'Realtor®',
            'dre'   => '00910284',
            'phone' => '+17603907533',
            'email' => 'marciarambauid@yahoo.com',
            'image' => 'https://sagecoastrealty.com/wp-content/uploads/2026/01/agent-placeholder-woman.jpg',
            'url'   => $home_url . 'team/MarciaRambaud/'
        ],
        [
            'name'  => 'Summer Sayed',
            'title' => 'Realtor®',
            'dre'   => '', 
            'phone' => '+17604152663',
            'email' => 'info@sagecoastrealty.com',
            'image' => 'https://sagecoastrealty.com/wp-content/uploads/2026/01/agent-placeholder-woman.jpg',
            'url'   => $home_url . 'team/SummerSayed/'
        ],
    ];

    // 4. BUILD INDIVIDUAL AGENT NODES
    $agent_nodes = [];
    foreach ($agents_data as $agent) {
        // Create unique ID for this person
        $person_id = $home_url . '#person-' . sanitize_title($agent['name']);
        
        $node = [
            '@type'     => 'RealEstateAgent', 
            '@id'       => $person_id,
            'name'      => $agent['name'],
            'jobTitle'  => $agent['title'],
            'url'       => $agent['url'],
            'telephone' => $agent['phone'],
            'email'     => $agent['email'],
            'image'     => $agent['image'],
            'address'   => $office_address, // <--- EXPLICIT ADDRESS ADDED HERE
            'worksFor'  => [
                '@id' => $org_id 
            ],
            'priceRange' => '$$$',
        ];

        // Only add identifier if DRE exists
        if (!empty($agent['dre'])) {
            $node['identifier'] = [
                '@type' => 'PropertyValue',
                'propertyID' => 'CA DRE',
                'value' => $agent['dre']
            ];
        }

        $agent_nodes[] = $node;
    }

    // 5. CONSTRUCT MAIN SCHEMA GRAPH
    $schema = [
        '@context' => 'https://schema.org',
        '@graph' => [
            // A. The Website
            [
                '@type' => 'WebSite',
                '@id'   => $home_url . '#website',
                'url'   => $home_url,
                'name'  => $brand_name,
            ],
            
            // B. The Organization (Parent)
            [
                '@type'    => 'RealEstateAgent',
                '@id'      => $org_id,
                'name'     => $brand_name,
                'url'      => $home_url,
                'logo'     => 'https://sagecoastrealty.com/wp-content/uploads/2024/07/Sage-Coast-Realty-Logo.png',
                'address'  => $office_address,
                'priceRange' => '$$$',
                'telephone'  => $main_phone
            ],

            // C. The Collection Page (Directory)
            [
                '@type' => 'CollectionPage',
                '@id'   => $page_url . '#webpage',
                'url'   => $page_url,
                'name'  => 'Meet the Team at Sage Coast Realty',
                'description' => 'Directory of top-rated real estate agents at Sage Coast Realty in Escondido, CA.',
                'isPartOf' => ['@id' => $home_url . '#website'],
                'about' => ['@id' => $org_id],
                // Link the agents to the page
                'hasPart' => $agent_nodes
            ]
        ]
    ];

    // Merge agent nodes into the main graph so they exist as top-level entities
    $schema['@graph'] = array_merge($schema['@graph'], $agent_nodes);

    // 6. OUTPUT
    echo "\n\n";
    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";

}, 999);