<?php
/**
 * Template Name: Landing Page
 * Template Post Type: page
 */
if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main class="site-main template-landing-page">
    <section class="landing-page-primary">
        <div class="landing-page-primary-inner">
            <div class="container">
                <?php
                wp_nav_menu(array(
                    'theme_location'  => 'landing-page-nav',
                    'menu_class'      => 'landing-page-nav',
                    'container'       => 'nav',
                    'container_class' => 'landing-page-nav-wrapper',
                ));
                ?>
            </div>
            <div class="container landing-page-logo-container">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo-solheim-landing.svg'); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
            </div>
        </div>
    </section>

    <?php
    $section_1 = get_field('section_1');
    $hero_video = is_array($section_1) && !empty($section_1['hero_video']) ? $section_1['hero_video'] : null;
    $hero_image = is_array($section_1) && !empty($section_1['hero_image']) ? $section_1['hero_image'] : null;
    $section_logo = is_array($section_1) && !empty($section_1['logo']) ? $section_1['logo'] : null;

    $hero_video_url = is_array($hero_video) && !empty($hero_video['url']) ? $hero_video['url'] : '';
    $hero_video_mime = is_array($hero_video) && !empty($hero_video['mime_type']) ? $hero_video['mime_type'] : '';
    $hero_image_url = is_array($hero_image) && !empty($hero_image['url']) ? $hero_image['url'] : '';
    $section_logo_url = is_array($section_logo) && !empty($section_logo['url']) ? $section_logo['url'] : '';
    $section_logo_alt = is_array($section_logo) && !empty($section_logo['alt']) ? $section_logo['alt'] : '';
    ?>

    <section class="section-1 landing-page-section-1">
        <?php if (!empty($hero_image_url)) : ?>
            <img class="landing-page-section-1-fallback-image" src="<?php echo esc_url($hero_image_url); ?>" alt="" />
        <?php endif; ?>

        <?php if (!empty($hero_video_url)) : ?>
            <video
                class="landing-page-section-1-video"
                autoplay
                muted
                loop
                playsinline
                preload="metadata"
                <?php echo !empty($hero_image_url) ? 'poster="' . esc_url($hero_image_url) . '"' : ''; ?>
            >
                <source src="<?php echo esc_url($hero_video_url); ?>" <?php echo !empty($hero_video_mime) ? 'type="' . esc_attr($hero_video_mime) . '"' : ''; ?> />
            </video>
        <?php endif; ?>

        <?php if (!empty($section_logo_url)) : ?>
            <div class="landing-page-section-1-logo">
                <img src="<?php echo esc_url($section_logo_url); ?>" alt="<?php echo esc_attr($section_logo_alt); ?>" />
            </div>
        <?php endif; ?>
    </section>

    <?php
    $section_2 = get_field('section_2');
    $section_splitter_logo = is_array($section_2) && !empty($section_2['section_splitter_logo']) ? $section_2['section_splitter_logo'] : null;
    $section_logo = is_array($section_2) && !empty($section_2['logo']) ? $section_2['logo'] : null;
    $subtitle = is_array($section_2) && !empty($section_2['subtitle']) ? $section_2['subtitle'] : '';
    $hero_image = is_array($section_2) && !empty($section_2['hero_image']) ? $section_2['hero_image'] : null;

    $section_splitter_logo_url = is_array($section_splitter_logo) && !empty($section_splitter_logo['url']) ? $section_splitter_logo['url'] : '';
    $section_splitter_logo_alt = is_array($section_splitter_logo) && !empty($section_splitter_logo['alt']) ? $section_splitter_logo['alt'] : '';

    $section_logo_url = is_array($section_logo) && !empty($section_logo['url']) ? $section_logo['url'] : '';
    $section_logo_alt = is_array($section_logo) && !empty($section_logo['alt']) ? $section_logo['alt'] : '';

    $hero_image_url = is_array($hero_image) && !empty($hero_image['url']) ? $hero_image['url'] : '';
    ?>

    <section class="section-2 landing-page-section-2">
        <?php if (!empty($hero_image_url)) : ?>
            <img class="landing-page-section-2-hero-image" src="<?php echo esc_url($hero_image_url); ?>" alt="" />
        <?php endif; ?>

        <?php if (!empty($section_splitter_logo_url)) : ?>
            <div class="landing-page-section-2-splitter-logo">
                <img src="<?php echo esc_url($section_splitter_logo_url); ?>" alt="<?php echo esc_attr($section_splitter_logo_alt); ?>" />
            </div>
        <?php endif; ?>

        <div class="landing-page-section-2-inner">
            <?php if (!empty($section_logo_url)) : ?>
                <div class="landing-page-section-2-logo">
                    <img src="<?php echo esc_url($section_logo_url); ?>" alt="<?php echo esc_attr($section_logo_alt); ?>" />
                </div>
            <?php endif; ?>

            <?php if (!empty($subtitle)) : ?>
                <div class="landing-page-section-2-subtitle">
                    <?php echo wp_kses_post($subtitle); ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php
    $section_3 = get_field('section_3');
    $couples_image = is_array($section_3) && !empty($section_3['couples_image']) ? $section_3['couples_image'] : null;
    $vendor_image = is_array($section_3) && !empty($section_3['vendor_image']) ? $section_3['vendor_image'] : null;
    $subtitle_3 = is_array($section_3) && !empty($section_3['subtitle']) ? $section_3['subtitle'] : '';
    $section_logo_3 = is_array($section_3) && !empty($section_3['logo']) ? $section_3['logo'] : null;

    $couples_image_url = is_array($couples_image) && !empty($couples_image['url']) ? $couples_image['url'] : '';
    $vendor_image_url = is_array($vendor_image) && !empty($vendor_image['url']) ? $vendor_image['url'] : '';

    $section_logo_3_url = is_array($section_logo_3) && !empty($section_logo_3['url']) ? $section_logo_3['url'] : '';
    $section_logo_3_alt = is_array($section_logo_3) && !empty($section_logo_3['alt']) ? $section_logo_3['alt'] : '';
    ?>

    <section class="section-3 landing-page-section-3">
        <?php if (!empty($subtitle_3) || !empty($section_logo_3_url)) : ?>
            <div class="landing-page-section-3-center">
                <?php if (!empty($subtitle_3)) : ?>
                    <div class="landing-page-section-3-center-subtitle">
                        <?php echo wp_kses_post($subtitle_3); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($section_logo_3_url)) : ?>
                    <div class="landing-page-section-3-center-logo">
                        <img src="<?php echo esc_url($section_logo_3_url); ?>" alt="<?php echo esc_attr($section_logo_3_alt); ?>" />
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="landing-page-section-3-halves">
            <div class="landing-page-section-3-half landing-page-section-3-half--couples">
                <?php if (!empty($couples_image_url)) : ?>
                    <img class="landing-page-section-3-bg" src="<?php echo esc_url($couples_image_url); ?>" alt="" />
                <?php endif; ?>

                <div class="landing-page-section-3-content">
                    <a class="landing-page-button landing-page-button--white-outline-trans" href="/couples">COUPLES</a>
                    <a class="landing-page-button landing-page-button--minimal-white" href="/login">LOGIN</a>
                </div>
            </div>

            <div class="landing-page-section-3-half landing-page-section-3-half--vendors">
                <?php if (!empty($vendor_image_url)) : ?>
                    <img class="landing-page-section-3-bg" src="<?php echo esc_url($vendor_image_url); ?>" alt="" />
                <?php endif; ?>

                <div class="landing-page-section-3-content">
                    <a class="landing-page-button landing-page-button--white-outline-trans" href="/vendors">VENDORS</a>
                    <a class="landing-page-button landing-page-button--minimal-white" href="/login">LOGIN</a>
                </div>
            </div>
        </div>
    </section>

    <?php
    while (have_posts()) {
        the_post();
        the_content();
    }
    ?>
</main>

<?php
get_footer();

