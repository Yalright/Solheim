<?php
/**
 * Block - Cards (Overlayed)
 */

$block_data     = include get_template_directory() . '/acf-blocks/block-settings/block-settings.php';
$style_classes  = $block_data['style_classes'];
$block_id       = ! empty($block_data['block_id']) ? 'id="' . esc_attr($block_data['block_id']) . '"' : '';

$block_name = 'cards-overlayed';
array_unshift($style_classes, $block_name);
$style_classes[] = $block_name;

$cards = get_field('cards');
$cards = is_array($cards) ? $cards : array();

$classes = implode(' ', array_filter(array_map('esc_attr', $style_classes)));
?>

<section <?php echo $block_id; ?> class="guten-block <?php echo esc_attr($classes); ?>">
    <?php if (! empty($cards)) : ?>
        <div class="cards-overlayed__stack">
            <?php foreach ($cards as $index => $card) : ?>
                <?php
                if (! is_array($card)) {
                    continue;
                }

                $theme   = isset($card['theme']) ? trim((string) $card['theme']) : '';
                $image   = isset($card['image']) ? $card['image'] : null;
                $title   = isset($card['title']) ? trim((string) $card['title']) : '';
                $content = isset($card['content']) ? (string) $card['content'] : '';
                $cta     = isset($card['cta']) && is_array($card['cta']) ? $card['cta'] : array();

                $image_url = is_array($image) && ! empty($image['url']) ? $image['url'] : '';
                $image_alt = is_array($image) && isset($image['alt']) ? (string) $image['alt'] : '';

                $card_classes = array('cards-overlayed__card');
                if ($index === 0) {
                    $card_classes[] = 'is-active';
                }

                if ($theme !== '') {
                    if (strpos($theme, 'has-') !== false) {
                        $theme_tokens = preg_split('/\s+/', $theme);
                        if (is_array($theme_tokens)) {
                            foreach ($theme_tokens as $token) {
                                $token = sanitize_html_class($token);
                                if ($token !== '') {
                                    $card_classes[] = $token;
                                }
                            }
                        }
                    } else {
                        $slug = sanitize_html_class($theme);
                        if ($slug !== '') {
                            $card_classes[] = 'has-background';
                            $card_classes[] = 'has-' . $slug . '-background-color';
                        }
                    }
                }
                ?>
                <article class="<?php echo esc_attr(implode(' ', array_filter($card_classes))); ?>" style="--card-index:<?php echo esc_attr((string) $index); ?>;">
                    <div class="cards-overlayed__image-col">
                        <?php if ($image_url !== '') : ?>
                            <div
                                class="cards-overlayed__image"
                                style="background-image:url(<?php echo esc_url($image_url); ?>);"
                                <?php if ($image_alt !== '') : ?>
                                    role="img"
                                    aria-label="<?php echo esc_attr($image_alt); ?>"
                                <?php else : ?>
                                    aria-hidden="true"
                                <?php endif; ?>
                            ></div>
                        <?php endif; ?>
                    </div>

                    <div class="cards-overlayed__content-col">
                        <?php if ($title !== '') : ?>
                            <h3 class="cards-overlayed__title"><?php echo esc_html($title); ?></h3>
                            <span class="cards-overlayed__title-rotated" aria-hidden="true"><?php echo esc_html($title); ?></span>
                        <?php endif; ?>

                        <?php if ($content !== '') : ?>
                            <div class="cards-overlayed__content"><?php echo wp_kses_post($content); ?></div>
                        <?php endif; ?>

                        <?php if (! empty($cta['url'])) : ?>
                            <a
                                class="btn-navy cards-overlayed__cta"
                                href="<?php echo esc_url($cta['url']); ?>"
                                <?php echo ! empty($cta['target']) ? ' target="' . esc_attr($cta['target']) . '"' : ''; ?>
                                <?php echo ! empty($cta['target']) && $cta['target'] === '_blank' ? ' rel="noopener noreferrer"' : ''; ?>
                            >
                                <?php echo esc_html($cta['title'] !== '' ? $cta['title'] : $cta['url']); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
