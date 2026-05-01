<?php
// Initialize the styles array
$style_classes = [];

// Add background and text color classes to the array
if (!empty(get_field('block_settings_background_colour'))) {
    $style_classes[] = esc_attr(get_field('block_settings_background_colour'));
}
if (!empty(get_field('block_settings_text_colour'))) {
    $style_classes[] = esc_attr(get_field('block_settings_text_colour'));
}

// Add desktop padding and margin classes
if (!empty(get_field('block_settings_desktop_padding_top')) && get_field('block_settings_desktop_padding_top') !== 'default') {
    $style_classes[] = "d-pt-".esc_attr(get_field('block_settings_desktop_padding_top'));
}
if (!empty(get_field('block_settings_desktop_padding_bottom')) && get_field('block_settings_desktop_padding_bottom') !== 'default') {
    $style_classes[] = "d-pb-".esc_attr(get_field('block_settings_desktop_padding_bottom'));
}
if (!empty(get_field('block_settings_desktop_margin_top')) && get_field('block_settings_desktop_margin_top') !== 'default') {
    $style_classes[] = "d-mt-".esc_attr(get_field('block_settings_desktop_margin_top'));
}
if (!empty(get_field('block_settings_desktop_margin_bottom')) && get_field('block_settings_desktop_margin_bottom') !== 'default') {
    $style_classes[] = "d-mb-".esc_attr(get_field('block_settings_desktop_margin_bottom'));
}

// Add mobile padding and margin classes
if (!empty(get_field('block_settings_mobile_padding_top')) && get_field('block_settings_mobile_padding_top') !== 'default') {
    $style_classes[] = "m-pt-".esc_attr(get_field('block_settings_mobile_padding_top'));
}
if (!empty(get_field('block_settings_mobile_padding_bottom')) && get_field('block_settings_mobile_padding_bottom') !== 'default') {
    $style_classes[] = "m-pb-".esc_attr(get_field('block_settings_mobile_padding_bottom'));
}
if (!empty(get_field('block_settings_mobile_margin_top')) && get_field('block_settings_mobile_margin_top') !== 'default') {
    $style_classes[] = "m-mt-".esc_attr(get_field('block_settings_mobile_margin_top'));
}
if (!empty(get_field('block_settings_mobile_margin_bottom')) && get_field('block_settings_mobile_margin_bottom') !== 'default') {
    $style_classes[] = "m-mb-".esc_attr(get_field('block_settings_mobile_margin_bottom'));
}

$block_id = !empty(get_field('block_settings_block_id')) ? get_field('block_settings_block_id') : '';

// Return the array
return [
    'style_classes' => $style_classes,
    'block_id' => $block_id,
];
