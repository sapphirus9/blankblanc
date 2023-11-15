<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Template: footer
 * Description: 共通フッター
 */
bb_theme_check();
?>
    <footer id="global-footer">
      <div class="wrap">
        <div class="footer-widgets">
          <?php if (!empty($GLOBALS['bb_theme_config']['mobile_nav_footer'])) : ?>
            <ul id="mobile-footer-widget" class="is-mobile"></ul>
          <?php endif; ?>
          <?php if (is_active_sidebar('mobile-footer-widget-top')) : ?>
            <ul id="mobile-footer-widget-top" class="is-mobile">
              <?php dynamic_sidebar('mobile-footer-widget-top'); ?>
            </ul>
          <?php endif; ?>
          <?php if (is_active_sidebar('footer-widget-1')) : ?>
            <ul id="footer-widget-1">
              <?php dynamic_sidebar('footer-widget-1'); ?>
            </ul>
          <?php endif; ?>
          <?php if (is_active_sidebar('footer-widget-2')) : ?>
            <ul id="footer-widget-2">
              <?php dynamic_sidebar('footer-widget-2'); ?>
            </ul>
          <?php endif; ?>
          <?php if (is_active_sidebar('footer-widget-3')) : ?>
            <ul id="footer-widget-3">
              <?php dynamic_sidebar('footer-widget-3'); ?>
            </ul>
          <?php endif; ?>
          <?php if (is_active_sidebar('footer-widget-4')) : ?>
            <ul id="footer-widget-4">
              <?php dynamic_sidebar('footer-widget-4'); ?>
            </ul>
          <?php endif; ?>
          <?php if (is_active_sidebar('mobile-footer-widget-bottom')) : ?>
            <ul id="mobile-footer-widget-bottom" class="is-mobile">
              <?php dynamic_sidebar('mobile-footer-widget-bottom'); ?>
            </ul>
          <?php endif; ?>
        </div>
      </div>
      <div class="copyright"><?php bb_copyright(); ?></div>
    </footer>

    <div id="gotop">
      <div id="gotop-button">
        <div class="gotop-symbol"><span class="symbol"></span></div>
      </div>
    </div>
  </div><!-- /#main-container -->
</div><!-- /#main-screen -->
<?php wp_footer(); ?>
</body>
</html>
