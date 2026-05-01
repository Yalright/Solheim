<?php

/***
 * Block - Wysiwyg
 ***/


// Include block settings and get the returned array
$block_data = include get_stylesheet_directory() . '/acf-blocks/block-settings/block-settings.php';

// Extract values from the returned array
$style_classes = $block_data['style_classes'];

$block_id = !empty($block_data['block_id']) ? "id='" . $block_data['block_id'] . "'" : '';

$block_name     = "wysiwyg";

array_unshift($style_classes, $block_name);
$style_classes[] = $block_name;

$inner_style_classes[]    = get_field("background_colour");
$inner_style_classes[]    = get_field("text_colour");
$content            = get_field("content");

$classes = implode(' ', $style_classes);
$inner_classes = implode(' ', $inner_style_classes);
?>

<section <?php echo $block_id; ?> class="guten-block <?php echo $classes; ?>">
    <?php if (!empty($content)) { ?>
        <div class="container <?php echo $inner_classes; ?>">
            <div class="wysiwyg-container">
                <?php echo $content; ?>
            </div>
        </div>
    <?php } ?>
</section>