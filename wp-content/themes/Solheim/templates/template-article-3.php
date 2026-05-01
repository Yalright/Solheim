<?php
/**
 * Template Name: Article Template 3
 * Template Post Type: post, real-wedding
 */
if (!defined('ABSPATH')) {
    exit;
}

$hero = get_field('hero');
$hero = is_array($hero) ? $hero : array();
$hero_title = isset($hero['title']) ? $hero['title'] : '';

$main_content = get_field('main_content');
$main_content = is_array($main_content) ? $main_content : array();
$main_content_text = isset($main_content['content']) ? $main_content['content'] : (isset($main_content['Content']) ? $main_content['Content'] : '');

$main_gallery = array();
if (isset($main_content['gallery']) && is_array($main_content['gallery'])) {
    $main_gallery = $main_content['gallery'];
} elseif (isset($main_content['Gallery']) && is_array($main_content['Gallery'])) {
    $main_gallery = $main_content['Gallery'];
}

$gallery_items = array();
foreach ($main_gallery as $image) {
    if (!is_array($image) || empty($image['url'])) {
        continue;
    }
    $gallery_items[] = array(
        'url' => $image['url'],
        'alt' => !empty($image['alt']) ? $image['alt'] : '',
    );
}

$category_labels = array();
$categories = get_the_category();
if (!empty($categories) && !is_wp_error($categories)) {
    if (class_exists('WPSEO_Primary_Term')) {
        $primary_term = new WPSEO_Primary_Term('category', get_the_ID());
        $primary_term_id = (int) $primary_term->get_primary_term();
        if ($primary_term_id > 0 && !is_wp_error($primary_term_id)) {
            $primary_term_obj = get_term($primary_term_id);
            if ($primary_term_obj && !is_wp_error($primary_term_obj) && !empty($primary_term_obj->name)) {
                $category_labels[] = $primary_term_obj->name;
            }
        }
    }
    foreach ($categories as $cat) {
        if (!empty($cat->name) && !in_array($cat->name, $category_labels, true)) {
            $category_labels[] = $cat->name;
        }
    }
}
if (empty($category_labels)) {
    $category_labels[] = 'CATEGORY';
}

get_header();
?>

<main class="site-main article-template article-template-3">
    <?php while (have_posts()) : the_post(); ?>
        <section class="article-template-3__main">
            <div class="container">
                <div class="article-template-3__layout">
                    <div class="article-template-3__left-col">
                        <div class="article-template-3__left-sticky">
                            <?php if (!empty($hero_title)) : ?>
                                <div class="article-template-3__title"><?php echo wp_kses_post($hero_title); ?></div>
                            <?php else : ?>
                                <h1 class="article-template-3__title"><?php the_title(); ?></h1>
                            <?php endif; ?>

                            <div class="article-template-3__pills">
                                <?php foreach ($category_labels as $label) : ?>
                                    <span class="article-template-3__pill"><?php echo esc_html($label); ?></span>
                                <?php endforeach; ?>
                            </div>

                            <?php if (!empty($main_content_text)) : ?>
                                <div class="article-template-3__content">
                                    <?php echo wp_kses_post($main_content_text); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="article-template-3__right-col">
                        <?php foreach ($gallery_items as $item) : ?>
                            <img class="article-template-3__gallery-image" src="<?php echo esc_url($item['url']); ?>" alt="<?php echo esc_attr($item['alt']); ?>" />
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>

        <?php require get_template_directory() . '/templates/template-part-article-related-slider.php'; ?>
        <?php require get_template_directory() . '/templates/template-part-article-promo.php'; ?>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>


