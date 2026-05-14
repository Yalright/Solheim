<?php
/**
 * Block - FAQs
 *
 * style-1: Two-column layout (unchanged). Category nav = anchor jump links; each category block visible.
 * style-2: Single column max 750px; ripple decoration; block title; category tabs switch visible FAQ set.
 *
 * ACF: style (style-1 | style-2), background_image, image, title, faqs_categories → title, faqs → question, answer
 */

$block_data     = include get_template_directory() . '/acf-blocks/block-settings/block-settings.php';
$style_classes  = $block_data['style_classes'];
$block_id       = ! empty($block_data['block_id']) ? 'id="' . esc_attr($block_data['block_id']) . '"' : '';

$block_name = 'faqs';
array_unshift($style_classes, $block_name);
$style_classes[] = $block_name;

$style_raw = get_field('style');
$style     = (is_string($style_raw) && trim($style_raw) === 'style-2') ? 'style-2' : 'style-1';
$style_classes[] = 'faqs--' . $style;

if ($style === 'style-1' && function_exists('solheim_is_acf_faqs_first_block_after_header') && solheim_is_acf_faqs_first_block_after_header()) {
    $style_classes[] = 'faqs--first-after-header';
}

$background_image = get_field('background_image');
$image            = get_field('image');

$bg_url = is_array($background_image) && ! empty($background_image['url']) ? $background_image['url'] : '';

$img_url = is_array($image) && ! empty($image['url']) ? $image['url'] : '';
$img_alt = is_array($image) && isset($image['alt']) ? (string) $image['alt'] : '';

$faqs_categories = get_field('faqs_categories');
$faqs_categories = is_array($faqs_categories) ? $faqs_categories : array();
$category_count    = count($faqs_categories);

$classes = implode(' ', array_filter(array_map('esc_attr', $style_classes)));

$faqs_uid = function_exists('wp_unique_id') ? wp_unique_id('faqs-') : 'faqs-' . uniqid('', false);

/** @var array<int, array{title: string, faqs: array<int, array{question: string, answer: string}>}> $s2_categories */
$s2_categories = array();
if ($style === 'style-2') {
    foreach ($faqs_categories as $cat_row) {
        if (! is_array($cat_row)) {
            continue;
        }
        $cat_title = isset($cat_row['title']) ? trim((string) $cat_row['title']) : '';
        $faqs_rows = isset($cat_row['faqs']) && is_array($cat_row['faqs']) ? $cat_row['faqs'] : array();
        $items     = array();
        foreach ($faqs_rows as $faq_row) {
            if (! is_array($faq_row)) {
                continue;
            }
            $question = isset($faq_row['question']) ? trim((string) $faq_row['question']) : '';
            $answer   = isset($faq_row['answer']) ? trim((string) $faq_row['answer']) : '';
            if ($question === '' && $answer === '') {
                continue;
            }
            $items[] = array(
                'question' => $question,
                'answer'   => $answer,
            );
        }
        if ($cat_title === '' && count($items) === 0) {
            continue;
        }
        $s2_categories[] = array(
            'title' => $cat_title,
            'faqs'  => $items,
        );
    }
}

$block_title = get_field('title');
$block_title = is_string($block_title) ? trim($block_title) : '';

$ripple_rel = '/assets/images/ripple-navy.png';
$ripple_abs = get_template_directory() . $ripple_rel;
$ripple_url = file_exists($ripple_abs)
    ? (get_template_directory_uri() . $ripple_rel)
    : '';
?>

<?php if ($style === 'style-1') : ?>
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

<?php elseif ($style === 'style-2' && (count($s2_categories) > 0 || $block_title !== '')) : ?>
<section
    <?php echo $block_id; ?>
    class="guten-block <?php echo esc_attr($classes); ?>"
    <?php echo count($s2_categories) > 1 ? ' data-faqs-style2="1"' : ''; ?>
