<?php

$footer_group = get_field('footer', 'option');
$footer_group = is_array($footer_group) ? $footer_group : array();

$footer_logo     = isset($footer_group['logo']) ? $footer_group['logo'] : null;
$footer_logo_url = is_array($footer_logo) && ! empty($footer_logo['url']) ? $footer_logo['url'] : '';
$footer_logo_alt = is_array($footer_logo) && ! empty($footer_logo['alt']) ? $footer_logo['alt'] : '';

$footer_title = isset($footer_group['title']) && is_string($footer_group['title']) ? trim($footer_group['title']) : '';
if ($footer_title === '') {
  $footer_title = __('THE BIGGEST EVENT IN WOMEN’S GOLF', 'solheim');
}

$footer_copyright = isset($footer_group['copyright']) && is_string($footer_group['copyright']) ? trim($footer_group['copyright']) : '';

$footer_socials = isset($footer_group['socials']) && is_array($footer_group['socials']) ? $footer_group['socials'] : array();
?>

<footer class="footer" role="contentinfo">
  <div class="container footer__inner">
    <div class="footer__brand">
      <?php if ($footer_logo_url !== '') : ?>
        <a class="footer__logo" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php esc_attr_e('Home', 'solheim'); ?>">
          <img src="<?php echo esc_url($footer_logo_url); ?>" alt="<?php echo esc_attr($footer_logo_alt); ?>" />
        </a>
      <?php endif; ?>
    </div>

    <div class="footer__tagline" aria-label="<?php esc_attr_e('Footer tagline', 'solheim'); ?>">
      <?php echo esc_html($footer_title); ?>
    </div>

    <div class="footer__meta">
      <div class="footer__meta-top">
        <div class="footer__navs">
          <nav class="footer__nav footer__nav--1" aria-label="<?php esc_attr_e('Footer navigation', 'solheim'); ?>">
            <?php
            wp_nav_menu(
              array(
                'theme_location' => 'footer-nav-1',
                'menu_class'     => 'footer__menu',
                'container'      => false,
                'fallback_cb'    => '__return_false',
              )
            );
            ?>
          </nav>

          <nav class="footer__nav footer__nav--2" aria-label="<?php esc_attr_e('Footer navigation', 'solheim'); ?>">
            <?php
            wp_nav_menu(
              array(
                'theme_location' => 'footer-nav-2',
                'menu_class'     => 'footer__menu',
                'container'      => false,
                'fallback_cb'    => '__return_false',
              )
            );
            ?>
          </nav>
        </div>

        <div class="footer__social-col">
          <?php if (! empty($footer_socials)) : ?>
            <nav class="footer__social" aria-label="<?php esc_attr_e('Social links', 'solheim'); ?>">
              <ul class="footer__social-menu">
                <?php foreach ($footer_socials as $social) : ?>
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
                    <li class="footer__social-item">
                      <a class="footer__social-link" href="<?php echo esc_url($href); ?>"
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

          <div class="footer__copyright">
            <?php if ($footer_copyright !== '') : ?>
              <?php echo esc_html($footer_copyright); ?>
            <?php else : ?>
              <?php echo esc_html(sprintf(__('© %s Solheim Cup', 'solheim'), date_i18n('Y'))); ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</footer>


 <?php wp_footer(); ?>
 </body>

 </html>