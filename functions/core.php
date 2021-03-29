<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Description: テーマ用基本ファンクション (core)
 */

/**
 * body の id, class を定義
 * @return string  ページに合わせた id や class を出力し、設定用の配列を返す
 */
function bb_body_id_class($classes = array()) {
  global $bb_theme_id_class, $bb_theme_config;
  if (!empty($bb_theme_id_class->class)) {
    array_push($classes, $bb_theme_id_class->class);
  }
  if ($bb_theme_config['mobile_nav_position'] === 'right') {
    array_push($classes, 'nav-window-right');
  }
  if (is_admin_bar_showing()) {
    array_push($classes, 'show-wp-admin-bar');
  }
  if ($bb_theme_config['add_body_class']) {
    $classes = get_body_class($classes);
  }
  $class = '';
  if (!empty($classes)) {
    $class = ' class="' . join(' ', array_unique($classes)) . '"';
  } else {
  }
  echo "id=\"{$bb_theme_id_class->id}\"{$class}";
}


/**
 * 年月日を個別タグ囲みで出力
 * @param string $type  コメントでの使用は comment と記述
 * @return string  span の囲み付きで日本並びの日付を返す
 */
function bb_get_custom_date($type = null) {
  global $bb_theme_config;
  if ($type == 'comment') {
    $src  = '<span class="year">' . get_comment_time($bb_theme_config['date_format'][0]) . '</span>';
    $src .= '<span class="month">' . get_comment_time($bb_theme_config['date_format'][1]) . '</span>';
    $src .= '<span class="day">' . get_comment_time($bb_theme_config['date_format'][2]) . '</span>';
  } else {
    $src  = '<span class="year">' . get_the_time($bb_theme_config['date_format'][0]) . '</span>';
    $src .= '<span class="month">' . get_the_time($bb_theme_config['date_format'][1]) . '</span>';
    $src .= '<span class="day">' . get_the_time($bb_theme_config['date_format'][2]) . '</span>';
  }
  $src = apply_filters('bb_get_custom_date', $src);
  return $src;
}


/**
 * アーカイブのページネーション
 * 参考:
 * http://www.kriesi.at/archives/how-to-build-a-wordpress-post-pagination-without-plugin
 * @param array $args
 *        range 左右のページ番号表示個数
 *        type 送りボタンの指定  all すべてを表示  1 前へ・後ろへのみ  2 先頭へ・最後へのみ
 *        pages ページ総数（カスタム投稿時に利用等 [etc. $the_query->max_num_pages]）
 * @return string  div class="pagination"
 */
function bb_get_pagination($args = array()) {
  global $wp_query, $paged;
  $args = array_merge(array(
    'range' => 4,
    'type' => 'all',
    'pages' => $wp_query->max_num_pages,
  ), $args);
  extract($args);
  $number = $range * 2 + 1;
  if (empty($paged)) {
    $paged = 1;
  }
  if(!$pages) {
    $pages = 1;
  }
  if (1 != $pages) {
    $src = '<div class="pagination archive-pagination">' . "\n";
    if ($paged <= $range) {
      $range = $number - $paged;
    } elseif ($paged >= $pages - $range) {
      $range = $number + $paged - $pages - 1;
    }
    if(($type == 'all' || $type == 2) && $paged > $range + 1 && $pages > $range + 1) {
      $src .= '<a href="' . get_pagenum_link(1) . '" class="first page-numbers">先頭へ</a>' . "\n";
    }
    if(($type == 'all' || $type == 1) && $paged > 1) {
      $src .= '<a href="' . get_pagenum_link($paged - 1) . '" class="prev page-numbers">前へ</a>' . "\n";
    }
    for ($i = 1; $i <= $pages; $i++) {
      if ($i >= $paged - $range && $i <= $paged + $range) {
        $src .= ($paged == $i) ? '<span class="page-numbers current">' . $i . '</span>' . "\n" : '<a href="' . get_pagenum_link($i) . '" class="page-numbers inactive">' . $i . '</a>' . "\n";
      }
    }
    if (($type == 'all' || $type == 1) && $paged < $pages) {
      $src .= '<a href="' . get_pagenum_link($paged + 1) . '" class="next page-numbers">次へ</a>' . "\n";
    }
    if (($type == 'all' || $type == 2) && $paged < $pages - $range && $pages > $range + 1) {
      $src .= '<a href="' . get_pagenum_link($pages) . '" class="last page-numbers">最後へ</a>' . "\n";
    }
    $src .= "</div>";
    $src = apply_filters('bb_get_pagination', $src);
    echo $src;
  }
}


/**
 * 設定: body に id と class および css ファイルの指定
 */
