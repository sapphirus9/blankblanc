<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Template: inc-breadcrumb
 * Description: パンくずを表示 (ex-breadcrumb [extension])
 */
bb_theme_check();
?>
<?php if (function_exists('bb_get_bread_crumb')) : ?>
  <div id="bread-crumb">
    <div class="wrap">
      <?php echo bb_get_bread_crumb(); ?>
    </div>
  </div>
<?php endif;
