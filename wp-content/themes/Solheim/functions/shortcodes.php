<?php
/**
 * Front-end shortcodes.
 *
 * Forgot password: [solheim_forgot_password]
 * Optional attributes: redirect="/url/" submit_text="Send" email_label="Email"
 * Register CTA (.end-of-form): call solheim_render_end_of_login_form() after the form shortcode
 * or use show_register_link="1" on the shortcode.
 */
if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_post_nopriv_solheim_forgot_password', 'solheim_process_forgot_password_submission');
add_action('admin_post_solheim_forgot_password', 'solheim_process_forgot_password_submission');

/**
 * Handle POST from solheim_forgot_password shortcode form.
 */
function solheim_process_forgot_password_submission()
{
    if (!isset($_POST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), 'solheim_forgot_password')) {
        wp_die(esc_html__('Security check failed.', 'solheim'), '', array('response' => 403));
    }

    $redirect = isset($_POST['solheim_redirect']) ? esc_url_raw(wp_unslash($_POST['solheim_redirect'])) : home_url('/');
    $redirect = wp_validate_redirect($redirect, home_url('/'));

    $email_raw = isset($_POST['user_login']) ? wp_unslash($_POST['user_login']) : '';
    $trimmed   = is_string($email_raw) ? trim($email_raw) : '';

    if ($trimmed === '') {
        wp_safe_redirect(add_query_arg('solheim_fp', 'empty', $redirect));
        exit;
    }

    $email = sanitize_email($email_raw);
    if (!is_email($email)) {
        wp_safe_redirect(add_query_arg('solheim_fp', 'invalid', $redirect));
        exit;
    }

    retrieve_password($email);
    // Same redirect whether the user exists or not (avoid email enumeration).
    wp_safe_redirect(add_query_arg('solheim_fp', 'sent', $redirect));
    exit;
}

add_shortcode('solheim_forgot_password', 'solheim_forgot_password_shortcode');

/**
 * Markup shown below login / forgot-password forms ("Register here").
 *
 * @param string $register_href Register link URL.
 */
function solheim_render_end_of_login_form($register_href = '#')
{
    $href = '#';
    if (is_string($register_href) && $register_href !== '' && $register_href !== '#') {
        $href = esc_url($register_href);
    }
    ?>
    <div class="end-of-form">
        <?php esc_html_e('Don\'t have an account?', 'solheim'); ?>
        <a href="<?php echo esc_attr($href); ?>"><?php esc_html_e('REGISTER HERE', 'solheim'); ?></a>
    </div>
    <?php
}

/**
 * @param array|string $atts Shortcode attributes.
 */
function solheim_forgot_password_shortcode($atts)
{
    $atts = shortcode_atts(
        array(
            'redirect'          => '',
            'submit_text'       => __('Send', 'solheim'),
            'email_label'       => __('Email', 'solheim'),
            'show_register_link' => '',
            'register_url'      => '#',
        ),
        $atts,
        'solheim_forgot_password'
    );

    global $post;
    $redirect = $atts['redirect'] !== '' ? $atts['redirect'] : ($post ? get_permalink($post) : home_url('/'));
    $redirect = wp_validate_redirect(esc_url_raw($redirect), home_url('/'));

    $notice_key = isset($_GET['solheim_fp']) ? sanitize_key(wp_unslash($_GET['solheim_fp'])) : '';

    ob_start();
    ?>
    <div class="solheim-forgot-password">
        <?php if ($notice_key === 'empty') : ?>
            <p class="solheim-forgot-password__notice solheim-forgot-password__notice--error" role="alert">
                <?php esc_html_e('Please enter your email address.', 'solheim'); ?>
            </p>
        <?php elseif ($notice_key === 'invalid') : ?>
            <p class="solheim-forgot-password__notice solheim-forgot-password__notice--error" role="alert">
                <?php esc_html_e('Please enter a valid email address.', 'solheim'); ?>
            </p>
        <?php elseif ($notice_key === 'sent') : ?>
            <p class="solheim-forgot-password__notice solheim-forgot-password__notice--success" role="status">
                <?php esc_html_e('If an account exists for that email address, you will receive reset instructions shortly.', 'solheim'); ?>
            </p>
        <?php endif; ?>

        <form class="solheim-forgot-password__form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" novalidate>
            <input type="hidden" name="action" value="solheim_forgot_password" />
            <input type="hidden" name="solheim_redirect" value="<?php echo esc_url($redirect); ?>" />
            <?php wp_nonce_field('solheim_forgot_password', '_wpnonce', true, true); ?>

            <div class="solheim-forgot-password__field">
                <label class="solheim-forgot-password__label" for="solheim_fp_user_login"><?php echo esc_html($atts['email_label']); ?></label>
                <input
                    class="solheim-forgot-password__input"
                    type="email"
                    name="user_login"
                    id="solheim_fp_user_login"
                    value=""
                    autocomplete="email"
                    required
                />
            </div>

            <div class="solheim-forgot-password__actions">
                <button type="submit" class="solheim-forgot-password__submit"><?php echo esc_html($atts['submit_text']); ?></button>
            </div>
        </form>
        <?php
        if (in_array(strtolower((string) $atts['show_register_link']), array('1', 'true', 'yes'), true)) {
            $reg = trim((string) $atts['register_url']);
            if ($reg === '' || $reg === '#') {
                solheim_render_end_of_login_form('#');
            } else {
                $valid = wp_validate_redirect(esc_url_raw($reg), '#');
                solheim_render_end_of_login_form($valid !== '' ? $valid : '#');
            }
        }
        ?>
    </div>
    <?php
    return ob_get_clean();
}
