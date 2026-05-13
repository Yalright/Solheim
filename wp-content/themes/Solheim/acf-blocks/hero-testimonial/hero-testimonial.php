<?php
/**
 * Block - Hero - Testimonial
 *
 * ACF (attach field group to this block in WP):
 * - testimonials (repeater)
 *   - theme (select or colour — flourish for quote marks + author)
 *   - image (image, full-width cover background)
 *   - quote (textarea)
 *   - author (text)
 *   - subtitle (text)
 */

$block_data     = include get_template_directory() . '/acf-blocks/block-settings/block-settings.php';
$style_classes  = $block_data['style_classes'];
$block_id       = ! empty($block_data['block_id']) ? 'id="' . esc_attr($block_data['block_id']) . '"' : '';

$block_name = 'hero-testimonial';
array_unshift($style_classes, $block_name);
$style_classes[] = $block_name;

if (! function_exists('solheim_hero_testimonial_icon_quote_svg')) {
    /**
     * @return string Raw SVG markup (trusted theme asset) with fill set to currentColor.
     */
    function solheim_hero_testimonial_icon_quote_svg()
    {
        static $cached = null;
        if ($cached !== null) {
            return $cached;
        }
        $path = get_template_directory() . '/assets/images/icon-quote.svg';
        if (! is_readable($path)) {
            $cached = '';
            return $cached;
        }
        $raw     = file_get_contents($path);
        $cached = is_string($raw) ? str_replace('fill="#FB5F86"', 'fill="currentColor"', $raw) : '';
        return $cached;
    }
}

if (! function_exists('solheim_hero_testimonial_theme_attrs')) {
    /**
     * Map ACF theme value to modifier class or inline CSS variable (hex colour).
     *
     * @return array{class:string, style:string} style is safe for HTML attribute (escaped by caller).
     */
    function solheim_hero_testimonial_theme_attrs($theme_raw)
    {
        $default_class = 'hero-testimonial__slide--theme-light-red';
        if (is_array($theme_raw) && isset($theme_raw['color']) && is_string($theme_raw['color'])) {
            $theme_raw = $theme_raw['color'];
        }
        if (! is_string($theme_raw)) {
            return array('class' => $default_class, 'style' => '');
        }
        $t = trim($theme_raw);
        if ($t === '') {
            return array('class' => $default_class, 'style' => '');
        }
        if (preg_match('/^#[0-9A-Fa-f]{3,8}$/', $t)) {
            return array(
                'class' => '',
                'style' => '--hero-testimonial-flourish:' . $t . ';',
            );
        }
        $slug = strtolower(str_replace('_', '-', $t));
        $slug = preg_replace('/^has-/', '', $slug);
        $allowed = array('light-red', 'red', 'navy', 'blue', 'light-blue', 'yellow', 'green', 'white', 'black');
        if (! in_array($slug, $allowed, true)) {
            $slug = 'light-red';
        }
        return array(
            'class' => 'hero-testimonial__slide--theme-' . $slug,
            'style' => '',
        );
    }
}

$slides = array();
if (have_rows('testimonials')) {
    while (have_rows('testimonials')) {
        the_row();
        $theme_raw = get_sub_field('theme');
        $image     = get_sub_field('image');
        $quote     = get_sub_field('quote');
        $author    = get_sub_field('author');
        $subtitle  = get_sub_field('subtitle');

        $quote    = is_string($quote) ? trim($quote) : '';
        $author   = is_string($author) ? trim($author) : '';
        $subtitle = is_string($subtitle) ? trim($subtitle) : '';

        $image_url = is_array($image) && ! empty($image['url']) ? $image['url'] : '';
        $image_alt = is_array($image) && isset($image['alt']) ? (string) $image['alt'] : '';

        if ($quote === '' && $author === '' && $subtitle === '' && $image_url === '') {
            continue;
        }

        $theme_attrs = solheim_hero_testimonial_theme_attrs($theme_raw);

        $slides[] = array(
            'theme_class' => $theme_attrs['class'],
            'theme_style' => $theme_attrs['style'],
            'image_url'   => $image_url,
            'image_alt'   => $image_alt,
            'quote'       => $quote,
            'author'      => $author,
            'subtitle'    => $subtitle,
        );
    }
}

