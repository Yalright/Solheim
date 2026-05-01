<?php
/**
 * Template Name: Template - Vendors Login
 * Template Post Type: page
 */
if (!defined('ABSPATH')) {
    exit;
}

$image = get_field('image');
$overlay_image = get_field('overlay_image');
$overlay_logo = get_field('overlay_logo');
$title = get_field('title');
$content = get_field('content');
// Gravity Forms shortcode, e.g. [gravityform id="1" title="false" ajax="true"]
$form_shortcode = get_field('form_shortcode');

$image_url = is_array($image) && !empty($image['url']) ? $image['url'] : '';
$overlay_image_url = is_array($overlay_image) && !empty($overlay_image['url']) ? $overlay_image['url'] : '';
$overlay_image_alt = is_array($overlay_image) && !empty($overlay_image['alt']) ? $overlay_image['alt'] : '';
$overlay_logo_url = is_array($overlay_logo) && !empty($overlay_logo['url']) ? $overlay_logo['url'] : '';
$overlay_logo_alt = is_array($overlay_logo) && !empty($overlay_logo['alt']) ? $overlay_logo['alt'] : '';
?>

<?php get_header(); ?>

<main class="site-main template-vendors-login">
    <section class="vendors-login-hero">
        <?php if (!empty($overlay_logo_url)) : ?>
            <img class="vendors-login-overlay-logo" src="<?php echo esc_url($overlay_logo_url); ?>" alt="<?php echo esc_attr($overlay_logo_alt); ?>" />
        <?php endif; ?>

        <div class="vendors-login-left">
            <?php if (!empty($image_url)) : ?>
                <img class="vendors-login-bg-image" src="<?php echo esc_url($image_url); ?>" alt="" />
            <?php endif; ?>

            <div class="vendors-login-left-overlay">
                <?php if (!empty($overlay_image_url)) : ?>
                    <img class="vendors-login-overlay-image" src="<?php echo esc_url($overlay_image_url); ?>" alt="<?php echo esc_attr($overlay_image_alt); ?>" />
                <?php endif; ?>
            </div>
        </div>

        <div class="vendors-login-right">
            <div class="vendors-login-content">
                <?php if (!empty($title)) : ?>
                    <h1 class="vendors-login-title"><?php echo esc_html($title); ?></h1>
                <?php endif; ?>

                <?php if (!empty($content)) : ?>
                    <div class="vendors-login-text"><?php echo nl2br(esc_html($content)); ?></div>
                <?php endif; ?>

                <div class="vendors-login-form">
                    <?php
                    if (!empty($form_shortcode)) {
                        echo do_shortcode($form_shortcode);
                    }
                    lalista_render_end_of_login_form(apply_filters('lalista_login_register_url', '#', 'vendors'));
                    ?>
                </div>
            </div>

            <img class="vendors-login-right-bottom-logo" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/VENDORS.svg'); ?>" alt="Vendors">
        </div>
    </section>
</main>

<?php get_footer(); ?>

