<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Template: inc-thumbnail
 * Description: サムネイルを表示
 */
?>
<?php if (current_theme_supports('post-thumbnails') && has_post_thumbnail() && $GLOBALS['page'] < 2) : ?>
  <div class="post-thumbnail">
    <?php /*
    --- 画像URLリンクありの場合は以下を使用 ---
    <div class="image"><a href="<?php echo wp_get_attachment_url(get_post_thumbnail_id()); ?>"><?php echo get_the_post_thumbnail(); ?></a></div>
    */ ?>
    <div class="image"><?php echo get_the_post_thumbnail(); ?></div>
    <?php if ($thumbnail_caption = get_the_post_thumbnail_caption()) : ?>
      <p class="caption"><?php echo $thumbnail_caption; ?></p>
    <?php endif; ?>
  </div>
<?php endif;
