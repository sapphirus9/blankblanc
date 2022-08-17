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
  $default_layout = $bb_theme_config['column_layout'];
  if ($default_layout == 'default') { // 旧設定を差し替え default -> twocolumn
    $default_layout = 'twocolumn';
  }
  if (is_home() || is_front_page()) {
    if (is_page()) {
      $layout = get_post_meta(get_the_ID(), 'bb_page_layout_select', true);
      if ($layout == 'default') { // 旧設定を差し替え default -> twocolumn
        $layout = 'twocolumn';
      }
    } else {
      $layout = $bb_theme_config['homepage_layout']['column'];
    }
    array_push($classes, (empty($layout) ? $default_layout :  $layout) . '-layout');
  } elseif (is_page() || is_single()) {
    $layout = get_post_meta(get_the_ID(), 'bb_page_layout_select', true);
    if ($layout == 'default') { // 旧設定を差し替え default -> twocolumn
      $layout = 'twocolumn';
    }
    if (is_attachment()) {
      $layout = 'onecolumn';
    }
    array_push($classes, (empty($layout) ? $default_layout :  $layout) . '-layout');
  } elseif (is_archive()) {
    $obj = get_queried_object();
    if (isset($obj->term_id)) {
      $layout = get_term_meta($obj->term_id, 'bb_term_layout_select', true);
      if (empty($layout)) {
        $layout = $default_layout;
      }
    } else {
      $layout = $default_layout;
    }
    if ($layout == 'default') { // 旧設定を差し替え default -> twocolumn
      $layout = 'twocolumn';
    }
    array_push($classes, $layout . '-layout');
  }
  if ($bb_theme_config['loading_screen']) { // ローディング画面用
    array_push($classes, 'loading-screen-enable');
  }
  if (!empty($classes)) {
    $class = ' class="' . join(' ', array_unique($classes)) . '"';
  } else {
    $class = '';
  }
  echo "id=\"{$bb_theme_id_class->id}\"{$class}";
}


/**
 * フッターにモバイルナビ用のhtmlを追加
 */
function bb_mobile_nav_src() {
  get_template_part('includes/inc', 'mobile-nav');
}
add_action('wp_footer', 'bb_mobile_nav_src', 10);


/**
 * ページが構築されるまでのローディング画面
 */
function bb_loading_screen() {
  global $bb_theme_config;
  if ($bb_theme_config['loading_screen']) {
    echo "<div id=\"loading-screen\"></div>\n";
  }
}
add_action('wp_body_open', 'bb_loading_screen', 9);


/**
 * 年月日を個別タグ囲みで出力
 * @param string $type  コメントでの使用は comment と記述
 * @return string  span の囲み付きで日本並びの日付を返す
 */
