<?php
/**
 * Template Name: Template - Results Grid
 * Template Post Type: page
 *
 * ACF fields:
 * - title (text)
 * - menu (repeater) > menu_item (link)
 * - description (text)
 */
if (!defined('ABSPATH')) {
    exit;
}

$results_title = trim((string) get_field('title'));
$results_description = trim((string) get_field('description'));
$results_shortcode = trim((string) get_field('results_shortcode'));
$search_filter_shortcode = trim((string) get_field('search_filter_shortcode'));

$menu_raw = get_field('menu');
$menu_raw = is_array($menu_raw) ? $menu_raw : array();
$menu_items = array();
foreach ($menu_raw as $row) {
    if (!is_array($row)) {
        continue;
    }
    $menu_item = isset($row['menu_item']) && is_array($row['menu_item']) ? $row['menu_item'] : null;
    if (!$menu_item) {
        continue;
    }
    $item_url = !empty($menu_item['url']) ? (string) $menu_item['url'] : '';
    $item_title = !empty($menu_item['title']) ? trim((string) $menu_item['title']) : '';
    $item_target = !empty($menu_item['target']) ? (string) $menu_item['target'] : '';
    if ($item_url === '' || $item_title === '') {
        continue;
    }
    $menu_items[] = array(
        'url' => $item_url,
        'title' => $item_title,
        'target' => $item_target,
    );
}

$filter_shortcodes_raw = get_field('filter_shortcodes');
$filter_shortcodes_raw = is_array($filter_shortcodes_raw) ? $filter_shortcodes_raw : array();
$filter_shortcodes = array();
foreach ($filter_shortcodes_raw as $row) {
    if (!is_array($row)) {
        continue;
    }
    $shortcode = trim((string) ($row['filter_shortcode'] ?? ''));
    if ($shortcode === '') {
        continue;
    }
    $filter_shortcodes[] = $shortcode;
}

$region_display_name = 'ALL VENUES';
$region_query_value = '';
foreach ($_GET as $query_key => $query_value) {
    if (stripos((string) $query_key, 'region') === false) {
        continue;
    }

    if (is_array($query_value)) {
        $query_value = reset($query_value);
    }
    $query_value = trim((string) $query_value);
    if ($query_value === '') {
        continue;
    }

    $region_query_value = explode(',', $query_value)[0];
    break;
}

if ($region_query_value !== '') {
    $region_query_value = sanitize_title($region_query_value);
    $region_term = get_term_by('slug', $region_query_value, 'region');
    if ($region_term && !is_wp_error($region_term) && !empty($region_term->name)) {
        $region_display_name = strtoupper((string) $region_term->name);
    } else {
        $region_display_name = strtoupper(str_replace('-', ' ', $region_query_value));
    }
}

get_header();
?>

<main class="site-main template-results-grid">
    <section class="results-grid-head">
        <div class="container results-grid-head__inner">
            <div class="results-grid-head__left">
                <?php if ($results_title !== '') : ?>
                    <h1 class="results-grid-head__title"><?php echo esc_html($results_title); ?></h1>
                <?php else : ?>
                    <h1 class="results-grid-head__title"><?php echo esc_html(get_the_title()); ?></h1>
                <?php endif; ?>
                <?php if (!empty($menu_items)) : ?>
                    <ul class="results-grid-head__menu">
                        <?php foreach ($menu_items as $item) : ?>
                            <li>
                                <a
                                    href="<?php echo esc_url($item['url']); ?>"
                                    <?php echo $item['target'] !== '' ? ' target="' . esc_attr($item['target']) . '"' : ''; ?>
                                    <?php echo $item['target'] === '_blank' ? ' rel="noopener noreferrer"' : ''; ?>
                                ><?php echo esc_html($item['title']); ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <div class="results-grid-head__right">
                <?php if ($results_description !== '') : ?>
                    <p class="results-grid-head__description"><?php echo esc_html($results_description); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="results-grid-content">
        <div class="container results-grid-content__inner">
            <aside class="results-grid-content__filters" aria-label="Filters">
                <?php if (!empty($filter_shortcodes)) : ?>
                    <?php foreach ($filter_shortcodes as $shortcode) : ?>
                        <div class="results-grid-content__filter-item">
                            <?php echo do_shortcode($shortcode); ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </aside>

            <div class="results-grid-content__results">
                <div class="results-grid-content__meta">
                    <div class="results-grid-content__breadcrumbs">
                        DIRECTORY
                        <span>/</span>
                        REGIONS
                        <span>/</span>
                        <?php echo esc_html($region_display_name); ?>
                    </div>
                    <div class="results-grid-content__search">
                        <?php if ($search_filter_shortcode !== '') : ?>
                            <?php echo do_shortcode($search_filter_shortcode); ?>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ($results_shortcode !== '') : ?>
                    <?php echo do_shortcode($results_shortcode); ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
