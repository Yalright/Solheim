<?php
if (!defined('ABSPATH')) {
    exit;
}

$current_post_id = get_the_ID();
$related_posts_query = new WP_Query(array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => 8,
    'orderby' => 'rand',
    'post__not_in' => array($current_post_id),
    'ignore_sticky_posts' => true,
));

if (!$related_posts_query->have_posts()) {
    wp_reset_postdata();
    return;
}
?>

<section class="article-related-slider" aria-label="Related posts">
    <div class="container">
        <h2 class="article-related-slider__title">Be inspired...</h2>
        <div class="article-related-slider__track" role="list">
            <?php while ($related_posts_query->have_posts()) : $related_posts_query->the_post(); ?>
                <?php
                $card_image_url = get_the_post_thumbnail_url(get_the_ID(), 'large');

                $category_label = 'CATEGORY';
                $categories = get_the_category();
                if (!empty($categories) && !is_wp_error($categories)) {
                    if (class_exists('WPSEO_Primary_Term')) {
                        $primary_term = new WPSEO_Primary_Term('category', get_the_ID());
                        $primary_term_id = (int) $primary_term->get_primary_term();
                        if ($primary_term_id > 0) {
                            $primary_term_obj = get_term($primary_term_id);
                            if ($primary_term_obj && !is_wp_error($primary_term_obj) && !empty($primary_term_obj->name)) {
                                $category_label = $primary_term_obj->name;
                            }
                        }
                    }
                    if ($category_label === 'CATEGORY' && !empty($categories[0]->name)) {
                        $category_label = $categories[0]->name;
                    }
                }
                ?>
                <article class="article-related-slider__item" role="listitem">
                    <a class="article-related-slider__card" href="<?php the_permalink(); ?>">
                        <?php if (!empty($card_image_url)) : ?>
                            <img class="article-related-slider__image" src="<?php echo esc_url($card_image_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" />
                        <?php else : ?>
                            <div class="article-related-slider__image article-related-slider__image--empty"></div>
                        <?php endif; ?>
                        <div class="article-related-slider__content">
                            <span class="article-related-slider__pill"><?php echo esc_html($category_label); ?></span>
                            <h3 class="article-related-slider__post-title"><?php the_title(); ?></h3>
                        </div>
                    </a>
                </article>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<?php wp_reset_postdata(); ?>
