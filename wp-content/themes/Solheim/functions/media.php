<?php
/*
 * Media
 */

add_action('after_setup_theme', 'wpdocs_theme_setup');
function wpdocs_theme_setup()
{
  add_image_size('md-square', 800, 800, true); // (cropped)
}

