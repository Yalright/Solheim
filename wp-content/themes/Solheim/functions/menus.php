<?php
// Register menus
function custom_menus()
{
  register_nav_menus(
    array(
      'header-menu' => __('Header Menu'),
      'header-menu-couples' => __('Header Menu Couples'),
      'header-menu-vendors' => __('Header Menu Vendors'),
      'mobile-nav' => __('Mobile Navigation'),
      'landing-page-nav' => __('Landing Page Navigation'),
      'footer-nav-1' => __('Footer Navigation 1'),
      'footer-nav-2' => __('Footer Navigation 2'),
      'footer-nav-3' => __('Footer Navigation 3'),
      // 'footer-nav-4' => __('Social Navigation'),
    )
  );
}
add_action('init', 'custom_menus');

// Add active class to menu
add_filter('nav_menu_css_class', 'active_nav_class', 10, 2);
function active_nav_class($classes, $item)
{
  $first_uri_part = explode("/", $_SERVER["REQUEST_URI"])[1];
  if (!empty($first_uri_part) && strstr($item->url, $first_uri_part) > -1) {
    $classes[] = 'active';
  }
  return $classes;
}

// Filter menu item classes to only allow specific ones
function filter_nav_menu_css_classes($classes, $item, $args) {
    // Define allowed classes
    $allowed_classes = array(
        'current-menu-item',
        'menu-item-has-children',
        'active',
        'full-width'
    );
    
    // Create new array for filtered classes
    $filtered_classes = array();
    
    // Only keep classes that are in allowed list
    foreach($classes as $class) {
        if(in_array($class, $allowed_classes)) {
            $filtered_classes[] = $class;
        }
    }
    
    return $filtered_classes;
}
add_filter('nav_menu_css_class', 'filter_nav_menu_css_classes', 10, 3);


// ACF Custom menu
add_filter('wp_nav_menu_objects', 'my_wp_nav_menu_objects', 10, 2);

function my_wp_nav_menu_objects($items, $args)
{
  // loop through menu items
  foreach ($items as $item) {

    // get the 'icon' field associated with the menu item
    $image = get_field('icon', $item);

    // if an icon exists, prepend it to the title
    if ($image) {
      $item->title = '<img width="24" height="25" alt="" src="' . esc_url($image['url']) . '" /> ' . $item->title;
    }
  }

  // return modified menu items
  return $items;
}

