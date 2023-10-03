<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Description: テーマ用ファンクション (basis)
 */

/**
 * 基本
 */
if (!function_exists('set_theme_support')) {
  function set_theme_support() {
    global $bb_theme_config;
    add_theme_support('menus');
    add_theme_support('title-tag');
    add_theme_support('html5', array(
      'search-form',
      'comment-form',
      'comment-list',
      'caption',
      'gallery',
    ));
    function _crop($crop) {
      return strpos($crop, ',') !== false ? explode(',', str_replace(array(' ', "\t"), '', $crop)) : $crop;
    }
    // アーカイブページ用サムネイル
    add_image_size('archive-thumbnail', $bb_theme_config['archive_thumbnail'][0], $bb_theme_config['archive_thumbnail'][1], _crop($bb_theme_config['archive_thumbnail'][2]));
    // アイキャッチ
    if (!empty($bb_theme_config['post_thumbnail'][0]) && !empty($bb_theme_config['post_thumbnail'][1])) {
      add_theme_support('post-thumbnails');
      set_post_thumbnail_size($bb_theme_config['post_thumbnail'][0], $bb_theme_config['post_thumbnail'][1], _crop($bb_theme_config['post_thumbnail'][2]));
    }
    // メインビジュアル
    if (!empty($bb_theme_config['mv_image_size'][0]) && !empty($bb_theme_config['mv_image_size'][1])) {
      add_image_size('mainvisual', $bb_theme_config['mv_image_size'][0], $bb_theme_config['mv_image_size'][1], _crop($bb_theme_config['mv_image_size'][2]));
    }
  }
}
add_action('after_setup_theme', 'set_theme_support');


/**
 * ヘッダー情報の登録を無効化
 */
if (!function_exists('set_wp_head')) {
  function set_wp_head() {
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'rel_canonical');
  }
}
add_action('init', 'set_wp_head');


/**
 * フィード
 */
if (!function_exists('set_automatic_feed_links')) {
  function set_automatic_feed_links() {
    add_theme_support('automatic-feed-links');
  }
}
add_action('after_setup_theme', 'set_automatic_feed_links');
if (!function_exists('set_feed_links')) {
  function set_feed_links() {
    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'feed_links_extra', 3);
  }
}
add_action('init', 'set_feed_links');
if (!function_exists('bb_feed_links')) {
  function bb_feed_links($args = array()) {
    if (!current_theme_supports('automatic-feed-links')) return;
    $defaults = array(
      'separator' => _x('&raquo;', 'feed link'),
      'feedtitle' => __('%1$s %2$s Feed'),
      'cattitle'  => __('%1$s %2$s %3$s Category Feed'),
      'tagtitle'  => __('%1$s %2$s %3$s Tag Feed'),
    );
    $args = wp_parse_args($args, $defaults);
    echo '<link rel="alternate" type="' . feed_content_type() . '" title="' . esc_attr(sprintf($args['feedtitle'], get_bloginfo('name'), $args['separator'])) . '" href="' . esc_url(get_feed_link()) . "\" />\n";
    if (is_category()) { // カテゴリーのフィード
      $term = get_queried_object();
      if ($term) {
        $title = sprintf($args['cattitle'], get_bloginfo('name'), $args['separator'], $term->name);
        $href = get_category_feed_link($term->term_id);
      }
    } elseif (is_tag()) { // タグのフィード
      $term = get_queried_object();
      if ($term) {
        $title = sprintf($args['tagtitle'], get_bloginfo('name'), $args['separator'], $term->name);
        $href = get_tag_feed_link($term->term_id);
      }
    }
    if (isset($title) && isset($href)) {
      echo '<link rel="alternate" type="' . feed_content_type() . '" title="' . esc_attr($title) . '" href="' . esc_url($href) . '" />' . "\n";
    }
  }
}
add_action('wp_head', 'bb_feed_links', 3);


/**
 * ナビゲーションメニューを利用
 */
register_nav_menus(array(
  'header_nav' => 'ヘッダーナビゲーション',
  'global_nav' => 'グローバルナビゲーション'
));


/**
 * ウィジェットエリアを設置
 */
if (!function_exists('add_widgets_init')) {
  function add_widgets_init() {
    register_sidebar(array(
      'name'        => 'サイドバー 1',
      'id'          => 'sidebar-widget-1',
      'description' => 'サイドバーの1段目にウィジェットを配置します。'
    ));
    register_sidebar(array(
      'name'        => 'サイドバー 2',
      'id'          => 'sidebar-widget-2',
      'description' => 'サイドバーの2段目にウィジェットを配置します。'
    ));
    register_sidebar(array(
      'name'        => 'サイドバー 3',
      'id'          => 'sidebar-widget-3',
      'description' => 'サイドバーの3段目にウィジェットを配置します。'
    ));
    register_sidebar(array(
      'name'        => 'フッター 1',
      'id'          => 'footer-widget-1',
      'description' => 'フッターの1列目のカラムにウィジェットを配置します。'
    ));
    register_sidebar(array(
      'name'        => 'フッター 2',
      'id'          => 'footer-widget-2',
      'description' => 'フッターの2列目のカラムにウィジェットを配置します。'
    ));
    register_sidebar(array(
      'name'        => 'フッター 3',
      'id'          => 'footer-widget-3',
      'description' => 'フッターの3列目のカラムにウィジェットを配置します。'
    ));
    register_sidebar(array(
      'name'        => 'フッター 4',
      'id'          => 'footer-widget-4',
      'description' => 'フッターの4列目のカラムにウィジェットを配置します。'
    ));
    register_sidebar(array(
      'name'        => 'モバイル Top',
      'id'          => 'mobile-widget-top',
      'description' => 'テーマオプションで設定のモバイルメニューより上部にウィジェットを配置します。'
    ));
    register_sidebar(array(
      'name'        => 'モバイル Bottom',
      'id'          => 'mobile-widget-bottom',
      'description' => 'テーマオプションで設定のモバイルメニューより下部にウィジェットを配置します。'
    ));
  }
}
add_action('widgets_init', 'add_widgets_init');


