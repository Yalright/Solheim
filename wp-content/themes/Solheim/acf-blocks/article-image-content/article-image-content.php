<?php
/**
 * Block - Article Image + Content
 */

$block_data    = include get_template_directory() . '/acf-blocks/block-settings/block-settings.php';
$style_classes = $block_data['style_classes'];
$block_id      = ! empty($block_data['block_id']) ? 'id="' . esc_attr($block_data['block_id']) . '"' : '';

$block_name = 'article-image-content';
array_unshift($style_classes, $block_name);
$style_classes[] = $block_name;

$theme          = get_field('theme');
$image          = get_field('image');
$content        = get_field('content');
$image_position = get_field('image_position');

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

$image_position = is_string($image_position) ? $image_position : 'left';
if (! in_array($image_position, array('left', 'right'), true)) {
    $image_position = 'left';
}
$style_classes[] = 'article-image-content--img-' . $image_position;

$image_url = is_array($image) && ! empty($image['url']) ? $image['url'] : '';
$image_alt = is_array($image) && isset($image['alt']) ? (string) $image['alt'] : '';

$classes = implode(' ', array_filter(array_map('esc_attr', $style_classes)));
?>

<section <?php echo $block_id; ?> class="guten-block <?php echo esc_attr($classes); ?>">
    <div class="article-image-content__layout">
        <div class="article-image-content__image-col">
            <?php if ($image_url !== '') : ?>
                <div
                    class="article-image-content__image"
                    style="background-image:url(<?php echo esc_url($image_url); ?>);"
                    <?php if ($image_alt !== '') : ?>
                        role="img"
                        aria-label="<?php echo esc_attr($image_alt); ?>"
                    <?php else : ?>
                        aria-hidden="true"
                    <?php endif; ?>
                ></div>
            <?php endif; ?>
        </div>

        <div class="article-image-content__content-col">
            <div class="article-image-content__content">
                <?php echo wp_kses_post((string) $content); ?>
            </div>
        </div>
    </div>
</section>