function bb_setup_theme_id_class() {
  $obj = get_queried_object();
  if (is_home() || is_front_page() && !is_page()) {
    $cfg = array(
      'name'  => 'home',
      'id'    => 'home',
      'class' => '',
    );
  } elseif (is_404()) {
    $cfg = array(
      'name'  => 'error404',
      'id'    => 'error404',
      'class' => '',
    );
  } elseif (is_page()) {
    $name = preg_match('/%/', $obj->post_name) ? 'page-' . $obj->ID : $obj->post_name;
    $cfg = array(
      'name'  => $name,
      'id'    => $name,
      'class' => $obj->post_parent === 0 ? 'page-parent' : 'page-child',
    );
  } elseif (is_attachment()) {
    $cfg = array(
      'name'  => $obj->post_type,
      'id'    => $obj->post_type,
      'class' => $obj->post_type,
    );
  } elseif (is_single()) {
    $name = preg_match('/%/', $obj->post_name) ? 'post-' . $obj->ID : $obj->post_name;
    $cfg = array(
      'name'  => $name,
      'id'    => $name,
      'class' => '',
    );
  } elseif (is_category() || is_tag()) {
    $name = preg_match('/%/', $obj->slug) ? 'archive-' . $obj->term_id : $obj->slug;
    $cfg = array(
      'name'   => $name,
      'id'     => 'wp',
      'class'  => $obj->parent === 0 ? 'archive-parent' : 'archive-child',
      'custom' => get_query_var('post_type'),
    );
  } elseif (is_date()) {
    $cfg = array(
      'name'   => 'date',
      'id'     => 'wp',
      'class'  => '',
      'custom' => get_query_var('post_type'),
    );
  } elseif (is_author()) {
    $cfg = array(
      'name'   => 'author',
      'id'     => 'wp',
      'class'  => '',
      'custom' => get_query_var('post_type'),
    );
  } elseif (is_tax()) {
    $name = preg_match('/%/', $obj->slug) ? 'taxonomy-' . $obj->term_id : $obj->slug;
    $cfg = array(
      'name'   => $name,
      'id'     => 'wp',
      'class'  => $name,
      'custom' => $name,
    );
  } else {
    $cfg = array(
      'name'   => 'undefined',
      'id'     => 'wp',
      'class'  => '',
    );
  }
  $cfg['id'] .= '-page';
  $cfg['css'] = 'style-' . $cfg['name'] . '.css';
  $cfg['js'] = 'js-' . $cfg['name'] . '.js';
  global $bb_theme_id_class;
  $bb_theme_id_class = (object) $cfg;
}
add_action('wp', 'bb_setup_theme_id_class');


/**
 * カテゴリーのスラッグ名のある投稿用テンプレートが存在する場合テンプレートを差し替え
 * @template ex.)single-catslug.php
 */
function custom_template_include($template) {
  if (is_single() && !is_attachment()) {
    if ($current_cat = get_the_category()) {
      if ($current_cat[0]->parent === 0) {
        $suffix = $current_cat[0]->slug;
      } else {
        $parent_cat = get_category($current_cat[0]->parent);
        $suffix = $parent_cat->slug;
      }
      if ($new_template = locate_template(array('single-' . $suffix . '.php'), false, true)) {
        return $new_template;
      }
    }
  }
  return $template;
}
add_filter('template_include', 'custom_template_include', 99);


/**
 * ショートコードを登録
 * @shortcode template_url
 * @shortcode home_url
 * @shortcode upload_url
 */
add_filter('widget_text', 'do_shortcode');
function add_sc_template_url() {
  return get_stylesheet_directory_uri();
}
add_shortcode('template_url', 'add_sc_template_url');

function add_sc_home_url($path = '', $scheme = '') {
  return home_url($path, $scheme);
}
add_shortcode('home_url', 'add_sc_home_url');

function get_upload_url() {
  $upload_info = wp_upload_dir();
  return $upload_info['url'];
}
add_shortcode('upload_url', 'get_upload_url');


/**
 * 記事抜粋時の続き文字
 */
function customize_excerpt_more($more) {
  global $bb_theme_config;
  if (!empty($bb_theme_config['excerpt_more'])) {
    return $bb_theme_config['excerpt_more'];
  }
  return $more;
}
add_filter('excerpt_more', 'customize_excerpt_more');


/**
 * 共通の抜粋最大文字数を変更
 */
function customize_excerpt_length($length) {
  global $bb_theme_config;
  if (!empty($bb_theme_config['excerpt_length'])) {
    return $bb_theme_config['excerpt_length'];
  }
  return $length;
}
add_filter('excerpt_mblength', 'customize_excerpt_length');


/**
 * RSS フィードの文字数
 */
function customize_feeds_excerpt($content) {
  global $bb_theme_config;
  if (get_option('rss_use_excerpt') === 1 && !empty($bb_theme_config['excerpt_length_rss'])) {
    $content = wp_trim_words(strip_shortcodes(get_the_content()), $bb_theme_config['excerpt_length_rss'], $bb_theme_config['excerpt_more']);
  }
  return $content;
}
add_filter('the_excerpt_rss', 'customize_feeds_excerpt');
add_filter('the_content_feed', 'customize_feeds_excerpt');


/**
 * 検索結果が空の場合
 */
function redirect_search_empty($search) {
  if (!is_admin() && isset($_GET['s']) && empty($_GET['s'])) {
    wp_redirect(home_url('/'));
    exit;
  }
  return $search;
}
add_filter('posts_search', 'redirect_search_empty');


/**
 * タグクラウドの出力を変更
 */
function customize_widget_tag_cloud($args) {
  $args['smallest'] = 75;
  $args['largest'] = 150;
  $args['unit'] = '%';
  $args['format'] = 'list';
  return $args;
}
add_filter('widget_tag_cloud_args', 'customize_widget_tag_cloud');
