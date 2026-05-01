<?php
/**
 * Template Name: Article Template 1
 * Template Post Type: post, real-wedding
 */
if (!defined('ABSPATH')) {
    exit;
}

$hero = get_field('hero');
$hero = is_array($hero) ? $hero : array();

$hero_title = isset($hero['title']) ? $hero['title'] : '';
$hero_primary_image = isset($hero['primary_image']) && is_array($hero['primary_image']) ? $hero['primary_image'] : null;
$hero_subtitle = isset($hero['subtitle']) ? $hero['subtitle'] : '';
$hero_gallery = isset($hero['image_gallery']) && is_array($hero['image_gallery']) ? $hero['image_gallery'] : array();

$hero_primary_image_url = $hero_primary_image && !empty($hero_primary_image['url']) ? $hero_primary_image['url'] : '';
$hero_primary_image_alt = $hero_primary_image && !empty($hero_primary_image['alt']) ? $hero_primary_image['alt'] : '';

$hero_gallery_items = array();
foreach ($hero_gallery as $item) {
    if (!is_array($item) || empty($item['url'])) {
        continue;
    }
    $hero_gallery_items[] = array(
        'url' => $item['url'],
        'alt' => !empty($item['alt']) ? $item['alt'] : '',
    );
}

$hero_featured_url = !empty($hero_gallery_items) ? $hero_gallery_items[0]['url'] : $hero_primary_image_url;
$hero_featured_alt = !empty($hero_gallery_items) ? $hero_gallery_items[0]['alt'] : $hero_primary_image_alt;

$hero_thumbs = $hero_gallery_items;

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

// Scaffolds for next sections (ACF groups to be designed next).
$introduction = get_field('introduction');
$main_content = get_field('main_content');
$introduction = is_array($introduction) ? $introduction : array();
$introduction_text = isset($introduction['introduction']) ? $introduction['introduction'] : (isset($introduction['Introduction']) ? $introduction['Introduction'] : '');
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

    $position = isset($row['image_position']) ? strtolower(trim((string) $row['image_position'])) : '';
    if ($position !== 'right') {
        $position = 'left';
    }

    $image = isset($row['image']) && is_array($row['image']) ? $row['image'] : null;
    $image_url = $image && !empty($image['url']) ? $image['url'] : '';
    $image_alt = $image && !empty($image['alt']) ? $image['alt'] : '';

    $content = isset($row['content']) ? $row['content'] : '';
    if ($image_url === '' && trim(wp_strip_all_tags((string) $content)) === '') {
        continue;
    }

    $image_content_rows[] = array(
        'position' => $position,
        'image_url' => $image_url,
        'image_alt' => $image_alt,
        'content' => $content,
    );
}

get_header();
?>

<main class="site-main article-template article-template-1">
    <?php while (have_posts()) : the_post(); ?>
        <section class="article-template-1__hero">
            <div class="article-template-1__hero-col article-template-1__hero-col--left">
                <?php if ($hero_featured_url !== '') : ?>
                    <img
                        class="article-template-1__hero-featured"
                        src="<?php echo esc_url($hero_featured_url); ?>"
                        alt="<?php echo esc_attr($hero_featured_alt); ?>"
                        data-article-template-1-featured
                    />
                <?php endif; ?>

                <?php if (!empty($hero_thumbs)) : ?>
                    <div class="article-template-1__hero-thumbs">
                        <?php foreach ($hero_thumbs as $thumb) : ?>
                            <button
                                class="article-template-1__hero-thumb<?php echo $thumb['url'] === $hero_featured_url ? ' is-active' : ''; ?>"
                                type="button"
                                data-image-url="<?php echo esc_url($thumb['url']); ?>"
                                data-image-alt="<?php echo esc_attr($thumb['alt']); ?>"
                            >
                                <img src="<?php echo esc_url($thumb['url']); ?>" alt="<?php echo esc_attr($thumb['alt']); ?>" />
                            </button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="article-template-1__hero-col article-template-1__hero-col--right">
                <div class="article-template-1__hero-content">
                    <span class="article-template-1__category-pill"><?php echo esc_html($primary_category_label); ?></span>

                    <?php if (!empty($hero_title)) : ?>
                        <div class="article-template-1__hero-title">
                            <?php echo wp_kses_post($hero_title); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($hero_primary_image_url !== '') : ?>
                        <img class="article-template-1__hero-primary-image" src="<?php echo esc_url($hero_primary_image_url); ?>" alt="<?php echo esc_attr($hero_primary_image_alt); ?>" />
                    <?php endif; ?>

                    <?php if (!empty($hero_subtitle)) : ?>
                        <div class="article-template-1__hero-subtitle">
                            <?php echo wp_kses_post($hero_subtitle); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <?php if (!empty($introduction_text)) : ?>
            <section class="article-template-1__introduction">
                <div class="container">
                    <div class="article-template-1__introduction-wrapper">
                        <?php echo wp_kses_post($introduction_text); ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <?php if (!empty($image_content_rows)) : ?>
            <section class="article-template-1__main-content">
                <div class="container">
                    <?php foreach ($image_content_rows as $row) : ?>
                        <div class="article-template-1__content-row article-template-1__content-row--image-<?php echo esc_attr($row['position']); ?>">
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

<script>
    (function () {
        var template = document.querySelector('.article-template-1');
        if (!template) {
            return;
        }
        var featured = template.querySelector('[data-article-template-1-featured]');
        var thumbs = template.querySelectorAll('.article-template-1__hero-thumb');
        if (!featured || !thumbs.length) {
            return;
        }

        thumbs.forEach(function (thumb) {
            thumb.addEventListener('click', function () {
                var nextUrl = thumb.getAttribute('data-image-url');
                var nextAlt = thumb.getAttribute('data-image-alt') || '';
                if (!nextUrl) {
                    return;
                }
                featured.setAttribute('src', nextUrl);
                featured.setAttribute('alt', nextAlt);
                thumbs.forEach(function (item) {
                    item.classList.remove('is-active');
                });
                thumb.classList.add('is-active');
            });
        });
    })();
</script>

<?php get_footer(); ?>

