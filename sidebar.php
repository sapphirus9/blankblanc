<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Template: sidebar
 * Description: サイドバー
 */
bb_theme_check();
?>

<aside id="global-widget"<?php echo bb_get_fixed_widget();?>>
  <?php if (is_active_sidebar('sidebar-widget-1')) : ?>
    <ul id="sidebar-widget-1">
      <?php dynamic_sidebar('sidebar-widget-1'); ?>
    </ul>
  <?php endif; ?>
  <?php if (is_active_sidebar('sidebar-widget-2')) : ?>
    <ul id="sidebar-widget-2">
      <?php dynamic_sidebar('sidebar-widget-2'); ?>
    </ul>
  <?php endif; ?>
  <?php if (is_active_sidebar('sidebar-widget-3')) : ?>
    <ul id="sidebar-widget-3">
      <?php dynamic_sidebar('sidebar-widget-3'); ?>
    </ul>
  <?php endif; ?>
</aside>
