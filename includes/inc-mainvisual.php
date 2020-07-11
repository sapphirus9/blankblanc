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
$bb_mainvisual_page = 'mv-page';
if (is_home() || is_front_page()) {
  $bb_mainvisual_page = 'mv-home';
  if (!$bb_mainvisual_image = $bb_theme_config['mv_home_image']) {
    $bb_mainvisual_image = get_post_meta($post->ID, 'bb_mainvisual', true);
  }
  $bb_mainvisual_title = is_page() ? esc_attr(get_the_title()) : get_bloginfo('description');
} elseif (is_singular() && have_posts()) {
  $bb_mainvisual_image = get_post_meta($post->ID, 'bb_mainvisual', true);
  $bb_mainvisual_title = esc_attr(get_the_title());
} else {
  $bb_mainvisual_image = get_term_meta(get_queried_object_id(), 'bb_mainvisual', true);
  $bb_mainvisual_title = get_the_archive_title();
}
if (empty($bb_mainvisual_image)) {
  $bb_mainvisual_image = $bb_theme_config['mv_image'];
}


/**
 * Output
 */
if (!empty($bb_mainvisual_image)) : ?>
  <!-- <header id="main-visual" class="<?php echo $bb_mainvisual_page; ?>" style="background-image: url(<?php echo wp_get_attachment_image_src($bb_mainvisual_image, 'full')[0]; ?>);"> -->
  <header id="main-visual" class="<?php echo $bb_mainvisual_page; ?>">
    <img src="<?php echo wp_get_attachment_image_src($bb_mainvisual_image, 'full')[0]; ?>" alt="" class="background-image-src">
    <div class="wrap">
      <?php if (is_home() || is_front_page()) : // HOME ?>
        <div class="page-title">
          <?php if ($mv_home_content = $bb_theme_config['mv_home_content']) : ?>
            <div class="mv-title-content">
              <?php echo apply_filters('the_content', ($mv_home_content)); ?>
            </div>
          <?php else : ?>
            <p class="page-title"><?php echo $bb_mainvisual_title; ?></p>
          <?php endif; ?>
        </div>
      <?php else : ?>
        <h1 class="page-title"><?php echo $bb_mainvisual_title; ?></h1>
      <?php endif; ?>
    </div>
  </header>
<?php endif;
