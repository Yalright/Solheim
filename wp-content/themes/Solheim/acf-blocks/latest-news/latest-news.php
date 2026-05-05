<?php
/**
 * Block - Latest News
 */

$block_data     = include get_template_directory() . '/acf-blocks/block-settings/block-settings.php';
$style_classes  = $block_data['style_classes'];
$block_id       = ! empty($block_data['block_id']) ? 'id="' . esc_attr($block_data['block_id']) . '"' : '';

$block_name = 'latest-news';
array_unshift($style_classes, $block_name);
$style_classes[] = $block_name;

$title           = get_field('title');
$browse_all_cta  = get_field('browse_all_cta');
$article_output  = get_field('article_output');
$user_defined    = get_field('user_defined_articles');

$title = is_string($title) && trim($title) !== '' ? trim($title) : 'Latest News';
$article_output = is_string($article_output) ? $article_output : 'latest';

$posts = array();

if ($article_output === 'user-defined' && is_array($user_defined) && ! empty($user_defined)) {
    foreach ($user_defined as $item) {
        if ($item instanceof WP_Post && $item->post_type === 'post' && $item->post_status === 'publish') {
            $posts[] = $item;
        }
    }
}

if (empty($posts)) {
    $query = new WP_Query(
        array(
            'post_type'      => 'post',
            'post_status'    => 'publish',
            'posts_per_page' => 10,
            'no_found_rows'  => true,
        )
    );
    if ($query->have_posts()) {
        $posts = $query->posts;
    }
    wp_reset_postdata();
}

$classes = implode(' ', array_filter(array_map('esc_attr', $style_classes)));
?>

<section <?php echo $block_id; ?> class="guten-block <?php echo esc_attr($classes); ?>">
    <div class="latest-news__layout">
        <h2 class="latest-news__title"><?php echo esc_html($title); ?></h2>

        <div class="latest-news__slider-wrap">
            <div class="latest-news__topbar">
                <?php if (is_array($browse_all_cta) && ! empty($browse_all_cta['url'])) : ?>
                    <a class="latest-news__browse-link" href="<?php echo esc_url($browse_all_cta['url']); ?>"<?php echo ! empty($browse_all_cta['target']) ? ' target="' . esc_attr($browse_all_cta['target']) . '"' : ''; ?><?php echo ! empty($browse_all_cta['target']) && $browse_all_cta['target'] === '_blank' ? ' rel="noopener noreferrer"' : ''; ?>>
                        <?php echo esc_html($browse_all_cta['title'] !== '' ? $browse_all_cta['title'] : $browse_all_cta['url']); ?>
                    </a>
                <?php endif; ?>

                <div class="latest-news__arrows">
                    <button class="latest-news__arrow latest-news__arrow--prev" type="button" aria-label="<?php esc_attr_e('Previous articles', 'solheim'); ?>">
                        &larr;
                    </button>
                    <button class="latest-news__arrow latest-news__arrow--next" type="button" aria-label="<?php esc_attr_e('Next articles', 'solheim'); ?>">
                        &rarr;
                    </button>
                </div>
            </div>

            <div class="splide latest-news__splide" data-latest-news-slider>
                <div class="splide__track">
                    <ul class="splide__list">
                        <?php foreach ($posts as $post_obj) : ?>
                            <?php
                            $post_id        = $post_obj->ID;
                            $post_title     = get_the_title($post_id);
                            $post_permalink = get_permalink($post_id);
                            $thumb_url      = get_the_post_thumbnail_url($post_id, 'large');
                            ?>
                            <li class="splide__slide">
                                <article class="latest-news__card">
                                    <a class="latest-news__card-link" href="<?php echo esc_url($post_permalink); ?>">
                                        <h3 class="latest-news__card-title"><?php echo esc_html($post_title); ?></h3>
                                        <div class="latest-news__card-media"<?php echo $thumb_url ? ' style="background-image:url(' . esc_url($thumb_url) . ');"' : ''; ?>></div>
                                        <span class="latest-news__card-cta"><?php esc_html_e('READ ARTICLE', 'solheim'); ?></span>
                                    </a>
                                </article>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
