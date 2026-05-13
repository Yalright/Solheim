<?php
/**
 * Block - Contact Form
 *
 * ACF: theme (editor palette slug), title, form_shortcode (Contact Form 7).
 * For the 2×2 + message + submit layout, order CF7 fields: four single-line fields, then message, then submit.
 */

$block_data     = include get_template_directory() . '/acf-blocks/block-settings/block-settings.php';
$style_classes  = $block_data['style_classes'];
$block_id       = ! empty($block_data['block_id']) ? 'id="' . esc_attr($block_data['block_id']) . '"' : '';

$block_name = 'contact-form';
array_unshift($style_classes, $block_name);
$style_classes[] = $block_name;

$theme_slugs = array('yellow', 'navy', 'red', 'blue', 'green', 'light-blue', 'light-red', 'black', 'white', 'solheim-black', 'solheim-white');
if (! in_array($theme, $theme_slugs, true)) {
    $theme = 'yellow';
}
$style_classes[] = 'contact-form--accent-' . sanitize_html_class($theme);

$title           = get_field('title');
$form_shortcode  = get_field('form_shortcode');

$title_html = '';
if (is_string($title) && trim($title) !== '') {
    // Textarea with “New Lines”: br — safe HTML for line breaks only.
    $title_html = wp_kses_post($title);
}

$classes = implode(' ', array_filter(array_map('esc_attr', $style_classes)));
?>

<section <?php echo $block_id; ?> class="guten-block <?php echo esc_attr($classes); ?>">
    <div class="container contact-form__inner">
        <div class="contact-form__col contact-form__col--title">
            <?php if ($title_html !== '') : ?>
                <h2 class="contact-form__title"><?php echo $title_html; ?></h2>
            <?php endif; ?>
        </div>
        <div class="contact-form__col contact-form__col--form">
            <?php if (is_string($form_shortcode) && trim($form_shortcode) !== '') : ?>
                <div class="contact-form__form">
                    <?php echo do_shortcode(wp_unslash(trim($form_shortcode))); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
