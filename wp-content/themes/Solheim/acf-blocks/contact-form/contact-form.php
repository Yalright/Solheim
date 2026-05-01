<?php
/**
 * Block - Contact Form
 */

$block_data     = include get_template_directory() . '/acf-blocks/block-settings/block-settings.php';
$style_classes  = $block_data['style_classes'];
$block_id       = ! empty($block_data['block_id']) ? 'id="' . esc_attr($block_data['block_id']) . '"' : '';

$block_name = 'contact-form';
array_unshift($style_classes, $block_name);
$style_classes[] = $block_name;

// $form_shortcode = get_field('form_shortcode');

$classes = implode(' ', array_filter(array_map('esc_attr', $style_classes)));
?>

<section <?php echo $block_id; ?> class="guten-block <?php echo esc_attr($classes); ?>">
    <div class="container">
        <?php // Markup TBD ?>
    </div>
</section>
