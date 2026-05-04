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

/**
 * Whole calendar days from today (site timezone) until the given ACF date.
 *
 * @param mixed $acf_date ACF date string (Y-m-d, Ymd, etc.) or DateTimeInterface.
 * @return int|null Days remaining (0 if today or past), or null if unparseable.
 */
function solheim_hero_countdown_days($acf_date)
{
  if ($acf_date === '' || $acf_date === null || false === $acf_date) {
    return null;
  }

  $tz = wp_timezone();
  $target = null;

  try {
    if ($acf_date instanceof DateTimeInterface) {
      $target = DateTimeImmutable::createFromInterface($acf_date)->setTimezone($tz)->setTime(0, 0, 0);
    } elseif (is_array($acf_date)) {
      $y = isset($acf_date['year']) ? (int) $acf_date['year'] : null;
      $m = isset($acf_date['month']) ? (int) $acf_date['month'] : null;
      $d = isset($acf_date['day']) ? (int) $acf_date['day'] : null;
      if ($y && $m && $d) {
        $target = date_create_immutable(sprintf('%04d-%02d-%02d', $y, $m, $d), $tz);
        if ($target) {
          $target = $target->setTime(0, 0, 0);
        }
      }
    } elseif (is_string($acf_date)) {
      $s = trim($acf_date);
      if ($s === '') {
        return null;
      }
      if (preg_match('/^(\d{4})(\d{2})(\d{2})$/', $s, $m)) {
        $target = date_create_immutable("{$m[1]}-{$m[2]}-{$m[3]}", $tz);
      } elseif (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $s, $m)) {
        $target = date_create_immutable("{$m[1]}-{$m[2]}-{$m[3]}", $tz);
      } elseif (preg_match('#^(\d{1,2})/(\d{1,2})/(\d{4})$#', $s, $m)) {
        $target = date_create_immutable(sprintf('%04d-%02d-%02d', (int) $m[3], (int) $m[2], (int) $m[1]), $tz);
      }
      if (! $target) {
        $target = date_create_immutable($s, $tz);
      }
      if ($target) {
        $target = $target->setTime(0, 0, 0);
      }
    } elseif (is_numeric($acf_date)) {
      $target = (new DateTimeImmutable('@' . (int) $acf_date))->setTimezone($tz)->setTime(0, 0, 0);
    }
  } catch (Exception $e) {
    return null;
  }

  if (! $target) {
    return null;
  }

  $today = new DateTimeImmutable('today', $tz);

  if ($target < $today) {
    return 0;
  }

  return (int) $today->diff($target)->days;
}
