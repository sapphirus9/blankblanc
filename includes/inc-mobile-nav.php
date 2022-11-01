<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Template: inc-mobile-nav
 * Description: モバイル用メニュー
 */
bb_theme_check();
?>
<?php
global $bb_theme_config;
if (!empty($bb_theme_config['mobile_nav']) || is_active_sidebar('mobile-widget-top') || is_active_sidebar('mobile-widget-bottom')) :
  $empty = empty($bb_theme_config['mobile_nav']) ? ' nav-block-empty' : '';
?>
  <div id="nav-window-area" class="is-mobile">
    <div id="nav-window-scroll">
      <div id="nav-window-container">
        <div id="nav-window-widgets" class="nav-block<?php echo $empty; ?>"></div>
      </div>
    </div>
  </div>
<?php endif;
