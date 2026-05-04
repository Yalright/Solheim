<?php
/**
 * Block - Hero + CTA
 */

$block_data     = include get_template_directory() . '/acf-blocks/block-settings/block-settings.php';
$style_classes  = $block_data['style_classes'];
$block_id       = ! empty($block_data['block_id']) ? 'id="' . esc_attr($block_data['block_id']) . '"' : '';

$block_name = 'hero-cta';
array_unshift($style_classes, $block_name);
$style_classes[] = $block_name;

$classes = implode(' ', array_filter(array_map('esc_attr', $style_classes)));

$bg_image = get_field('background_image');
$bg_video = get_field('background_video');
$cta      = get_field('cta');

$image_url = is_array($bg_image) && ! empty($bg_image['url']) ? $bg_image['url'] : '';

$video_url  = '';
$video_type = '';
if (is_array($bg_video) && ! empty($bg_video['url'])) {
    $video_url = $bg_video['url'];
    if (! empty($bg_video['mime_type'])) {
        $video_type = (string) $bg_video['mime_type'];
    }
}

if ($video_url !== '' && $video_type === '') {
    $path = wp_parse_url($video_url, PHP_URL_PATH);
    $path = is_string($path) ? $path : '';
    if ($path !== '') {
        $checked = wp_check_filetype(basename($path));
        if (is_array($checked) && ! empty($checked['type'])) {
            $video_type = $checked['type'];
        }
    }
}
if ($video_url !== '' && $video_type === '') {
    $video_type = 'video/mp4';
}

$has_cta = is_array($cta) && ! empty($cta['url']);
?>

<section <?php echo $block_id; ?> class="guten-block <?php echo esc_attr($classes); ?>">
    <div class="hero-cta__media" aria-hidden="true">
        <?php if ($image_url !== '') : ?>
            <div class="hero-cta__poster" style="background-image:url(<?php echo esc_url($image_url); ?>);"></div>
        <?php endif; ?>

        <?php if ($video_url !== '') : ?>
            <video
                class="hero-cta__video"
                autoplay
                muted
                loop
                playsinline
                <?php echo $image_url !== '' ? ' poster="' . esc_url($image_url) . '"' : ''; ?>
            >
                <source src="<?php echo esc_url($video_url); ?>" type="<?php echo esc_attr($video_type); ?>" />
            </video>
        <?php endif; ?>
    </div>

    <?php if ($has_cta) : ?>
        <div class="hero-cta__inner">
            <a
                class="btn-outline-white hero-cta__cta"
                href="<?php echo esc_url($cta['url']); ?>"
                <?php echo ! empty($cta['target']) ? ' target="' . esc_attr($cta['target']) . '"' : ''; ?>
                <?php echo ! empty($cta['target']) && $cta['target'] === '_blank' ? ' rel="noopener noreferrer"' : ''; ?>
            >
                <?php echo esc_html($cta['title'] !== '' ? $cta['title'] : $cta['url']); ?>
            </a>
        </div>
    <?php endif; ?>
</section>
