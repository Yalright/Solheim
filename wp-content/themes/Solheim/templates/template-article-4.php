<?php
/**
 * Template Name: Article Template 4
 * Template Post Type: post
 */
if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main class="site-main article-template article-template-4">
    <?php
    while (have_posts()) {
        the_post();
        require get_template_directory() . '/templates/template-part-article-related-slider.php';
        require get_template_directory() . '/templates/template-part-article-promo.php';
    }
    ?>
</main>

<?php get_footer(); ?>


