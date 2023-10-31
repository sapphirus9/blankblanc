<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Template: archive
 * Description: アーカイブページ
 */
get_header(); ?>

<main role="main" id="contents" class="archive-page">
  <?php get_template_part('includes/inc', 'mainvisual'); ?>
  <?php get_template_part('includes/inc', 'breadcrumb'); ?>

  <div id="contents-container" class="wrap">
    <div id="first-column">
      <div class="archive-<?php echo bb_get_taxonomy_layout(); ?>">
        <?php if (empty($bb_mainvisual_image)) : ?>
          <header class="archive-header">
            <h1 class="page-title"><?php echo get_the_archive_title(); ?></h1>
          </header>
        <?php endif; ?>

        <?php if (have_posts()) : ?>
          <?php get_template_part('includes/inc', 'archive'); ?>
        <?php else : ?>
          <div class="entry-articles">
            <div id="no-result">
              <p>該当する記事は見つかりませんでした</p>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <div id="second-column">
      <?php get_sidebar(); ?>
    </div>
  </div>
</main>

<?php get_footer();
