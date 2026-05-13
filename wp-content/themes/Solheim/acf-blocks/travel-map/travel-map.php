<?php
/**
 * Block - Travel Map
 *
 * ACF (create in WP): image, column_1_content (wysiwyg), column_2_content (wysiwyg).
 */

$block_data     = include get_template_directory() . '/acf-blocks/block-settings/block-settings.php';
$style_classes  = $block_data['style_classes'];
$block_id       = ! empty($block_data['block_id']) ? 'id="' . esc_attr($block_data['block_id']) . '"' : '';

$block_name = 'travel-map';
array_unshift($style_classes, $block_name);
$style_classes[] = $block_name;

$image = get_field('image');
$img_url = is_array($image) && ! empty($image['url']) ? $image['url'] : '';
$img_alt = is_array($image) && isset($image['alt']) ? (string) $image['alt'] : '';

$raw_col1 = get_field('column_1_content');
$raw_col2 = get_field('column_2_content');

$col1_html = '';
if (is_string($raw_col1) && trim($raw_col1) !== '') {
    $plain = trim(wp_strip_all_tags($raw_col1, true));
    if ($plain !== '') {
        $col1_html = wp_kses_post($raw_col1);
    }
}

$col2_html = '';
if (is_string($raw_col2) && trim($raw_col2) !== '') {
    $plain = trim(wp_strip_all_tags($raw_col2, true));
    if ($plain !== '') {
        $col2_html = wp_kses_post($raw_col2);
    }
}

if ($img_url === '' && $col1_html === '' && $col2_html === '') {
    return;
}

$classes = implode(' ', array_filter(array_map('esc_attr', $style_classes)));
?>

<section <?php echo $block_id; ?> class="guten-block <?php echo esc_attr($classes); ?>">
    <div class="container">
        <?php if ($img_url !== '') : ?>
            <div class="travel-map__image-row">
                <figure class="travel-map__figure">
                    <img
                        class="travel-map__img"
                        src="<?php echo esc_url($img_url); ?>"
                        alt="<?php echo esc_attr($img_alt); ?>"
                        loading="lazy"
                        decoding="async"
                    />
                </figure>
            </div>
        <?php endif; ?>

        <?php if ($col1_html !== '' || $col2_html !== '') : ?>
            <div class="travel-map__cols">
                <div class="travel-map__col travel-map__col--1">
                    <?php if ($col1_html !== '') : ?>
                        <div class="travel-map__content">
                            <?php echo $col1_html; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="travel-map__col travel-map__col--2">
                    <?php if ($col2_html !== '') : ?>
                        <div class="travel-map__content">
                            <?php echo $col2_html; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
