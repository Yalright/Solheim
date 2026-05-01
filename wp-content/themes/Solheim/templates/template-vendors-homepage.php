<?php
/**
 * Template Name: Vendors - Homepage
 * Template Post Type: page
 */
if (!defined('ABSPATH')) {
    exit;
}

$hero = get_field('hero');
$background_video = is_array($hero) && !empty($hero['background_video']) ? $hero['background_video'] : null;
$background_image = is_array($hero) && !empty($hero['background_image']) ? $hero['background_image'] : null;

$text_line_1 = is_array($hero) && !empty($hero['text_line_1']) ? $hero['text_line_1'] : '';
$text_line_2_pre = is_array($hero) && !empty($hero['text_line_2_-_pre']) ? $hero['text_line_2_-_pre'] : '';
$text_line_2_main = is_array($hero) && !empty($hero['text_line_2_-_main']) ? $hero['text_line_2_-_main'] : '';
$text_line_2_post = is_array($hero) && !empty($hero['text_line_2_-_post']) ? $hero['text_line_2_-_post'] : '';
$text_line_3 = is_array($hero) && !empty($hero['text_line_3']) ? $hero['text_line_3'] : '';

$background_video_url = is_array($background_video) && !empty($background_video['url']) ? $background_video['url'] : '';
$background_video_mime = is_array($background_video) && !empty($background_video['mime_type']) ? $background_video['mime_type'] : '';
$background_image_url = is_array($background_image) && !empty($background_image['url']) ? $background_image['url'] : '';
?>

<?php get_header(); ?>

