<?php

if (!defined('ABSPATH')) {
    exit;
}

echo "<div class='search-results-container'>";

if ($query->have_posts()) {
    echo "<div class='search-results-grid'>";
    while ($query->have_posts()) {
        $query->the_post();
        $post_id = get_the_ID();

        $thumb_url = get_the_post_thumbnail_url($post_id, 'large');
        $thumb_url = is_string($thumb_url) ? $thumb_url : '';
        $title     = get_the_title($post_id);
        $title     = is_string($title) ? $title : '';
        $permalink = get_permalink($post_id);
        $permalink = is_string($permalink) ? $permalink : '';
        $img_alt   = $title !== '' ? $title : __('Article image', 'solheim');

        $primary_category_name = '';
        $categories            = get_the_category($post_id);
        if (is_array($categories) && ! empty($categories) && ! is_wp_error($categories)) {
            if (class_exists('WPSEO_Primary_Term')) {
                $primary_term = new WPSEO_Primary_Term('category', $post_id);
                $primary_term_id = (int) $primary_term->get_primary_term();
                if ($primary_term_id > 0) {
                    $primary_term_obj = get_term($primary_term_id);
                    if ($primary_term_obj && ! is_wp_error($primary_term_obj) && ! empty($primary_term_obj->name)) {
                        $primary_category_name = (string) $primary_term_obj->name;
                    }
                }
            }
            if ($primary_category_name === '' && ! empty($categories[0]->name)) {
                $primary_category_name = (string) $categories[0]->name;
            }
        }
        ?>
        <article class="search-filter-results-item" data-post-type="<?php echo esc_attr(get_post_type()); ?>">
            <a class="search-filter-results-item__card" href="<?php echo esc_url($permalink); ?>">
                <div class="search-filter-results-item__media">
                    <?php if ($thumb_url !== '') : ?>
                        <img
                            class="search-filter-results-item__image"
                            src="<?php echo esc_url($thumb_url); ?>"
                            alt="<?php echo esc_attr($img_alt); ?>"
                            loading="lazy"
                            decoding="async"
                        />
                    <?php endif; ?>
                </div>

                <div class="search-filter-results-item__body">
                    <?php if ($primary_category_name !== '') : ?>
                        <p class="search-filter-results-item__category"><?php echo esc_html(strtoupper($primary_category_name)); ?></p>
                    <?php endif; ?>

                    <h3 class="search-filter-results-item__title"><?php echo esc_html($title); ?></h3>

                    <span class="search-filter-results-item__cta"><?php esc_html_e('READ ARTICLE', 'solheim'); ?></span>
                </div>
            </a>
        </article>
        <?php
    }
    echo '</div>';
} else { ?>
    <div class='search-filter-results-list' data-search-filter-action='infinite-scroll-end'>
        <!-- <span>End of Results</span> -->
    </div>
<?php }

echo '</div>';
