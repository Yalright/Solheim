<?php
/**
 * Block - Hero + Video
 */

$block_data     = include get_template_directory() . '/acf-blocks/block-settings/block-settings.php';
$style_classes  = $block_data['style_classes'];
$block_id       = ! empty($block_data['block_id']) ? 'id="' . esc_attr($block_data['block_id']) . '"' : '';

$block_name = 'hero-video';
array_unshift($style_classes, $block_name);
$style_classes[] = $block_name;

$background_image = get_field('background_image');
$cover_image      = get_field('image');
$video_file       = get_field('video');

$bg_url = is_array($background_image) && ! empty($background_image['url']) ? $background_image['url'] : '';

$cover_url = is_array($cover_image) && ! empty($cover_image['url']) ? $cover_image['url'] : '';
$cover_alt = is_array($cover_image) && isset($cover_image['alt']) ? (string) $cover_image['alt'] : '';

$video_url  = '';
$video_type = '';
if (is_array($video_file) && ! empty($video_file['url'])) {
    $video_url = $video_file['url'];
    if (! empty($video_file['mime_type'])) {
        $video_type = (string) $video_file['mime_type'];
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

$has_video = $video_url !== '';
$has_cover = $cover_url !== '';
$has_frame = $has_video || $has_cover;

$section_style = '';
if ($bg_url !== '') {
    $section_style = sprintf(
        'background-image:url(%s);background-size:cover;background-position:center;background-repeat:no-repeat;',
        esc_url($bg_url)
    );
}

$classes = implode(' ', array_filter(array_map('esc_attr', $style_classes)));
?>

<section <?php echo $block_id; ?> class="guten-block <?php echo esc_attr($classes); ?>"<?php echo $section_style !== '' ? ' style="' . esc_attr($section_style) . '"' : ''; ?>>
    <?php if ($has_frame) : ?>
        <div class="hero-video__inner">
            <div
                class="hero-video__frame"
                <?php if ($has_video) : ?>
                    data-hero-video-frame
                <?php endif; ?>
            >
                <?php if ($has_video) : ?>
                    <video
                        class="hero-video__video"
                        playsinline
                        controls
                        preload="none"
                        <?php if ($cover_url !== '') : ?>
                            poster="<?php echo esc_url($cover_url); ?>"
                        <?php endif; ?>
                        hidden
                    >
                        <source src="<?php echo esc_url($video_url); ?>" type="<?php echo esc_attr($video_type); ?>" />
                    </video>

                    <div class="hero-video__poster">
                        <?php if ($has_cover) : ?>
                            <img
                                class="hero-video__poster-img"
                                src="<?php echo esc_url($cover_url); ?>"
                                alt="<?php echo esc_attr($cover_alt); ?>"
                                decoding="async"
                                loading="lazy"
                                <?php echo $cover_alt === '' ? ' role="presentation"' : ''; ?>
                            />
                        <?php endif; ?>
                        <button
                            type="button"
                            class="hero-video__play"
                            data-hero-video-play
                            aria-label="<?php echo esc_attr__('Play video', 'solheim'); ?>"
                        >
                            <span class="hero-video__play-icon" aria-hidden="true">
                                <svg width="80" height="80" viewBox="0 0 80 80" focusable="false" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="40" cy="40" r="38" fill="rgba(255,255,255,0.92)" />
                                    <path d="M33 26 L33 54 L55 40 Z" fill="currentColor" />
                                </svg>
                            </span>
                        </button>
                    </div>
                <?php elseif ($has_cover) : ?>
                    <img
                        class="hero-video__cover-only"
                        src="<?php echo esc_url($cover_url); ?>"
                        alt="<?php echo esc_attr($cover_alt); ?>"
                        decoding="async"
                        loading="lazy"
                        <?php echo $cover_alt === '' ? ' role="presentation"' : ''; ?>
                    />
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</section>