/**
 * ページタイトルを設定
 */
if (!function_exists('bb_get_document_title')) {
  function bb_get_document_title($title) {
    global $post, $page, $paged, $bb_theme_config;
    $_title = get_bloginfo('name');
    $suffix = $bb_theme_config['archive_title_suffix'];
    $sep = $bb_theme_config['title_separator'];
    $_paged = $paged >= 2 || $page >= 2 ? sprintf('（%sページ）', max($paged, $page)) : '';
    if (is_front_page()) {
      $subtitle = empty($bb_theme_config['title_catchphrase']) ? '' : $sep . $bb_theme_config['title_catchphrase'];
      $title = $_title . $_paged . $subtitle;
    } elseif (is_home()) {
      $title = single_post_title('', false) . $_paged . $sep . $_title;
    } elseif (is_search()) {
      $title = '「' . get_search_query() . '」の検索結果一覧' . $_paged . $sep . $_title;
    } elseif (is_year()) {
      $title = get_the_date($bb_theme_config['date_format'][0]) . $suffix . $_paged . $sep . $_title;
    } elseif (is_month()) {
      $title = get_the_date($bb_theme_config['date_format'][0] . $bb_theme_config['date_format'][1]) . $suffix . $_paged . $sep . $_title;
    } elseif (is_day()) {
      $title = get_the_date($bb_theme_config['date_format'][0] . $bb_theme_config['date_format'][1] . $bb_theme_config['date_format'][2]) . $suffix . $_paged . $sep . $_title;
    } elseif (is_author()) {
      $title = get_the_author_meta('nickname', get_query_var('author')) . $suffix . $_paged . $sep . $_title;
    } elseif (is_single() || is_page()) {
      $title = (get_the_title() ? get_the_title() : $post->post_name) . $_paged . $sep . $_title;
    } elseif (is_category() || is_tag()) {
      $title = single_cat_title('', false) . $_paged . $sep . $_title;
    } elseif(is_post_type_archive()) {
      $custom = get_post_type_object(get_query_var('post_type'));
      $title = $custom->labels->singular_name . $_paged . $sep . $_title;
    } elseif (is_tax()) {
      $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
      $title = $term->name . $_paged . $sep . $_title;
    } elseif (is_404()) {
      $title = 'ページが見つかりません' . $sep . $_title;
    }
    $title = apply_filters('bb_get_document_title', $title);
    return esc_attr($title);
  }
}
add_filter('pre_get_document_title', 'bb_get_document_title');


/**
 * JS, CSS の読込
 */
if (!function_exists('add_styles_scripts')) {
  function add_styles_scripts() {
    global $bb_theme_config;
    if ($bb_theme_config['with_parent_css'] === true) {
      wp_register_style(
        'theme',
        get_template_directory_uri() . '/assets/css/theme.css',
        array(),
        VERSION_PARAM
      );
      wp_enqueue_style('theme');
    }
    // comments
    if (is_singular() && comments_open() && get_option('thread_comments')) {
      wp_enqueue_script('comment-reply');
    }
  }
}
add_action('wp_enqueue_scripts', 'add_styles_scripts');

// モバイル用
if (!function_exists('add_mobile_styles_scripts')) {
  function add_mobile_styles_scripts() {
    if (is_admin()) return;
    global $bb_theme_config;
    if ($bb_theme_config['with_parent_script'] === true) {
      wp_register_script(
        'mobile-nav',
        get_template_directory_uri() . '/assets/js/mobile-nav.js',
        array(),
        VERSION_PARAM,
        true
      );
      wp_enqueue_script('mobile-nav');
    }
    // スライドメニューの登録
    $_js = array();
    if (!empty($bb_theme_config['mobile_nav'])) {
      $_js[] = "slideNav:['" . implode("','", $bb_theme_config['mobile_nav']) . "']";
    }
    if (!empty($bb_theme_config['mobile_nav_footer'])) {
      $_js[] = "footerNav:['" . implode("','", $bb_theme_config['mobile_nav_footer']) . "']";
    }
    // モバイルウィジェットの登録
    if (is_active_sidebar('mobile-widget-top') || is_active_sidebar('mobile-widget-bottom')) {
      $_js[] = "mobileWidgets:'" . get_admin_url(null, 'admin-ajax.php?action=mobile-widgets') . "'";
    }
    if (!empty($_js)) {
      $js = 'var bbCfgMobileNav={' . implode(',', $_js) . '};';
      wp_add_inline_script('mobile-nav', $js, 'before');
    }
  }
}
add_action('wp_enqueue_scripts', 'add_mobile_styles_scripts', 40);

