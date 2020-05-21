<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Template: single
 * Description: 投稿ページ
 */
get_header(); ?>

<main role="main" id="contents" class="article">
  <?php get_template_part('includes/inc', 'mainvisual'); ?>
  <?php get_template_part('includes/inc', 'breadcrumb'); ?>

  <div id="contents-conatiner" class="wrap">
    <div id="first-column">
      <article class="main-article">
        <?php if (have_posts()) :
          while (have_posts()) :
            the_post(); ?>
          <header class="entry-header">
            <?php if (empty($bb_mainvisual_image)) : ?>
              <h1 class="page-title"><?php echo esc_attr(get_the_title()); ?></h1>
            <?php endif; ?>
            <ul class="meta">
              <li class="date font-icon"><time datetime="<?php echo get_the_date('Y-m-d'); ?>"><?php echo bb_get_custom_date(); ?></time></li>
              <li class="author font-icon"><?php echo the_author_posts_link(); ?></li>
              <?php if (!is_attachment()) : ?>
                <?php if ($post->post_type != 'post') : ?>
                  <?php if ($tax = array_keys(get_the_taxonomies())) : ?>
                    <li class="categories font-icon"><?php echo get_the_term_list('', $tax[0], '', ', '); ?></li>
                  <?php endif; ?>
                <?php else : ?>
                  <li class="categories font-icon"><?php echo get_the_category_list(', '); ?></li>
                <?php endif; ?>
                <?php if (get_the_tag_list()) : ?>
                  <li class="tags font-icon"><?php echo get_the_tag_list('', ', '); ?></li>
                <?php endif; ?>
              <?php endif; ?>
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

          <?php
          $prev_post = get_adjacent_post(false, '', true);
          $next_post = get_adjacent_post(false, '', false);
          if ($prev_post || $next_post) :
          ?>
            <aside class="page-navigation">
              <ul>
                <li class="prev">
                  <?php if ($prev_post) : ?>
                    <a href="<?php echo get_permalink($prev_post->ID); ?>">
                      <span class="dir">前の記事</span>
                      <span class="title"><?php echo esc_attr($prev_post->post_title); ?></span>
                    </a>
                  <?php endif; ?>
                </li>
                <li class="next">
                  <?php if ($next_post) : ?>
                    <a href="<?php echo get_permalink($next_post->ID); ?>">
                      <span class="dir">次の記事</span>
                      <span class="title"><?php echo esc_attr($next_post->post_title); ?></span>
                    </a>
                  <?php endif; ?>
                </li>
              </ul>
            </aside>
          <?php endif; ?>
        <?php endwhile;
          endif; ?>
      </article>
    </div>

    <div id="second-column">
      <?php get_sidebar(); ?>
    </div>
  </div>
</main>

<?php get_footer();
