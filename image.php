<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Template: media
 * Description: メディアページ
 */
get_header(); ?>

<main role="main" id="contents" class="article attachment">
  <div id="contents-container" class="wrap">
    <div id="first-column">
      <article class="main-article">
        <?php if (have_posts()) :
          while (have_posts()) :
            the_post(); ?>
          <header class="entry-header">
            <h1 class="page-title"><?php echo esc_attr(get_the_title()); ?></h1>
            <ul class="meta">
              <li class="date font-icon"><time datetime="<?php echo get_the_date('Y-m-d'); ?>"><?php echo bb_get_custom_date(); ?></time></li>
              <li class="author font-icon"><?php echo the_author_posts_link(); ?></li>
              <?php if (!is_attachment()) : ?>
                <?php if ($post->post_type != 'post') :
                  $tax = array_keys(get_the_taxonomies()); ?>
                  <li class="categories font-icon"><?php echo get_the_term_list('', $tax[0], '', ', '); ?></li>
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
            <?php
            $img_src = wp_get_attachment_image_src(get_the_ID(), 'large');
            $img_width = $img_src[1] > 900 ? 900 : $img_src[1]; // Max 900px
            ?>
            <div class="entry-body">
              <div class="entry-attachment" style="width: <?php echo $img_width; ?>px;">
                <div class="image"><?php echo wp_get_attachment_link(get_the_ID(), 'large'); ?></div>
                <?php if ($caption = get_post(get_the_ID())->post_excerpt) : ?>
                  <p class="caption"><?php echo $caption; ?></p>
                <?php endif; ?>
              </div>
              <?php bb_the_content(); ?>
            </div>
          </section>

          <?php if ($referer = wp_get_referer()) : ?>
          <aside class="page-navigation">
            <p><a href="<?php echo $referer; ?>">元のページに戻る</a></p>
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
