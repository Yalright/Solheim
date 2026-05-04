<?php
/**
 * Block - CTA Ticker
 */

$block_data    = include get_template_directory() . '/acf-blocks/block-settings/block-settings.php';
$style_classes = $block_data['style_classes'];
$block_id      = ! empty($block_data['block_id']) ? 'id="' . esc_attr($block_data['block_id']) . '"' : '';

$block_name = 'cta-ticker';
array_unshift($style_classes, $block_name);
$style_classes[] = $block_name;

$rows = get_field('ctas');
$items = array();

if ( ! empty($rows) && is_array($rows)) {
    foreach ($rows as $row) {
        if ( ! is_array($row)) {
            continue;
        }
        $text = isset($row['text']) ? trim((string) $row['text']) : '';
        $url  = isset($row['link']) ? trim((string) $row['link']) : '';
        if ($text === '' && $url === '') {
            continue;
        }
        $display = $text !== '' ? $text : $url;
        $items[] = array(
            'text' => $display,
            'url'  => $url,
        );
    }
}

if (count($items) === 1) {
    $single = $items[0];
    $items  = array(
        array(
            'text' => $single['text'],
            'url'  => $single['url'],
        ),
        array(
            'text' => $single['text'],
            'url'  => $single['url'],
        ),
        array(
            'text' => $single['text'],
            'url'  => $single['url'],
        ),
    );
}

$classes = implode(' ', array_filter(array_map('esc_attr', $style_classes)));

if (empty($items)) {
    return;
}

/**
 * @param array<int, array{text: string, url: string}> $ticker_items
 */
$render_sequence = static function ($ticker_items) {
    $chunks = array();
    foreach ($ticker_items as $item) {
        ob_start();
        if ($item['url'] !== '') {
            printf(
                '<a class="cta-ticker__link" href="%s">%s</a>',
                esc_url($item['url']),
                esc_html($item['text'])
            );
        } else {
            printf('<span class="cta-ticker__text">%s</span>', esc_html($item['text']));
        }
        $chunks[] = ob_get_clean();
    }
    $sep = '<span class="cta-ticker__bullet" aria-hidden="true">•</span>';
    echo implode($sep, $chunks);
    echo $sep;
};
?>

<section <?php echo $block_id; ?> class="guten-block <?php echo esc_attr($classes); ?>">
    <div class="cta-ticker__viewport">
        <div class="cta-ticker__marquee">
            <div class="cta-ticker__group">
                <?php $render_sequence($items); ?>
            </div>
            <div class="cta-ticker__group" aria-hidden="true">
                <?php $render_sequence($items); ?>
            </div>
        </div>
    </div>
</section>
