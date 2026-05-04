<?php
/**
 * Block - Team Promo
 */

$block_data     = include get_template_directory() . '/acf-blocks/block-settings/block-settings.php';
$style_classes  = $block_data['style_classes'];
$block_id       = ! empty($block_data['block_id']) ? 'id="' . esc_attr($block_data['block_id']) . '"' : '';

$block_name = 'team-promo';
array_unshift($style_classes, $block_name);
$style_classes[] = $block_name;

$theme    = get_field('theme');
$title    = get_field('title');
$image    = get_field('image');
$subtitle = get_field('subtitle');
$content  = get_field('content');
$cta      = get_field('cta');

$theme = is_string($theme) ? sanitize_html_class(trim($theme)) : 'blue';
if ($theme === '') {
    $theme = 'blue';
}
$style_classes[] = 'team-promo--theme-' . $theme;

$title = is_string($title) ? trim($title) : '';

$image_url = is_array($image) && ! empty($image['url']) ? $image['url'] : '';
$image_alt = is_array($image) && ! empty($image['alt']) ? (string) $image['alt'] : '';

$subtitle = is_string($subtitle) ? trim($subtitle) : '';
$content  = is_string($content) ? trim($content) : '';

$has_cta = is_array($cta) && ! empty($cta['url']);

$cta_btn_class = $theme === 'blue' ? 'btn-yellow-navy' : 'btn-navy';

$captains_slides = array();
if (have_rows('captains')) {
    while (have_rows('captains')) {
        the_row();
        $primary_image   = get_sub_field('primary_image');
        $secondary_image = get_sub_field('secondary_image');

        $secondary_url = is_array($secondary_image) && ! empty($secondary_image['url']) ? $secondary_image['url'] : '';
        if ($secondary_url === '') {
            continue;
        }

        $primary_url = is_array($primary_image) && ! empty($primary_image['url']) ? $primary_image['url'] : '';
        $secondary_alt = is_array($secondary_image) && isset($secondary_image['alt']) ? (string) $secondary_image['alt'] : '';

        $captains_slides[] = array(
            'primary_url'   => $primary_url,
            'secondary_url' => $secondary_url,
            'secondary_alt' => $secondary_alt,
        );
    }
}

$has_captains_slides = count($captains_slides) > 0;

$captains_slides_json = array();
foreach ($captains_slides as $row) {
    $captains_slides_json[] = array(
        'primary'   => $row['primary_url'],
        'secondary' => $row['secondary_url'],
        'alt'       => $row['secondary_alt'],
    );
}
$captains_slides_attr = wp_json_encode($captains_slides_json);

$captains_n       = count($captains_slides);
$captains_idx_pre = $captains_n > 1 ? $captains_n - 1 : 0;
$captains_idx_cur = 0;
$captains_idx_nex = $captains_n > 1 ? 1 : 0;

$classes = implode(' ', array_filter(array_map('esc_attr', $style_classes)));
?>

