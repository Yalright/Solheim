<?php
/**
 * Block - Logo bar
 *
 * ACF: style (style-1 | style-2), theme (background colour classes), title, logos (gallery).
 * style-1: title column + Splide logo slider (existing).
 * style-2: “Global partners” — navy panel, centred title, all logos in a static grid (max 4 columns), no slider / no data-logo-bar-slider.
 */

$block_data     = include get_template_directory() . '/acf-blocks/block-settings/block-settings.php';
$style_classes  = $block_data['style_classes'];
$block_id       = ! empty($block_data['block_id']) ? 'id="' . esc_attr($block_data['block_id']) . '"' : '';

$block_name = 'logo-bar';
array_unshift($style_classes, $block_name);
$style_classes[] = $block_name;

$style_raw = get_field('style');
$layout    = (is_string($style_raw) && trim($style_raw) === 'style-2') ? 'style-2' : 'style-1';
$style_classes[] = 'logo-bar--' . $layout;

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
    <?php if ($layout === 'style-2') : ?>
        <div class="logo-bar__layout logo-bar__layout--partners">
            <div class="container logo-bar__partners-inner">
                <?php if ($title !== '') : ?>
                    <h2 class="logo-bar__title"><?php echo esc_html($title); ?></h2>
                <?php endif; ?>

                <?php if (! empty($logos)) : ?>
                    <ul class="logo-bar__grid">
                        <?php foreach ($logos as $logo) : ?>
                            <?php
                            $logo_url = is_array($logo) && ! empty($logo['url']) ? $logo['url'] : '';
                            $logo_alt = is_array($logo) && isset($logo['alt']) ? (string) $logo['alt'] : '';
                            if ($logo_url === '') {
                                continue;
                            }
                            ?>
                            <li class="logo-bar__grid-item">
                                <div class="logo-bar__logo-item logo-bar__logo-item--partners">
                                    <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($logo_alt); ?>" loading="lazy" decoding="async" />
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    <?php else : ?>
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
    <?php endif; ?>
</section>
