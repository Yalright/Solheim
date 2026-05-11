<?php
/**
 * Block - Image + Content
 */

$block_data     = include get_template_directory() . '/acf-blocks/block-settings/block-settings.php';
$style_classes  = $block_data['style_classes'];
$block_id       = ! empty($block_data['block_id']) ? 'id="' . esc_attr($block_data['block_id']) . '"' : '';

$block_name = 'image-content';
array_unshift($style_classes, $block_name);
$style_classes[] = $block_name;

$style = get_field('style');
$style = is_string($style) ? strtolower(trim($style)) : 'style-1';
if ($style !== 'style-2') {
    $style = 'style-1';
}
$style_classes[] = 'image-content--' . sanitize_html_class($style);

$theme          = get_field('theme');
$image          = get_field('image');
$content        = get_field('content');
$cta            = get_field('cta');
$image_position = get_field('image_position');

$title    = $style === 'style-2' ? get_field('title') : '';
$subtitle = $style === 'style-2' ? get_field('subtitle') : '';
$title    = is_string($title) ? trim($title) : '';
$subtitle = is_string($subtitle) ? trim($subtitle) : '';

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

$image_position = is_string($image_position) ? strtolower(trim($image_position)) : 'right';
if (! in_array($image_position, array('left', 'right'), true)) {
    $image_position = 'right';
}
$style_classes[] = 'image-content--img-' . $image_position;

$image_url = is_array($image) && ! empty($image['url']) ? $image['url'] : '';
$image_alt = is_array($image) && isset($image['alt']) ? (string) $image['alt'] : '';

$has_cta = is_array($cta) && ! empty($cta['url']);

$classes = implode(' ', array_filter(array_map('esc_attr', $style_classes)));
?>

<section <?php echo $block_id; ?> class="guten-block <?php echo esc_attr($classes); ?>">
    <div class="image-content__layout">
        <div class="image-content__content-col">
            <div class="image-content__inner">
                <?php if ($style === 'style-2') : ?>
                    <?php if ($title !== '') : ?>
                        <h3 class="image-content__title"><?php echo esc_html($title); ?></h3>
                    <?php endif; ?>
                    <?php if ($subtitle !== '') : ?>
                        <p class="image-content__subtitle"><?php echo esc_html($subtitle); ?></p>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if (is_string($content) && trim($content) !== '') : ?>
                    <div class="image-content__content">
                        <?php echo wp_kses_post($content); ?>
                    </div>
                <?php endif; ?>

                <?php if ($has_cta) : ?>
                    <a
                        class="btn-blue-white image-content__cta"
                        href="<?php echo esc_url($cta['url']); ?>"
                        <?php echo ! empty($cta['target']) ? ' target="' . esc_attr($cta['target']) . '"' : ''; ?>
                        <?php echo ! empty($cta['target']) && $cta['target'] === '_blank' ? ' rel="noopener noreferrer"' : ''; ?>
                    >
                        <?php echo esc_html($cta['title'] !== '' ? $cta['title'] : $cta['url']); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="image-content__image-col">
            <?php if ($image_url !== '') : ?>
                <div
                    class="image-content__image"
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
    </div>
</section>
