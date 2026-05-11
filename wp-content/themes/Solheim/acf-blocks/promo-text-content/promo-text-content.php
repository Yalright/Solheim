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

$theme = get_field('theme');
$promo = get_field('promotional_text');
$content = get_field('content');

$promo = is_string($promo) ? $promo : '';
$content = is_string($content) ? $content : '';

// Theme comes from the editor palette (e.g. "has-background has-navy-background-color")
if (is_string($theme) && trim($theme) !== '') {
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
?>

<section <?php echo $block_id; ?> class="guten-block <?php echo esc_attr($classes); ?>" style="background-image:url(<?php echo esc_url($ripple_url); ?>);">
    <div class="promo-text-content__inner">
        <div class="promo-text-content__col promo-text-content__col--promo">
            <div class="promo-text-content__promo">
                <?php echo wp_kses_post($promo); ?>
            </div>
        </div>
        <div class="promo-text-content__col promo-text-content__col--content">
            <div class="promo-text-content__content">
                <?php echo wp_kses_post($content); ?>
            </div>
        </div>
    </div>
</section>