>
    <?php if ($ripple_url !== '') : ?>
        <div class="faqs__s2-ripple" aria-hidden="true" style="<?php echo esc_attr('background-image:url(' . esc_url($ripple_url) . ');'); ?>"></div>
    <?php endif; ?>

    <div class="container faqs__s2-container">
        <div class="faqs__s2-inner">
            <?php if ($block_title !== '') : ?>
                <?php
                $title_lines = preg_split('/\r\n|\r|\n/', $block_title);
                $title_lines = is_array($title_lines) ? array_values(array_filter(array_map('trim', $title_lines))) : array($block_title);
                if (count($title_lines) === 0) {
                    $title_lines = array($block_title);
                }
                $line_count = count($title_lines);
                ?>
                <h2 class="faqs__s2-title">
                    <?php foreach ($title_lines as $ti => $line) : ?>
                        <?php
                        $is_last = ($ti === $line_count - 1);
                        $line_class = 'faqs__s2-title-line' . ($is_last && $line_count > 1 ? ' faqs__s2-title-line--accent' : ($line_count > 1 ? ' faqs__s2-title-line--lead' : ' faqs__s2-title-line--single'));
                        ?>
                        <span class="<?php echo esc_attr($line_class); ?>"><?php echo esc_html($line); ?></span>
                        <?php if (! $is_last) : ?>
                            <br />
                        <?php endif; ?>
                    <?php endforeach; ?>
                </h2>
            <?php endif; ?>

            <?php if (count($s2_categories) > 1) : ?>
                <div class="faqs__s2-tabs" role="tablist" aria-label="<?php echo esc_attr(__('FAQ categories', 'solheim')); ?>">
                    <?php foreach ($s2_categories as $ti => $cat) : ?>
                        <?php
                        $tab_id    = $faqs_uid . '-tab-' . $ti;
                        $panel_id  = $faqs_uid . '-panel-' . $ti;
                        $tab_label = $cat['title'] !== '' ? $cat['title'] : sprintf(
                            /* translators: %d: category index (1-based) */
                            __('Category %d', 'solheim'),
                            $ti + 1
                        );
                        ?>
                        <button
                            class="faqs__s2-tab<?php echo $ti === 0 ? ' is-active' : ''; ?>"
                            type="button"
                            role="tab"
                            id="<?php echo esc_attr($tab_id); ?>"
                            aria-selected="<?php echo $ti === 0 ? 'true' : 'false'; ?>"
                            aria-controls="<?php echo esc_attr($panel_id); ?>"
                            tabindex="<?php echo $ti === 0 ? '0' : '-1'; ?>"
                            data-faqs-s2-tab
                            data-faqs-s2-index="<?php echo (int) $ti; ?>"
                        >
                            <?php echo esc_html($tab_label); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (count($s2_categories) > 0) : ?>
                <div class="faqs__s2-panels">
                    <?php foreach ($s2_categories as $pi => $cat) : ?>
                        <?php
                        $tab_id   = $faqs_uid . '-tab-' . $pi;
                        $panel_id = $faqs_uid . '-panel-' . $pi;
                        $use_tabs = count($s2_categories) > 1;
                        ?>
                        <div
                            class="faqs__s2-panel"
                            id="<?php echo esc_attr($panel_id); ?>"
                            role="<?php echo $use_tabs ? 'tabpanel' : 'region'; ?>"
                            <?php if ($use_tabs) : ?>
                                aria-labelledby="<?php echo esc_attr($tab_id); ?>"
                            <?php else : ?>
                                aria-label="<?php echo esc_attr($cat['title'] !== '' ? $cat['title'] : __('FAQs', 'solheim')); ?>"
                            <?php endif; ?>
                            <?php echo $pi !== 0 ? ' hidden' : ''; ?>
                            data-faqs-s2-panel
                            data-faqs-s2-index="<?php echo (int) $pi; ?>"
                        >
                            <?php if (count($cat['faqs']) > 0) : ?>
                                <ul class="faqs__list faqs__s2-list">
                                    <?php foreach ($cat['faqs'] as $faq) : ?>
                                        <li class="faqs__list-item">
                                            <details class="faqs__item">
                                                <summary class="faqs__question">
                                                    <span class="faqs__question-text"><?php echo esc_html($faq['question'] !== '' ? $faq['question'] : __('Question', 'solheim')); ?></span>
                                                    <span class="faqs__question-icon" aria-hidden="true"></span>
                                                </summary>
                                                <?php if ($faq['answer'] !== '') : ?>
                                                    <div class="faqs__answer">
                                                        <?php echo wp_kses_post($faq['answer']); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </details>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>
