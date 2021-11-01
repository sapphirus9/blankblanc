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
<div id="main-screen">
<header id="global-header">
  <div class="wrap">
    <div class="logo"><?php bb_logo_image(); ?></div>
    <?php if (is_home() || is_front_page()) : ?>
      <h1 class="copy"><?php echo get_bloginfo('description'); ?></h1>
    <?php else : ?>
      <p class="copy"><?php echo get_bloginfo('description'); ?></p>
    <?php endif; ?>
    <?php if (has_nav_menu('header_nav')) : ?>
      <nav id="header-nav">
        <?php wp_nav_menu(array(
          'theme_location' => 'header_nav',
          'container' => false,
        )); ?>
      </nav>
    <?php endif; ?>
  </div>
</header>

<?php if (has_nav_menu('global_nav')) : ?>
  <div id="header-part">
    <div id="header-part-inner">
      <nav id="global-nav">
        <div class="wrap">
          <?php wp_nav_menu(array(
            'theme_location' => 'global_nav',
            'container' => false,
          )); ?>
        </div>
      </nav>
    </div>
  </div>
<?php endif;