// 管理画面で登録されたモバイル用ウィジェットの追加
function add_ajax_mobile_widgets() {
  $widgets = [
    'mobile-widget-top',
    'mobile-widget-bottom',
  ];
  foreach ($widgets as $widget) {
    if (is_active_sidebar($widget)) {
?>
<div id="<?php echo $widget; ?>" class="nav-block">
  <ol>
    <?php dynamic_sidebar($widget); ?>
  </ol>
</div>
<?php
    }
  }
  wp_die();
}
add_action('wp_ajax_mobile-widgets', 'add_ajax_mobile_widgets');
add_action('wp_ajax_nopriv_mobile-widgets', 'add_ajax_mobile_widgets');

// ユーザー定義関数
if (!function_exists('add_common_scripts')) {
  function add_common_scripts() {
    if (is_admin()) return;
    global $bb_theme_config;
    if ($bb_theme_config['with_parent_script'] === true) {
      wp_register_script(
        'functions',
        get_template_directory_uri() . '/assets/js/functions.js',
        array(),
        VERSION_PARAM,
        true
      );
      wp_enqueue_script('functions');
    }
  }
}
add_action('wp_enqueue_scripts', 'add_common_scripts', 40);

// 個別の css ファイルがある場合に追加
function add_cfg_styles_scripts() {
  global $bb_theme_id_class;
  // css
  if (!empty($bb_theme_id_class->css)) {
    if (is_file(get_stylesheet_directory() . '/assets/css/' . $bb_theme_id_class->css)) {
      wp_register_style(
        'current',
        get_stylesheet_directory_uri() . '/assets/css/' . $bb_theme_id_class->css,
        array(),
        VERSION_PARAM
      );
    } elseif (is_file(get_stylesheet_directory() . '/css/' . $bb_theme_id_class->css)) {
      wp_register_style(
        'current',
        get_stylesheet_directory_uri() . '/css/' . $bb_theme_id_class->css,
        array(),
        VERSION_PARAM
      );
    } elseif (is_file(get_stylesheet_directory() . '/' . $bb_theme_id_class->css)) {
      wp_register_style(
        'current',
        get_stylesheet_directory_uri() . '/' . $bb_theme_id_class->css,
        array(),
        VERSION_PARAM
      );
    }
    wp_enqueue_style('current');
  }
  // js
  if (!empty($bb_theme_id_class->js)) {
    if (is_file(get_stylesheet_directory() . '/assets/js/' . $bb_theme_id_class->js)) {
      wp_register_script(
        'current',
        get_stylesheet_directory_uri() . '/assets/js/' . $bb_theme_id_class->js,
        array(),
        VERSION_PARAM
      );
    } elseif (is_file(get_stylesheet_directory() . '/js/' . $bb_theme_id_class->js)) {
      wp_register_script(
        'current',
        get_stylesheet_directory_uri() . '/js/' . $bb_theme_id_class->js,
        array(),
        VERSION_PARAM
      );
    } elseif (is_file(get_stylesheet_directory() . '/' . $bb_theme_id_class->js)) {
      wp_register_script(
        'current',
        get_stylesheet_directory_uri() . '/' . $bb_theme_id_class->js,
        array(),
        VERSION_PARAM
      );
    }
    wp_enqueue_script('current');
  }
}
add_action('wp_enqueue_scripts', 'add_cfg_styles_scripts', 90);

// プリロード
function bb_preload_files() {
  global $bb_theme_config;
  // アイコンフォント用
  $font_file = '/assets/fonts/BlankBlanc-Icons.woff2';
  if ($bb_theme_config['with_parent_css'] === true) {
    echo '<link rel="preload" href="' . get_template_directory_uri() . $font_file . '?7nner8" as="font" type="font/woff2" crossorigin>' . "\n";
  } elseif (file_exists(get_stylesheet_directory() . $font_file)) {
    echo '<link rel="preload" href="' . get_stylesheet_directory_uri() . $font_file . '?7nner8" as="font" type="font/woff2" crossorigin>' . "\n";
  }
}
add_action('wp_head', 'bb_preload_files', 5);


/**
 * インライン css / js
 */
// head | css
function add_inline_css_head() {
  global $post;
  if (!is_object($post) || !is_singular()) {
    return;
  }
  if ($src = get_post_meta($post->ID, 'bb_inline_css_head', true)) {
    $src = trim($src);
    echo "<style>\n{$src}\n</style>\n";
  }
}
add_action('wp_head', 'add_inline_css_head', 8);

// head | js
function add_inline_js_head() {
  global $post;
  if (!is_object($post) || !is_singular()) {
    return;
  }
  if ($src = get_post_meta($post->ID, 'bb_inline_js_head', true)) {
    $src = trim($src);
    echo "<script>\n{$src}\n</script>\n";
  }
}
add_action('wp_head', 'add_inline_js_head', 99);

// body | js
function add_inline_js_body() {
  global $post;
  if (!is_object($post) || !is_singular()) {
    return;
  }
  if ($src = get_post_meta($post->ID, 'bb_inline_js_body', true)) {
    $src = trim($src);
    echo "<script>\n{$src}\n</script>\n";
  }
}
add_action('wp_footer', 'add_inline_js_body', 99);


/**
 * 子テーマ用 JS, CSS の読込
 */
