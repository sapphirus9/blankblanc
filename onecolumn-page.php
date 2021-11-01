<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Template: onecolumn-page
 * Template Post Type: page
 * Description: 1カラム幅固定レイアウト固定ページ
 * Template Name: 1カラム幅固定レイアウト
 */
get_header(); ?>

<main role="main" id="contents" class="article static">
  <?php get_template_part('includes/inc', 'mainvisual'); ?>
  <?php get_template_part('includes/inc', 'breadcrumb'); ?>

  <div id="contents-container" class="wrap">
    <div id="one-column">
      <article class="main-article">
        <?php if (have_posts()) : ?>
          <?php get_template_part('includes/inc', 'page'); ?>
        <?php else : ?>
          <div id="no-result">
            <p>コンテンツはありません</p>
          </div>
        <?php endif; ?>
      </article>
    </div>

    <div id="second-column">
      <?php get_sidebar(); ?>
    </div>
  </div>
</main>

<?php get_footer();
