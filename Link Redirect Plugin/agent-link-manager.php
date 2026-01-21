<?php
/*
Plugin Name: Sage Coast Agent Links
Description: Admin manager for Agent QR code links.
Version: 1.0
Author: The Marketing Systems Collective
*/

if ( ! defined( 'ABSPATH' ) ) exit;

// 1. Register the "Agent Link" Content Type
add_action('init', 'scal_register_cpt');
function scal_register_cpt() {
    register_post_type('agent_link', [
        'labels' => [
            'name' => 'Agent Links',
            'singular_name' => 'Agent Link',
            'add_new' => 'Add Agent Link',
            'add_new_item' => 'New Agent QR Link',
            'edit_item' => 'Edit Agent Link',
            'search_items' => 'Search Agents'
        ],
        'public' => false,  
        'show_ui' => true, 
        'menu_icon' => 'dashicons-businessman', // Icon is now a person
        'supports' => ['title'], // Title = The Agent Name (e.g. Atifa Rashan)
        'rewrite' => false
    ]);
}

// 2. Add Custom Fields
add_action('add_meta_boxes', 'scal_add_meta_boxes');
function scal_add_meta_boxes() {
    add_meta_box('scal_details', 'Link Routing', 'scal_render_meta_box', 'agent_link', 'normal', 'high');
}

function scal_render_meta_box($post) {
    $url = get_post_meta($post->ID, '_scal_target_url', true);
    $owner = get_post_meta($post->ID, '_scal_owner_name', true);
    $clicks = get_post_meta($post->ID, '_scal_clicks', true);
    if(!$clicks) $clicks = 0;
    
    // Default to homepage if empty
    if(empty($url)) $url = home_url('/'); 
    ?>
    <style>
        .scal-row { margin-bottom: 20px; }
        .scal-row label { display:block; font-weight:bold; margin-bottom:5px; font-size: 14px; }
        .scal-input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius:4px; }
        .scal-info { background: #e7f5fe; padding: 15px; border-left: 4px solid #00a0d2; margin-bottom: 20px; }
    </style>

    <?php
    // Get the post slug (URL-safe version of the title)
    $post_slug = $post->post_name ? $post->post_name : sanitize_title($post->post_title);
    ?>
    <div class="scal-info">
        <strong>Redirect Link:</strong> 
        <a href="<?php echo home_url('/go/' . $post_slug); ?>" target="_blank" style="font-size:16px; font-weight:bold;">
            <?php echo home_url('/go/' . $post_slug); ?>
        </a>
        <p style="margin:5px 0 0 0;">Copy this link into Canva to generate the QR code.</p>
    </div>

    <div class="scal-row">
        <label>Notes (Optional)</label>
        <input type="text" name="scal_owner_name" class="scal-input" value="<?php echo esc_attr($owner); ?>" placeholder="e.g. Business cards, Open house flyer, etc.">
        <p class="description">Add notes about where this QR code is used (optional).</p>
    </div>

    <div class="scal-row">
        <label>Current Destination URL</label>
        <input type="url" name="scal_target_url" class="scal-input" value="<?php echo esc_attr($url); ?>" placeholder="https://..." required>
        <p class="description">Where should users go RIGHT NOW when they scan the code?</p>
    </div>

    <div class="scal-row">
        <label>Total Scans/Clicks: <?php echo number_format($clicks); ?></label>
    </div>
    <?php
}

// 3. Save Data
add_action('save_post', 'scal_save_meta');
function scal_save_meta($post_id) {
    if (array_key_exists('scal_target_url', $_POST)) {
        update_post_meta($post_id, '_scal_target_url', esc_url_raw($_POST['scal_target_url']));
    }
    if (array_key_exists('scal_owner_name', $_POST)) {
        update_post_meta($post_id, '_scal_owner_name', sanitize_text_field($_POST['scal_owner_name']));
    }
}

// 4. Admin Columns (The List View)
add_filter('manage_agent_link_posts_columns', 'scal_custom_columns');
function scal_custom_columns($columns) {
    return [
        'cb' => '<input type="checkbox" />',
        'title' => 'Agent Name',
        'full_link' => 'Redirect Link',
        'target' => 'Current Destination',
        'owner' => 'Notes',
        'clicks' => 'Scans'
    ];
}

add_action('manage_agent_link_posts_custom_column', 'scal_fill_columns', 10, 2);
function scal_fill_columns($column, $post_id) {
    switch ($column) {
        case 'full_link':
            $post = get_post($post_id);
            $slug = $post->post_name;
            $full = home_url('/go/' . $slug);
            echo '<input type="text" value="'.$full.'" readonly style="width:100%; border:none; background:transparent;" onclick="this.select(); document.execCommand(\'copy\'); alert(\'Copied!\');">';
            break;
        case 'target':
            echo '<a href="'.get_post_meta($post_id, '_scal_target_url', true).'" target="_blank">Test Link</a>';
            break;
        case 'owner':
            echo get_post_meta($post_id, '_scal_owner_name', true);
            break;
        case 'clicks':
            echo number_format((int)get_post_meta($post_id, '_scal_clicks', true));
            break;
    }
}

// 5. The Redirect Engine
add_action('template_redirect', 'scal_handle_redirect');
function scal_handle_redirect() {
    $path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    $parts = explode('/', $path);

    if (count($parts) >= 2 && $parts[0] === 'go') {
        $slug = sanitize_title($parts[1]);

        $args = [
            'post_type' => 'agent_link',
            'name'      => $slug,  // Use 'name' to search by post slug, not title
            'posts_per_page' => 1,
            'post_status' => 'publish'
        ];

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();
            $target_url = get_post_meta($post_id, '_scal_target_url', true);

            if ($target_url) {
                $clicks = (int) get_post_meta($post_id, '_scal_clicks', true);
                update_post_meta($post_id, '_scal_clicks', $clicks + 1);
                wp_redirect($target_url, 301);
                exit;
            }
        }
    }
}