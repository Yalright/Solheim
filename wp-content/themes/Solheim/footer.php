 <?php

  ?>
<footer class="footer">
  <div class="container footer-inner">
    <?php
    $footer_logo = get_field('footer_logo', 'option');
    $footer_logo_url = is_array($footer_logo) && !empty($footer_logo['url']) ? $footer_logo['url'] : '';
    $footer_logo_alt = is_array($footer_logo) && !empty($footer_logo['alt']) ? $footer_logo['alt'] : '';
    ?>

    <div class="footer-col-1">
      <?php if (!empty($footer_logo_url)) : ?>
        <div class="footer-logo">
          <img src="<?php echo esc_url($footer_logo_url); ?>" alt="<?php echo esc_attr($footer_logo_alt); ?>" />
        </div>
      <?php endif; ?>
    </div>

    <div class="footer-col-2">
      <div class="footer-nav-columns">
        <nav class="footer-nav footer-nav-1" aria-label="Footer Navigation 1">
          <?php
          wp_nav_menu(
            array(
              'theme_location' => 'footer-nav-1',
              'menu_class' => 'footer-menu',
              'container' => false,
            )
          );
          ?>
        </nav>

        <nav class="footer-nav footer-nav-2" aria-label="Footer Navigation 2">
          <?php
          wp_nav_menu(
            array(
              'theme_location' => 'footer-nav-2',
              'menu_class' => 'footer-menu',
              'container' => false,
            )
          );
          ?>
        </nav>
      </div>
    </div>
  </div>
</footer>


 <?php wp_footer(); ?>
 </body>

 </html>