<?php
/**
 * Block - Accommodation
 *
 * ACF (create in WP):
 * - image (image, label “Image”) — block hero; full width, natural height, top center
 * - introduction (wysiwyg)
 * - locations (repeater) → subtitle, title, content (wysiwyg), image, pin_x, pin_y (numbers 0–100, % on hero map)
 */

$block_data     = include get_template_directory() . '/acf-blocks/block-settings/block-settings.php';
$style_classes  = $block_data['style_classes'];
$block_id       = ! empty($block_data['block_id']) ? 'id="' . esc_attr($block_data['block_id']) . '"' : '';

$block_name = 'accommodation';
array_unshift($style_classes, $block_name);
$style_classes[] = $block_name;

$pin_icon_url          = get_template_directory_uri() . '/assets/images/icon-pin.svg';
$pin_map_icon_url      = get_template_directory_uri() . '/assets/images/icon-pin-lrg.svg';
$pin_map_icon_yellow_url = get_template_directory_uri() . '/assets/images/icon-pin-lrg-yellow.svg';

$hero_image = get_field('image');
$hero_url   = is_array($hero_image) && ! empty($hero_image['url']) ? $hero_image['url'] : '';
$hero_alt   = is_array($hero_image) && isset($hero_image['alt']) ? (string) $hero_image['alt'] : '';

$intro_raw = get_field('introduction');
$intro_html = '';
if (is_string($intro_raw) && trim($intro_raw) !== '') {
    $plain = trim(wp_strip_all_tags($intro_raw, true));
    if ($plain !== '') {
        $intro_html = wp_kses_post($intro_raw);
    }
}

$locations = array();
if (have_rows('locations')) {
    while (have_rows('locations')) {
        the_row();
        $subtitle = get_sub_field('subtitle');
        $title     = get_sub_field('title');
        $content   = get_sub_field('content');
        $loc_image = get_sub_field('image');
        $pin_x_raw = get_sub_field('pin_x');
        $pin_y_raw = get_sub_field('pin_y');

        $subtitle = is_string($subtitle) ? trim($subtitle) : '';
        $title     = is_string($title) ? trim($title) : '';

        $content_html = '';
        if (is_string($content) && trim($content) !== '') {
            $pt = trim(wp_strip_all_tags($content, true));
            if ($pt !== '') {
                $content_html = wp_kses_post($content);
            }
        }

        $img_url = is_array($loc_image) && ! empty($loc_image['url']) ? $loc_image['url'] : '';
        $img_alt = is_array($loc_image) && isset($loc_image['alt']) ? (string) $loc_image['alt'] : '';

        $pin_x = null;
        $pin_y = null;
        if ($pin_x_raw !== null && $pin_x_raw !== '' && is_numeric($pin_x_raw)) {
            $pin_x = max(0.0, min(100.0, (float) $pin_x_raw));
        }
        if ($pin_y_raw !== null && $pin_y_raw !== '' && is_numeric($pin_y_raw)) {
            $pin_y = max(0.0, min(100.0, (float) $pin_y_raw));
        }

        $has_map_pin = $pin_x !== null && $pin_y !== null;
        if ($subtitle === '' && $title === '' && $content_html === '' && $img_url === '' && ! $has_map_pin) {
            continue;
        }

        $loc_index = count($locations);
        $locations[] = array(
            'loc_index'    => $loc_index,
            'subtitle'     => $subtitle,
            'title'        => $title,
            'content_html' => $content_html,
            'image_url'    => $img_url,
            'image_alt'    => $img_alt,
            'pin_x'        => $pin_x,
            'pin_y'        => $pin_y,
        );
    }
}

$locations_panel = array_values(
    array_filter(
        $locations,
        static function ($loc) {
            return $loc['subtitle'] !== ''
                || $loc['title'] !== ''
                || $loc['content_html'] !== ''
                || $loc['image_url'] !== '';
        }
    )
);

if ($hero_url === '' && $intro_html === '' && count($locations_panel) === 0) {
    return;
}

$has_any_hero_pin = false;
foreach ($locations as $_loc) {
    if ($_loc['pin_x'] !== null && $_loc['pin_y'] !== null) {
        $has_any_hero_pin = true;
        break;
    }
}

