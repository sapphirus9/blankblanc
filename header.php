<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Template: header
 * Description: 共通ヘッダー
 */
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, viewport-fit=cover">
<?php wp_head(); ?>
</head>

<body <?php bb_body_id_class(); ?>>
<?php wp_body_open(); ?>
<div id="main-screen">
  <header id="global-header">
    <div class="wrap header-container">
      <div class="header-brand">
        <div class="logo"><?php bb_logo_image(); ?></div>
        <?php if (is_home() || is_front_page()) : ?>
          <h1 class="copy"><?php echo get_bloginfo('description'); ?></h1>
        <?php else : ?>
          <p class="copy"><?php echo get_bloginfo('description'); ?></p>
        <?php endif; ?>
      </div>
      <div class="header-right">
        <?php if (has_nav_menu('header_nav')) : ?>
          <nav id="header-nav">
            <?php wp_nav_menu(array(
              'theme_location' => 'header_nav',
              'container' => false,
              'walker' => new custom_walker_nav_menu,
            )); ?>
          </nav>
        <?php endif; ?>
      </div>
    </div>
  </header>

  <div id="main-container">
    <?php if (has_nav_menu('global_nav')) : ?>
      <div id="header-part">
        <div id="header-part-inner">
          <nav id="global-nav">
            <div class="wrap">
              <?php wp_nav_menu(array(
                'theme_location' => 'global_nav',
                'container' => false,
                'walker' => new custom_walker_nav_menu,
              )); ?>
            </div>
          </nav>
        </div>
      </div>
    <?php endif;
