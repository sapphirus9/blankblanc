<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Template: front-page
 * Description: フロントページ
 */
get_header(); ?>

<main role="main" id="contents" class="front-page archive">
  <?php get_template_part('includes/inc', 'mainvisual'); ?>
  <?php get_template_part('includes/inc', 'breadcrumb'); ?>

  <div id="contents-conatiner" class="wrap">
    <div id="first-column">
      <?php if (is_page()) : // 固定ページ ?>
        <article class="main-article">
          <?php if (have_posts()) : ?>
            <?php get_template_part('includes/inc', 'page'); ?>
          <?php endif; ?>
        </article>
      <?php else : // 最新の投稿 ?>
        <div class="archive-tiles">
          <?php if (have_posts()) : ?>
            <?php get_template_part('includes/inc', 'archive'); ?>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>

    <div id="second-column">
      <?php get_sidebar(); ?>
    </div>
  </div>
</main>

<?php get_footer();
