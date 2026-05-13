<?php
/**
 * Block - Links + Cards
 *
 * Style 1: tabbed nav + blue rounded card (title, image, CTA).
 * Style 2: same tabs; card is flat — image, optional title, WYSIWYG content, CTA (no blue panel / no rounded corners).
 */

$block_data     = include get_template_directory() . '/acf-blocks/block-settings/block-settings.php';
$style_classes  = $block_data['style_classes'];
$block_id       = ! empty($block_data['block_id']) ? 'id="' . esc_attr($block_data['block_id']) . '"' : '';

$block_name = 'links-cards';
array_unshift($style_classes, $block_name);
$style_classes[] = $block_name;

$block_style = get_field('style');
$block_style = is_string($block_style) ? strtolower(trim($block_style)) : 'style-1';
if ($block_style !== 'style-2') {
    $block_style = 'style-1';
}
$style_classes[] = 'links-cards--' . sanitize_html_class($block_style);

$title = get_field('title');
$title = is_string($title) ? trim($title) : '';

$cards = array();
if (have_rows('cards')) {
    while (have_rows('cards')) {
        the_row();
        $link_title = get_sub_field('link_title');
        $card_title = get_sub_field('card_title');
        $card_image = get_sub_field('card_image');
        $card_cta   = get_sub_field('card_cta');
        $card_raw   = get_sub_field('card_content');

        $link_title = is_string($link_title) ? trim($link_title) : '';
        $card_title = is_string($card_title) ? trim($card_title) : '';

        $content_html = '';
        if (is_string($card_raw) && trim($card_raw) !== '') {
            $plain = trim(wp_strip_all_tags($card_raw, true));
            if ($plain !== '') {
                $content_html = wp_kses_post($card_raw);
            }
        }

        $image_url = is_array($card_image) && ! empty($card_image['url']) ? $card_image['url'] : '';
        $image_alt = is_array($card_image) && isset($card_image['alt']) ? (string) $card_image['alt'] : '';

        $has_cta = is_array($card_cta) && ! empty($card_cta['url']);

        if ($link_title === '' && $card_title === '' && $image_url === '' && ! $has_cta && $content_html === '') {
            continue;
        }

        $cards[] = array(
            'link_title'   => $link_title,
            'card_title'   => $card_title,
            'content_html' => $content_html,
            'image_url'    => $image_url,
            'image_alt'    => $image_alt,
            'cta'          => $card_cta,
            'has_cta'      => $has_cta,
        );
    }
}

if (count($cards) === 0) {
    return;
}

$instance_id = 'links-cards';
if (isset($block) && is_array($block) && ! empty($block['id'])) {
    $instance_id .= '-' . preg_replace('/[^a-z0-9_-]+/i', '-', (string) $block['id']);
} else {
    $instance_id .= '-' . uniqid();
}

$classes = implode(' ', array_filter(array_map('esc_attr', $style_classes)));
?>

