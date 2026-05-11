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

$theme = get_field('theme');
if (is_string($theme) && trim($theme) !== '') {
    $theme_parts = preg_split('/\s+/', trim($theme));
    if (is_array($theme_parts)) {
        foreach ($theme_parts as $part) {
            $part = sanitize_html_class($part);
            if ($part !== '') {
                $style_classes[] = $part;
            }
        }
    }
}

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

$classes = implode(' ', array_filter(array_map('esc_attr', $style_classes)));

if (empty($items)) {
    return;
}

// Repeat the sequence so Splide loop + autoWidth always has enough slides to clone (avoids “end” of track).
$ticker_items = $items;
$base_count     = count($ticker_items);
if ($base_count > 0) {
    while (count($ticker_items) < 8) {
        foreach ($items as $row) {
            $ticker_items[] = $row;
        }
    }
}
?>

<section <?php echo $block_id; ?> class="guten-block <?php echo esc_attr($classes); ?>">
    <div class="cta-ticker__viewport">
        <div class="splide cta-ticker__splide" data-cta-ticker>
            <div class="splide__track">
                <ul class="splide__list">
                    <?php foreach ($ticker_items as $item) : ?>
                        <li class="splide__slide cta-ticker__slide">
                            <?php if ($item['url'] !== '') : ?>
                                <a class="cta-ticker__link" href="<?php echo esc_url($item['url']); ?>">
                                    <?php echo esc_html($item['text']); ?>
                                </a>
                            <?php else : ?>
                                <span class="cta-ticker__text"><?php echo esc_html($item['text']); ?></span>
                            <?php endif; ?>
                            <span class="cta-ticker__bullet" aria-hidden="true">•</span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</section>
