<?php

add_theme_support('post-thumbnails');

// CPT - Business
function business_cpt()
{

  $labels = array(
    'name'                  => _x('Businesses', 'Post Type General Name', 'text_domain'),
    'singular_name'         => _x('Business', 'Post Type Singular Name', 'text_domain'),
    'menu_name'             => __('Businesses', 'text_domain'),
    'name_admin_bar'        => __('Business', 'text_domain'),
    'archives'              => __('Item Archives', 'text_domain'),
    'attributes'            => __('Item Attributes', 'text_domain'),
    'parent_item_colon'     => __('Parent Item:', 'text_domain'),
    'all_items'             => __('All Items', 'text_domain'),
    'add_new_item'          => __('Add New Item', 'text_domain'),
    'add_new'               => __('Add New', 'text_domain'),
    'new_item'              => __('New Item', 'text_domain'),
    'edit_item'             => __('Edit Item', 'text_domain'),
    'update_item'           => __('Update Item', 'text_domain'),
    'view_item'             => __('View Item', 'text_domain'),
    'view_items'            => __('View Items', 'text_domain'),
    'search_items'          => __('Search Item', 'text_domain'),
    'not_found'             => __('Not found', 'text_domain'),
    'not_found_in_trash'    => __('Not found in Trash', 'text_domain'),
    'featured_image'        => __('Featured Image', 'text_domain'),
    'set_featured_image'    => __('Set featured image', 'text_domain'),
    'remove_featured_image' => __('Remove featured image', 'text_domain'),
    'use_featured_image'    => __('Use as featured image', 'text_domain'),
    'insert_into_item'      => __('Insert into item', 'text_domain'),
    'uploaded_to_this_item' => __('Uploaded to this item', 'text_domain'),
    'items_list'            => __('Items list', 'text_domain'),
    'items_list_navigation' => __('Items list navigation', 'text_domain'),
    'filter_items_list'     => __('Filter items list', 'text_domain'),
  );
  $rewrite = array(
    'slug'                  => 'our-businesses',
    'with_front'            => false,
    'pages'                 => true,
    'feeds'                 => true,
  );
  $args = array(
    'label'                 => __('Businesses', 'text_domain'),
    'description'           => __('business', 'text_domain'),
    'labels'                => $labels,
    'supports'              => array('title', 'editor', 'thumbnail'),
    'hierarchical'          => false,
    'public'                => true,
    'show_ui'               => true,
    'show_in_menu'          => true,
    'menu_position'         => 5,
    'menu_icon'             => 'dashicons-building',
    'show_in_admin_bar'     => true,
    'show_in_nav_menus'     => true,
    'can_export'            => true,
    'has_archive'           => false,
    'exclude_from_search'   => false,
    'publicly_queryable'    => true,
    'rewrite'               => $rewrite,
    'capability_type'       => 'page',
  );
  register_post_type('business', $args);
}
add_action('init', 'business_cpt', 0);


add_action('init', function () {
  remove_post_type_support('business', 'editor');
}, 99);


// // Tax: Business Categories
function business_tax()
{

  $labels = array(
    'name'                       => _x('Business Categories', 'Taxonomy General Name', 'text_domain'),
    'singular_name'              => _x('Business Category', 'Taxonomy Singular Name', 'text_domain'),
    'menu_name'                  => __('Business Categories', 'text_domain'),
    'all_items'                  => __('All Items', 'text_domain'),
    'parent_item'                => __('Parent Item', 'text_domain'),
    'parent_item_colon'          => __('Parent Item:', 'text_domain'),
    'new_item_name'              => __('New Item Name', 'text_domain'),
    'add_new_item'               => __('Add New Item', 'text_domain'),
    'edit_item'                  => __('Edit Item', 'text_domain'),
    'update_item'                => __('Update Item', 'text_domain'),
    'view_item'                  => __('View Item', 'text_domain'),
    'separate_items_with_commas' => __('Separate items with commas', 'text_domain'),
    'add_or_remove_items'        => __('Add or remove items', 'text_domain'),
    'choose_from_most_used'      => __('Choose from the most used', 'text_domain'),
    'popular_items'              => __('Popular Items', 'text_domain'),
    'search_items'               => __('Search Items', 'text_domain'),
    'not_found'                  => __('Not Found', 'text_domain'),
    'no_terms'                   => __('No items', 'text_domain'),
    'items_list'                 => __('Items list', 'text_domain'),
    'items_list_navigation'      => __('Items list navigation', 'text_domain'),
  );
  $rewrite = array(
    'slug'                       => 'business-cat',
    'with_front'                 => true,
    'hierarchical'               => false,
  );
  $args = array(
    'labels'                     => $labels,
    'hierarchical'               => true,
    'public'                     => true,
    'show_ui'                    => true,
    'show_admin_column'          => true,
    'show_in_nav_menus'          => true,
    'show_tagcloud'              => true,
    'rewrite'                    => $rewrite,
  );
  register_taxonomy('business_tax', array('business'), $args);
}
add_action('init', 'business_tax', 0);