$slide_count = count($slides);

$classes    = implode(' ', array_filter(array_map('esc_attr', $style_classes)));
$icon_svg   = solheim_hero_testimonial_icon_quote_svg();
?>

<?php if ($slide_count > 0) : ?>
<section <?php echo $block_id; ?> class="guten-block <?php echo esc_attr($classes); ?>">
    <?php if ($slide_count > 1) : ?>
        <div class="hero-testimonial__nav" role="group" aria-label="<?php esc_attr_e('Testimonial carousel', 'solheim'); ?>">
            <button
                class="hero-testimonial__nav-btn hero-testimonial__nav-btn--prev"
                type="button"
                aria-label="<?php esc_attr_e('Previous testimonial', 'solheim'); ?>"
            >
                <span class="hero-testimonial__nav-icon" aria-hidden="true">&lsaquo;</span>
            </button>
            <button
                class="hero-testimonial__nav-btn hero-testimonial__nav-btn--next"
                type="button"
                aria-label="<?php esc_attr_e('Next testimonial', 'solheim'); ?>"
            >
                <span class="hero-testimonial__nav-icon" aria-hidden="true">&rsaquo;</span>
            </button>
        </div>
    <?php endif; ?>

    <div class="splide hero-testimonial__splide" data-hero-testimonial-slider>
        <div class="splide__track">
            <ul class="splide__list">
                <?php foreach ($slides as $row) : ?>
                    <?php
                    $slide_classes = array(
                        'splide__slide',
                        'hero-testimonial__slide',
                    );
                    if ($row['theme_class'] !== '') {
                        $slide_classes[] = $row['theme_class'];
                    }
                    $slide_style = $row['theme_style'] !== '' ? $row['theme_style'] : '';
                    ?>
                    <li
                        class="<?php echo esc_attr(implode(' ', $slide_classes)); ?>"
                        <?php echo $slide_style !== '' ? ' style="' . esc_attr($slide_style) . '"' : ''; ?>
                    >
                        <?php if ($row['image_url'] !== '') : ?>
                            <div
                                class="hero-testimonial__bg"
                                style="<?php echo esc_attr('background-image:url(' . esc_url($row['image_url']) . ');'); ?>"
                                role="img"
                                <?php echo $row['image_alt'] !== '' ? ' aria-label="' . esc_attr($row['image_alt']) . '"' : ' aria-hidden="true"'; ?>
                            ></div>
                        <?php endif; ?>

                        <div class="hero-testimonial__scrim" aria-hidden="true"></div>

                        <div class="hero-testimonial__inner">
                            <div class="container">
                                <div class="hero-testimonial__content">
                                    <?php if ($icon_svg !== '') : ?>
                                        <div class="hero-testimonial__quote-open" aria-hidden="true">
                                            <span class="hero-testimonial__quote-icon hero-testimonial__quote-icon--open">
                                                <?php echo $icon_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted theme SVG asset. ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($row['quote'] !== '') : ?>
                                        <blockquote class="hero-testimonial__quote">
                                            <p class="hero-testimonial__quote-text">
                                                <?php echo nl2br(esc_html($row['quote'])); ?>
                                                <?php if ($icon_svg !== '') : ?>
                                                    <span class="hero-testimonial__quote-icon hero-testimonial__quote-icon--close" aria-hidden="true">
                                                        <?php echo $icon_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                                    </span>
                                                <?php endif; ?>
                                            </p>
                                        </blockquote>
                                    <?php endif; ?>

                                    <?php if ($row['author'] !== '') : ?>
                                        <cite class="hero-testimonial__author"><?php echo esc_html($row['author']); ?></cite>
                                    <?php endif; ?>

                                    <?php if ($row['subtitle'] !== '') : ?>
                                        <p class="hero-testimonial__subtitle"><?php echo nl2br(esc_html($row['subtitle'])); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</section>
<?php endif; ?>