<section <?php echo $block_id; ?> class="guten-block <?php echo esc_attr($classes); ?>">
    <div class="team-promo__main<?php echo $has_captains_slides ? ' team-promo__main--with-captains' : ''; ?>">
        <div class="team-promo__primary">
            <?php if ($title !== '') : ?>
                <div class="team-promo__title-bar">
                    <h2 class="team-promo__title">
                        <span class="team-promo__star" aria-hidden="true"></span>
                        <span class="team-promo__title-text"><?php echo esc_html($title); ?></span>
                        <span class="team-promo__star" aria-hidden="true"></span>
                    </h2>
                </div>
            <?php endif; ?>

            <div class="team-promo__primary-body">
                <div class="team-promo__image-col">
                    <?php if ($image_url !== '') : ?>
                        <div
                            class="team-promo__image"
                            style="background-image:url(<?php echo esc_url($image_url); ?>);"
                            <?php if ($image_alt !== '') : ?>
                                role="img"
                                aria-label="<?php echo esc_attr($image_alt); ?>"
                            <?php else : ?>
                                aria-hidden="true"
                            <?php endif; ?>
                        ></div>
                    <?php endif; ?>
                </div>

                <div class="team-promo__content-col">
                    <div class="team-promo__content-inner">
                        <?php if ($subtitle !== '') : ?>
                            <h3 class="team-promo__subtitle"><?php echo esc_html($subtitle); ?></h3>
                        <?php endif; ?>

                        <?php if ($content !== '') : ?>
                            <div class="team-promo__content"><?php echo wp_kses_post($content); ?></div>
                        <?php endif; ?>

                        <?php if ($has_cta) : ?>
                            <a
                                class="<?php echo esc_attr($cta_btn_class); ?> team-promo__cta"
                                href="<?php echo esc_url($cta['url']); ?>"
                                <?php echo ! empty($cta['target']) ? ' target="' . esc_attr($cta['target']) . '"' : ''; ?>
                                <?php echo ! empty($cta['target']) && $cta['target'] === '_blank' ? ' rel="noopener noreferrer"' : ''; ?>
                            >
                                <?php echo esc_html($cta['title'] !== '' ? $cta['title'] : $cta['url']); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($has_captains_slides) : ?>
            <div
                class="team-promo__captains"
                data-team-promo-captains
                data-team-promo-slides="<?php echo esc_attr($captains_slides_attr); ?>"
            >
                <div class="team-promo__captains-decor" aria-hidden="true"></div>

                <?php if ($title !== '') : ?>
                    <h3 class="team-promo__captains-heading">
                        <span class="team-promo__captains-heading-title"><?php echo esc_html($title); ?></span><br />
                        <span class="team-promo__captains-heading-label"><?php esc_html_e('Captains', 'solheim'); ?></span>
                    </h3>
                <?php else : ?>
                    <h3 class="team-promo__captains-heading">
                        <span class="team-promo__captains-heading-label"><?php esc_html_e('Captains', 'solheim'); ?></span>
                    </h3>
                <?php endif; ?>

                <div class="team-promo__captains-panel">
                    <div
                        class="team-promo__captains-primary"
                        data-team-promo-primary
                        <?php if ($captains_slides[0]['primary_url'] !== '') : ?>
                            style="background-image:url(<?php echo esc_url($captains_slides[0]['primary_url']); ?>);"
                        <?php endif; ?>
                    ></div>

                    <div
                        class="team-promo__captains-thumbs<?php echo $captains_n < 2 ? ' team-promo__captains-thumbs--single' : ''; ?>"
                        role="tablist"
                        aria-label="<?php esc_attr_e('Team captains', 'solheim'); ?>"
                    >
                        <button
                            type="button"
                            class="team-promo__thumb team-promo__thumb--prev"
                            data-team-promo-thumb="prev"
                            role="tab"
                            tabindex="<?php echo $captains_n > 1 ? '0' : '-1'; ?>"
                            aria-selected="false"
                            aria-label="<?php esc_attr_e('Previous captain', 'solheim'); ?>"
                        >
                            <img
                                src="<?php echo esc_url($captains_slides[ $captains_idx_pre ]['secondary_url']); ?>"
                                alt="<?php echo esc_attr($captains_slides[ $captains_idx_pre ]['secondary_alt']); ?>"
                                loading="eager"
                                decoding="async"
                            />
                        </button>
                        <button
                            type="button"
                            class="team-promo__thumb team-promo__thumb--active is-active"
                            data-team-promo-thumb="active"
                            role="tab"
                            tabindex="0"
                            aria-selected="true"
                            aria-label="<?php esc_attr_e('Current captain', 'solheim'); ?>"
                        >
                            <img
                                src="<?php echo esc_url($captains_slides[ $captains_idx_cur ]['secondary_url']); ?>"
                                alt="<?php echo esc_attr($captains_slides[ $captains_idx_cur ]['secondary_alt']); ?>"
                                loading="eager"
                                decoding="async"
                            />
                        </button>
                        <button
                            type="button"
                            class="team-promo__thumb team-promo__thumb--next"
                            data-team-promo-thumb="next"
                            role="tab"
                            tabindex="<?php echo $captains_n > 1 ? '0' : '-1'; ?>"
                            aria-selected="false"
                            aria-label="<?php esc_attr_e('Next captain', 'solheim'); ?>"
                        >
                            <img
                                src="<?php echo esc_url($captains_slides[ $captains_idx_nex ]['secondary_url']); ?>"
                                alt="<?php echo esc_attr($captains_slides[ $captains_idx_nex ]['secondary_alt']); ?>"
                                loading="eager"
                                decoding="async"
                            />
                        </button>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
