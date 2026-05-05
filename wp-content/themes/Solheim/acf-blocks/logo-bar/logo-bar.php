<?php
/**
 * Block - Logo bar
 */

$block_data     = include get_template_directory() . '/acf-blocks/block-settings/block-settings.php';
$style_classes  = $block_data['style_classes'];
$block_id       = ! empty($block_data['block_id']) ? 'id="' . esc_attr($block_data['block_id']) . '"' : '';

$block_name = 'logo-bar';
array_unshift($style_classes, $block_name);
$style_classes[] = $block_name;

$theme = get_field('theme');
$title = get_field('title');
$logos = get_field('logos');

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

$title = is_string($title) ? trim($title) : '';
$logos = is_array($logos) ? $logos : array();

$classes = implode(' ', array_filter(array_map('esc_attr', $style_classes)));
?>

<section <?php echo $block_id; ?> class="guten-block <?php echo esc_attr($classes); ?>">
    <div class="logo-bar__layout">
        <div class="logo-bar__title-col">
            <?php if ($title !== '') : ?>
                <h2 class="logo-bar__title"><?php echo esc_html($title); ?></h2>
            <?php endif; ?>
        </div>

        <div class="logo-bar__slider-col">
            <?php if (! empty($logos)) : ?>
                <div class="splide logo-bar__splide" data-logo-bar-slider>
                    <div class="splide__track">
                        <ul class="splide__list">
                            <?php foreach ($logos as $logo) : ?>
                                <?php
                                $logo_url = is_array($logo) && ! empty($logo['url']) ? $logo['url'] : '';
                                $logo_alt = is_array($logo) && isset($logo['alt']) ? (string) $logo['alt'] : '';
                                if ($logo_url === '') {
                                    continue;
                                }
                                ?>
                                <li class="splide__slide">
                                    <div class="logo-bar__logo-item">
                                        <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($logo_alt); ?>" loading="lazy" />
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
