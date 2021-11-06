<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Template: search
 * Description: 検索結果ページ
 */
get_header(); ?>

<main role="main" id="contents" class="search archive">
  <?php get_template_part('includes/inc', 'breadcrumb'); ?>

  <div id="contents-container" class="wrap">
    <div id="first-column">
      <div class="archive-list">
        <header class="archive-header">
          <h1 class="page-title"><?php printf('検索「%s」', '<span>' . get_search_query() . '</span>'); ?></h1>
        </header>

        <?php if (have_posts()) : ?>
          <?php get_template_part('includes/inc', 'archive'); ?>
        <?php else : ?>
          <div class="entry-articles">
            <div id="no-result">
              <p><?php printf('「%s」が含まれるページは<br>見つかりませんでした', '<span>' . get_search_query() . '</span>'); ?></p>
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
