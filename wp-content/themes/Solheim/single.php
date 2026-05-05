<?php
get_header();
?>

<main class="post-single-template" id="content">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <?php
            $categories       = get_the_category();
            $primary_category = (! empty($categories) && isset($categories[0])) ? $categories[0] : null;
            $featured_image   = get_the_post_thumbnail_url(get_the_ID(), 'full');
            $excerpt          = get_the_excerpt();
            ?>

            <section class="post-single-template__hero">
                <div class="post-single-template__hero-left">
                    <?php if ($primary_category) : ?>
                        <span class="post-single-template__category-pill"><?php echo esc_html($primary_category->name); ?></span>
                    <?php endif; ?>

                    <h1 class="post-single-template__title"><?php the_title(); ?></h1>

                    <?php if (! empty($excerpt)) : ?>
                        <p class="post-single-template__excerpt"><?php echo esc_html($excerpt); ?></p>
                    <?php endif; ?>
                </div>

                <div
                    class="post-single-template__hero-right"
                    <?php if ($featured_image) : ?>
                        style="background-image:url(<?php echo esc_url($featured_image); ?>);"
                    <?php endif; ?>
                    <?php if (! $featured_image) : ?>
                        aria-hidden="true"
                    <?php endif; ?>
                ></div>
            </section>


                    <?php the_content(); ?>

        <?php endwhile; ?>
    <?php endif; ?>
</main>

<?php get_footer(); ?>