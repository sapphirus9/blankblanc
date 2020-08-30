<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Template: inc-mainvisual
 * Description: メインビジュアルを表示 (ex-mainvisual [extension])
 */
?>
<?php
global $bb_theme_config, $bb_mainvisual_image;
if (is_home() || is_front_page()) {
  $bb_mainvisual_page = 'mv-home';
  // $bb_mainvisual_title = is_page() ? esc_attr(get_the_title()) : get_bloginfo('description');
  // $bb_mainvisual_image = $bb_theme_config['mv_home_image'];
  if (is_page()) {
    $bb_mainvisual_title = esc_attr(get_the_title());
    if (!$bb_mainvisual_image = get_post_meta($post->ID, 'bb_mainvisual', true)) {
      $bb_mainvisual_image = $bb_theme_config['mv_home_image'];
    }
  } else {
    $bb_mainvisual_title = get_bloginfo('description');
    $bb_mainvisual_image = $bb_theme_config['mv_home_image'];
  }
} elseif (is_singular() && have_posts()) {
  $bb_mainvisual_page = 'mv-singular';
  $bb_mainvisual_title = esc_attr(get_the_title());
  if (!$bb_mainvisual_image = get_post_meta($post->ID, 'bb_mainvisual', true)) {
    $bb_mainvisual_image = $bb_theme_config['mv_image'];
  }
} else {
  $bb_mainvisual_page = 'mv-archive';
  $bb_mainvisual_title = get_the_archive_title();
  if (!$bb_mainvisual_image = get_term_meta(get_queried_object_id(), 'bb_mainvisual', true)) {
    $bb_mainvisual_image = $bb_theme_config['mv_image'];
  }
}
if (is_numeric($bb_mainvisual_image)) {
  $bb_mainvisual_image = wp_get_attachment_image_src($bb_mainvisual_image, 'full')[0];
}


/**
 * Output
 */
if (!empty($bb_mainvisual_image)) : ?>
  <header id="main-visual" class="<?php echo $bb_mainvisual_page; ?>">
    <img src="<?php echo $bb_mainvisual_image; ?>" alt="" class="background-image-src">
    <div class="wrap">
      <?php if (is_home() || is_front_page()) : // HOME ?>
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
