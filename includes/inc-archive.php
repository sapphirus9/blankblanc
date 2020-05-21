<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Template: inc-archive
 * Description: 記事を抜粋して一覧表示
 */
?>
<?php echo bb_get_term_description(); ?>
<?php while (have_posts()) :
  the_post();
  global $bb_theme_config; ?>
  <div class="entry-article">
    <article id="post-<?php echo $post->ID; ?>" class="article-container">
      <?php if (current_theme_supports('post-thumbnails')) : ?>
        <?php if (has_post_thumbnail()) :
          $thumbnail_size = array_slice($bb_theme_config['archive_thumbnail'], 0, 2); ?>
          <div class="thumbnail"><a href="<?php echo get_permalink(); ?>"><?php echo get_the_post_thumbnail($post->ID, $thumbnail_size); ?></a></div>
        <?php else : ?>
          <div class="thumbnail no-thumbnail"><a href="<?php echo get_permalink(); ?>"><span>No Image</span></a></div>
        <?php endif; ?>
      <?php endif; ?>
      <div class="entry-content">
        <div class="entry-header">
          <h2 class="title"><a href="<?php echo get_permalink(); ?>"><?php echo esc_attr(get_the_title()); ?></a></h2>
          <ul class="meta">
            <li class="date font-icon"><time datetime="<?php echo get_the_date('Y-m-d'); ?>"><?php echo bb_get_custom_date(); ?></time></li>
            <li class="author font-icon"><?php the_author_posts_link(); ?></li>
            <?php if ($post->post_type != 'post' && $post->post_type != 'page') : ?>
              <?php if ($tax = array_keys(get_the_taxonomies())) : ?>
                <li class="categories font-icon"><?php echo get_the_term_list('', $tax[0], '', ', '); ?></li>
              <?php endif; ?>
            <?php else : ?>
              <li class="categories font-icon"><?php echo get_the_category_list(', '); ?></li>
            <?php endif; ?>
          </ul>
        </div>

        <div class="entry-body">
          <?php the_excerpt(); ?>
        </div>
      </div>
    </article>
  </div>
<?php endwhile; ?>
<?php bb_get_pagination();
