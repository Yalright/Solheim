<?php
/**
 * Block - Hero
 */

$block_data     = include get_template_directory() . '/acf-blocks/block-settings/block-settings.php';
$style_classes  = $block_data['style_classes'];
$block_id       = ! empty($block_data['block_id']) ? 'id="' . esc_attr($block_data['block_id']) . '"' : '';

$block_name = 'hero';
array_unshift($style_classes, $block_name);
$style_classes[] = $block_name;

$style         = get_field('style');
$desktop_image = get_field('desktop_image');
$mobile_image  = get_field('mobile_image');

$allowed_styles = array('style-1', 'style-2', 'style-3');
if (! is_string($style) || ! in_array($style, $allowed_styles, true)) {
    $style = 'style-1';
}

$style_classes[] = 'hero--' . $style;

// Countdown fields apply only to style-1 (ACF should mirror with conditional logic in WP).
$include_countdown   = false;
$countdown_date      = null;
$countdown_offset_y  = null;
if ($style === 'style-1') {
    $include_raw       = get_field('include_countdown');
    $include_countdown = in_array($include_raw, array(true, 1, '1', 'true', 'yes', 'on'), true);
    $countdown_date    = get_field('countdown_date');
    $countdown_offset_y = get_field('countdown_offset_-_y_%');
}

$desktop_url = is_array($desktop_image) && ! empty($desktop_image['url']) ? $desktop_image['url'] : '';
$mobile_url  = is_array($mobile_image) && ! empty($mobile_image['url']) ? $mobile_image['url'] : '';

if ($desktop_url === '' && $mobile_url !== '') {
    $desktop_url = $mobile_url;
}
if ($mobile_url === '' && $desktop_url !== '') {
    $mobile_url = $desktop_url;
}

$countdown_days = null;
if ($style === 'style-1' && $include_countdown && $countdown_date) {
    $countdown_days = solheim_hero_countdown_days($countdown_date);
}

$countdown_top_offset = 0.0;
if ($style === 'style-1' && $countdown_offset_y !== '' && $countdown_offset_y !== null && is_numeric($countdown_offset_y)) {
    $countdown_top_offset = (float) $countdown_offset_y;
}

$classes = implode(' ', array_filter(array_map('esc_attr', $style_classes)));

/**
 * @param array<string, mixed> $image ACF image field array.
 * @return array{url: string, alt: string, w: string, h: string} Attributes for <img> (w/h empty if unknown).
 */
$hero_img_from_acf = static function ($image) {
    $out = array(
        'url' => '',
        'alt' => '',
        'w'   => '',
        'h'   => '',
    );
    if (! is_array($image) || empty($image['url'])) {
        return $out;
    }
    $out['url'] = (string) $image['url'];
    $out['alt'] = isset($image['alt']) ? (string) $image['alt'] : '';
    if (! empty($image['width']) && is_numeric($image['width'])) {
        $out['w'] = (string) (int) $image['width'];
    }
    if (! empty($image['height']) && is_numeric($image['height'])) {
        $out['h'] = (string) (int) $image['height'];
    }

    return $out;
};

$m_img = $hero_img_from_acf(is_array($mobile_image) ? $mobile_image : array());
$d_img = $hero_img_from_acf(is_array($desktop_image) ? $desktop_image : array());

if ($m_img['url'] === '' && $d_img['url'] !== '') {
    $m_img = $d_img;
}
if ($d_img['url'] === '' && $m_img['url'] !== '') {
    $d_img = $m_img;
}
?>

<section <?php echo $block_id; ?> class="guten-block <?php echo esc_attr($classes); ?>">
    <?php if ($style === 'style-3') : ?>
        <?php if ($m_img['url'] !== '') : ?>
            <div class="hero__intrinsic hero__intrinsic--mobile">
                <img
                    class="hero__img"
                    src="<?php echo esc_url($m_img['url']); ?>"
                    alt="<?php echo esc_attr($m_img['alt']); ?>"
                    <?php echo $m_img['w'] !== '' ? ' width="' . esc_attr($m_img['w']) . '"' : ''; ?>
                    <?php echo $m_img['h'] !== '' ? ' height="' . esc_attr($m_img['h']) . '"' : ''; ?>
                    loading="eager"
                    decoding="async"
                />
            </div>
        <?php endif; ?>
        <?php if ($d_img['url'] !== '') : ?>
            <div class="hero__intrinsic hero__intrinsic--desktop">
                <img
                    class="hero__img"
                    src="<?php echo esc_url($d_img['url']); ?>"
                    alt="<?php echo esc_attr($d_img['alt']); ?>"
                    <?php echo $d_img['w'] !== '' ? ' width="' . esc_attr($d_img['w']) . '"' : ''; ?>
                    <?php echo $d_img['h'] !== '' ? ' height="' . esc_attr($d_img['h']) . '"' : ''; ?>
                    loading="eager"
                    decoding="async"
                />
            </div>
        <?php endif; ?>
    <?php else : ?>
        <?php if ($mobile_url !== '') : ?>
            <div
                class="hero__bg hero__bg--mobile"
                style="background-image: url(<?php echo esc_url($mobile_url); ?>);"
                role="img"
                aria-hidden="true"
            ></div>
        <?php endif; ?>
        <?php if ($desktop_url !== '') : ?>
            <div
                class="hero__bg hero__bg--desktop"
                style="background-image: url(<?php echo esc_url($desktop_url); ?>);"
                role="img"
                aria-hidden="true"
            ></div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($style === 'style-1' && $include_countdown && $countdown_days !== null) : ?>
        <div
            class="hero__countdown"
            role="status"
            style="<?php echo esc_attr('top: calc(50% + ' . $countdown_top_offset . '%);'); ?>"
        >
            <?php
            $days_label = sprintf(
                _n('%s DAY', '%s DAYS', $countdown_days, 'solheim'),
                number_format_i18n($countdown_days)
            );
            ?>
            <span class="hero__countdown-label"><?php esc_html_e('COUNTDOWN:', 'solheim'); ?></span>
            <span class="hero__countdown-value"><?php echo esc_html($days_label); ?></span>
        </div>
    <?php endif; ?>
</section>
