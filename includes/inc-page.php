<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Template: inc-page
 * Description: 固定ページ表示
 */
?>
<?php while (have_posts()) :
  the_post(); ?>
  <header class="entry-header">
    <?php if (empty($GLOBALS['bb_mainvisual_image'])) : ?>
      <h1 class="page-title"><?php echo esc_attr(get_the_title()); ?></h1>
    <?php endif; ?>
    <ul class="meta">
      <li class="date font-icon"><time datetime="<?php echo get_the_date('Y-m-d'); ?>"><?php echo bb_get_custom_date(); ?></time></li>
      <li class="author font-icon"><?php echo the_author_posts_link(); ?></li>
    </ul>
  </header>

  <section id="post-<?php echo get_the_ID(); ?>" <?php post_class('entry-content'); ?>>
    <?php get_template_part('includes/inc', 'thumbnail'); ?>
    <div class="entry-body">
      <?php bb_the_content(); ?>
    </div>
    <?php get_template_part('includes/inc', 'linkpages'); ?>
  </section>

  <?php comments_template('', true); ?>

<?php endwhile;
