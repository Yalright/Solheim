<?php
/**
 * Block - WYSIWYG
 */

$block_data     = include get_template_directory() . '/acf-blocks/block-settings/block-settings.php';
$style_classes  = $block_data['style_classes'];
$block_id       = ! empty($block_data['block_id']) ? 'id="' . esc_attr($block_data['block_id']) . '"' : '';

$block_name = 'wysiwyg';
array_unshift($style_classes, $block_name);
$style_classes[] = $block_name;

$theme = get_field('theme');
if (is_string($theme) && trim($theme) !== '') {
    $theme_parts = preg_split('/\s+/', trim($theme));
    if (is_array($theme_parts)) {
        foreach ($theme_parts as $part) {
            $part = sanitize_html_class($part);
            if ($part !== '') {
                $style_classes[] = $part;
            }
        }
    }
}

$content = get_field('content');

$classes = implode(' ', array_filter(array_map('esc_attr', $style_classes)));
?>

<section <?php echo $block_id; ?> class="guten-block <?php echo esc_attr($classes); ?>">
    <?php if (is_string($content) && trim($content) !== '') : ?>
        <div class="container">
            <div class="wysiwyg-container">
                <?php echo wp_kses_post($content); ?>
            </div>
        </div>
    <?php endif; ?>
</section>
