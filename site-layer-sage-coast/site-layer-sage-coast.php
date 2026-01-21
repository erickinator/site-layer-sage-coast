<?php
/**
 * Plugin Name: Site Layer — Sage Coast Realty
 * Description: Custom CSS/JS site layer for Sage Coast Realty (managed via Git).
 * Version: 0.1.0
 * Author: The Marketing Systems Collective
 */

if (!defined('ABSPATH')) exit;

/**
 * Enqueue CSS/JS with filemtime cache-busting
 */
add_action('wp_enqueue_scripts', function () {
  if (is_admin()) return;

  $base_dir = plugin_dir_path(__FILE__);
  $base_url = plugin_dir_url(__FILE__);

  $css = 'assets/css/styles.css';
  $js  = 'assets/js/main.js';

  // Load AFTER Elementor + theme styles
  $style_deps = array(
    'hello-elementor',
    'hello-elementor-theme-style',
    'hello-elementor-header-footer',
    'elementor-frontend',
  );

  if (file_exists($base_dir . $css)) {
    wp_enqueue_style(
      'site-layer-sage-coast-css',
      $base_url . $css,
      $style_deps,
      filemtime($base_dir . $css)
    );
  }

  if (file_exists($base_dir . $js)) {
    wp_enqueue_script(
      'site-layer-sage-coast-js',
      $base_url . $js,
      array('jquery'),
      filemtime($base_dir . $js),
      true
    );
  }
}, 999);

/**
 * Admin Status Page (Tools -> Site Layer Status)
 * Gives confidence that assets exist and shows paths, URLs, modified times.
 */
add_action('admin_menu', function () {
  add_management_page(
    'Site Layer Status',
    'Site Layer Status',
    'manage_options',
    'site-layer-status',
    'site_layer_render_status_page'
  );
});

function site_layer_asset_info($relative_path) {
  $relative_path = ltrim((string)$relative_path, '/');

  $abs = plugin_dir_path(__FILE__) . $relative_path;
  $url = plugin_dir_url(__FILE__) . $relative_path;

  $exists = file_exists($abs);

  return [
    'relative' => $relative_path,
    'abs'      => $abs,
    'url'      => $url,
    'exists'   => $exists,
    'size'     => $exists ? filesize($abs) : null,
    'mtime'    => $exists ? filemtime($abs) : null,
  ];
}

function site_layer_format_bytes($bytes) {
  if ($bytes === null) return '—';
  $units = ['B','KB','MB','GB','TB'];
  $i = 0;
  $n = (float)$bytes;
  while ($n >= 1024 && $i < count($units) - 1) { $n /= 1024; $i++; }
  return sprintf('%.1f %s', $n, $units[$i]);
}

function site_layer_format_time($ts) {
  if (!$ts) return '—';
  return date_i18n('Y-m-d H:i:s', $ts) . ' (' . human_time_diff($ts, time()) . ' ago)';
}

function site_layer_render_status_page() {
  if (!current_user_can('manage_options')) return;

  $css = site_layer_asset_info('assets/css/styles.css');
  $js  = site_layer_asset_info('assets/js/main.js');

  ?>
  <div class="wrap">
    <h1>Site Layer Status</h1>
    <p style="max-width: 900px;">
      Confirms the Git-managed Site Layer assets exist and shows their URLs and last modified times.
    </p>

    <h2>Health Checks</h2>
    <table class="widefat striped" style="max-width: 900px;">
      <thead><tr><th>Check</th><th>Status</th></tr></thead>
      <tbody>
        <tr><td>Plugin Active</td><td><strong style="color:#1e7e34;">PASS</strong></td></tr>
        <tr><td>CSS File Exists</td><td><strong style="color:<?php echo $css['exists'] ? '#1e7e34' : '#b32d2e'; ?>"><?php echo $css['exists'] ? 'PASS' : 'FAIL'; ?></strong></td></tr>
        <tr><td>JS File Exists</td><td><strong style="color:<?php echo $js['exists'] ? '#1e7e34' : '#b32d2e'; ?>"><?php echo $js['exists'] ? 'PASS' : 'FAIL'; ?></strong></td></tr>
      </tbody>
    </table>

    <h2 style="margin-top: 24px;">Asset Details</h2>
    <table class="widefat striped" style="max-width: 900px;">
      <thead>
        <tr>
          <th>Asset</th>
          <th>Exists</th>
          <th>Size</th>
          <th>Last Modified</th>
          <th>URL</th>
          <th>Absolute Path</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ([['CSS',$css], ['JS',$js]] as $row): list($name, $a) = $row; ?>
          <tr>
            <td><?php echo esc_html($name); ?></td>
            <td><?php echo $a['exists'] ? 'Yes' : 'No'; ?></td>
            <td><?php echo esc_html(site_layer_format_bytes($a['size'])); ?></td>
            <td><?php echo esc_html(site_layer_format_time($a['mtime'])); ?></td>
            <td><code><?php echo esc_html($a['url']); ?></code></td>
            <td><code><?php echo esc_html($a['abs']); ?></code></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php
}