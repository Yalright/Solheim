<?php
/**
 * Block - Hero - Team
 */

$block_data     = include get_template_directory() . '/acf-blocks/block-settings/block-settings.php';
$style_classes  = $block_data['style_classes'];
$block_id       = ! empty($block_data['block_id']) ? 'id="' . esc_attr($block_data['block_id']) . '"' : '';

$block_name = 'hero-team';
array_unshift($style_classes, $block_name);
$style_classes[] = $block_name;

$theme = get_field('theme');
$theme = is_string($theme) ? strtolower(trim($theme)) : 'red';
if (! in_array($theme, array('red', 'navy'), true)) {
    $theme = 'red';
}
$style_classes[] = 'hero-team--theme-' . sanitize_html_class($theme);

$image    = get_field('image');
$title    = get_field('title');
$subtitle = get_field('subtitle');
$cta      = get_field('cta');

$title    = is_string($title) ? trim($title) : '';
$subtitle = is_string($subtitle) ? trim($subtitle) : '';

$image_url = is_array($image) && ! empty($image['url']) ? $image['url'] : '';
$image_alt = is_array($image) && isset($image['alt']) ? (string) $image['alt'] : '';

if ($image_url !== '') {
    $style_classes[] = 'hero-team--has-image';
}

$has_cta = is_array($cta) && ! empty($cta['url']);
$cta_class = $theme === 'navy' ? 'btn-navy' : 'btn-red-white';

$classes = implode(' ', array_filter(array_map('esc_attr', $style_classes)));

$section_style = $image_url !== '' ? 'background-image:url(' . esc_url($image_url) . ');' : '';
?>

<section
    <?php echo $block_id; ?>
    class="guten-block <?php echo esc_attr($classes); ?>"
    <?php echo $section_style !== '' ? ' style="' . esc_attr($section_style) . '"' : ''; ?>
>
    <?php if ($image_url !== '' && $image_alt !== '') : ?>
        <span class="hero-team__image-desc"><?php echo esc_html($image_alt); ?></span>
    <?php endif; ?>

    <div class="hero-team__grid">
        <div class="hero-team__content">
            <?php if ($title !== '') : ?>
                <h2 class="hero-team__title">
                    <span class="hero-team__star" aria-hidden="true"></span>
                    <span class="hero-team__title-text"><?php echo esc_html($title); ?></span>
                    <span class="hero-team__star" aria-hidden="true"></span>
                </h2>
            <?php endif; ?>

            <?php if ($subtitle !== '') : ?>
                <p class="hero-team__subtitle"><?php echo esc_html($subtitle); ?></p>
            <?php endif; ?>

            <?php if ($has_cta) : ?>
                <a
                    class="hero-team__cta <?php echo esc_attr($cta_class); ?>"
                    href="<?php echo esc_url($cta['url']); ?>"
                    <?php echo ! empty($cta['target']) ? ' target="' . esc_attr($cta['target']) . '"' : ''; ?>
                    <?php echo ! empty($cta['target']) && $cta['target'] === '_blank' ? ' rel="noopener noreferrer"' : ''; ?>
                >
                    <?php echo esc_html($cta['title'] !== '' ? $cta['title'] : $cta['url']); ?>
                </a>
            <?php endif; ?>
        </div>

        <div class="hero-team__aside" aria-hidden="true"></div>
    </div>
</section>
