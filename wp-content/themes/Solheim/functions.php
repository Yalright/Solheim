<?php
if (!defined('ABSPATH')) {
    exit;
}

require get_template_directory() . '/functions/block-helpers.php';
require get_template_directory() . '/functions/enqueues.php';
require get_template_directory() . '/functions/shortcodes.php';
require get_template_directory() . '/functions/menus.php';
// require get_template_directory() . '/functions/custom-post-types.php';
require get_template_directory() . '/functions/acf-gutenberg-blocks.php';
require get_template_directory() . '/functions/media.php';
require get_template_directory() . '/functions/user-roles.php';
require get_template_directory() . '/functions/theme-support.php';
require get_template_directory() . '/functions/allowed-blocks.php';
// require get_template_directory() . '/functions/patterns.php';

// Page Slug Body Class
function add_slug_body_class($classes)
{
    global $post;
    if (isset($post)) {
        $classes[] = $post->post_type . '-' . $post->post_name;
    }
    return $classes;
}
add_filter('body_class', 'add_slug_body_class');

// Generalise WP Login errors on WordPress
function no_wordpress_errors()
{
    return 'Something is wrong!';
}
add_filter('login_errors', 'no_wordpress_errors');

// Rest Authentication Errors
add_filter('rest_authentication_errors', function ($result) {
    if (!empty($result)) {
        return $result;
    }
    if (!is_user_logged_in()) {
        return new WP_Error('rest_not_logged_in', 'You are not currently logged in.', array('status' => 401));
    }
    return $result;
});


add_action('acf/init', 'register_all_acf_blocks');
function register_all_acf_blocks() {
    if (function_exists('register_block_type')) {
        $block_directories = glob(get_template_directory() . '/acf-blocks/*', GLOB_ONLYDIR);

        foreach ($block_directories as $block_dir) {
            $block_json = $block_dir . '/block.json';
            if (file_exists($block_json)) {
                register_block_type($block_json);
            }
        }
    }
}