<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Template: archive
 * Description: アーカイブページ
 */
get_header(); ?>

<main role="main" id="contents" class="archive">
  <?php get_template_part('includes/inc', 'mainvisual'); ?>
  <?php get_template_part('includes/inc', 'breadcrumb'); ?>

  <div id="contents-conatiner" class="wrap">
    <div id="first-column">
      <?php
      if ($bb_taxonomy_layout = get_term_meta(get_queried_object_id(), 'bb_taxonomy_option', true)) {
        $bb_taxonomy_layout = $bb_taxonomy_layout['layout']['value'];
      } else {
        $bb_taxonomy_layout = $bb_theme_config['taxonomy_layout'];
      }
      ?>
      <div class="archive-<?php echo $bb_taxonomy_layout; ?>">
        <?php if (empty($bb_mainvisual_image)) : ?>
          <header class="archive-header">
            <h1 class="page-title"><?php echo get_the_archive_title(); ?></h1>
          </header>
        <?php endif; ?>

        <?php if (have_posts()) : ?>
          <?php get_template_part('includes/inc', 'archive'); ?>
        <?php else : ?>
          <div id="no-result">
            <p>該当する記事は見つかりませんでした</p>
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
