<?php

// Theme Colours
// function theme_colour_setup()
// {
//     // Disable Custom Colors
//     add_theme_support('disable-custom-colors');

//     // Editor Color Palette
//     add_theme_support('editor-color-palette', array(
//         array(
//             'name'  => __('White', 'yalright'),
//             'slug'  => 'white',
//             'color'  => '#ffffff',
//         ),
//         array(
//             'name'  => __('Black', 'yalright'),
//             'slug'  => 'black',
//             'color'  => '#000000',
//         ),
//         array(
//             'name'  => __('Teal', 'yalright'),
//             'slug'  => 'teal',
//             'color'  => '#8ed6ec',
//         ),
//         array(
//             'name'  => __('Blue', 'yalright'),
//             'slug'  => 'blue',
//             'color'  => '#0056a3',
//         )
//     ));
// }
// add_action('after_setup_theme', 'theme_colour_setup');
// Moved to theme.json

add_theme_support('post-thumbnails');

function my_mce_buttons_2($buttons)
{
    // Add font family dropdown + keep styles dropdown available
    array_unshift($buttons, 'fontselect', 'styleselect');
    return $buttons;
}
add_filter('mce_buttons_2', 'my_mce_buttons_2');



function my_tiny_mce_before_init($mceInit)
{
    // Expose theme font families in the TinyMCE "Font family" dropdown.
    // These should match the @font-face declarations in `src/scss/global/_fonts.scss`.
    $mceInit['font_formats'] = implode(';', array(
        'Neue Haas Display=NeueHaasDisplay,sans-serif',
        'Moisette=Moisette,serif',
        'Jane Austen=JaneAusten,cursive',
    ));

    return $mceInit;
}
add_filter('tiny_mce_before_init', 'my_tiny_mce_before_init');



function gb_gutenberg_admin_styles()
{
    echo '
        <style>
            /* Main column width */
            .wp-block {
                max-width: 100%;
            }
        </style>
    ';
}
add_action('admin_head', 'gb_gutenberg_admin_styles');



// function disable_block_editor_for_page_ids($use_block_editor, $post)
// {

//     $page_for_posts = get_option('page_for_posts');
//     $excluded_ids = array($page_for_posts);
//     if (in_array($post->ID, $excluded_ids)) {
//         return false;
//     }
//     return $use_block_editor;
// }
// add_filter('use_block_editor_for_post', 'disable_block_editor_for_page_ids', 10, 2);