if (is_child_theme()) {
  // js
  if (!function_exists('child_theme_scripts')) {
    function child_theme_scripts() {
      $filename = '/js/mobile-nav.js';
      if (is_file(get_stylesheet_directory() . '/assets' . $filename)) {
        wp_register_script(
          'child-mobile-nav',
          get_stylesheet_directory_uri() . '/assets' . $filename,
          array(),
          VERSION_PARAM,
          true
        );
      } elseif (is_file(get_stylesheet_directory() . $filename)) {
        wp_register_script(
          'child-mobile-nav',
          get_stylesheet_directory_uri() . $filename, array(),
          VERSION_PARAM,
          true
        );
      }
      wp_enqueue_script('child-mobile-nav');

      $filename = '/js/functions.js';
      if (is_file(get_stylesheet_directory() . '/assets' . $filename)) {
        wp_register_script(
          'child-functions',
          get_stylesheet_directory_uri() . '/assets' . $filename,
          array(),
          VERSION_PARAM,
          true
        );
      } elseif (is_file(get_stylesheet_directory() . $filename)) {
        wp_register_script(
          'child-functions',
          get_stylesheet_directory_uri() . $filename, array(),
          VERSION_PARAM,
          true
        );
      }
      wp_enqueue_script('child-functions');
    }
  }
  add_action('wp_enqueue_scripts', 'child_theme_scripts', 50);

  // css
  if (!function_exists('child_theme_styles')) {
    function child_theme_styles() {
      $filename = 'theme.css';
      if (is_file(get_stylesheet_directory() . '/assets/css/' . $filename)) {
        wp_register_style(
          'child',
          get_stylesheet_directory_uri() . '/assets/css/' . $filename,
          array(),
          VERSION_PARAM
        );
      } elseif (is_file(get_stylesheet_directory() . '/css/' . $filename)) {
        wp_register_style(
          'child',
          get_stylesheet_directory_uri() . '/css/' . $filename,
          array(),
          VERSION_PARAM
        );
      } else {
        wp_register_style(
          'child',
          get_stylesheet_uri(),
          array(),
          VERSION_PARAM
        );
      }
      wp_enqueue_style('child');
    }
  }
  add_action('wp_enqueue_scripts', 'child_theme_styles', 30);
}

// JSに遅延（defer）読み込み属性を追加
function add_script_loader_tag($tag, $handle) {
  $handles = array(
    'functions',
    'mobile-nav',
    'comment-reply',
    'child-functions',
    'child-mobile-nav',
  );
  $handles = apply_filters('bb_defer_script_loader_tag', $handles);
  if (array_search($handle, $handles) !== false) {
    return str_replace('src', 'defer src', $tag);
  }
  return $tag;
}
add_filter('script_loader_tag', 'add_script_loader_tag', 10, 2);

// CSSに非同期での読み込み記述を追加
function add_style_loader_tag($tag, $handle) {
  $handles = array(
    'wp-block-library',
  );
  $handles = apply_filters('bb_async_style_loader_tag', $handles);
  if (array_search($handle, $handles) !== false) {
    return str_replace("media='all'", "media='print' onload='this.media=\"all\"; this.onload=null;'", $tag);
  }
  return $tag;
}
add_filter('style_loader_tag', 'add_style_loader_tag', 10, 2);


/**
 * 絵文字を無効化
 */
function customize_disable_emoji() {
  global $bb_theme_config;
  if ($bb_theme_config['disable_emoji'] === true) {
    function remove_emoji_svg_url($url) {
      return '';
    }
    function remove_emoji_tinymce($plugins) {
      if ($key = array_search('wpemoji', $plugins)) {
        unset($plugins[$key]);
      }
      return $plugins;
    }
    function disable_emoji() {
      remove_action('wp_head', 'print_emoji_detection_script', 7);
      remove_action('admin_print_scripts', 'print_emoji_detection_script');
      remove_action('wp_print_styles', 'print_emoji_styles');
      remove_action('admin_print_styles', 'print_emoji_styles');
      remove_filter('the_content_feed', 'wp_staticize_emoji');
      remove_filter('comment_text_rss', 'wp_staticize_emoji');
      remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
      add_filter('tiny_mce_plugins', 'remove_emoji_tinymce');
      add_filter('emoji_svg_url', 'remove_emoji_svg_url');
    }
    add_action('init', 'disable_emoji');
  }
}
add_action('after_setup_theme', 'customize_disable_emoji');


/**
 * canonical の追加 また複数ページの場合 prev, next を追加
 * ※要 adjacent_posts_rel_link_wp_head の無効化
 * @return string  link rel="canonical", rel="prev", rel="next"
 */
