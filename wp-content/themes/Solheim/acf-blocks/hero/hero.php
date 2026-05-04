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

$style               = get_field('style');
$desktop_image       = get_field('desktop_image');
$mobile_image        = get_field('mobile_image');
$include_raw       = get_field('include_countdown');
$include_countdown = in_array($include_raw, array(true, 1, '1', 'true', 'yes', 'on'), true);
$countdown_date    = get_field('countdown_date');
$countdown_offset_y = get_field('countdown_offset_-_y_%');

$allowed_styles = array('style-1', 'style-2');
if (! is_string($style) || ! in_array($style, $allowed_styles, true)) {
    $style = 'style-1';
}

$style_classes[] = 'hero--' . $style;

$desktop_url = is_array($desktop_image) && ! empty($desktop_image['url']) ? $desktop_image['url'] : '';
$mobile_url  = is_array($mobile_image) && ! empty($mobile_image['url']) ? $mobile_image['url'] : '';

if ($desktop_url === '' && $mobile_url !== '') {
    $desktop_url = $mobile_url;
}
if ($mobile_url === '' && $desktop_url !== '') {
    $mobile_url = $desktop_url;
}

$countdown_days = null;
if ($include_countdown && $countdown_date) {
    $countdown_days = solheim_hero_countdown_days($countdown_date);
}

$countdown_top_offset = 0.0;
if ($countdown_offset_y !== '' && $countdown_offset_y !== null && is_numeric($countdown_offset_y)) {
    $countdown_top_offset = (float) $countdown_offset_y;
}

$classes = implode(' ', array_filter(array_map('esc_attr', $style_classes)));
?>

<section <?php echo $block_id; ?> class="guten-block <?php echo esc_attr($classes); ?>">
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

    <?php if ($include_countdown && $countdown_days !== null) : ?>
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
