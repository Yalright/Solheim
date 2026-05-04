<?php
/**
 * Block - Promotional - Image + Text
 */

$block_data     = include get_template_directory() . '/acf-blocks/block-settings/block-settings.php';
$style_classes  = $block_data['style_classes'];
$block_id       = ! empty($block_data['block_id']) ? 'id="' . esc_attr($block_data['block_id']) . '"' : '';

$block_name = 'promotional-image-text';
array_unshift($style_classes, $block_name);
$style_classes[] = $block_name;

$classes = implode(' ', array_filter(array_map('esc_attr', $style_classes)));

$cta = get_field('cta');
$has_cta = is_array($cta) && ! empty($cta['url']);
?>

<section <?php echo $block_id; ?> class="guten-block <?php echo esc_attr($classes); ?>">
    <?php if (have_rows('section_row')) : ?>
        <?php
        while (have_rows('section_row')) :
            the_row();
            $title          = get_sub_field('title');
            $content        = get_sub_field('content');
            $image          = get_sub_field('image');
            $image_position     = get_sub_field('image_position');
            $title_colour       = get_sub_field('title_colour');
            $horizontal_offset  = get_sub_field('horizontal_offset_%');

            $title   = is_string($title) ? trim($title) : '';
            $content = is_string($content) ? trim($content) : '';

            $image_url = '';
            $image_alt = '';
            if (is_array($image) && ! empty($image['url'])) {
                $image_url = $image['url'];
                $image_alt = isset($image['alt']) ? (string) $image['alt'] : '';
            }

            if ($title === '' && $content === '' && $image_url === '') {
                continue;
            }

            $img_label = $image_alt !== '' ? $image_alt : $title;

            $image_position = is_string($image_position) ? $image_position : 'left';
            if (! in_array($image_position, array('left', 'right'), true)) {
                $image_position = 'left';
            }

            $section_mod = 'promotional-image-text__section--img-' . $image_position;
            $chunk_order = $image_position === 'right'
                ? array('content', 'title', 'image')
                : array('image', 'title', 'content');

            $title_classes = 'promotional-image-text__title';
            if (is_string($title_colour) && trim($title_colour) !== '') {
                $title_colour_classes = preg_split('/\s+/', trim($title_colour));
                if (is_array($title_colour_classes) && ! empty($title_colour_classes)) {
                    $title_colour_classes = array_filter(array_map('sanitize_html_class', $title_colour_classes));
                    if (! empty($title_colour_classes)) {
                        $title_classes .= ' ' . implode(' ', $title_colour_classes);
                    }
                }
            }

            $row_transform = '';
            if ($horizontal_offset !== '' && $horizontal_offset !== null && is_numeric($horizontal_offset)) {
                $row_transform = sprintf('transform: translateX(%s%%);', floatval($horizontal_offset));
            }
            ?>
            <div class="promotional-image-text__section <?php echo esc_attr($section_mod); ?>">
                <div class="promotional-image-text__row"<?php echo $row_transform !== '' ? ' style="' . esc_attr($row_transform) . '"' : ''; ?>>
                    <?php
                    foreach ($chunk_order as $chunk) {
                        if ($chunk === 'title' && $title !== '') {
                            ?>
                            <h2 class="<?php echo esc_attr($title_classes); ?>"><?php echo esc_html($title); ?></h2>
                            <?php
                        } elseif ($chunk === 'content' && $content !== '') {
                            ?>
                            <div class="promotional-image-text__content"><?php echo wp_kses_post($content); ?></div>
                            <?php
                        } elseif ($chunk === 'image' && $image_url !== '') {
                            ?>
                            <div
                                class="promotional-image-text__image"
                                style="background-image:url(<?php echo esc_url($image_url); ?>);"
                                <?php if ($img_label !== '') : ?>
                                    role="img"
                                    aria-label="<?php echo esc_attr($img_label); ?>"
                                <?php else : ?>
                                    aria-hidden="true"
                                <?php endif; ?>
                            ></div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>

    <?php if ($has_cta) : ?>
        <div class="promotional-image-text__cta-wrap">
            <a class="btn-navy promotional-image-text__cta"
                href="<?php echo esc_url($cta['url']); ?>"
                <?php echo ! empty($cta['target']) ? ' target="' . esc_attr($cta['target']) . '"' : ''; ?>
                <?php echo ! empty($cta['target']) && $cta['target'] === '_blank' ? ' rel="noopener noreferrer"' : ''; ?>>
                <?php echo esc_html($cta['title'] !== '' ? $cta['title'] : $cta['url']); ?>
            </a>
        </div>
    <?php endif; ?>
</section>
