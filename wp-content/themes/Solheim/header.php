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
    $header_logo = get_field('header_logo', 'option');
    $header_logo_url = is_array($header_logo) && !empty($header_logo['url']) ? $header_logo['url'] : '';
    $header_logo_alt = is_array($header_logo) && !empty($header_logo['alt']) ? $header_logo['alt'] : get_bloginfo('name');

    // Show header only if not the homepage/front page and not using the landing page template
    if (
        !is_front_page() &&
        !is_home() &&
        !(is_page() && is_page_template('templates/template-landing-page.php')) &&
        !(is_page() && is_page_template('templates/template-directory-entry.php'))
    ) : ?>
        <header class="header site-header">
            <div class="container site-header__inner">
                <div class="site-header__col site-header__col--left">
                    <nav class="site-header__lang" aria-label="Language switcher">
                        <a href="#" lang="en">EN</a>
                        <a href="#" lang="it">IT</a>
                    </nav>
                    <a class="site-header__account" href="#">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/icon-my-account.svg'); ?>" alt="My account" />
                    </a>
                </div>

                <div class="site-header__col site-header__col--center">
                    <a class="site-header__logo" href="<?php echo esc_url(home_url('/')); ?>">
                        <?php if ($header_logo_url !== '') : ?>
                            <img src="<?php echo esc_url($header_logo_url); ?>" alt="<?php echo esc_attr($header_logo_alt); ?>" />
                        <?php else : ?>
                            <span><?php echo esc_html(get_bloginfo('name')); ?></span>
                        <?php endif; ?>
                    </a>
                </div>

                <div class="site-header__col site-header__col--right">
                    <a class="site-header__icon-link" href="#">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/icon-cart.svg'); ?>" alt="Cart" />
                    </a>
                    <button class="site-header__menu-toggle" type="button" aria-expanded="false" aria-controls="site-header-offcanvas">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/icon-menu.svg'); ?>" alt="Open menu" />
                    </button>
                </div>
            </div>

            <div class="site-header__offcanvas" id="site-header-offcanvas" hidden>
                <div class="container site-header__offcanvas-inner">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'header-menu',
                        'container' => false,
                        'menu_class' => 'site-header__nav',
                        'fallback_cb' => false,
                    ));
                    ?>
                </div>
            </div>
        </header>
    <?php endif; ?>