<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Template: index
 * Description: インデックスページ
 */
get_header(); ?>

<main role="main" id="contents" class="index archive-page">
  <?php get_template_part('includes/inc', 'mainvisual'); ?>
  <?php get_template_part('includes/inc', 'breadcrumb'); ?>

  <div id="contents-container" class="wrap">
    <div id="first-column">
      <div class="archive-<?php echo bb_get_taxonomy_layout(); ?>">
        <?php if (have_posts()) : ?>
          <?php get_template_part('includes/inc', 'archive'); ?>
        <?php endif; ?>
      </div>
    </div>

    <div id="second-column">
      <?php get_sidebar(); ?>
    </div>
  </div>
</main>

<?php get_footer();
