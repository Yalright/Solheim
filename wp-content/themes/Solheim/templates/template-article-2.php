<?php
/**
 * Template Name: Article Template 2
 * Template Post Type: post, real-wedding
 */
if (!defined('ABSPATH')) {
    exit;
}

$hero = get_field('hero');
$hero = is_array($hero) ? $hero : array();

$hero_title = isset($hero['title']) ? $hero['title'] : '';
$hero_primary_image = isset($hero['primary_image']) && is_array($hero['primary_image']) ? $hero['primary_image'] : null;
$hero_primary_image_url = $hero_primary_image && !empty($hero_primary_image['url']) ? $hero_primary_image['url'] : '';
$hero_primary_image_alt = $hero_primary_image && !empty($hero_primary_image['alt']) ? $hero_primary_image['alt'] : '';

$primary_category_label = 'PRIMARY CATEGORY';
$categories = get_the_category();
if (!empty($categories) && !is_wp_error($categories)) {
    if (class_exists('WPSEO_Primary_Term')) {
        $primary_term = new WPSEO_Primary_Term('category', get_the_ID());
        $primary_term_id = (int) $primary_term->get_primary_term();
        if ($primary_term_id > 0 && !is_wp_error($primary_term_id)) {
            $primary_term_obj = get_term($primary_term_id);
            if ($primary_term_obj && !is_wp_error($primary_term_obj) && !empty($primary_term_obj->name)) {
                $primary_category_label = $primary_term_obj->name;
            }
        }
    }
    if ($primary_category_label === 'PRIMARY CATEGORY' && !empty($categories[0]->name)) {
        $primary_category_label = $categories[0]->name;
    }
}

$main_content = get_field('main_content');

$main_content = is_array($main_content) ? $main_content : array();
$image_content_rows = array();

$image_content_repeater = array();
if (isset($main_content['image_+_content']) && is_array($main_content['image_+_content'])) {
    $image_content_repeater = $main_content['image_+_content'];
} elseif (isset($main_content['image_content']) && is_array($main_content['image_content'])) {
    $image_content_repeater = $main_content['image_content'];
} elseif (isset($main_content['image___content']) && is_array($main_content['image___content'])) {
    $image_content_repeater = $main_content['image___content'];
}

foreach ($image_content_repeater as $row) {
    if (!is_array($row)) {
        continue;
    }

    $image = isset($row['image']) && is_array($row['image']) ? $row['image'] : null;
    $image_url = $image && !empty($image['url']) ? $image['url'] : '';
    $image_alt = $image && !empty($image['alt']) ? $image['alt'] : '';

    $content = isset($row['content']) ? $row['content'] : '';
    if ($image_url === '' && trim(wp_strip_all_tags((string) $content)) === '') {
        continue;
    }

    $image_content_rows[] = array(
        'image_url' => $image_url,
        'image_alt' => $image_alt,
        'content' => $content,
    );
}

get_header();
?>

<main class="site-main article-template article-template-2">
    <?php while (have_posts()) : the_post(); ?>
        <section class="article-template-2__hero">
            <div class="container">
                <div class="article-template-2__hero-head">
                    <?php if (!empty($hero_title)) : ?>
                        <div class="article-template-2__hero-title"><?php echo wp_kses_post($hero_title); ?></div>
                    <?php else : ?>
                        <h1 class="article-template-2__hero-title"><?php the_title(); ?></h1>
                    <?php endif; ?>

                    <span class="article-template-2__category-pill"><?php echo esc_html($primary_category_label); ?></span>
                </div>

                <?php if ($hero_primary_image_url !== '') : ?>
                    <div
                        class="article-template-2__hero-image"
                        style="background-image: url('<?php echo esc_url($hero_primary_image_url); ?>');"
                        role="img"
                        aria-label="<?php echo esc_attr($hero_primary_image_alt); ?>"
                    ></div>
                <?php endif; ?>
            </div>
        </section>

        <?php if (!empty($image_content_rows)) : ?>
            <section class="article-template-1__main-content">
                <div class="container">
                    <?php foreach ($image_content_rows as $row) : ?>
                        <div class="article-template-1__content-row article-template-1__content-row--image-right">
                            <div class="article-template-1__content-image-col">
                                <?php if ($row['image_url'] !== '') : ?>
                                    <img class="article-template-1__content-image" src="<?php echo esc_url($row['image_url']); ?>" alt="<?php echo esc_attr($row['image_alt']); ?>" />
                                <?php endif; ?>
                            </div>
                            <div class="article-template-1__content-text-col">
                                <div class="article-template-1__content-text">
                                    <?php echo wp_kses_post($row['content']); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>

        <?php require get_template_directory() . '/templates/template-part-article-related-slider.php'; ?>
        <?php require get_template_directory() . '/templates/template-part-article-promo.php'; ?>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>