function customize_output_canonical() {
  global $bb_theme_config;
  if ($bb_theme_config['output_canonical'] === true) {
    function get_next_prev_link_page($i) {
      $url = '';
      if ($i === 1) {
        $url = get_permalink();
      } elseif (get_option('permalink_structure')) {
        $url = trailingslashit(get_permalink()) . user_trailingslashit($i, 'single_paged');
      } else {
        $url = add_query_arg('page', $i, get_permalink());
      }
      return $url;
    }

    function bb_link_rel_canonical_next_prev() {
      global $wp_query, $post, $page, $paged;
      $meta_url = array('canonical' => null, 'prev' => null, 'next' => null);
      if (!is_singular()) {
        if (empty($paged)) {
          $paged = 1;
        }
        $pages = $wp_query->max_num_pages;
        $meta_url['canonical'] = get_pagenum_link($paged);
        if ($paged > 1) {
          $meta_url['prev'] = previous_posts(false);
        }
        if ($paged < $pages) {
          $meta_url['next'] = next_posts($pages, false);
        }
      }
      if (is_single() || is_page()) {
        $pages = substr_count($post->post_content, '<!--nextpage-->') + 1;
        if (empty($page)) {
          $page = 1;
        }
        $meta_url['canonical'] = get_pagenum_link();
        if ($page > 1) {
          $meta_url['prev'] = get_next_prev_link_page($page - 1);
        }
        if ($page < $pages) {
          $meta_url['next'] = get_next_prev_link_page($page + 1);
        }
      }
      $src  = '';
      if ($meta_url['canonical']) {
        $src .= "<link rel='canonical' href='{$meta_url['canonical']}'>\n";
      }
      if ($meta_url['prev']) {
        $src .= "<link rel='prev' href='{$meta_url['prev']}'>\n";
      }
      if ($meta_url['next']) {
        $src .= "<link rel='next' href='{$meta_url['next']}'>\n";
      }
      $src = apply_filters('bb_link_rel_canonical_next_prev', $src);
      echo $src;
    }
    add_action('wp_head', 'bb_link_rel_canonical_next_prev', 4);
  }
}
add_action('after_setup_theme', 'customize_output_canonical');


/**
 * Cookieの使用同意画面を表示
 * @return output  includes/inc-cookie-banner.php
 */
if (!function_exists('bb_cookie_banner')) {
  function bb_cookie_banner() {
    global $bb_theme_config;
    if ($bb_theme_config['cookie_banner']['indicate'] === true) {
      get_template_part('includes/inc', 'cookie-banner');
    }
  }
  function bb_cookie_banner_content($key = '') {
    global $bb_theme_config;
    if ($key && $value = $bb_theme_config['cookie_banner'][$key]) {
      echo $value;
    }
  }
}
add_action('wp_footer', 'bb_cookie_banner', 10);


/**
 * カテゴリータイトルを変更
 */
if (!function_exists('customize_archive_title')) {
  function customize_archive_title($title) {
    global $bb_theme_config;
    if (is_category()) {
      $title = single_cat_title('', false);
    } elseif (is_tag()) {
      $title = single_tag_title('', false);
    } elseif (is_day()) {
      $title = get_the_time($bb_theme_config['date_format'][0] . $bb_theme_config['date_format'][1] . $bb_theme_config['date_format'][2]);
    } elseif (is_month()) {
      $title = get_the_time($bb_theme_config['date_format'][0] . $bb_theme_config['date_format'][1]);
    } elseif (is_year()) {
      $title = get_the_time($bb_theme_config['date_format'][0]);
    } elseif (is_author()) {
      $title = get_the_author_meta('display_name') . 'の一覧';
    } elseif (is_post_type_archive()) {
      $title = get_post_type_object(get_query_var('post_type'))->labels->singular_name;
    } elseif (is_tax()) {
      $title = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'))->name . 'の一覧';
    } else {
      $title = $title . 'の一覧';
    }
    return $title;
  }
}
add_filter('get_the_archive_title', 'customize_archive_title');


/**
 * 投稿コンテンツから br 後の改行を除去
 */
if (!function_exists('remove_br_after_lf')) {
  function remove_br_after_lf($content) {
    if (is_singular()) {
      return preg_replace('!(<br\s?\/?>)(\r\n|\n)!', '$1', $content);
    }
    return $content;
  }
}
add_filter('the_content', 'remove_br_after_lf');


/**
 * 画像に alt が無い場合、タイトル、キャプション、ファイル名を利用
 */
if (!function_exists('add_attachment_image_alt')) {
  function add_attachment_image_alt($attr, $attachment) {
    if ($attr['alt']) {
      return $attr;
    }
    if ($attachment->post_title) {
      $attr['alt'] = $attachment->post_title;
    } elseif ($attachment->post_excerpt) {
      $attr['alt'] = $attachment->post_excerpt;
    } else {
      $attr['alt'] = basename($attachment->guid);
    }
    return $attr;
  }
}
add_filter('wp_get_attachment_image_attributes', 'add_attachment_image_alt', 10, 2);


/**
 * 編集時、画像に alt が無い場合、タイトル、キャプション、ファイル名を利用
 */
if (!function_exists('add_alt_image_send_to_editor')) {
  function add_alt_image_send_to_editor($html, $id, $caption, $title, $align, $url, $size, $alt) {
    if ($alt) {
      return $html;
    }
    $attachment = get_post($id);
    if ($attachment->post_title) {
      $replace = $attachment->post_title;
    } elseif ($attachment->post_excerpt) {
      $replace = $attachment->post_excerpt;
    } else {
      $replace = basename($attachment->guid);
    }
    $replace = esc_attr($replace);
    $html = preg_replace('/alt="(.*?)"/', "alt=\"{$replace}\"", $html);
    return $html;
  }
}
add_filter('image_send_to_editor', 'add_alt_image_send_to_editor', 10, 8);


/**
 * hentryを削除
 */
