<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Template: inc-cookie-banner
 * Description: Cookieバナー表示用
 */
bb_theme_check();
?>
<div id="cookie-banner">
  <div class="cookie-wrap wrap">
    <p class="cookie-text"><?php bb_cookie_banner_content('text'); ?></p>
    <button class="cookie-btn"><?php bb_cookie_banner_content('label'); ?></button>
  </div>
</div>
