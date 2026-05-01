<?php
//Role editing updates

// Allow editors to access the Menus page under Appearance but hide other options
// Note that users who know the correct path to the hidden options can still access them
function hide_menu()
{
    $user = wp_get_current_user();

    // Check if the current user is an Editor
    if (in_array('editor', (array) $user->roles)) {

        // They're an editor, so grant the edit_theme_options capability if they don't have it
        if (!current_user_can('edit_theme_options')) {
            $role_object = get_role('editor');
            $role_object->add_cap('edit_theme_options');
        }

        // Hide the Themes page
        remove_submenu_page('themes.php', 'themes.php');
        // Hide the Widgets page
        remove_submenu_page('themes.php', 'widgets.php');
        // Hide the Customize page
        remove_submenu_page('themes.php', 'customize.php');
        // Remove Customize from the Appearance submenu
        global $submenu;
        unset($submenu['themes.php'][6]);
    }
}

add_action('admin_menu', 'hide_menu', 10);

// Remove the WordPress update notifications from users
function hide_core_update_notifications_from_users()
{
    if (!current_user_can('update_core')) {
        remove_action('admin_notices', 'update_nag', 3);
    }
}
add_action('admin_head', 'hide_core_update_notifications_from_users', 1);
