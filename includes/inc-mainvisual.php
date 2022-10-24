<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Template: inc-mainvisual
 * Description: メインビジュアルを表示 (ex-mainvisual [extension])
 */
bb_theme_check();
?>
<?php
global $bb_theme_config, $bb_mainvisual_image;
$bb_mainvisual_title = '';
if (is_front_page()) {
  $bb_mainvisual_page = 'mv-home';
  if (is_page()) {
    if (get_post_meta($post->ID, 'bb_mainvisual_disable', true) === 'disabled') {
      $bb_mainvisual_image = null;
    } else {
      $bb_mainvisual_title = esc_attr(get_the_title());
      if (!$bb_mainvisual_image = get_post_meta($post->ID, 'bb_mainvisual', true)) {
        $bb_mainvisual_image = $bb_theme_config['mv_home_image'];
      }
    }
  } else {
    $bb_mainvisual_title = get_bloginfo('description');
    $bb_mainvisual_image = $bb_theme_config['mv_home_image'];
  }
} elseif (is_home()) {
  $bb_mainvisual_page = 'mv-archive';
  $home_id = get_option('page_for_posts');
  if (get_post_meta($home_id, 'bb_mainvisual_disable', true) === 'disabled') {
    $bb_mainvisual_image = null;
  } else {
    $bb_mainvisual_title = esc_attr(single_post_title('', false));
    if (!$bb_mainvisual_image = get_post_meta($home_id, 'bb_mainvisual', true)) {
      $bb_mainvisual_image = $bb_theme_config['mv_image'];
    }
  }
} elseif (is_singular() && have_posts()) {
  $bb_mainvisual_page = 'mv-singular';
  if (get_post_meta($post->ID, 'bb_mainvisual_disable', true) === 'disabled') {
    $bb_mainvisual_image = null;
  } else {
    $bb_mainvisual_title = esc_attr(get_the_title());
    if (!$bb_mainvisual_image = get_post_meta($post->ID, 'bb_mainvisual', true)) {
      $bb_mainvisual_image = $bb_theme_config['mv_image'];
    }
  }
} else {
  $bb_mainvisual_page = 'mv-archive';
  if (get_term_meta(get_queried_object_id(), 'bb_mainvisual_disable', true) === 'disabled') {
    $bb_mainvisual_image = null;
  } else {
    $bb_mainvisual_title = get_the_archive_title();
    if (!$bb_mainvisual_image = get_term_meta(get_queried_object_id(), 'bb_mainvisual', true)) {
      $bb_mainvisual_image = $bb_theme_config['mv_image'];
    }
  }
}
if (is_numeric($bb_mainvisual_image)) {
  if ($_image = wp_get_attachment_image_src($bb_mainvisual_image, 'mainvisual')) {
    $bb_mainvisual_image = $_image[0];
  } else {
    $bb_mainvisual_image = '';
  }
}


/**
 * Output
 */
if (function_exists('call_bb_mainvisual_term_meta') && !empty($bb_mainvisual_image)) : ?>
  <header id="main-visual" class="<?php echo $bb_mainvisual_page; ?>">
    <img src="<?php echo $bb_mainvisual_image; ?>" alt="" class="background-image-src">
    <div class="wrap">
      <?php if (is_front_page()) : // フロントページ ?>
        <div class="page-title">
          <?php if ($mv_home_content = $bb_theme_config['mv_home_content']) : ?>
            <div class="mv-title-content">
              <?php echo apply_filters('the_content', ($mv_home_content)); ?>
            </div>
          <?php else : ?>
            <p class="mv-title"><?php echo $bb_mainvisual_title; ?></p>
          <?php endif; ?>
        </div>
      <?php else : ?>
        <h1 class="page-title"><?php echo $bb_mainvisual_title; ?></h1>
      <?php endif; ?>
    </div>
  </header>
<?php endif;
