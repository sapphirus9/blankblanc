<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Template: onecolumn-single
 * Template Post Type: post
 * Description: 1カラム幅固定レイアウト投稿ページ
 * Template Name: 1カラム幅固定レイアウト
 */
get_header(); ?>

<main role="main" id="contents" class="article">
  <?php get_template_part('includes/inc', 'mainvisual'); ?>
  <?php get_template_part('includes/inc', 'breadcrumb'); ?>

  <div id="contents-container" class="wrap">
    <div id="one-column">
      <article class="main-article">
        <?php if (have_posts()) : ?>
          <?php get_template_part('includes/inc', 'single'); ?>
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
