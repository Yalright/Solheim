<?php
/**
 * Block - FAQs
 *
 * Category titles: horizontal “tab-style” nav of anchor links + same title as h2 above each FAQ group.
 * No faqs_sections repeater — each category contains faqs (question / answer) only.
 *
 * ACF: background_image, image, faqs_categories (repeater) → title, faqs (repeater) → question, answer
 */

$block_data     = include get_template_directory() . '/acf-blocks/block-settings/block-settings.php';
$style_classes  = $block_data['style_classes'];
$block_id       = ! empty($block_data['block_id']) ? 'id="' . esc_attr($block_data['block_id']) . '"' : '';

$block_name = 'faqs';
array_unshift($style_classes, $block_name);
$style_classes[] = $block_name;

if (function_exists('solheim_is_acf_faqs_first_block_after_header') && solheim_is_acf_faqs_first_block_after_header()) {
    $style_classes[] = 'faqs--first-after-header';
}

$background_image = get_field('background_image');
$image            = get_field('image');

$bg_url = is_array($background_image) && ! empty($background_image['url']) ? $background_image['url'] : '';

$img_url = is_array($image) && ! empty($image['url']) ? $image['url'] : '';
$img_alt = is_array($image) && isset($image['alt']) ? (string) $image['alt'] : '';

$faqs_categories = get_field('faqs_categories');
$category_count  = is_array($faqs_categories) ? count($faqs_categories) : 0;

$classes = implode(' ', array_filter(array_map('esc_attr', $style_classes)));

$faqs_uid = function_exists('wp_unique_id') ? wp_unique_id('faqs-') : 'faqs-' . uniqid('', false);
?>

<section <?php echo $block_id; ?> class="guten-block <?php echo esc_attr($classes); ?>">
    <?php if ($bg_url !== '') : ?>
        <div class="faqs__bg-image" aria-hidden="true">
            <img
                class="faqs__bg-image-img"
                src="<?php echo esc_url($bg_url); ?>"
                alt=""
            />
        </div>
    <?php endif; ?>

    <div class="container faqs__inner">
        <div class="faqs__col faqs__col--image">
            <?php if ($img_url !== '') : ?>
                <figure class="faqs__figure">
                    <img
                        class="faqs__image"
                        src="<?php echo esc_url($img_url); ?>"
                        alt="<?php echo esc_attr($img_alt); ?>"
                        loading="lazy"
                        decoding="async"
                    />
                </figure>
            <?php endif; ?>
        </div>

        <div class="faqs__col faqs__col--content">
            <?php if ($category_count > 0) : ?>
                <div class="faqs__content-stack">
                    <?php if ($category_count > 1) : ?>
                        <nav class="faqs__nav-wrap" aria-label="<?php echo esc_attr__('Jump to FAQ category', 'solheim'); ?>">
                            <ul class="faqs__nav">
                                <?php
                                $nav_i = 0;
                                while (have_rows('faqs_categories')) :
                                    the_row();
                                    $nav_label = get_sub_field('title');
                                    $nav_label = is_string($nav_label) ? trim($nav_label) : '';
                                    if ($nav_label === '') {
                                        $nav_label = sprintf(
                                            /* translators: %d: category index (1-based) */
                                            __('Category %d', 'solheim'),
                                            $nav_i + 1
                                        );
                                    }
                                    $anchor_id = $faqs_uid . '-cat-' . $nav_i;
                                    ?>
                                    <li class="faqs__nav-item">
                                        <a class="faqs__nav-link" href="#<?php echo esc_attr($anchor_id); ?>">
                                            <?php echo esc_html($nav_label); ?>
                                        </a>
                                    </li>
                                    <?php
                                    $nav_i++;
                                endwhile;
                                ?>
                            </ul>
                        </nav>
                        <?php
                        reset_rows('faqs_categories');
                    endif;
                    ?>

                    <div class="faqs__categories">
                        <?php
                        $cat_i = 0;
                        while (have_rows('faqs_categories')) :
                            the_row();
                            $cat_title = get_sub_field('title');
                            $cat_title = is_string($cat_title) ? trim($cat_title) : '';
                            $heading     = $cat_title !== '' ? $cat_title : sprintf(
                                __('Category %d', 'solheim'),
                                $cat_i + 1
                            );
                            $anchor_id = $faqs_uid . '-cat-' . $cat_i;
                            ?>
                            <div class="faqs__category" id="<?php echo esc_attr($anchor_id); ?>">
                                <h2 class="faqs__category-title"><?php echo esc_html($heading); ?></h2>

                                <?php if (have_rows('faqs')) : ?>
                                    <ul class="faqs__list">
                                        <?php
                                        while (have_rows('faqs')) :
                                            the_row();
                                            $question = get_sub_field('question');
                                            $answer   = get_sub_field('answer');
                                            $question = is_string($question) ? trim($question) : '';
                                            $answer   = is_string($answer) ? trim($answer) : '';
                                            if ($question === '' && $answer === '') {
                                                continue;
                                            }
                                            ?>
                                        <li class="faqs__list-item">
                                            <details class="faqs__item">
                                                <summary class="faqs__question">
                                                    <span class="faqs__question-text"><?php echo esc_html($question !== '' ? $question : __('Question', 'solheim')); ?></span>
                                                    <span class="faqs__question-icon" aria-hidden="true"></span>
                                                </summary>
                                                <?php if ($answer !== '') : ?>
                                                    <div class="faqs__answer">
                                                        <?php echo wp_kses_post($answer); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </details>
                                        </li>
                                            <?php
                                        endwhile;
                                        ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                            <?php
                            $cat_i++;
                        endwhile;
                        ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
