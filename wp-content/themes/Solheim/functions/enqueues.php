<?php
function site_scripts()
{
	global $wp_styles; // Call global $wp_styles variable to add conditional wrapper around ie stylesheet the WordPress way

	// Adding scripts file in the footer
	// wp_enqueue_script('matchheight-js', '//cdnjs.cloudflare.com/ajax/libs/jquery.matchHeight/0.7.2/jquery.matchHeight-min.js', array('jquery'), true);
	// wp_enqueue_script('init-js', get_template_directory_uri() . '/assets/js/init.js', array('jquery'), '1.0', false);
	wp_enqueue_script('slick-js', '//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js', array('jquery'), '1.8.1', true);
	wp_enqueue_script('site-js', get_template_directory_uri() . '/assets/js/scripts.js', array('jquery', 'slick-js'), filemtime(get_template_directory() . '/assets/js/scripts.js'), true);
	// wp_enqueue_script('jqueryui-js', 'https://code.jquery.com/ui/1.13.0/jquery-ui.min.js', array('jquery'), true);

	// Register main stylesheet
	wp_enqueue_style('site-css', get_template_directory_uri() . '/assets/css/main.css', array(), filemtime(get_template_directory() . '/assets/css/main.css'));
	// wp_enqueue_style('fontawesome-css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css', array(), 'all');
	wp_enqueue_style('slick-css', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css', array(), 'all');

	if (is_page_template(array('templates/template-couples-login.php', 'templates/template-vendors-login.php'))) {
		wp_enqueue_script(
			'login-page-gravity-forms',
			get_template_directory_uri() . '/assets/js/login-page-gravity-forms.js',
			array(),
			filemtime(get_template_directory() . '/assets/js/login-page-gravity-forms.js'),
			true
		);
	}

	// Comment reply script for threaded comments
	// if (is_singular() and comments_open() and (get_option('thread_comments') == 1)) {
	// 	wp_enqueue_script('comment-reply');
	// }

	// if (has_block('acf/hero-banner')) {
	// 	wp_enqueue_script('inline-svg-js', 'https://cdn.jsdelivr.net/gh/jonnyhaynes/inline-svg/dist/inlineSVG.min.js',  true, '1.0');
	// }
	// if (has_block('acf/text-media')) {
	// 	wp_enqueue_script('lottie-js', 'https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js',  true, '1.0');
	// }
}
add_action('wp_enqueue_scripts', 'site_scripts', 999);




/**
 * Enqueue block JavaScript and CSS for the editor
 */
function my_block_plugin_editor_scripts()
{
	if (is_admin() && function_exists('get_current_screen')) {
		$screen = get_current_screen();
		if (!$screen || (method_exists($screen, 'is_block_editor') && !$screen->is_block_editor())) {
			return;
		}
	}

	$admin_css_rel = '/assets/css/main-admin.css';
	$admin_css_path = get_template_directory() . $admin_css_rel;
	$admin_css_ver = file_exists($admin_css_path) ? filemtime($admin_css_path) : null;

	// Enqueue block editor styles
	wp_enqueue_style('site-css-admin', get_template_directory_uri() . $admin_css_rel, array(), $admin_css_ver);
	wp_enqueue_style('typekit', 'https://use.typekit.net/nud4yfn.css', array(), '1');

	// wp_enqueue_style('fontawesome-css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css', array(), 'all');
	// wp_enqueue_style('slick-css', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.css', array(), 'all');

	// Enqueue block editor JS
	wp_enqueue_script('site-js', get_template_directory_uri() . '/assets/scripts/scripts.js', array('jquery'), true, '1.0');
	// wp_enqueue_script('slick-js', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array('jquery'), true);
}

function my_theme_add_editor_styles() {
    add_editor_style('assets/css/editor-style.css');
}
add_action('admin_init', 'my_theme_add_editor_styles');




// Hook the enqueue functions into the editor
add_action('enqueue_block_editor_assets', 'my_block_plugin_editor_scripts');