if (!function_exists('remove_hentry')) {
  function remove_hentry($classes) {
    $classes = array_diff($classes, array('hentry'));
    return $classes;
  }
}
add_filter('post_class', 'remove_hentry');


/**
 * コンテンツ内のテーマオプションの反映
 */
function add_image_link_target($content) {
  global $bb_theme_config;
  // 画像へのリンクはすべて別窓（_blank）として開く
  if ($bb_theme_config['image_link_target'] === true) {
    $ext = apply_filters('add_image_link_target', 'je?pg|png|gif|svg|pdf');
    $pat = sprintf('!<(a.+?href=".+?\.(%s)[^\s]*"?)>!i', $ext);
    $rep = '<$1 target="_blank" rel="noopener"$3>';
    $content = preg_replace($pat, $rep, $content);
  }
  // 画像に「bb-fade-in」data属性を付加
  if ($bb_theme_config['image_fade_in'] === true) {
    $pat = '!<(img.+?)(\s?\/?)>!i';
    $rep = '<$1 data-bb-option="fade-in"$3>';
    $content = preg_replace($pat, $rep, $content);
  }
  return $content;
}
add_filter('the_content', 'add_image_link_target', 99, 1);


/**
 * サムネイルに「bb-fade-in」data属性を付加
 */
function add_post_thumbnail_html($html) {
  global $bb_theme_config;
  if ($bb_theme_config['image_fade_in'] === true) {
    $pat = '!<(img.+?)(\s?\/?)>!i';
    $rep = '<$1 data-bb-option="fade-in"$3>';
    $html = preg_replace($pat, $rep, $html);
  }
  return $html;
}
add_filter('post_thumbnail_html', 'add_post_thumbnail_html');


/**
 * カテゴリーウィジェットの title 属性を削除
 */
if (!function_exists('remove_cat_title')) {
  function remove_cat_title($cat_args) {
    $cat_args['use_desc_for_title'] = 0;
    return $cat_args;
  }
}
add_filter('widget_categories_args', 'remove_cat_title');


/**
 * ウィジェットタイトルを表示・非表示
 */
function remove_widget_title($title) {
  if (substr($title, 0, 1) == '!') {
    return '';
  }
  return $title;
}
add_filter('widget_title', 'remove_widget_title');


/**
 * 日本語のスラッグを{prefix}-{ID}に自動設定
 * 参考:
 * http://www.warna.info/archives/2317/
 */
if (!function_exists('bb_ja_auto_post_slug')) {
  function bb_ja_auto_post_slug($slug, $post_ID, $post_status, $post_type) {
    global $bb_theme_config;
    if ($bb_theme_config['ja_auto_post_slug']['rewrite'] === true) {
      if (preg_match('/(%[0-9a-f]{2})+/', $slug)) {
        $prefix = $bb_theme_config['ja_auto_post_slug']['prefix'] ? $bb_theme_config['ja_auto_post_slug']['prefix'] : (utf8_uri_encode($post_type) . '-');
        $slug = $prefix . $post_ID;
      }
    }
    $slug = apply_filters('bb_ja_auto_post_slug', $slug);
    return $slug;
  }
}
add_filter('wp_unique_post_slug', 'bb_ja_auto_post_slug', 10, 4);


/**
 * コメント欄をカスタマイズ
 */
// コメントリスト表示用カスタマイズコード
function bb_theme_comment($comment, $args, $depth) {
  $GLOBALS['comment'] = $comment;
?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
  <div id="comment-<?php comment_ID(); ?>">
    <div class="avatar">
      <div class="thumb"><?php echo get_avatar($comment, 48); ?></div>
      <div class="reply">
        <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
      </div>
    </div>
    <div class="comment-block">
      <div class="header">
        <p class="author"><?php echo get_comment_author_link(); ?></p>
        <p class="date"><?php echo bb_get_custom_date('comment'); ?></p>
      </div>
      <div class="comment-text">
        <?php if ($comment->comment_approved == '0'): ?>
          <p class="awaiting"><?php _e('Your comment is awaiting moderation.'); ?></p>
        <?php endif; ?>
        <?php comment_text(); ?>
      </div>
    </div>
  </div>
<?php
}

