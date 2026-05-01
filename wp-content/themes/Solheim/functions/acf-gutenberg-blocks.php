<?php
// Add ACF options page
if (function_exists('acf_add_options_page')) {
  acf_add_options_page(array(
    'page_title'   => __('Theme Settings', 'your-text-domain'),
    'menu_title'   => __('Theme Settings', 'your-text-domain'),
    'menu_slug'    => 'theme-settings',
    'capability'   => 'edit_posts',
    'redirect'     => false,
  ));
}

/**
 * ACF Google Map functionality
 */
function my_acf_init() {
    $api_key = 'AIzaSyDm46g4QI3vHY3RClL7AaAYVQJMzwdJfIU';
    
    // Update ACF's Google Maps API key setting
    acf_update_setting('google_api_key', $api_key);
}
add_action('acf/init', 'my_acf_init');

/**
 * Enqueue Google Maps scripts
 */
function my_acf_google_map_scripts() {
    $api_key = 'AIzaSyDm46g4QI3vHY3RClL7AaAYVQJMzwdJfIU';
    
    // First enqueue our custom map script
    wp_enqueue_script(
        'google-maps-init',
        get_template_directory_uri() . '/src/js/scripts/map.js',
        array('jquery'),
        '1.0.0',
        true
    );
    
    // Then enqueue Google Maps API after our script
    wp_enqueue_script(
        'google-maps', 
        "https://maps.googleapis.com/maps/api/js?key={$api_key}&callback=initGoogleMaps",
        array('google-maps-init'),
        null,
        true
    );
}
add_action('wp_enqueue_scripts', 'my_acf_google_map_scripts');

function custom_gutenberg_category($categories, $post)
{
  return array_merge(
    $categories,
    array(
      array(
        'slug'  => 'custom-blocks',
        'title' => __('Custom Blocks', 'custom-blocks'),
      ),
    )
  );
}
add_filter('block_categories_all', 'custom_gutenberg_category', 10, 2);

function my_acf_block_render_callback($block)
{

  // Convert name ("acf/testimonial") into path-friendly slug ("testimonial")
  $slug = str_replace('acf/', '', $block['name']);

  // Include a template part from within the "acf-blocks" folder
  if (file_exists(get_theme_file_path("/acf-blocks/content-{$slug}.php"))) {
    include(get_theme_file_path("/acf-blocks/content-{$slug}.php"));
  }
}