$accommodation_spy_attrs = '';
if ($hero_url !== '' && $has_any_hero_pin && count($locations_panel) > 0) {
    $accommodation_spy_attrs = ' data-accommodation-spy="1"'
        . ' data-accommodation-pin-default-src="' . esc_attr($pin_map_icon_url) . '"'
        . ' data-accommodation-pin-active-src="' . esc_attr($pin_map_icon_yellow_url) . '"'
        . ' data-accommodation-scroll-offset="140"';
}

$classes = implode(' ', array_filter(array_map('esc_attr', $style_classes)));
?>

<section <?php echo $block_id; ?> class="guten-block <?php echo esc_attr($classes); ?>"<?php echo $accommodation_spy_attrs; ?>>
    <?php if ($hero_url !== '') : ?>
        <div class="accommodation__hero-stage">
            <div class="accommodation__hero">
                <figure class="accommodation__hero-figure">
                    <img
                        class="accommodation__hero-img"
                        src="<?php echo esc_url($hero_url); ?>"
                        alt="<?php echo esc_attr($hero_alt); ?>"
                        loading="lazy"
                        decoding="async"
                    />
                    <?php foreach ($locations as $loc) : ?>
                        <?php
                        if ($loc['pin_x'] === null || $loc['pin_y'] === null) {
                            continue;
                        }
                        $pin_label = $loc['title'] !== '' ? $loc['title'] : ($loc['subtitle'] !== '' ? $loc['subtitle'] : '');
                        ?>
                        <span
                            class="accommodation__hero-pin"
                            data-accommodation-loc="<?php echo esc_attr((string) (int) $loc['loc_index']); ?>"
                            style="left: <?php echo esc_attr((string) $loc['pin_x']); ?>%; top: <?php echo esc_attr((string) $loc['pin_y']); ?>%;"
                            <?php echo $pin_label !== '' ? ' role="img" aria-label="' . esc_attr($pin_label) . '"' : ' aria-hidden="true"'; ?>
                        >
                            <img
                                class="accommodation__hero-pin-img"
                                src="<?php echo esc_url($pin_map_icon_url); ?>"
                                alt=""
                                width="51"
                                height="51"
                                loading="lazy"
                                decoding="async"
                            />
                        </span>
                    <?php endforeach; ?>
                </figure>
            </div>
            <div class="accommodation__hero-overlay">
    <?php endif; ?>

    <div class="container accommodation__split">
        <div class="accommodation__split-left" aria-hidden="true"></div>
        <div class="accommodation__split-right">
            <div class="accommodation__panel">
                <?php if ($intro_html !== '') : ?>
                    <div class="accommodation__introduction">
                        <?php echo $intro_html; ?>
                    </div>
                <?php endif; ?>

                <?php if (count($locations_panel) > 0) : ?>
                    <ul class="accommodation__locations">
                        <?php foreach ($locations_panel as $loc) : ?>
                            <li class="accommodation__location" data-accommodation-loc="<?php echo esc_attr((string) (int) $loc['loc_index']); ?>">
                                <?php if ($loc['subtitle'] !== '') : ?>
                                    <p class="accommodation__location-subtitle">
                                        <img
                                            class="accommodation__location-subtitle-icon"
                                            src="<?php echo esc_url($pin_icon_url); ?>"
                                            alt=""
                                            width="21"
                                            height="21"
                                            loading="lazy"
                                            decoding="async"
                                        />
                                        <span class="accommodation__location-subtitle-text"><?php echo esc_html($loc['subtitle']); ?></span>
                                    </p>
                                <?php endif; ?>
                                <?php if ($loc['title'] !== '') : ?>
                                    <h3 class="accommodation__location-title"><?php echo esc_html($loc['title']); ?></h3>
                                <?php endif; ?>
                                <?php if ($loc['content_html'] !== '') : ?>
                                    <div class="accommodation__location-content">
                                        <?php echo $loc['content_html']; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($loc['image_url'] !== '') : ?>
                                    <figure class="accommodation__location-figure">
                                        <img
                                            class="accommodation__location-img"
                                            src="<?php echo esc_url($loc['image_url']); ?>"
                                            alt="<?php echo esc_attr($loc['image_alt']); ?>"
                                            loading="lazy"
                                            decoding="async"
                                        />
                                    </figure>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php if ($hero_url !== '') : ?>
            </div>
        </div>
    <?php endif; ?>
</section>
