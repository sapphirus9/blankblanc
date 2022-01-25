<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Template: sidebar
 * Description: サイドバー
 */
?>

<aside id="global-widget" class="sticky-widget">
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