function bb_get_custom_date($type = null) {
  global $bb_theme_config;
  $separator = '';
  if (!empty($bb_theme_config['date_format'][3])) {
    $separator = '<span class="separator">' . $bb_theme_config['date_format'][3] . '</span>';
  }
  if ($type == 'comment') {
    $src  = '<span class="year">' . get_comment_time($bb_theme_config['date_format'][0]) . '</span>';
    $src .= $separator;
    $src .= '<span class="month">' . get_comment_time($bb_theme_config['date_format'][1]) . '</span>';
    $src .= $separator;
    $src .= '<span class="day">' . get_comment_time($bb_theme_config['date_format'][2]) . '</span>';
  } else {
    $src  = '<span class="year">' . get_the_time($bb_theme_config['date_format'][0]) . '</span>';
    $src .= $separator;
    $src .= '<span class="month">' . get_the_time($bb_theme_config['date_format'][1]) . '</span>';
    $src .= $separator;
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
  if (is_front_page()) {
    $cfg = array(
      'name'  => 'home',
      'id'    => 'front',
      'class' => '',
    );
  } elseif (is_home()) {
    $name = preg_match('/%/', $obj->post_name) ? 'page-' . $obj->ID : $obj->post_name;
    $cfg = array(
      'name'  => $name,
      'id'    => 'wp',
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
 * テンプレートの差し替え
 * 1.ページレイアウト指定時のテンプレート（ex-page-lauout.php）
 * @template e.g. onecolumn-single.php, fullwidth-page.php
 * 2.カテゴリーのスラッグ名のある投稿用テンプレートが存在する場合
 * @template e.g. single-catslug.php
 */
function custom_template_include($template) {
  if (!is_attachment()) {
    global $post;
    if (is_single()) {
      if ($template_name = get_post_meta($post->ID, 'bb_page_layout_select', true)) {
        if ($new_template = locate_template($template_name . '-single.php', false, true)) {
          return $new_template;
        }
      }
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
    } elseif (is_page()) {
      if ($template_name = get_post_meta($post->ID, 'bb_page_layout_select', true)) {
        if ($new_template = locate_template($template_name . '-page.php', false, true)) {
          return $new_template;
        }
      }
    } elseif (is_archive()) {
      $obj = get_queried_object();
      if (isset($obj->term_id) && $template_name = get_term_meta($obj->term_id, 'bb_term_layout_select', true)) {
        if ($new_template = locate_template($template_name . '-archive.php', false, true)) {
          return $new_template;
        }
      }
    }
  }
  return $template;
}
add_filter('template_include', 'custom_template_include', 99);


/**
 * ファビコンとサイトアイコンを設定
 * @return output  link icon, apple-touch-icon
 */
function add_theme_favicon() {
  global $bb_theme_config;
  // テーマカスタマイザでサイトアイコンが設定されている場合は無効
  if (get_site_icon_url()) return;
  if (!empty($bb_theme_config['favicon'])) {
    echo "<link rel=\"icon\" href=\"{$bb_theme_config['favicon']}\">\n";
  }
  if (!empty($bb_theme_config['siteicon'])) {
    echo "<link rel=\"apple-touch-icon\" href=\"{$bb_theme_config['siteicon']}\">\n";
  }
}
add_action('wp_head', 'add_theme_favicon', 10);
// ファビコンが未設定の場合にテーマアイコンを設定
function bb_theme_favicon() {
  global $bb_theme_config;
  if (empty($bb_theme_config['favicon'])) {
    $favicon = '/assets/img/favicon.ico';
    if (is_file(get_stylesheet_directory() . $favicon)) {
      wp_redirect(get_site_icon_url(512, get_stylesheet_directory_uri() . $favicon));
    } elseif (is_file(get_template_directory() . $favicon)) {
      wp_redirect(get_site_icon_url(512, get_template_directory_uri() . $favicon));
    }
  }
  exit;
}
add_action('do_faviconico', 'bb_theme_favicon');


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
 * アップロードできるファイルタイプを追加
 */
function customize_mime_types($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'customize_mime_types');


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


/**
 * グローバルナビ／ウィジェットの下層リストをラップ
 */
// グローバルナビ
class custom_walker_nav_menu extends Walker_Nav_Menu {
  public function start_lvl(&$output, $depth = 0, $args = null) {
    if ($depth === 0) {
      $output .= "\n<div class=\"child-group\">";
    }
    parent::start_lvl($output, $depth, $args);
  }
  public function end_lvl(&$output, $depth = 0, $args = null) {
    parent::end_lvl($output, $depth, $args);
    if ($depth === 0) {
      $output .= "</div>\n";
    }
  }
}
function customize_widget_nav_menu_args($args) {
  $args['walker'] =  new custom_walker_nav_menu();
  return $args;
}
add_filter('widget_nav_menu_args', 'customize_widget_nav_menu_args', 10, 2);

// カテゴリー
class custom_walker_category extends Walker_Category {
  public function start_lvl(&$output, $depth = 0, $args = null) {
    if ($depth === 0) {
      $output .= "\n<div class=\"child-group\">";
    }
    parent::start_lvl($output, $depth, $args);
  }
  public function end_lvl(&$output, $depth = 0, $args = null) {
    parent::end_lvl($output, $depth, $args);
    if ($depth === 0) {
      $output .= "</div>\n";
    }
  }
}
function customize_widget_categories_args($args, $instance) {
  $args['walker'] =  new custom_walker_category();
  return $args;
}
add_filter('widget_categories_args', 'customize_widget_categories_args', 10, 2);

// 固定ページ
class custom_walker_page extends Walker_Page {
  public function start_lvl(&$output, $depth = 0, $args = null) {
    if ($depth === 0) {
      $output .= "\n<div class=\"child-group\">";
    }
    parent::start_lvl($output, $depth, $args);
  }
  public function end_lvl(&$output, $depth = 0, $args = null) {
    parent::end_lvl($output, $depth, $args);
    if ($depth === 0) {
      $output .= "</div>\n";
    }
  }
}
function customize_widget_pages_args($args, $instance) {
  $args['walker'] =  new custom_walker_page();
  return $args;
}
add_filter('widget_pages_args', 'customize_widget_pages_args', 10, 2);


/**
 * inputなどstring型をフィルター処理
 * numeric -> int or float
 * true/false -> bool
 */
function bb_string_type_filter($values) {
  $values = filter_var($values, FILTER_CALLBACK, array(
    'options' => function ($val) {
      if (is_numeric($val)) return is_float($val) ? (float) $val : (int) $val;
      if ($val === 'true') return true;
      if ($val === 'false') return false;
      return $val;
  }));
  return $values;
}


/**
 * 最近の投稿ウィジェットをカスタマイズ
 * wp-includes/widgets/class-wp-widget-recent-posts.php
 */
class Bb_WP_Widget_Recent_Posts extends WP_Widget_Recent_Posts {
  public function __construct() {
    parent::__construct();
  }
  public function widget( $args, $instance ) {
    if ( ! isset( $args['widget_id'] ) ) {
      $args['widget_id'] = $this->id;
    }

    $default_title = __( 'Recent Posts' );
    $title         = ( ! empty( $instance['title'] ) ) ? $instance['title'] : $default_title;

    $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

    $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
    if ( ! $number ) {
      $number = 5;
    }
    $show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;

    $r = new WP_Query(
      apply_filters(
        'widget_posts_args',
        array(
          'posts_per_page'      => $number,
          'no_found_rows'       => true,
          'post_status'         => 'publish',
          'ignore_sticky_posts' => true,
        ),
        $instance
      )
    );

    if ( ! $r->have_posts() ) {
      return;
    }
    ?>

    <?php echo $args['before_widget']; ?>

    <?php
    if ( $title ) {
      echo $args['before_title'] . $title . $args['after_title'];
    }

    $format = current_theme_supports( 'html5', 'navigation-widgets' ) ? 'html5' : 'xhtml';

    $format = apply_filters( 'navigation_widgets_format', $format );

    if ( 'html5' === $format ) {
      $title      = trim( strip_tags( $title ) );
      $aria_label = $title ? $title : $default_title;
      echo '<nav aria-label="' . esc_attr( $aria_label ) . '">';
    }
    ?>

    <ul>
      <?php foreach ( $r->posts as $recent_post ) : ?>
        <?php
        $post_title   = get_the_title( $recent_post->ID );
        $title        = ( ! empty( $post_title ) ) ? $post_title : __( '(no title)' );
        $aria_current = '';

        if ( get_queried_object_id() === $recent_post->ID ) {
          $aria_current = ' aria-current="page"';
        }
        ?>
        <li>
          <a href="<?php the_permalink( $recent_post->ID ); ?>"<?php echo $aria_current; ?>><span class="entry-title"><?php echo $title; ?></span><?php if ( $show_date ) : ?><span class="post-date"><?php echo get_the_date( '', $recent_post->ID ); ?></span><?php endif; ?></a>
        </li>
      <?php endforeach; ?>
    </ul>

    <?php
    if ( 'html5' === $format ) {
      echo '</nav>';
    }

    echo $args['after_widget'];
  }
  public function update( $new_instance, $old_instance ) {
    parent::update( $new_instance, $old_instance );
  }
  public function form( $instance ) {
    parent::form( $instance );
  }
}
function bb_register_widget_recent_posts() {
  register_widget('Bb_WP_Widget_Recent_Posts');
}
add_action('widgets_init', 'bb_register_widget_recent_posts');


/**
 * 最近のコメントウィジェットをカスタマイズ
 * wp-includes/widgets/class-wp-widget-recent-comments.php
 */
class Bb_WP_Widget_Recent_Comments extends WP_Widget_Recent_Comments {
  public function __construct() {
    parent::__construct();
  }
  public function widget( $args, $instance ) {
    static $first_instance = true;

    if ( ! isset( $args['widget_id'] ) ) {
      $args['widget_id'] = $this->id;
    }

    $output = '';

    $default_title = __( 'Recent Comments' );
    $title         = ( ! empty( $instance['title'] ) ) ? $instance['title'] : $default_title;

    /** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
    $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

    $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
    if ( ! $number ) {
      $number = 5;
    }

    $comments = get_comments(
      apply_filters(
        'widget_comments_args',
        array(
          'number'      => $number,
          'status'      => 'approve',
          'post_status' => 'publish',
        ),
        $instance
      )
    );

    $output .= $args['before_widget'];
    if ( $title ) {
      $output .= $args['before_title'] . $title . $args['after_title'];
    }

    $recent_comments_id = ( $first_instance ) ? 'recentcomments' : "recentcomments-{$this->number}";
    $first_instance     = false;

    $format = current_theme_supports( 'html5', 'navigation-widgets' ) ? 'html5' : 'xhtml';

    $format = apply_filters( 'navigation_widgets_format', $format );

    if ( 'html5' === $format ) {
      // The title may be filtered: Strip out HTML and make sure the aria-label is never empty.
      $title      = trim( strip_tags( $title ) );
      $aria_label = $title ? $title : $default_title;
      $output    .= '<nav aria-label="' . esc_attr( $aria_label ) . '">';
    }

    $output .= '<ul id="' . esc_attr( $recent_comments_id ) . '">';
    if ( is_array( $comments ) && $comments ) {
      $post_ids = array_unique( wp_list_pluck( $comments, 'comment_post_ID' ) );
      _prime_post_caches( $post_ids, strpos( get_option( 'permalink_structure' ), '%category%' ), false );

      foreach ( (array) $comments as $comment ) {
        $output .= '<li class="recentcomments">';
        $output .= sprintf('<a href="%1$s"><span class="entry-title">%2$s</span><span class="comment-user">%3$s</span></a>', esc_url(get_comment_link($comment)), get_the_title($comment->comment_post_ID), get_comment_author($comment));
        $output .= '</li>';
      }
    }
    $output .= '</ul>';

    if ( 'html5' === $format ) {
      $output .= '</nav>';
    }

    $output .= $args['after_widget'];

    echo $output;
  }
  public function update( $new_instance, $old_instance ) {
    parent::update( $new_instance, $old_instance );
  }
  public function form( $instance ) {
    parent::form( $instance );
  }
}
function bb_register_widget_recent_comments() {
  register_widget('Bb_WP_Widget_Recent_Comments');
}
add_filter('show_recent_comments_widget_style', '__return_false');
add_action('widgets_init', 'bb_register_widget_recent_comments');
