<?php
/*
 * Headers
 *
 * Providing headers for WPE as .htaccess support is deprecated
 * https://wpengine.com/support/htaccess-deprecation
 */

// TEST: https://csp-evaluator.withgoogle.com/
// INFO: https://htaccessbook.com/important-security-headers/#csp

// ONLY execute on the front-end
if(!is_admin()) {
  // Migrating from .htaccess configuration
  header('Strict-Transport-Security: max-age=31536000 env=HTTPS');
  header('X-Frame-Options: SAMEORIGIN');
  header('Referrer-Policy: same-origin');
  header('X-XSS-Protection: 1; mode=block');
  header('X-Permitted-Cross-Domain-Policies: none');
  header('X-Content-Type-Options: nosniff');
  // CSP Headers causing problems
  // header('Content-Security-Policy: default-src https:; font-src https: data:; img-src https: data:; script-src https:; style-src https:; object-src https:;');
}
?>
