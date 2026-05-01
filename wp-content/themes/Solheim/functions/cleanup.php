<?php
/*
 * Cleanup
 */

// Less stuff in <head>

// if (!function_exists('b4st_cleanup_head')) {
//   function b4st_cleanup_head()
//   {
//     remove_action('wp_head', 'wp_generator');
//     remove_action('wp_head', 'rsd_link');
//     remove_action('wp_head', 'wlwmanifest_link');
//     remove_action('wp_head', 'index_rel_link');
//     remove_action('wp_head', 'feed_links', 2);
//     remove_action('wp_head', 'feed_links_extra', 3);
//     remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
//     remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
//     remove_action('wp_head', 'print_emoji_detection_script', 7);
//     remove_action('wp_print_styles', 'print_emoji_styles');
//   }
// }
// add_action('init', 'b4st_cleanup_head');

// Show less info to users on failed login for security.
// (Will not let a valid username be known.)

// if (!function_exists('show_less_login_info')) {
//   function show_less_login_info()
//   {
//     return "<strong>ERROR</strong>: Stop guessing!";
//   }
// }
// add_filter('login_errors', 'show_less_login_info');

// Do not generate and display WordPress version

// if (!function_exists('b4st_remove_generator')) {
//   function b4st_remove_generator()
//   {
//     return '';
//   }
// }
// add_filter('the_generator', 'no_generator');

// Remove Query Strings From Static Resources

/*if ( ! function_exists('b4st_remove_script_version') ) {
  function b4st_remove_script_version( $src ) {
    if ( current_user_can('manage_options') ) {
      return $src;
    }
    if( strpos( $src, '?ver=' ) ) {
      $src = remove_query_arg( 'ver', $src );
      return $src;
    }
  }
}
add_filter( 'script_loader_src', 'b4st_remove_script_version', 15, 1 );
add_filter( 'style_loader_src', 'b4st_remove_script_version', 15, 1 );*/

//
// UPDATES
//

// Allow automatic upgrades on GIT Repos
// add_filter('automatic_updates_is_vcs_checkout', '__return_false', 1);

//
// COMMENTS
//

function md_remove_menus()
{

  $current_user = wp_get_current_user();
  if ($current_user->user_login !== "mikedistras") {
    // remove_menu_page('index.php');                  //Dashboard
    // remove_menu_page('edit.php');                   //Posts
    // remove_menu_page('upload.php');                 //Media
    remove_menu_page('edit-comments.php');          //Comments
    // remove_menu_page('themes.php');                 //Appearance
    // remove_menu_page('plugins.php');                //Plugins
    // remove_menu_page('users.php');                  //Users
    // remove_menu_page('tools.php');                  //Tools
    // remove_menu_page('smush');                  //Tools
    // remove_menu_page('sbp-settings');                  //Tools
    // remove_menu_page('Speed Booster');                  //Tools
    // remove_menu_page('edit.php?post_type=search-filter-widget');                  //Tools
    // remove_menu_page('edit.php?post_type=acf-field-group');
    // remove_menu_page('options-general.php');

    // Submenus
    // remove_submenu_page('index.php', 'update-core.php');
    // remove_submenu_page('themes.php', 'themes.php');
    // remove_submenu_page('themes.php', 'theme-editor.php');
    // remove_submenu_page('themes.php', 'customize.php?return=' . urlencode($_SERVER['SCRIPT_NAME']));
  }
}
add_action('admin_menu', 'md_remove_menus', 999);