// コメントフォーム
function bb_theme_comment_form() {
  global $post;
  // デフォルト値取得
  $post_id = $post->ID;
  $commenter = wp_get_current_commenter();
  $user = wp_get_current_user();
  $req = get_option('require_name_email');
  $aria_req = ($req ? ' aria-required="true"' : '');
  $req_text = '<span class="required">（必須）</span>';

  // $fields 設定
  $fields = array(
    'author' => '<p class="inputtext">' . '<label for="author">' . '名前'
      . ($req ? $req_text : null) . '</label>' .
      '<input id="author" name="author" type="text" value="'
      . esc_attr($commenter['comment_author']) . '"' . $aria_req . ' /></p>' . "\n",

    'email'  => '<p class="inputtext"><label for="email">' . 'メールアドレス'
      . ($req ? $req_text : null) . '</label>' .
      '<span class="not-publish">※メールアドレスは公開されません</span>' .
      '<input id="email" name="email" type="text" value="'
      . esc_attr($commenter['comment_author_email']) . '"' . $aria_req . ' /></p>' . "\n",

    'url'    => '<p class="inputtext"><label for="url">' . 'ウェブサイト'
      . '</label>' .
      '<input id="url" name="url" type="text" value="'
      . esc_attr($commenter['comment_author_url']) . '"' . ' /></p>' . "\n",
    );

  // $comment_field 設定
  $comment_field = '<p class="comment-form-comment"><label for="comment">' . 'コメント'
    . $req_text . '</label>'
    . '<textarea id="comment" name="comment" aria-required="true"></textarea></p>' . "\n";

  // $args 設定
  $args = array(
    'fields'               => apply_filters('comment_form_default_fields', $fields),
    'cancel_reply_link'    => '<span class="cancel-comment-reply-link-text">キャンセル</span>',
    'comment_field'        => $comment_field,
    'logged_in_as'         => sprintf(
      '<p class="logged-in-as">%s</p>',
      sprintf(
        __('<a href="%1$s">%2$s</a>としてログインしています。<br class="is-mobile">（<a href="%3$s">ログアウト</a>する）'),
        get_edit_user_link(),
        $user->exists() ? $user->display_name : '',
        wp_logout_url(apply_filters('the_permalink', get_permalink($post_id), $post_id))
      )
    ),
    'comment_notes_before' => '',
    'comment_notes_after'  => '',
    'title_reply_before'   => '<div id="reply-title" class="comment-reply-title">',
    'title_reply_after'    => "</div>\n",
  );
  comment_form($args);
}


/**
 * アーカイブの記事数表示を変更（対象: 日本語の一覧表示）
 */
if (!function_exists('bb_get_archives_link')) {
  function bb_get_archives_link($html, $url, $text, $format, $before, $after) {
    if ($format != 'html' || !strpos($html, '年')) {
      return $html;
    }
    static $year = null;
    $src = '';
    preg_match('/(\d{4})年(\d{1,2})月/', $text, $date);
    if ($year < $date[1]) {
      $year = null;
    }
    if (!empty($date)) {
      if ($year != $date[1]) {
        if (!empty($year)) {
          $src .= "</ul>\n";
          $src .= "<ul>\n";
        }
        $year = $date[1];
        $src .= "\t<li class=\"year-title\"><span>{$date[1]}年</span></li>\n";
      }
      $class = '';
      if (!empty($after)) {
        $after = preg_replace('/.*\((\d+)\)/', '$1', $after);
        $after = "<span class=\"count\">{$after}</span>";
        $class = ' class="with-count"';
      }
      $src .= "\t<li{$class}><a href=\"{$url}\"><span class=\"year\">{$date[1]}年</span><span class=\"month\">{$date[2]}月</span>{$after}</a></li>\n";
    }
    $src = apply_filters('bb_get_archives_link', $src);
    return $src;
  }
}
add_filter('get_archives_link', 'bb_get_archives_link', 10, 6);


/**
 * カテゴリーの記事数表示を変更
 */
if (!function_exists('bb_list_categories')) {
  function bb_list_categories($html, $args) {
    $src = preg_replace('/<\/a>((&nbsp;|\s)\((\d+)\))/', '<span class="count">${3}</span></a>', $html);
    $src = apply_filters('bb_list_categories', $src);
    return $src;
  }
}
add_filter('wp_list_categories', 'bb_list_categories', 10, 2);


/**
 * ロゴイメージを出力
 * @return output  img src alt
 */
if (!function_exists('bb_logo_image')) {
  function bb_logo_image() {
    global $bb_theme_config;
    if (!$alt = esc_html($bb_theme_config['logo_alt'])) {
      $alt = get_bloginfo('name');
    }
    if (empty($bb_theme_config['logo_image']) || $bb_theme_config['logo_image'] == -1) {
      echo '<a href="' . home_url('/') . '"><span class="site-title">' . $alt . '</span></a>';
      return;
    } elseif (ctype_digit($bb_theme_config['logo_image'])) {
      $logo_image = wp_get_attachment_image_src($bb_theme_config['logo_image'], 'full');
      $img = $logo_image[0];
    } else {
      $img = $bb_theme_config['logo_image'];
    }
    $size = '';
    if (!empty($bb_theme_config['logo_size'])) {
      if (!empty($bb_theme_config['logo_size'][0])) $size .= " width=\"{$bb_theme_config['logo_size'][0]}\"";
      if (!empty($bb_theme_config['logo_size'][1])) $size .= " height=\"{$bb_theme_config['logo_size'][1]}\"";
    }
    $src = '<a href="' . home_url('/') . '">' . "<img src=\"{$img}\" alt=\"{$alt}\"{$size}></a>";
    $src = apply_filters('bb_logo_image', $src);
    echo $src;
    return;
  }
}


/**
 * コピーライトを出力
 * @return output  prefix (copyright sign) year text
 */
if (!function_exists('bb_copyright')) {
  function bb_copyright() {
    global $bb_theme_config;
    if (empty($bb_theme_config['copyright']['text'])) {
      echo '';
      return;
    }
    $prefix = '';
    if (!empty($bb_theme_config['copyright']['prefix'])) {
      $prefix = "<span class=\"prefix\">{$bb_theme_config['copyright']['prefix']}</span>";
    }
    $year = '';
    if (!empty($bb_theme_config['copyright']['start_year'])) {
      $year = date_i18n('Y');
      if ((string) $bb_theme_config['copyright']['start_year'] < $year) {
        $year = $bb_theme_config['copyright']['start_year'] . '-' . $year;
      }
      $year = "<span class=\"year\">{$year}</span>";
    }
    $suffix = '';
    if (!empty($bb_theme_config['copyright']['suffix'])) {
      $suffix = "<span class=\"suffix\">{$bb_theme_config['copyright']['suffix']}</span>";
    }
    $src = "{$prefix}<span class=\"copyright-text\"><span class=\"sign\">&#169;</span>{$year}<span class=\"text\">{$bb_theme_config['copyright']['text']}</span></span>{$suffix}";
    $src = apply_filters('bb_copyright', $src);
    echo $src;
    return;
  }
}


