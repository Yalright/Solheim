<?php
/**
 * Block - Image Slider
 */

$block_data     = include get_template_directory() . '/acf-blocks/block-settings/block-settings.php';
$style_classes  = $block_data['style_classes'];
$block_id       = ! empty($block_data['block_id']) ? 'id="' . esc_attr($block_data['block_id']) . '"' : '';

$block_name = 'image-slider';
array_unshift($style_classes, $block_name);
$style_classes[] = $block_name;

$theme = get_field('theme');
$gallery = get_field('gallery');
$gallery = is_array($gallery) ? $gallery : array();

// Theme comes from the editor palette (e.g. "has-background has-navy-background-color")
if (is_string($theme) && trim($theme) !== '') {
    $theme_tokens = preg_split('/\s+/', trim($theme));
    if (is_array($theme_tokens)) {
        $theme_tokens = array_filter(array_map('sanitize_html_class', $theme_tokens));
        if (! empty($theme_tokens)) {
            $style_classes = array_merge($style_classes, $theme_tokens);
        }
    }
}

$classes = implode(' ', array_filter(array_map('esc_attr', $style_classes)));
?>

<section <?php echo $block_id; ?> class="guten-block <?php echo esc_attr($classes); ?>">
    <?php if (! empty($gallery)) : ?>
        <div class="splide image-slider__splide" data-image-slider>
            <div class="splide__track">
                <ul class="splide__list">
                    <?php foreach ($gallery as $img) : ?>
                        <?php
                        $url = is_array($img) && ! empty($img['url']) ? $img['url'] : '';
                        $alt = is_array($img) && isset($img['alt']) ? (string) $img['alt'] : '';
                        ?>
                        <?php if ($url !== '') : ?>
                            <li class="splide__slide image-slider__slide">
                                <img class="image-slider__img" src="<?php echo esc_url($url); ?>" alt="<?php echo esc_attr($alt); ?>" loading="lazy" decoding="async" />
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>
</section>
