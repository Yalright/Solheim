<?php
/**
 * Block - News
 *
 * ACF (create in WP): Textarea or Text fields — keys should match:
 * - filter_1, filter_2, filter_3 — Search & Filter Pro (or other) shortcodes, shown inline in one row
 * - results — shortcode that outputs the results area (grid markup comes from theme search-filter/results.php when S&F uses that template)
 */

$block_data     = include get_template_directory() . '/acf-blocks/block-settings/block-settings.php';
$style_classes  = $block_data['style_classes'];
$block_id       = ! empty($block_data['block_id']) ? 'id="' . esc_attr($block_data['block_id']) . '"' : '';

$block_name = 'news';
array_unshift($style_classes, $block_name);
$style_classes[] = $block_name;

$filter_1 = get_field('filter_1');
$filter_2 = get_field('filter_2');
$filter_3 = get_field('filter_3');
$results  = get_field('results');

$classes = implode(' ', array_filter(array_map('esc_attr', $style_classes)));
?>

<section <?php echo $block_id; ?> class="guten-block <?php echo esc_attr($classes); ?>">
    <div class="container news__inner">
        <div class="news__filters" role="toolbar" aria-label="<?php esc_attr_e('News filters', 'solheim'); ?>">
            <div class="news__filter">
                <?php
                if (is_string($filter_1) && trim($filter_1) !== '') {
                    echo do_shortcode(wp_unslash(trim($filter_1)));
                }
                ?>
            </div>
            <div class="news__filter">
                <?php
                if (is_string($filter_2) && trim($filter_2) !== '') {
                    echo do_shortcode(wp_unslash(trim($filter_2)));
                }
                ?>
            </div>
            <div class="news__filter">
                <?php
                if (is_string($filter_3) && trim($filter_3) !== '') {
                    echo do_shortcode(wp_unslash(trim($filter_3)));
                }
                ?>
            </div>
        </div>

        <div class="news__results">
            <?php
            if (is_string($results) && trim($results) !== '') {
                echo do_shortcode(wp_unslash(trim($results)));
            }
            ?>
        </div>
    </div>
</section>