/**
 * the_content の出力時、more 以降を div で囲む
 * @param string  $more_text  more の テキスト（デフォルトは bb_config_default で指定）
 * @return output  <div class="more-content"> で以降を囲んで出力
 */
if (!function_exists('bb_more_content')) {
  function bb_more_content($content) {
    if (is_singular() && strpos($content, 'id="more-')) {
      $content = preg_replace('!<p.+?id="more\-.+?\/p>\n?!s', '', $content);
      $pid = get_the_ID();
      $content = "<div class=\"more-content\" id=\"more-{$pid}\">\n{$content}</div>\n";
    }
    return $content;
  }
}
add_filter('the_content', 'bb_more_content');

if (!function_exists('bb_the_content')) {
  function bb_the_content($more_text = '') {
    if (is_singular()) {
      if (strpos(get_the_content(), 'id="more-')) {
        global $more, $bb_theme_config;
        $more_text = !$more_text ? $bb_theme_config['more_text'] : $more_text;
        $more = 0;
        the_content($more_text);
        $more = 1;
        the_content('', true);
      } else {
        the_content();
      }
    } else {
      the_content();
    }
    return;
  }
}


/**
 * タームの説明文を出力
 * @param string  CSSのクラス名（デフォルトは term-description）
 * @return output  <section class="$name">{説明文}</section>
 */
if (!function_exists('bb_get_term_description')) {
  function bb_get_term_description($name = 'term-description') {
    if (is_category() || is_tag() || is_tax()) {
      if ($description = term_description()) {
        return "<section class=\"{$name}\">\n\t{$description}</section>\n";
      }
    }
    return;
  }
}


/**
 * タクソノミーのレイアウトタイプを出力
 * @return output  list|tiles
 */
if (!function_exists('bb_get_taxonomy_layout')) {
  function bb_get_taxonomy_layout() {
    global $bb_theme_config;
    if (is_home() || is_front_page()) {
      return $bb_theme_config['homepage_layout']['articles'];
    }
    if ($bb_taxonomy_layout = get_term_meta(get_queried_object_id(), 'bb_taxonomy_option', true)) {
      $bb_taxonomy_layout = $bb_taxonomy_layout['layout']['value'];
    } else {
      $bb_taxonomy_layout = $bb_theme_config['taxonomy_layout'];
    }
    return $bb_taxonomy_layout;
  }
}


/**
 * グローバルナビの固定
 * @return output  class=non-fixed
 */
if (!function_exists('bb_get_fixed_global_nav')) {
  function bb_get_fixed_global_nav() {
    global $bb_theme_config;
    return $bb_theme_config['fixed_global_nav'] === true ? '' : ' class="non-fixed"';
  }
}


/**
 * サイドバー（ウィジェット）の固定
 * @return output  class=bottom-fixed-widget
 */
if (!function_exists('bb_get_fixed_widget')) {
  function bb_get_fixed_widget() {
    global $bb_theme_config;
    return $bb_theme_config['fixed_widget'] === true ? ' class="bottom-fixed-widget"' : '';
  }
}


/**
 * 新着の日付＋タイトル一覧を取得
 * @param array  パラメータは WP_Query と同じ
 *        （デフォルトは5件、category__in および category__not_in はカンマ区切り）
 * @return string  dl > dt(date) + dd(title)
 * @shortcode bb_get_category_list
 */
function bb_get_category_list($args = '') {
  if (empty($args)) {
    $args = array();
  }
  if (isset($args['category__in'])) {
    $args['category__in'] = explode(',', $args['category__in']);
  }
  if (isset($args['category__not_in'])) {
    $args['category__not_in'] = explode(',', $args['category__not_in']);
  }
  if (isset($args['post__in'])) {
    $args['post__in'] = explode(',', $args['post__in']);
  }
  if (isset($args['post__not_in'])) {
    $args['post__not_in'] = explode(',', $args['post__not_in']);
  }
  $args = array_merge(array(
    'posts_per_page' => 5, // デフォルト5件
    'no_found_rows'  => true, // ページネーション不要
  ), $args);
  $cat = new WP_Query($args);
  $src = '';
  if ($cat->have_posts()) {
    $src .= "<dl>\n";
    while ($cat->have_posts()) {
      $cat->the_post();
      $src .= "\t<dt>" . get_the_date() . "</dt>\n";
      $src .= "\t<dd><a href=\"" . get_permalink() . "\">" . get_the_title() . "</a></dd>\n";
    }
    $src .= "</dl>\n";
    wp_reset_postdata();
  } else {
    $src = "<p class=\"no-result\">該当する記事は見つかりませんでした</p>\n";
  }
  $src = apply_filters('bb_get_category_list', $src);
  return $src;
}
add_shortcode('bb_get_category_list', 'bb_get_category_list');