<main class="site-main template-vendors-homepage">
    <section class="couples-home-hero">
        <?php if (!empty($background_image_url)) : ?>
            <img class="couples-home-hero-fallback-image" src="<?php echo esc_url($background_image_url); ?>" alt="" />
        <?php endif; ?>

        <?php if (!empty($background_video_url)) : ?>
            <video
                class="couples-home-hero-video"
                autoplay
                muted
                loop
                playsinline
                preload="metadata"
                <?php echo !empty($background_image_url) ? 'poster="' . esc_url($background_image_url) . '"' : ''; ?>
            >
                <source src="<?php echo esc_url($background_video_url); ?>" <?php echo !empty($background_video_mime) ? 'type="' . esc_attr($background_video_mime) . '"' : ''; ?> />
            </video>
        <?php endif; ?>

        <div class="container couples-home-hero-content">
            <?php if (!empty($text_line_1)) : ?>
                <div class="couples-home-hero-line couples-home-hero-line-1"><?php echo esc_html($text_line_1); ?></div>
            <?php endif; ?>

            <?php if (!empty($text_line_2_pre) || !empty($text_line_2_main) || !empty($text_line_2_post)) : ?>
                <div class="couples-home-hero-line couples-home-hero-line-2">
                    <?php if (!empty($text_line_2_pre)) : ?>
                        <span><?php echo esc_html($text_line_2_pre); ?></span>
                    <?php endif; ?>

                    <?php if (!empty($text_line_2_main)) : ?>
                        <span class="couples-home-hero-line-2-main"><?php echo nl2br(wp_kses($text_line_2_main, array('br' => array()))); ?></span>
                    <?php endif; ?>

                    <?php if (!empty($text_line_2_post)) : ?>
                        <span><?php echo esc_html($text_line_2_post); ?></span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($text_line_3)) : ?>
                <div class="couples-home-hero-line couples-home-hero-line-3"><?php echo esc_html($text_line_3); ?></div>
            <?php endif; ?>
        </div>
    </section>

    <?php
    $section_split_1 = get_field('section_split_1');
    $content_1 = is_array($section_split_1) && !empty($section_split_1['content_1']) ? $section_split_1['content_1'] : '';
    $cta_1 = is_array($section_split_1) && !empty($section_split_1['cta_1']) ? $section_split_1['cta_1'] : null;
    $content_2 = is_array($section_split_1) && !empty($section_split_1['content_2']) ? $section_split_1['content_2'] : '';
    $cta_2 = is_array($section_split_1) && !empty($section_split_1['cta_2']) ? $section_split_1['cta_2'] : null;

    $cta_1_url = is_array($cta_1) && !empty($cta_1['url']) ? $cta_1['url'] : '';
    $cta_1_title = is_array($cta_1) && !empty($cta_1['title']) ? $cta_1['title'] : '';
    $cta_1_target = is_array($cta_1) && !empty($cta_1['target']) ? $cta_1['target'] : '';

    $cta_2_url = is_array($cta_2) && !empty($cta_2['url']) ? $cta_2['url'] : '';
    $cta_2_title = is_array($cta_2) && !empty($cta_2['title']) ? $cta_2['title'] : '';
    $cta_2_target = is_array($cta_2) && !empty($cta_2['target']) ? $cta_2['target'] : '';
    ?>

    <section class="couples-home-section-split-1">
        <img class="couples-home-section-split-1-bg-logo" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/ll-large-green.svg'); ?>" alt="" />

        <div class="container couples-home-section-split-1-inner">
            <div class="couples-home-section-split-1-col couples-home-section-split-1-col-1">
                <?php if (!empty($content_1)) : ?>
                    <div class="couples-home-section-split-1-content couples-home-section-split-1-content-1">
                        <?php echo wp_kses_post($content_1); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($cta_1_url) && !empty($cta_1_title)) : ?>
                    <a class="couples-home-section-split-1-cta landing-page-button landing-page-button--white-outline-trans" href="<?php echo esc_url($cta_1_url); ?>"<?php echo !empty($cta_1_target) ? ' target="' . esc_attr($cta_1_target) . '"' : ''; ?>>
                        <?php echo $cta_1_title; ?>
                    </a>
                <?php endif; ?>
            </div>

            <div class="couples-home-section-split-1-col couples-home-section-split-1-col-2">
                <div class="couples-home-section-split-1-col-2-bottom">
                    <?php if (!empty($content_2)) : ?>
                        <div class="couples-home-section-split-1-content couples-home-section-split-1-content-2">
                            <?php echo wp_kses_post($content_2); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($cta_2_url) && !empty($cta_2_title)) : ?>
                        <a class="couples-home-section-split-1-cta landing-page-button landing-page-button--white-outline-trans" href="<?php echo esc_url($cta_2_url); ?>"<?php echo !empty($cta_2_target) ? ' target="' . esc_attr($cta_2_target) . '"' : ''; ?>>
                            <?php echo $cta_2_title; ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <?php
    $join_the_club = get_field('join_the_club');
    $join_image = is_array($join_the_club) && !empty($join_the_club['image']) ? $join_the_club['image'] : null;
    $join_video = is_array($join_the_club) && !empty($join_the_club['video']) ? $join_the_club['video'] : null;
    $join_credits = is_array($join_the_club) && !empty($join_the_club['credits']) ? $join_the_club['credits'] : '';

    $join_title = is_array($join_the_club) && !empty($join_the_club['title']) ? $join_the_club['title'] : '';
    $join_content = is_array($join_the_club) && !empty($join_the_club['content']) ? $join_the_club['content'] : '';
    $join_cta = is_array($join_the_club) && !empty($join_the_club['cta']) ? $join_the_club['cta'] : null;
    $join_ending_title = is_array($join_the_club) && !empty($join_the_club['ending_title']) ? $join_the_club['ending_title'] : '';

    $join_image_url = is_array($join_image) && !empty($join_image['url']) ? $join_image['url'] : '';
    $join_video_url = is_array($join_video) && !empty($join_video['url']) ? $join_video['url'] : '';
    $join_video_mime = is_array($join_video) && !empty($join_video['mime_type']) ? $join_video['mime_type'] : '';

    $join_cta_url = is_array($join_cta) && !empty($join_cta['url']) ? $join_cta['url'] : '';
    $join_cta_title = is_array($join_cta) && !empty($join_cta['title']) ? $join_cta['title'] : '';
    $join_cta_target = is_array($join_cta) && !empty($join_cta['target']) ? $join_cta['target'] : '';

    $allowed_title_html = array('br' => array());
    ?>

    <section class="couples-home-join-the-club">
        <div class="container couples-home-join-the-club-inner">
            <div class="couples-home-join-the-club-columns">
            <div class="couples-home-join-the-club-col couples-home-join-the-club-col-left">
                <div class="couples-home-join-the-club-media">
                    <?php if (!empty($join_image_url)) : ?>
                        <img class="couples-home-join-the-club-fallback-image" src="<?php echo esc_url($join_image_url); ?>" alt="" />
                    <?php endif; ?>

                    <?php if (!empty($join_video_url)) : ?>
                        <video
                            class="couples-home-join-the-club-video"
                            autoplay
                            muted
                            loop
                            playsinline
                            preload="metadata"
                            <?php echo !empty($join_image_url) ? 'poster="' . esc_url($join_image_url) . '"' : ''; ?>
                        >
                            <source src="<?php echo esc_url($join_video_url); ?>" <?php echo !empty($join_video_mime) ? 'type="' . esc_attr($join_video_mime) . '"' : ''; ?> />
                        </video>
                    <?php endif; ?>
                </div>

                <?php if (!empty($join_credits)) : ?>
                    <div class="couples-home-join-the-club-credits">
                        <?php echo wp_kses_post($join_credits); ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="couples-home-join-the-club-col couples-home-join-the-club-col-right">
                <div class="couples-home-join-the-club-content">
                    <?php if (!empty($join_title)) : ?>
                        <div class="couples-home-join-the-club-title">
                            <?php echo nl2br(wp_kses($join_title, $allowed_title_html)); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($join_content)) : ?>
                        <div class="couples-home-join-the-club-text">
                            <?php echo nl2br(esc_html($join_content)); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($join_cta_url) && !empty($join_cta_title)) : ?>
                        <a class="landing-page-button landing-page-button--white-outline-trans" href="<?php echo esc_url($join_cta_url); ?>"<?php echo !empty($join_cta_target) ? ' target="' . esc_attr($join_cta_target) . '"' : ''; ?>>
                            <?php echo $join_cta_title; ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            </div>

            <?php if (!empty($join_ending_title)) : ?>
                <p class="couples-home-join-the-club-ending-title"><?php echo esc_html($join_ending_title); ?></p>
            <?php endif; ?>
        </div>
    </section>

    <?php
    $join_the_vendor_community = get_field('join_the_vendor_community');
    $jvc_image = is_array($join_the_vendor_community) && !empty($join_the_vendor_community['image']) ? $join_the_vendor_community['image'] : null;
    $jvc_title = is_array($join_the_vendor_community) && !empty($join_the_vendor_community['title']) ? $join_the_vendor_community['title'] : '';
    $jvc_content = is_array($join_the_vendor_community) && !empty($join_the_vendor_community['content']) ? $join_the_vendor_community['content'] : '';
    $jvc_cta = is_array($join_the_vendor_community) && !empty($join_the_vendor_community['cta']) ? $join_the_vendor_community['cta'] : null;

    $jvc_image_url = is_array($jvc_image) && !empty($jvc_image['url']) ? $jvc_image['url'] : '';
    $jvc_cta_url = is_array($jvc_cta) && !empty($jvc_cta['url']) ? $jvc_cta['url'] : '';
    $jvc_cta_title = is_array($jvc_cta) && !empty($jvc_cta['title']) ? $jvc_cta['title'] : '';
    $jvc_cta_target = is_array($jvc_cta) && !empty($jvc_cta['target']) ? $jvc_cta['target'] : '';

    $jvc_has_content = !empty($jvc_image_url) || !empty($jvc_title) || !empty($jvc_content) || (!empty($jvc_cta_url) && !empty($jvc_cta_title));
    ?>

    <?php if ($jvc_has_content) : ?>
        <section class="vendors-home-join-vendor-community" aria-label="Join The Vendor Community">
            <div class="vendors-home-join-vendor-community-col vendors-home-join-vendor-community-col--media"<?php echo !empty($jvc_image_url) ? ' style="background-image:url(' . esc_url($jvc_image_url) . ');"' : ''; ?>></div>

            <div class="vendors-home-join-vendor-community-col vendors-home-join-vendor-community-col--content">
                <div class="vendors-home-join-vendor-community-inner">
                    <?php if (!empty($jvc_title)) : ?>
                        <h2 class="vendors-home-join-vendor-community-title"><?php echo esc_html($jvc_title); ?></h2>
                    <?php endif; ?>

                    <?php if (!empty($jvc_content)) : ?>
                        <div class="vendors-home-join-vendor-community-content">
                            <?php echo wp_kses_post($jvc_content); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($jvc_cta_url) && !empty($jvc_cta_title)) : ?>
                        <a class="vendors-home-join-vendor-community-cta landing-page-button landing-page-button--white-outline-trans" href="<?php echo esc_url($jvc_cta_url); ?>"<?php echo !empty($jvc_cta_target) ? ' target="' . esc_attr($jvc_cta_target) . '"' : ''; ?>>
                            <?php echo $jvc_cta_title; ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php
    $meet_our_founders = get_field('meet_our_founders');
    $mof_title = is_array($meet_our_founders) && !empty($meet_our_founders['title']) ? $meet_our_founders['title'] : '';
    $mof_content = is_array($meet_our_founders) && !empty($meet_our_founders['content']) ? $meet_our_founders['content'] : '';
    $mof_cta_1 = is_array($meet_our_founders) && !empty($meet_our_founders['cta_1']) ? $meet_our_founders['cta_1'] : null;
    $mof_cta_2 = is_array($meet_our_founders) && !empty($meet_our_founders['cta_2']) ? $meet_our_founders['cta_2'] : null;
    $mof_image = is_array($meet_our_founders) && !empty($meet_our_founders['image']) ? $meet_our_founders['image'] : null;

    $mof_cta_1_url = is_array($mof_cta_1) && !empty($mof_cta_1['url']) ? $mof_cta_1['url'] : '';
    $mof_cta_1_title = is_array($mof_cta_1) && !empty($mof_cta_1['title']) ? $mof_cta_1['title'] : '';
    $mof_cta_1_target = is_array($mof_cta_1) && !empty($mof_cta_1['target']) ? $mof_cta_1['target'] : '';

    $mof_cta_2_url = is_array($mof_cta_2) && !empty($mof_cta_2['url']) ? $mof_cta_2['url'] : '';
    $mof_cta_2_title = is_array($mof_cta_2) && !empty($mof_cta_2['title']) ? $mof_cta_2['title'] : '';
    $mof_cta_2_target = is_array($mof_cta_2) && !empty($mof_cta_2['target']) ? $mof_cta_2['target'] : '';

    $mof_image_url = is_array($mof_image) && !empty($mof_image['url']) ? $mof_image['url'] : '';
    $mof_has_content = !empty($mof_title) || !empty($mof_content) || (!empty($mof_cta_1_url) && !empty($mof_cta_1_title)) || (!empty($mof_cta_2_url) && !empty($mof_cta_2_title)) || !empty($mof_image_url);
    ?>

    <?php if ($mof_has_content) : ?>
        <section class="vendors-home-meet-our-founders" aria-label="Meet Our Founders">
            <div class="vendors-home-meet-our-founders-col vendors-home-meet-our-founders-col--content">
                <div class="vendors-home-meet-our-founders-inner">
                    <?php if (!empty($mof_title)) : ?>
                        <h2 class="vendors-home-meet-our-founders-title"><?php echo esc_html($mof_title); ?></h2>
                    <?php endif; ?>

                    <?php if (!empty($mof_content)) : ?>
                        <div class="vendors-home-meet-our-founders-content"><?php echo wp_kses_post($mof_content); ?></div>
                    <?php endif; ?>

                    <?php if ((!empty($mof_cta_1_url) && !empty($mof_cta_1_title)) || (!empty($mof_cta_2_url) && !empty($mof_cta_2_title))) : ?>
                        <div class="vendors-home-meet-our-founders-ctas">
                            <?php if (!empty($mof_cta_1_url) && !empty($mof_cta_1_title)) : ?>
                                <a class="vendors-home-meet-our-founders-cta landing-page-button landing-page-button--white-outline-trans" href="<?php echo esc_url($mof_cta_1_url); ?>"<?php echo !empty($mof_cta_1_target) ? ' target="' . esc_attr($mof_cta_1_target) . '"' : ''; ?>>
                                    <?php echo $mof_cta_1_title; ?>
                                </a>
                            <?php endif; ?>

                            <?php if (!empty($mof_cta_2_url) && !empty($mof_cta_2_title)) : ?>
                                <a class="vendors-home-meet-our-founders-cta landing-page-button landing-page-button--white-outline-trans" href="<?php echo esc_url($mof_cta_2_url); ?>"<?php echo !empty($mof_cta_2_target) ? ' target="' . esc_attr($mof_cta_2_target) . '"' : ''; ?>>
                                    <?php echo $mof_cta_2_title; ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="vendors-home-meet-our-founders-col vendors-home-meet-our-founders-col--media"<?php echo !empty($mof_image_url) ? ' style="background-image:url(' . esc_url($mof_image_url) . ');"' : ''; ?>></div>
        </section>
    <?php endif; ?>

    <section class="couples-home-the-magazine" aria-label="The magazine">
        <p class="couples-home-the-magazine-text">THE MAGAZINE COMING SOON</p>
    </section>

</main>

<?php get_footer(); ?>

