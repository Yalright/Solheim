<?php
/**
 * Block - Meet The Team
 */

$block_data     = include get_template_directory() . '/acf-blocks/block-settings/block-settings.php';
$style_classes  = $block_data['style_classes'];
$block_id       = ! empty($block_data['block_id']) ? 'id="' . esc_attr($block_data['block_id']) . '"' : '';

$block_name = 'meet-the-team';
array_unshift($style_classes, $block_name);
$style_classes[] = $block_name;

$theme = get_field('theme');
$theme = is_string($theme) ? sanitize_html_class(trim($theme)) : '';
if ($theme === '') {
    $theme = 'navy';
}
$style_classes[] = 'meet-the-team--theme-' . $theme;

$title = get_field('title');
$title = is_string($title) ? trim($title) : '';

$title_lead = '';
$title_last = '';
if ($title !== '') {
    if (preg_match('/\s/u', $title)) {
        $parts      = preg_split('/\s+/u', $title);
        $title_last = (string) array_pop($parts);
        $title_lead = implode(' ', $parts);
    } else {
        $title_lead = $title;
    }
}

$introduction = get_field('introduction');

$team_members = array();
if (have_rows('team')) {
    while (have_rows('team')) {
        the_row();
        $image = get_sub_field('image');
        $name  = get_sub_field('name');
        $role  = get_sub_field('role');

        $name = is_string($name) ? trim($name) : '';
        $role = is_string($role) ? trim($role) : '';

        $img_url = is_array($image) && ! empty($image['url']) ? $image['url'] : '';
        $img_alt = is_array($image) && isset($image['alt']) ? (string) $image['alt'] : '';

        if ($img_url === '' && $name === '' && $role === '') {
            continue;
        }

        $team_members[] = array(
            'image_url' => $img_url,
            'image_alt' => $img_alt,
            'name'      => $name,
            'role'      => $role,
        );
    }
}

$classes = implode(' ', array_filter(array_map('esc_attr', $style_classes)));

$has_intro = is_string($introduction) && trim($introduction) !== '';
$header_mod  = '';
if ($title !== '' && ! $has_intro) {
    $header_mod = ' meet-the-team__header--title-only';
} elseif ($title === '' && $has_intro) {
    $header_mod = ' meet-the-team__header--intro-only';
}
?>

<section <?php echo $block_id; ?> class="guten-block <?php echo esc_attr($classes); ?>">
    <div class="container">
        <?php if ($title !== '' || $has_intro) : ?>
            <header class="meet-the-team__header<?php echo esc_attr($header_mod); ?>">
                <?php if ($title !== '') : ?>
                    <h2 class="meet-the-team__title">
                        <?php if ($title_last === '') : ?>
                            <span class="meet-the-team__title-lead"><?php echo esc_html($title_lead); ?></span>
                        <?php else : ?>
                            <span class="meet-the-team__title-lead"><?php echo esc_html($title_lead); ?></span>
                            <span class="meet-the-team__title-accent"><?php echo esc_html($title_last); ?></span>
                        <?php endif; ?>
                    </h2>
                <?php endif; ?>

                <?php if ($has_intro) : ?>
                    <div class="meet-the-team__intro">
                        <div class="meet-the-team__intro-inner">
                            <?php echo wp_kses_post($introduction); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </header>
        <?php endif; ?>

        <?php if (count($team_members) > 0) : ?>
            <div class="meet-the-team__grid">
                <?php foreach ($team_members as $member) : ?>
                    <article class="meet-the-team__member">
                        <?php if ($member['image_url'] !== '') : ?>
                            <div class="meet-the-team__member-photo">
                                <img
                                    class="meet-the-team__member-img"
                                    src="<?php echo esc_url($member['image_url']); ?>"
                                    alt="<?php echo esc_attr($member['image_alt'] !== '' ? $member['image_alt'] : $member['name']); ?>"
                                    loading="lazy"
                                    decoding="async"
                                />
                            </div>
                        <?php endif; ?>

                        <?php if ($member['name'] !== '' || $member['role'] !== '') : ?>
                            <div class="meet-the-team__member-meta">
                                <?php if ($member['name'] !== '') : ?>
                                    <p class="meet-the-team__member-name"><?php echo esc_html($member['name']); ?></p>
                                <?php endif; ?>
                                <?php if ($member['role'] !== '') : ?>
                                    <p class="meet-the-team__member-role"><?php echo esc_html($member['role']); ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
