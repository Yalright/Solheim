<?php
/**
 * Block - Promo Text + content
 */

$block_data     = include get_template_directory() . '/acf-blocks/block-settings/block-settings.php';
$style_classes  = $block_data['style_classes'];
$block_id       = ! empty($block_data['block_id']) ? 'id="' . esc_attr($block_data['block_id']) . '"' : '';

$block_name = 'promo-text-content';
array_unshift($style_classes, $block_name);
$style_classes[] = $block_name;

$layout_style = get_field('style');
$layout_style = is_string($layout_style) ? trim($layout_style) : '';
if ($layout_style !== 'style-2') {
    $layout_style = 'style-1';
}
$style_classes[] = 'promo-text-content--' . sanitize_html_class($layout_style);

$theme   = get_field('theme');
$promo   = get_field('promotional_text');
$content = get_field('content');
$cta     = get_field('cta');

$promo   = is_string($promo) ? $promo : '';
$content = is_string($content) ? $content : '';

// Theme (editor palette) applies to Style 1 only — field is hidden for Style 2 in ACF.
if ($layout_style === 'style-1' && is_string($theme) && trim($theme) !== '') {
    $theme_tokens = preg_split('/\s+/', trim($theme));
    if (is_array($theme_tokens)) {
        $theme_tokens = array_filter(array_map('sanitize_html_class', $theme_tokens));
        if (! empty($theme_tokens)) {
            $style_classes = array_merge($style_classes, $theme_tokens);
        }
    }
}

$classes = implode(' ', array_filter(array_map('esc_attr', $style_classes)));

$ripple_url = get_template_directory_uri() . '/assets/images/ripple-white.svg';

$section_style = '';
if ($layout_style === 'style-1') {
    $section_style = 'background-image:url(' . esc_url($ripple_url) . ');';
} else {
    $bg = get_field('background_image');
    if (is_array($bg) && ! empty($bg['url'])) {
        $section_style = sprintf(
            'background-image:url(%s);background-size:cover;background-position:center center;background-repeat:no-repeat;',
            esc_url($bg['url'])
        );
    }
}

$has_cta = $layout_style === 'style-2' && is_array($cta) && ! empty($cta['url']);
?>

<section <?php echo $block_id; ?> class="guten-block <?php echo esc_attr($classes); ?>"<?php echo $section_style !== '' ? ' style="' . esc_attr($section_style) . '"' : ''; ?>>
    <div class="promo-text-content__inner">
        <div class="promo-text-content__col promo-text-content__col--promo">
            <div class="promo-text-content__promo-stack">
                <div class="promo-text-content__promo">
                    <?php echo wp_kses_post($promo); ?>
                </div>
                <?php if ($has_cta) : ?>
                    <a
                        class="btn-outline-white promo-text-content__cta"
                        href="<?php echo esc_url($cta['url']); ?>"
                        <?php echo ! empty($cta['target']) ? ' target="' . esc_attr($cta['target']) . '"' : ''; ?>
                        <?php echo ! empty($cta['target']) && $cta['target'] === '_blank' ? ' rel="noopener noreferrer"' : ''; ?>
                    >
                        <?php echo esc_html(! empty($cta['title']) ? $cta['title'] : $cta['url']); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="promo-text-content__col promo-text-content__col--content">
            <?php if ($layout_style === 'style-2') : ?>
                <div class="promo-text-content__panel">
                    <div class="promo-text-content__content">
                        <?php echo wp_kses_post($content); ?>
                    </div>
                </div>
            <?php else : ?>
                <div class="promo-text-content__content">
                    <?php echo wp_kses_post($content); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