<section <?php echo $block_id; ?> class="guten-block <?php echo esc_attr($classes); ?>">
    <div class="container">
        <div class="links-cards__layout" data-links-cards>
            <div class="links-cards__nav-col">
                <?php if ($title !== '') : ?>
                    <p class="links-cards__heading"><?php echo esc_html($title); ?></p>
                <?php endif; ?>

                <ul class="links-cards__tabs" role="tablist" aria-label="<?php echo esc_attr($title !== '' ? $title : __('Content sections', 'solheim')); ?>">
                    <?php foreach ($cards as $i => $row) : ?>
                        <?php
                        $tab_id   = $instance_id . '-tab-' . $i;
                        $panel_id = $instance_id . '-panel-' . $i;
                        $is_first = $i === 0;
                        ?>
                        <li class="links-cards__tab-item" role="presentation">
                            <button
                                type="button"
                                class="links-cards__tab<?php echo $is_first ? ' is-active' : ''; ?>"
                                id="<?php echo esc_attr($tab_id); ?>"
                                role="tab"
                                aria-selected="<?php echo $is_first ? 'true' : 'false'; ?>"
                                aria-controls="<?php echo esc_attr($panel_id); ?>"
                                data-links-cards-tab
                                data-index="<?php echo (int) $i; ?>"
                                tabindex="<?php echo $is_first ? '0' : '-1'; ?>"
                            >
                                <?php echo esc_html($row['link_title'] !== '' ? $row['link_title'] : sprintf(/* translators: %d: item number */ __('Item %d', 'solheim'), $i + 1)); ?>
                            </button>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="links-cards__panel-col">
                <?php foreach ($cards as $i => $row) : ?>
                    <?php
                    $tab_id   = $instance_id . '-tab-' . $i;
                    $panel_id = $instance_id . '-panel-' . $i;
                    $is_first = $i === 0;
                    ?>
                    <div
                        class="links-cards__card<?php echo $is_first ? ' is-active' : ''; ?>"
                        id="<?php echo esc_attr($panel_id); ?>"
                        role="tabpanel"
                        aria-labelledby="<?php echo esc_attr($tab_id); ?>"
                        data-links-cards-panel
                        data-index="<?php echo (int) $i; ?>"
                        <?php echo $is_first ? '' : 'hidden'; ?>
                    >
                        <?php if ($block_style === 'style-2') : ?>
                            <?php if ($row['image_url'] !== '') : ?>
                                <div class="links-cards__card-media">
                                    <img
                                        class="links-cards__card-img"
                                        src="<?php echo esc_url($row['image_url']); ?>"
                                        alt="<?php echo esc_attr($row['image_alt']); ?>"
                                        decoding="async"
                                        loading="<?php echo $is_first ? 'eager' : 'lazy'; ?>"
                                        <?php echo $row['image_alt'] === '' ? ' role="presentation"' : ''; ?>
                                    />
                                </div>
                            <?php endif; ?>

                            <?php if ($row['card_title'] !== '') : ?>
                                <h3 class="links-cards__card-title"><?php echo esc_html($row['card_title']); ?></h3>
                            <?php endif; ?>

                            <?php if ($row['content_html'] !== '') : ?>
                                <div class="links-cards__card-content">
                                    <?php echo $row['content_html']; ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($row['has_cta']) : ?>
                                <?php $cta = $row['cta']; ?>
                                <div class="links-cards__card-cta">
                                    <a
                                        class="links-cards__cta links-cards__cta--style-2"
                                        href="<?php echo esc_url($cta['url']); ?>"
                                        <?php echo ! empty($cta['target']) ? ' target="' . esc_attr($cta['target']) . '"' : ''; ?>
                                        <?php echo ! empty($cta['target']) && $cta['target'] === '_blank' ? ' rel="noopener noreferrer"' : ''; ?>
                                    >
                                        <?php echo esc_html($cta['title'] !== '' ? $cta['title'] : $cta['url']); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        <?php else : ?>
                            <?php if ($row['card_title'] !== '') : ?>
                                <h3 class="links-cards__card-title"><?php echo esc_html($row['card_title']); ?></h3>
                            <?php endif; ?>

                            <?php if ($row['image_url'] !== '') : ?>
                                <div class="links-cards__card-media">
                                    <img
                                        class="links-cards__card-img"
                                        src="<?php echo esc_url($row['image_url']); ?>"
                                        alt="<?php echo esc_attr($row['image_alt']); ?>"
                                        decoding="async"
                                        loading="<?php echo $is_first ? 'eager' : 'lazy'; ?>"
                                        <?php echo $row['image_alt'] === '' ? ' role="presentation"' : ''; ?>
                                    />
                                </div>
                            <?php endif; ?>

                            <?php if ($row['has_cta']) : ?>
                                <?php $cta = $row['cta']; ?>
                                <div class="links-cards__card-cta">
                                    <a
                                        class="links-cards__cta"
                                        href="<?php echo esc_url($cta['url']); ?>"
                                        <?php echo ! empty($cta['target']) ? ' target="' . esc_attr($cta['target']) . '"' : ''; ?>
                                        <?php echo ! empty($cta['target']) && $cta['target'] === '_blank' ? ' rel="noopener noreferrer"' : ''; ?>
                                    >
                                        <?php echo esc_html($cta['title'] !== '' ? $cta['title'] : $cta['url']); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
