/**
 * Sage Coast Realty — llms.txt Virtual File
 * Serves a Markdown summary of the site at /llms.txt
 * Add via: Code Snippets (PHP) -> Run on Front-end.
 */
add_action('init', function () {
    
    // Check if this is the llms.txt request
    $request_uri = $_SERVER['REQUEST_URI'] ?? '';
    $path = trim(parse_url($request_uri, PHP_URL_PATH), '/');
    
    if ($path !== 'llms.txt') {
        return;
    }
    
    // Set content type
    header('Content-Type: text/plain; charset=utf-8');
    
    // Build Markdown content
    $markdown = "# Sage Coast Realty\n\n";
    $markdown .= "A boutique real estate brokerage specializing in North County San Diego, where inland calm meets coastal sophistication.\n\n";
    
    // Contact Information
    $markdown .= "## Contact\n\n";
    $markdown .= "- **Phone:** +1 (760) 705-8998\n";
    $markdown .= "- **Email:** info@sagecoastrealty.com\n";
    $markdown .= "- **Address:** 128 E Grand Ave #11, Escondido, CA 92025\n";
    $markdown .= "- **Website:** https://sagecoastrealty.com\n\n";
    
    // Service Areas
    $markdown .= "## Service Areas\n\n";
    $markdown .= "We specialize in North County San Diego real estate:\n\n";
    $markdown .= "- Escondido\n";
    $markdown .= "- Vista\n";
    $markdown .= "- San Marcos\n";
    $markdown .= "- Fallbrook\n";
    $markdown .= "- Valley Center\n\n";
    
    // Team Members
    $markdown .= "## Team\n\n";
    $markdown .= "### Leadership\n\n";
    $markdown .= "- **Jill Magnuson** - Broker & Founder\n\n";
    $markdown .= "### Agents\n\n";
    $markdown .= "- **Brianna Bentley** - Realtor®\n";
    $markdown .= "- **Art Felix** - Realtor®\n";
    $markdown .= "- **Atifa Rashan** - Realtor®\n";
    $markdown .= "- **Marcia Rambaud** - Realtor®\n";
    $markdown .= "- **Zehra Rizvi** - Realtor®\n";
    $markdown .= "- **Sharron Saidi** - Realtor®\n";
    $markdown .= "- **Summer Sayed** - Realtor®\n";
    $markdown .= "- **Jerome Stehly** - Realtor®\n\n";
    $markdown .= "### Support Staff\n\n";
    $markdown .= "- **Faith Camarata** - Support Staff (Transaction Coordinator)\n\n";
    
    // Specializations
    $markdown .= "## Specializations\n\n";
    $markdown .= "- Residential real estate sales\n";
    $markdown .= "- Luxury and estate properties\n";
    $markdown .= "- Rural and semi-rural properties\n";
    $markdown .= "- Investment properties\n";
    $markdown .= "- First-time homebuyer programs\n";
    $markdown .= "- Green and energy-efficient homes\n";
    $markdown .= "- Land use and zoning expertise\n";
    $markdown .= "- ADU (Accessory Dwelling Unit) guidance\n\n";
    
    // About
    $markdown .= "## About\n\n";
    $markdown .= "Sage Coast Realty was founded on a deep understanding of how San Diego County actually lives — from inland micro-climates to coastal influence, from land use to long-term livability.\n\n";
    $markdown .= "We specialize in North County San Diego, where lifestyle trade-offs matter as much as price, and where experience shows up in the details most buyers and sellers overlook.\n\n";
    $markdown .= "Our role is not to sell faster — it's to guide clearly, explain complexity, and help clients make decisions they'll feel good about years from now.\n\n";
    
    // Output and exit
    echo $markdown;
    exit;
});
