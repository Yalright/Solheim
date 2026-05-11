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

$hero_style = get_field('style');
$hero_style = is_string($hero_style) ? trim($hero_style) : '';
if ($hero_style !== 'style-2') {
    $hero_style = 'style-1';
}
$style_classes[] = 'hero-cta--' . sanitize_html_class($hero_style);

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

$has_cta       = is_array($cta) && ! empty($cta['url']);
$show_main_cta = $hero_style === 'style-1' && $has_cta;

$overlay_rows = array();
if ($hero_style === 'style-2' && have_rows('overlay_text')) {
    while (have_rows('overlay_text')) {
        the_row();
        $theme   = get_sub_field('theme');
        $text    = get_sub_field('text');
        $offset = get_sub_field('text_offset_percent');
        if ($offset === null || $offset === '') {
            $offset = get_sub_field('text_offset');
        }
        $row_cta      = get_sub_field('cta');
        $cta_position = get_sub_field('cta_position');
        $cta_position = is_string($cta_position) ? strtolower(trim($cta_position)) : 'right';
        if ($cta_position !== 'left') {
            $cta_position = 'right';
        }

        $text = is_string($text) ? trim($text) : '';
        if ($text === '' && (! is_array($row_cta) || empty($row_cta['url']))) {
            continue;
        }

        $theme_classes = array();
        if (is_string($theme) && trim($theme) !== '') {
            $parts = preg_split('/\s+/', trim($theme));
            if (is_array($parts)) {
                foreach ($parts as $part) {
                    $part = sanitize_html_class($part);
                    if ($part !== '') {
                        $theme_classes[] = $part;
                    }
                }
            }
        }

        $offset_val = is_numeric($offset) ? (float) $offset : 0.0;

        $overlay_rows[] = array(
            'theme_classes' => $theme_classes,
            'text'          => $text,
            'offset'        => $offset_val,
            'cta'           => $row_cta,
            'has_cta'       => is_array($row_cta) && ! empty($row_cta['url']),
            'cta_position'  => $cta_position,
        );
    }
}

$classes = implode(' ', array_filter(array_map('esc_attr', $style_classes)));
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

    <?php if ($show_main_cta) : ?>
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

    <?php if ($hero_style === 'style-2' && count($overlay_rows) > 0) : ?>
        <div class="hero-cta__overlay">
            <div class="hero-cta__overlay-content">
                <?php foreach ($overlay_rows as $row) : ?>
                    <div class="hero-cta__overlay-row">
                        <div
                            class="hero-cta__overlay-shift"
                            style="<?php echo esc_attr('transform:translateX(' . $row['offset'] . '%);'); ?>"
                        >
                            <div class="hero-cta__overlay-inner<?php echo $row['cta_position'] === 'left' ? ' hero-cta__overlay-inner--cta-left' : ''; ?>">
                                <?php if ($row['text'] !== '') : ?>
                                    <div class="hero-cta__overlay-text-wrap<?php echo count($row['theme_classes']) ? ' ' . esc_attr(implode(' ', $row['theme_classes'])) : ''; ?>">
                                        <div class="hero-cta__overlay-text">
                                            <?php echo wp_kses_post($row['text']); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if ($row['has_cta']) : ?>
                                    <?php $rcta = $row['cta']; ?>
                                    <a
                                        class="btn-outline-white hero-cta__overlay-cta"
                                        href="<?php echo esc_url($rcta['url']); ?>"
                                        <?php echo ! empty($rcta['target']) ? ' target="' . esc_attr($rcta['target']) . '"' : ''; ?>
                                        <?php echo ! empty($rcta['target']) && $rcta['target'] === '_blank' ? ' rel="noopener noreferrer"' : ''; ?>
                                    >
                                        <?php echo esc_html($rcta['title'] !== '' ? $rcta['title'] : $rcta['url']); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</section>
