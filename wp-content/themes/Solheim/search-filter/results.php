<?php

if (!defined('ABSPATH')) {
    exit;
}

echo "<div class='search-results-container'>";

if ($query->have_posts()) {
    echo "<div class='search-results-grid'>";
    while ($query->have_posts()) {
        $query->the_post();
        $region_terms = get_the_terms(get_the_ID(), 'region');
        $region_name = '';
        if (is_array($region_terms) && !empty($region_terms)) {
            $primary_term = $region_terms[0];
            if ($primary_term && !is_wp_error($primary_term) && !empty($primary_term->name)) {
                $region_name = (string) $primary_term->name;
            }
        }
        $thumb_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
        $thumb_url = is_string($thumb_url) ? $thumb_url : '';
        $title = get_the_title();
        $title = is_string($title) ? $title : '';
        $permalink = get_permalink();
        $permalink = is_string($permalink) ? $permalink : '';
        ?>
        <div class="search-filter-results-item" data-post-type="<?php echo get_post_type(); ?>">
            <a class="search-filter-results-item__card" href="<?php echo esc_url($permalink); ?>">
                <div class="search-filter-results-item__top">
                    <?php if ($region_name !== '') : ?>
                        <span class="search-filter-results-item__region-pill"><?php echo esc_html(strtoupper($region_name)); ?></span>
                    <?php endif; ?>
                </div>

                <div class="search-filter-results-item__image-wrap">
                    <?php if ($thumb_url !== '') : ?>
                        <img
                            class="search-filter-results-item__image"
                            src="<?php echo esc_url($thumb_url); ?>"
                            alt=""
                            loading="lazy"
                            decoding="async"
                        />
                    <?php endif; ?>
                </div>

                <h3 class="search-filter-results-item__title"><?php echo esc_html($title); ?></h3>
            </a>
        </div>
        <?php
    }
    echo "</div>";
} else { ?>
    <div class='search-filter-results-list' data-search-filter-action='infinite-scroll-end'>
        <!-- <span>End of Results</span> -->
    </div>
<?php }

echo "</div>";
