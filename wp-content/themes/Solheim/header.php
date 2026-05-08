<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#000000">
    <meta name="description" content="<?php echo get_bloginfo('description'); ?>">
    <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon.png">
    <link href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon.png" rel="apple-touch-icon" />
    <!-- <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet"> -->
    <title><?php wp_title(''); ?></title>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <?php
    $header_group = get_field('header', 'option');
    $header_group = is_array($header_group) ? $header_group : array();

    $header_logo     = isset($header_group['logo']) ? $header_group['logo'] : get_field('header_logo', 'option');
    $header_logo_url = is_array($header_logo) && ! empty($header_logo['url']) ? $header_logo['url'] : '';
    $header_logo_alt = is_array($header_logo) && ! empty($header_logo['alt']) ? $header_logo['alt'] : get_bloginfo('name');

    $cta_1 = isset($header_group['primary_cta']) ? $header_group['primary_cta'] : get_field('header_cta_1', 'option');
    $cta_2 = isset($header_group['secondary_cta']) ? $header_group['secondary_cta'] : get_field('header_cta_2', 'option');

    $cta_1_url    = is_array($cta_1) && ! empty($cta_1['url']) ? $cta_1['url'] : '';
    $cta_1_title  = is_array($cta_1) && ! empty($cta_1['title']) ? $cta_1['title'] : '';
    $cta_1_target = is_array($cta_1) && ! empty($cta_1['target']) ? $cta_1['target'] : '';

    $cta_2_url    = is_array($cta_2) && ! empty($cta_2['url']) ? $cta_2['url'] : '';
    $cta_2_title  = is_array($cta_2) && ! empty($cta_2['title']) ? $cta_2['title'] : '';
    $cta_2_target = is_array($cta_2) && ! empty($cta_2['target']) ? $cta_2['target'] : '';

    $header_socials = get_field('socials', 'option');
    $header_socials = is_array($header_socials) ? $header_socials : array();
    ?>

    <button class="site-header__menu-toggle" type="button" aria-expanded="false" aria-controls="site-header-offcanvas" aria-label="<?php esc_attr_e('Open menu', 'solheim'); ?>">
        <span class="site-header__hamburger" aria-hidden="true">
            <span class="site-header__hamburger-line"></span>
            <span class="site-header__hamburger-line"></span>
            <span class="site-header__hamburger-line"></span>
        </span>
    </button>

    <header class="site-header" role="banner">
        <div class="site-header__inner">
            <div class="site-header__col site-header__col--left">
            </div>

            <div class="site-header__col site-header__col--center">
                <a class="site-header__logo" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php esc_attr_e('Home', 'solheim'); ?>">
                    <?php if ($header_logo_url !== '') : ?>
                        <img src="<?php echo esc_url($header_logo_url); ?>" alt="<?php echo esc_attr($header_logo_alt); ?>" />
                    <?php else : ?>
                        <span><?php echo esc_html(get_bloginfo('name')); ?></span>
                    <?php endif; ?>
                </a>
            </div>

            <div class="site-header__col site-header__col--right">
                <?php if ($cta_1_url !== '' && $cta_1_title !== '') : ?>
                    <a class="site-header__cta site-header__cta--primary btn-yellow-navy" href="<?php echo esc_url($cta_1_url); ?>"<?php echo $cta_1_target !== '' ? ' target="' . esc_attr($cta_1_target) . '"' : ''; ?><?php echo $cta_1_target === '_blank' ? ' rel="noopener noreferrer"' : ''; ?>>
                        <?php echo esc_html($cta_1_title); ?>
                    </a>
                <?php endif; ?>

                <?php if ($cta_2_url !== '' && $cta_2_title !== '') : ?>
                    <a class="site-header__cta site-header__cta--secondary btn-outline-white" href="<?php echo esc_url($cta_2_url); ?>"<?php echo $cta_2_target !== '' ? ' target="' . esc_attr($cta_2_target) . '"' : ''; ?><?php echo $cta_2_target === '_blank' ? ' rel="noopener noreferrer"' : ''; ?>>
                        <?php echo esc_html($cta_2_title); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="site-header__offcanvas" id="site-header-offcanvas" hidden>
            <div class="site-header__offcanvas-inner">
                <div class="site-header__offcanvas-main">
                    <div class="site-header__offcanvas-columns" data-header-columns>
                        <nav class="site-header__primarycol" aria-label="<?php esc_attr_e('Site navigation', 'solheim'); ?>">
                            <?php
                            wp_nav_menu(
                                array(
                                    'theme_location' => 'header-menu',
                                    'container'      => false,
                                    'menu_class'     => 'site-header__nav',
                                    'fallback_cb'    => false,
                                )
                            );
                            ?>
                        </nav>

                        <div class="site-header__subcol" data-header-subcol aria-label="<?php esc_attr_e('Sub navigation', 'solheim'); ?>"></div>
                    </div>
                </div>

                <div class="site-header__offcanvas-footer">
                    <nav class="site-header__bottomnav" aria-label="<?php esc_attr_e('Header navigation (secondary)', 'solheim'); ?>">
                        <?php
                        wp_nav_menu(
                            array(
                                'theme_location' => 'header-nav-2',
                                'container'      => false,
                                'menu_class'     => 'site-header__bottomnav-list',
                                'fallback_cb'    => '__return_false',
                            )
                        );
                        ?>
                    </nav>

                    <?php if (! empty($header_socials)) : ?>
                        <nav class="site-header__socials" aria-label="<?php esc_attr_e('Social links', 'solheim'); ?>">
                            <ul class="site-header__socials-list">
                                <?php foreach ($header_socials as $social) : ?>
                                    <?php
                                    $icon = isset($social['icon']) ? $social['icon'] : null;
                                    $link = isset($social['link']) ? $social['link'] : null;

                                    $icon_url = is_array($icon) && ! empty($icon['url']) ? $icon['url'] : '';
                                    $icon_alt = is_array($icon) && isset($icon['alt']) ? (string) $icon['alt'] : '';

                                    $href   = is_array($link) && ! empty($link['url']) ? $link['url'] : '';
                                    $label  = is_array($link) && ! empty($link['title']) ? $link['title'] : '';
                                    $target = is_array($link) && ! empty($link['target']) ? $link['target'] : '';
                                    ?>
                                    <?php if ($href !== '' && $icon_url !== '') : ?>
                                        <li class="site-header__socials-item">
                                            <a class="site-header__socials-link" href="<?php echo esc_url($href); ?>"
                                                <?php echo $target !== '' ? ' target="' . esc_attr($target) . '"' : ''; ?>
                                                <?php echo $target === '_blank' ? ' rel="noopener noreferrer"' : ''; ?>
                                                <?php echo $label !== '' ? ' aria-label="' . esc_attr($label) . '"' : ''; ?>>
                                                <img src="<?php echo esc_url($icon_url); ?>" alt="<?php echo esc_attr($icon_alt); ?>" />
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>