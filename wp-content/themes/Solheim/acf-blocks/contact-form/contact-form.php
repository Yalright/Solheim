<?php
/**
 * Block - Contact Form
 *
 * ACF: style (style-1 | style-2), theme (accent), title (textarea / br), form_shortcode (CF7), image + content (style-2).
 * style-1: title column + form column inside .container (unchanged).
 * style-2: full-viewport-height image left (no vertical padding on block); title, WYSIWYG + CF7 in right column (.container on right only).
 */

$block_data     = include get_template_directory() . '/acf-blocks/block-settings/block-settings.php';
$style_classes  = $block_data['style_classes'];
$block_id       = ! empty($block_data['block_id']) ? 'id="' . esc_attr($block_data['block_id']) . '"' : '';

$block_name = 'contact-form';
array_unshift($style_classes, $block_name);
$style_classes[] = $block_name;

$style_raw = get_field('style');
$layout    = (is_string($style_raw) && trim($style_raw) === 'style-2') ? 'style-2' : 'style-1';
$style_classes[] = 'contact-form--' . $layout;

$theme = get_field('theme');
$theme = is_string($theme) ? strtolower(trim($theme)) : 'yellow';

$theme_slugs = array('yellow', 'navy', 'red', 'blue', 'green', 'light-blue', 'light-red', 'black', 'white', 'solheim-black', 'solheim-white');
if (! in_array($theme, $theme_slugs, true)) {
    $theme = 'yellow';
}
$style_classes[] = 'contact-form--accent-' . sanitize_html_class($theme);

$title          = get_field('title');
$form_shortcode = get_field('form_shortcode');
$image          = get_field('image');
$content        = get_field('content');

$title_html = '';
if (is_string($title) && trim($title) !== '') {
    $title_html = wp_kses_post($title);
}

$image_url = is_array($image) && ! empty($image['url']) ? $image['url'] : '';
$image_alt = is_array($image) && isset($image['alt']) ? (string) $image['alt'] : '';

$content_html = '';
if ($layout === 'style-2' && is_string($content) && trim($content) !== '') {
    $content_html = $content;
}

$classes = implode(' ', array_filter(array_map('esc_attr', $style_classes)));
?>

<section <?php echo $block_id; ?> class="guten-block <?php echo esc_attr($classes); ?>">
    <?php if ($layout === 'style-2') : ?>
        <div class="contact-form__split">
            <?php if ($image_url !== '') : ?>
                <div class="contact-form__split-media">
                    <img
                        class="contact-form__split-img"
                        src="<?php echo esc_url($image_url); ?>"
                        alt="<?php echo esc_attr($image_alt); ?>"
                        loading="lazy"
                        decoding="async"
                    />
                </div>
            <?php endif; ?>

            <div class="contact-form__split-main<?php echo $image_url === '' ? ' contact-form__split-main--full' : ''; ?>">
                <div class="container contact-form__split-main-inner">
                    <?php if ($title_html !== '') : ?>
                        <h2 class="contact-form__title"><?php echo $title_html; ?></h2>
                    <?php endif; ?>

                    <?php if ($content_html !== '') : ?>
                        <div class="contact-form__body">
                            <?php echo wp_kses_post($content_html); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (is_string($form_shortcode) && trim($form_shortcode) !== '') : ?>
                        <div class="contact-form__form">
                            <?php echo do_shortcode(wp_unslash(trim($form_shortcode))); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php else : ?>
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
    <?php endif; ?>
</section>
