<?php
/**
 * Template Name: Directory Entry
 * Template Post Type: page
 *
 * Expects ACF on the page (field names): title (text), logo (image array),
 * left_entry / right_entry (groups) each with image (array) and cta (link array).
 */
if (!defined('ABSPATH')) {
    exit;
}

$title = get_field('title');
$title = is_string($title) ? trim($title) : '';

$logo = get_field('logo');
$logo = is_array($logo) ? $logo : null;
$logo_url = $logo && !empty($logo['url']) ? $logo['url'] : '';
$logo_alt = $logo && !empty($logo['alt']) ? $logo['alt'] : '';

$left_entry = get_field('left_entry');
$left_entry = is_array($left_entry) ? $left_entry : [];
$left_image = is_array($left_entry['image'] ?? null) ? $left_entry['image'] : null;
$left_image_url = $left_image && !empty($left_image['url']) ? $left_image['url'] : '';
$left_cta = is_array($left_entry['cta'] ?? null) ? $left_entry['cta'] : null;
$left_cta_url = $left_cta && !empty($left_cta['url']) ? $left_cta['url'] : '';
$left_cta_title = $left_cta && !empty($left_cta['title']) ? trim((string) $left_cta['title']) : '';
$left_cta_target = $left_cta && !empty($left_cta['target']) ? $left_cta['target'] : '';

$right_entry = get_field('right_entry');
$right_entry = is_array($right_entry) ? $right_entry : [];
$right_image = is_array($right_entry['image'] ?? null) ? $right_entry['image'] : null;
$right_image_url = $right_image && !empty($right_image['url']) ? $right_image['url'] : '';
$right_cta = is_array($right_entry['cta'] ?? null) ? $right_entry['cta'] : null;
$right_cta_url = $right_cta && !empty($right_cta['url']) ? $right_cta['url'] : '';
$right_cta_title = $right_cta && !empty($right_cta['title']) ? trim((string) $right_cta['title']) : '';
$right_cta_target = $right_cta && !empty($right_cta['target']) ? $right_cta['target'] : '';

get_header();
?>

<main class="site-main template-directory-entry">
    <section class="directory-entry" aria-label="<?php echo esc_attr($title !== '' ? $title : get_the_title()); ?>">
        <div class="directory-entry__split">
            <div class="directory-entry__half directory-entry__half--left">
                <?php if (!empty($left_image_url)) : ?>
                    <img
                        class="directory-entry__image"
                        src="<?php echo esc_url($left_image_url); ?>"
                        alt=""
                        loading="eager"
                        decoding="async"
                    />
                <?php endif; ?>

                <?php if (!empty($left_cta_url) && $left_cta_title !== '') : ?>
                    <div class="directory-entry__cta-wrap">
                        <a
                            class="directory-entry__cta landing-page-button landing-page-button--white-outline-trans"
                            href="<?php echo esc_url($left_cta_url); ?>"
                            <?php echo $left_cta_target !== '' ? ' target="' . esc_attr($left_cta_target) . '"' : ''; ?>
                            <?php echo $left_cta_target === '_blank' ? ' rel="noopener noreferrer"' : ''; ?>
                        ><?php echo esc_html($left_cta_title); ?></a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="directory-entry__half directory-entry__half--right">
                <?php if (!empty($right_image_url)) : ?>
                    <img
                        class="directory-entry__image"
                        src="<?php echo esc_url($right_image_url); ?>"
                        alt=""
                        loading="eager"
                        decoding="async"
                    />
                <?php endif; ?>

                <?php if (!empty($right_cta_url) && $right_cta_title !== '') : ?>
                    <div class="directory-entry__cta-wrap">
                        <a
                            class="directory-entry__cta landing-page-button landing-page-button--white-outline-trans"
                            href="<?php echo esc_url($right_cta_url); ?>"
                            <?php echo $right_cta_target !== '' ? ' target="' . esc_attr($right_cta_target) . '"' : ''; ?>
                            <?php echo $right_cta_target === '_blank' ? ' rel="noopener noreferrer"' : ''; ?>
                        ><?php echo esc_html($right_cta_title); ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($title !== '' || !empty($logo_url)) : ?>
            <div class="directory-entry__center">
                <?php if ($title !== '') : ?>
                    <h1 class="directory-entry__title"><?php echo esc_html($title); ?></h1>
                <?php endif; ?>
                <?php if (!empty($logo_url)) : ?>
                    <div class="directory-entry__logo">
                        <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($logo_alt); ?>" />
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </section>

    <?php
    while (have_posts()) {
        the_post();
        if (get_the_content()) {
            the_content();
        }
    }
    ?>
</main>

<?php
get_footer();
