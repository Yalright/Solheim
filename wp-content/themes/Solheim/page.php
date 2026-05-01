<?php
/**
 * Default page template (BuddyPress and generic WordPress pages).
 */
if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main class="site-main site-main--page" id="content">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <div class="container">
                <?php the_content(); ?>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
