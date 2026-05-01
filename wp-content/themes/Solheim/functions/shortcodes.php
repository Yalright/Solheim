<?php
/**
 * Front-end shortcodes.
 *
 * Forgot password: [lalista_forgot_password]
 * Optional attributes: redirect="/url/" submit_text="Send" email_label="Email"
 * Register CTA (.end-of-form) is output by the couples/vendors login templates after the form shortcode.
 * On other templates, call lalista_render_end_of_login_form() after the shortcode or use show_register_link="1".
 */
if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_post_nopriv_lalista_forgot_password', 'lalista_process_forgot_password_submission');
add_action('admin_post_lalista_forgot_password', 'lalista_process_forgot_password_submission');

/**
 * Handle POST from lalista_forgot_password shortcode form.
 */
function lalista_process_forgot_password_submission()
{
    if (!isset($_POST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), 'lalista_forgot_password')) {
        wp_die(esc_html__('Security check failed.', 'la-lista'), '', array('response' => 403));
    }

    $redirect = isset($_POST['lalista_redirect']) ? esc_url_raw(wp_unslash($_POST['lalista_redirect'])) : home_url('/');
    $redirect = wp_validate_redirect($redirect, home_url('/'));

    $email_raw = isset($_POST['user_login']) ? wp_unslash($_POST['user_login']) : '';
    $trimmed   = is_string($email_raw) ? trim($email_raw) : '';

    if ($trimmed === '') {
        wp_safe_redirect(add_query_arg('lalista_fp', 'empty', $redirect));
        exit;
    }

    $email = sanitize_email($email_raw);
    if (!is_email($email)) {
        wp_safe_redirect(add_query_arg('lalista_fp', 'invalid', $redirect));
        exit;
    }

    retrieve_password($email);
    // Same redirect whether the user exists or not (avoid email enumeration).
    wp_safe_redirect(add_query_arg('lalista_fp', 'sent', $redirect));
    exit;
}

add_shortcode('lalista_forgot_password', 'lalista_forgot_password_shortcode');

/**
 * Markup shown below login / forgot-password forms ("Register here").
 *
 * @param string $register_href Register link URL.
 */
function lalista_render_end_of_login_form($register_href = '#')
{
    $href = '#';
    if (is_string($register_href) && $register_href !== '' && $register_href !== '#') {
        $href = esc_url($register_href);
    }
    ?>
    <div class="end-of-form">
        <?php esc_html_e('Don\'t have an account?', 'la-lista'); ?>
        <a href="<?php echo esc_attr($href); ?>"><?php esc_html_e('REGISTER HERE', 'la-lista'); ?></a>
    </div>
    <?php
}

/**
 * @param array|string $atts Shortcode attributes.
 */
function lalista_forgot_password_shortcode($atts)
{
    $atts = shortcode_atts(
        array(
            'redirect'          => '',
            'submit_text'       => __('Send', 'la-lista'),
            'email_label'       => __('Email', 'la-lista'),
            'show_register_link' => '',
            'register_url'      => '#',
        ),
        $atts,
        'lalista_forgot_password'
    );

    global $post;
    $redirect = $atts['redirect'] !== '' ? $atts['redirect'] : ($post ? get_permalink($post) : home_url('/'));
    $redirect = wp_validate_redirect(esc_url_raw($redirect), home_url('/'));

    $notice_key = isset($_GET['lalista_fp']) ? sanitize_key(wp_unslash($_GET['lalista_fp'])) : '';

    ob_start();
    ?>
    <div class="lalista-forgot-password">
        <?php if ($notice_key === 'empty') : ?>
            <p class="lalista-forgot-password__notice lalista-forgot-password__notice--error" role="alert">
                <?php esc_html_e('Please enter your email address.', 'la-lista'); ?>
            </p>
        <?php elseif ($notice_key === 'invalid') : ?>
            <p class="lalista-forgot-password__notice lalista-forgot-password__notice--error" role="alert">
                <?php esc_html_e('Please enter a valid email address.', 'la-lista'); ?>
            </p>
        <?php elseif ($notice_key === 'sent') : ?>
            <p class="lalista-forgot-password__notice lalista-forgot-password__notice--success" role="status">
                <?php esc_html_e('If an account exists for that email address, you will receive reset instructions shortly.', 'la-lista'); ?>
            </p>
        <?php endif; ?>

        <form class="lalista-forgot-password__form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" novalidate>
            <input type="hidden" name="action" value="lalista_forgot_password" />
            <input type="hidden" name="lalista_redirect" value="<?php echo esc_url($redirect); ?>" />
            <?php wp_nonce_field('lalista_forgot_password', '_wpnonce', true, true); ?>

            <div class="lalista-forgot-password__field">
                <label class="lalista-forgot-password__label" for="lalista_fp_user_login"><?php echo esc_html($atts['email_label']); ?></label>
                <input
                    class="lalista-forgot-password__input"
                    type="email"
                    name="user_login"
                    id="lalista_fp_user_login"
                    value=""
                    autocomplete="email"
                    required
                />
            </div>

            <div class="lalista-forgot-password__actions">
                <button type="submit" class="lalista-forgot-password__submit"><?php echo esc_html($atts['submit_text']); ?></button>
            </div>
        </form>
        <?php
        if (in_array(strtolower((string) $atts['show_register_link']), array('1', 'true', 'yes'), true)) {
            $reg = trim((string) $atts['register_url']);
            if ($reg === '' || $reg === '#') {
                lalista_render_end_of_login_form('#');
            } else {
                $valid = wp_validate_redirect(esc_url_raw($reg), '#');
                lalista_render_end_of_login_form($valid !== '' ? $valid : '#');
            }
        }
        ?>
    </div>
    <?php
    return ob_get_clean();
}
