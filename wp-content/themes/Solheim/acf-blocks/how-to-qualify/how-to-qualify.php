<?php
/**
 * Block - How to Qualify
 *
 * ACF (create in WP):
 * - ctas (repeater) → cta (link)
 * - teams (repeater) → title (text), content (wysiwyg)
 *
 * Team title: use " | " to split into two coloured parts (e.g. TEAM | EUROPE, U.S. | TEAM).
 */

$block_data     = include get_template_directory() . '/acf-blocks/block-settings/block-settings.php';
$style_classes  = $block_data['style_classes'];
$block_id       = ! empty($block_data['block_id']) ? 'id="' . esc_attr($block_data['block_id']) . '"' : '';

$block_name = 'how-to-qualify';
array_unshift($style_classes, $block_name);
$style_classes[] = $block_name;

$cta_rows = array();
if (have_rows('ctas')) {
    while (have_rows('ctas')) {
        the_row();
        $cta = get_sub_field('cta');
        if (! is_array($cta) || empty($cta['url'])) {
            continue;
        }
        $cta_rows[] = array(
            'url'    => $cta['url'],
            'title'  => ! empty($cta['title']) ? (string) $cta['title'] : '',
            'target' => ! empty($cta['target']) ? (string) $cta['target'] : '',
        );
    }
}

$team_rows = array();
if (have_rows('teams')) {
    while (have_rows('teams')) {
        the_row();
        $title = get_sub_field('title');
        $title  = is_string($title) ? trim($title) : '';
        $content = get_sub_field('content');
        $content_html = '';
        if (is_string($content) && trim($content) !== '') {
            $plain = trim(wp_strip_all_tags($content, true));
            if ($plain !== '') {
                $content_html = wp_kses_post($content);
            }
        }
        if ($title === '' && $content_html === '') {
            continue;
        }
        $team_rows[] = array(
            'title'        => $title,
            'content_html' => $content_html,
        );
    }
}

$ping_svg_url = get_template_directory_uri() . '/assets/images/text-ping-junior.svg';

$classes = implode(' ', array_filter(array_map('esc_attr', $style_classes)));
?>

<section <?php echo $block_id; ?> class="guten-block <?php echo esc_attr($classes); ?>">
    <div class="how-to-qualify__inner container">
        <div class="how-to-qualify__grid">
            <div class="how-to-qualify__col how-to-qualify__col--left">
                <h2 class="how-to-qualify__heading">
                    <span class="how-to-qualify__heading-line how-to-qualify__heading-line--small"><?php esc_html_e('HOW TO', 'solheim'); ?></span>
                    <span class="how-to-qualify__heading-line how-to-qualify__heading-line--large"><?php esc_html_e('QUALIFY', 'solheim'); ?></span>
                </h2>
                <div class="how-to-qualify__ping" aria-hidden="true">
                    <img
                        class="how-to-qualify__ping-img"
                        src="<?php echo esc_url($ping_svg_url); ?>"
                        alt=""
                        width="362"
                        height="84"
                        loading="lazy"
                        decoding="async"
                    />
                </div>
                <?php if (count($cta_rows) > 0) : ?>
                    <ul class="how-to-qualify__ctas">
                        <?php foreach ($cta_rows as $row) : ?>
                            <li class="how-to-qualify__cta-item">
                                <a
                                    class="btn-outline-white how-to-qualify__cta"
                                    href="<?php echo esc_url($row['url']); ?>"
                                    <?php echo $row['target'] !== '' ? ' target="' . esc_attr($row['target']) . '"' : ''; ?>
                                    <?php echo $row['target'] === '_blank' ? ' rel="noopener noreferrer"' : ''; ?>
                                >
                                    <?php echo esc_html($row['title'] !== '' ? $row['title'] : $row['url']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <div class="how-to-qualify__col how-to-qualify__col--right">
                <?php if (count($team_rows) > 0) : ?>
                    <div class="how-to-qualify__teams">
                        <?php foreach ($team_rows as $ti => $team) : ?>
                            <section class="how-to-qualify__team">
                                <?php if ($team['title'] !== '') : ?>
                                    <header class="how-to-qualify__team-header">
                                        <h3 class="how-to-qualify__team-title">
                                            <?php
                                            $title_raw = $team['title'];
                                            if (strpos($title_raw, '|') !== false) {
                                                $parts  = array_map('trim', explode('|', $title_raw, 2));
                                                $part_a = isset($parts[0]) ? $parts[0] : '';
                                                $part_b = isset($parts[1]) ? $parts[1] : '';
                                                $even   = ($ti % 2 === 0);
                                                ?>
                                                <span class="how-to-qualify__team-title-part <?php echo $even ? 'how-to-qualify__team-title-part--lead' : 'how-to-qualify__team-title-part--accent-pink'; ?>"><?php echo esc_html($part_a); ?></span>
                                                <?php if ($part_b !== '') : ?>
                                                    <span class="how-to-qualify__team-title-part <?php echo $even ? 'how-to-qualify__team-title-part--accent-blue' : 'how-to-qualify__team-title-part--lead'; ?>"><?php echo esc_html($part_b); ?></span>
                                                <?php endif; ?>
                                                <?php
                                            } else {
                                                ?>
                                                <span class="how-to-qualify__team-title-part how-to-qualify__team-title-part--single"><?php echo esc_html($title_raw); ?></span>
                                                <?php
                                            }
                                            ?>
                                        </h3>
                                        <span class="how-to-qualify__team-chevron" aria-hidden="true"></span>
                                    </header>
                                    <div class="how-to-qualify__team-rule" role="presentation"></div>
                                <?php endif; ?>
                                <?php if ($team['content_html'] !== '') : ?>
                                    <div class="how-to-qualify__team-content">
                                        <?php echo $team['content_html']; ?>
                                    </div>
                                <?php endif; ?>
                            </section>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
