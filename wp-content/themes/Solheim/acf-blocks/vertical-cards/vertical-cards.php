<?php
/**
 * Block - Vertical Cards
 */

$block_data     = include get_template_directory() . '/acf-blocks/block-settings/block-settings.php';
$style_classes  = $block_data['style_classes'];
$block_id       = ! empty($block_data['block_id']) ? 'id="' . esc_attr($block_data['block_id']) . '"' : '';

$block_name = 'vertical-cards';
array_unshift($style_classes, $block_name);

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

$title_label = get_field('title');
$title_label = is_string($title_label) ? trim($title_label) : '';
if ($title_label === '') {
    $title_label = 'Hole';
}

$rows = get_field('cards');
$cards = array();

if ( ! empty($rows) && is_array($rows)) {
    foreach ($rows as $row) {
        if ( ! is_array($row)) {
            continue;
        }
        $card_title = isset($row['title']) ? trim((string) $row['title']) : '';
        $d1         = isset($row['details_1']) ? trim((string) $row['details_1']) : '';
        $d2         = isset($row['details_2']) ? trim((string) $row['details_2']) : '';
        $d3         = isset($row['details_3']) ? trim((string) $row['details_3']) : '';
        $content    = isset($row['content']) ? $row['content'] : '';
        $image      = isset($row['image']) && is_array($row['image']) ? $row['image'] : array();

        $img_url = ! empty($image['url']) ? $image['url'] : '';
        $img_alt = isset($image['alt']) ? (string) $image['alt'] : '';

        if ($card_title === '' && $d1 === '' && $d2 === '' && $d3 === '' && ( ! is_string($content) || trim($content) === '') && $img_url === '') {
            continue;
        }

        $cards[] = array(
            'title'    => $card_title,
            'details'  => array_filter(array($d1, $d2, $d3)),
            'content'  => is_string($content) ? $content : '',
            'img_url'  => $img_url,
            'img_alt'  => $img_alt,
        );
    }
}

$classes = implode(' ', array_filter(array_map('esc_attr', $style_classes)));

if (empty($cards)) {
    return;
}

$vc_uid     = 'vertical-cards-' . ( function_exists('wp_unique_id') ? wp_unique_id() : uniqid() );
$arrow_path = 'm15.5 0.932-4.3 4.38 14.5 14.6-14.5 14.5 4.3 4.4 14.6-14.6 4.4-4.3-4.4-4.4-14.6-14.6z';
?>

<section <?php echo $block_id; ?> class="guten-block <?php echo esc_attr($classes); ?>" data-vertical-cards>
    <div class="vertical-cards__inner">
        <div class="vertical-cards__toolbar">
            <p class="vertical-cards__eyebrow"><?php echo esc_html($title_label); ?></p>

            <div class="vertical-cards__pagination" role="tablist" aria-label="<?php esc_attr_e('Slides', 'solheim'); ?>">
                <?php foreach ($cards as $idx => $_card) : ?>
                    <button
                        type="button"
                        class="vertical-cards__page<?php echo $idx === 0 ? ' is-active' : ''; ?>"
                        data-vcards-go="<?php echo (int) $idx; ?>"
                        role="tab"
                        aria-selected="<?php echo $idx === 0 ? 'true' : 'false'; ?>"
                        aria-controls="<?php echo esc_attr($vc_uid . '-slide-' . $idx); ?>"
                        id="<?php echo esc_attr($vc_uid . '-tab-' . $idx); ?>"
                    >
                        <?php echo (int) ($idx + 1); ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <div class="vertical-cards__arrows" data-vcards-arrows>
                <div class="vertical-cards__arrow-group">
                    <button
                        type="button"
                        class="vertical-cards__arrow vertical-cards__arrow--prev"
                        data-vcards-prev
                        aria-label="<?php esc_attr_e('Previous slide', 'solheim'); ?>"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" width="40" height="40" aria-hidden="true" focusable="false"><path d="<?php echo esc_attr($arrow_path); ?>"/></svg>
                    </button>
                    <button
                        type="button"
                        class="vertical-cards__arrow vertical-cards__arrow--next"
                        data-vcards-next
                        aria-label="<?php esc_attr_e('Next slide', 'solheim'); ?>"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" width="40" height="40" aria-hidden="true" focusable="false"><path d="<?php echo esc_attr($arrow_path); ?>"/></svg>
                    </button>
                </div>
            </div>
        </div>

        <div
            id="<?php echo esc_attr($vc_uid); ?>"
            class="vertical-cards__slider"
            data-vertical-cards-slider
            aria-roledescription="<?php echo esc_attr( __( 'carousel', 'solheim' ) ); ?>"
            aria-label="<?php echo esc_attr($title_label); ?>"
        >
            <div class="vertical-cards__track">
                <ul class="vertical-cards__list">
                    <?php foreach ($cards as $idx => $card) : ?>
                        <li
                            class="vertical-cards__slide<?php echo $idx === 0 ? ' is-active' : ''; ?>"
                            id="<?php echo esc_attr($vc_uid . '-slide-' . $idx); ?>"
                            role="tabpanel"
                            aria-labelledby="<?php echo esc_attr($vc_uid . '-tab-' . $idx); ?>"
                            <?php echo $idx !== 0 ? ' aria-hidden="true"' : ''; ?>
                        >
                            <article class="vertical-cards__card<?php echo $card['img_url'] === '' ? ' vertical-cards__card--no-media' : ''; ?>">
                                <div class="vertical-cards__card-body">
                                    <div class="vertical-cards__card-text">
                                        <?php if ($card['title'] !== '' || ! empty($card['details'])) : ?>
                                            <div class="vertical-cards__card-heading">
                                                <?php if ($card['title'] !== '') : ?>
                                                    <h3 class="vertical-cards__card-title"><?php echo esc_html($card['title']); ?></h3>
                                                <?php endif; ?>

                                                <?php if ( ! empty($card['details'])) : ?>
                                                    <ul class="vertical-cards__card-details">
                                                        <?php foreach ($card['details'] as $line) : ?>
                                                            <li class="vertical-cards__card-detail"><?php echo esc_html($line); ?></li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>

                                        <?php if (is_string($card['content']) && trim($card['content']) !== '') : ?>
                                            <div class="vertical-cards__card-content">
                                                <?php echo wp_kses_post($card['content']); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <?php if ($card['img_url'] !== '') : ?>
                                        <div class="vertical-cards__card-media">
                                            <img
                                                class="vertical-cards__card-img"
                                                src="<?php echo esc_url($card['img_url']); ?>"
                                                alt="<?php echo esc_attr($card['img_alt'] !== '' ? $card['img_alt'] : $card['title']); ?>"
                                                loading="<?php echo $idx === 0 ? 'eager' : 'lazy'; ?>"
                                                decoding="async"
                                            />
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </article>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</section>
