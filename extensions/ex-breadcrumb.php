<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Description: テーマ用パンくずリスト
 *
 * @param array $args
 *        home トップレベルの表示 初期値: HOME
 *        symbol 階層表示シンボル 初期値: <span class="mdi mdi-chevron-right"></span>
 *        child 子階層化 初期値: true
 *        multi 複数カテゴリーの表示 初期値: false
 *        before トップレベルを追加 array('{URL}', '{表示名}') 初期値: null
 *        ※ディレクトリ下やマルチサイト運用時など
 * @return string  div itemscope で階層を付加したパンくずを出力
 */

/**
 * 書き出し用関数
 */
if (!function_exists('bb_get_bread_crumb')) {
  function bb_get_bread_crumb($args = array()) {
    $bread_crumb = new bbBreadCrumb($args);
    return $bread_crumb->bb_get_bread_crumb_src();
  }
}

class bbBreadCrumb
{
  public $args;

  public function __construct($args) {
    global $bb_theme_config;
    $this->args = array_merge(array(
      'home'     => 'HOME',
      'symbol'   => '<span class="mdi mdi-chevron-right"></span>',
      'child'    => true,
      'notfound' => '404 Not found',
      'multi'    => $bb_theme_config['bread_crumb_multi'],
      'before'   => null,
    ), $args);
  }

  /**
   * パンくず用配列を生成
   */
  private function bb_get_bread_crumb_link() {
    global $post, $authordata, $bb_theme_config;
    $bc = array();
    $i = 0;
    if (is_home() || is_front_page()) {
      $bc[$i][100] = array(
        'url' => home_url('/'),
        'val' => esc_attr($this->args['home'])
      );
    } else {
      // ホーム
      $bc[$i][0] = array(
        'url' => home_url('/'),
        'val' => esc_attr($this->args['home'])
      );
      // 固定ページ
      if (is_page()) {
        if ($post->post_parent != 0) {
          $ancestors = array_reverse(get_post_ancestors($post->ID));
          foreach ($ancestors as $ancestor) {
            $bc[$i][] = array(
              'url' => get_permalink($ancestor),
              'val' => esc_attr(get_the_title($ancestor))
            );
          }
        }
        $bc[$i][99] = array(
          'url' => get_permalink(),
          'val' => esc_attr($post->post_title)
        );
      }
      // カテゴリーのアーカイブページ
      elseif (is_category()) {
        $category = get_queried_object();
        if ($category->parent != 0) {
          $ancestors = array_reverse(get_ancestors($category->cat_ID, $category->taxonomy));
          foreach ($ancestors as $ancestor) {
            $bc[$i][] = array(
              'url' => get_category_link($ancestor),
              'val' => esc_attr(get_cat_name($ancestor))
            );
          }
        }
        $bc[$i][99] = array(
          'url' => get_category_link($category->cat_ID),
          'val' => esc_attr($category->name)
        );
      }
      // カスタム投稿のアーカイブ
      elseif (is_post_type_archive()) {
        $custom = get_post_type_object(get_query_var('post_type'));
        $bc[$i][99] = array(
          'url' => get_post_type_archive_link(get_query_var('post_type')),
          'val' => esc_attr($custom->labels->singular_name)
        );
      }
      // タクソノミーのアーカイブ
      elseif (is_tax()) {
        $current_obj = get_queried_object();
        $taxonomy = get_taxonomy($current_obj->taxonomy);
        if ($custom = get_post_type_object($taxonomy->object_type[0])) {
          $bc[$i][] = array(
            'url' => get_post_type_archive_link($taxonomy->object_type[0]),
            'val' => esc_attr($custom->labels->singular_name)
          );
          if ($current_obj->parent != 0) {
            $ancestors = array_reverse(get_ancestors($current_obj->term_id, $taxonomy->name, 'taxonomy'));
            foreach ($ancestors as $ancestor) {
              $term = get_term($ancestor, $taxonomy->name);
              $bc[$i][] = array(
                'url' => get_term_link($ancestor),
                'val' => esc_attr($term->name)
              );
            }
          }
        }
        $bc[$i][99] = array(
          'url' => get_term_link($current_obj->term_id),
          'val' => esc_attr($current_obj->name)
        );
      }
      // 添付ファイルページ
      elseif (is_attachment()) {
        $bc[$i][99] = array(
          'url' => get_permalink(),
          'val' => esc_attr($post->post_title ? $post->post_title : $post->post_name)
        );
      }
      // ブログの個別記事ページ
      elseif (is_single()) {
        if ($post->post_type != 'post') {
          // カスタム投稿
          $custom = get_post_type_object($post->post_type);
          $bc[$i][] = array(
            'url' => get_post_type_archive_link($post->post_type),
            'val' => esc_attr($custom->labels->singular_name)
          );
          if (count($custom->taxonomies) && $current_obj = get_the_terms($post->ID, $custom->taxonomies[0])) {
            foreach ($current_obj as $obj) {
              $_c = is_array($obj) ? count($obj) : 1;
              for ($c = 0; $c < $_c; $c++) {
                if ($i > 0) {
                  $bc[$i][0] = $bc[0][0]; // home を複製
                  $bc[$i][1] = $bc[0][1]; // post_type を複製
                }
                if ($obj->parent != 0) {
                  $ancestors = array_reverse(get_ancestors($obj->term_id, $custom->taxonomies[0], 'taxonomy'));
                  foreach ($ancestors as $ancestor) {
                    $term = get_term($ancestor, $custom->taxonomies[0]);
                    $bc[$i][] = array(
                      'url' => get_term_link($ancestor),
                      'val' => esc_attr($term->name)
                    );
                  }
                }
                $bc[$i][] = array(
                  'url' => get_term_link($obj->term_id),
                  'val' => esc_attr($obj->name)
                );
                $bc[$i][99] = array(
                  'url' => get_permalink(),
                  'val' => esc_attr($post->post_title ? $post->post_title : $post->post_name)
                );
                $i++;
              }
            }
          } else {
            $bc[$i][99] = array(
              'url' => get_permalink(),
              'val' => esc_attr($post->post_title ? $post->post_title : $post->post_name)
            );
          }
        } else {
          // 投稿
          $categories = get_the_category($post->ID);
          foreach ($categories as $category) {
            if ($i > 0) $bc[$i][0] = $bc[0][0]; // home を複製
            if ($category->parent !== 0) {
              $ancestors = array_reverse(get_ancestors($category->cat_ID, $category->taxonomy));
              foreach ($ancestors as $ancestor) {
                $bc[$i][] = array(
                  'url' => get_category_link($ancestor),
                  'val' => esc_attr(get_cat_name($ancestor))
                );
              }
            }
            $bc[$i][] = array(
              'url' => get_category_link($category->cat_ID),
              'val' => esc_attr($category->name)
            );
            $bc[$i][99] = array(
              'url' => get_permalink(),
              'val' => esc_attr($post->post_title)
            );
            $i++;
          }
        }
      }
      // 日付ベースのアーカイブページ
      elseif (is_day()) {
        $bc[$i][] = array(
          'url' => get_year_link(get_the_date('Y')),
          'val' => get_the_date($bb_theme_config['date_format'][0])
        );
        $bc[$i][] = array(
          'url' => get_month_link(get_the_date('Y'), get_the_date('n')),
          'val' => get_the_date($bb_theme_config['date_format'][1])
        );
        $bc[$i][99] = array(
          'url' => get_month_link(get_the_date('Y'), get_the_date('n'), get_the_date('j')),
          'val' => get_the_date($bb_theme_config['date_format'][2])
        );
      }
      // 月別アーカイブ
      elseif (is_month()) {
        $bc[$i][] = array(
          'url' => get_year_link(get_the_date('Y')),
          'val' => get_the_date($bb_theme_config['date_format'][0])
        );
        $bc[$i][99] = array(
          'url' => get_month_link(get_the_date('Y'), get_the_date('n')),
          'val' => get_the_date($bb_theme_config['date_format'][1])
        );
      }
      // 年別アーカイブ
      elseif (is_year()) {
        $bc[$i][99] = array(
          'url' => get_year_link(get_the_date('Y')),
          'val' => get_the_date($bb_theme_config['date_format'][0])
        );
      }
      // 検索結果表示ページ
      elseif (is_search()) {
        $bc[$i][99] = array(
          'url' => home_url('?s=' . urlencode(get_search_query())),
          'val' => '「' . esc_attr(get_search_query()) . '」の検索結果'
        );
      }
      // 投稿者のアーカイブページ
      elseif (is_author()) {
        $bc[$i][99] = array(
          'url' => get_author_posts_url($authordata->ID),
          'val' => '<span class="prefix">投稿者：</span>' . esc_attr($authordata->display_name)
        );
      }
      // タグのアーカイブページ
      elseif (is_tag()) {
        $bc[$i][99] = array(
          'url' => get_tag_link(get_query_var('tag_id')),
          'val' => '<span class="prefix">タグ：</span>' . esc_attr(single_tag_title('', false))
        );
      }
      // 404 Not Found ページ
      elseif (is_404()) {
        $bc[$i][99] = array(
          'url' => get_pagenum_link(),
          'val' => esc_attr($this->args['notfound'])
        );
      }
      // その他
      else {
        $bc[$i][99] = array(
          'url' => get_pagenum_link(),
          'val' => esc_attr(the_title_attribute(array('echo' => false)))
        );
      }
    }
    $bc = apply_filters('bb_get_bread_crumb_link', $bc);
    return $bc;
  }

  /**
   *  HTMLコードを生成
   */
  public function bb_get_bread_crumb_src() {
    global $paged, $page;
    $bc = $this->bb_get_bread_crumb_link();
    if ($paged > 1) {
      $_page = "<span class=\"page\">（{$paged}ページ）</span>";
    } elseif ($page > 1) {
      $_page = "<span class=\"page\">（{$page}ページ）</span>";
    } else {
      $_page = '';
    }
    $src = '';
    foreach ($bc as $_key => $_bc) {
      if (is_array($this->args['before']) && count($this->args['before']) === 2) {
        $_bc = array_merge(array(0 => array(
          'url' => $this->args['before'][0],
          'val' => $this->args['before'][1],
        )), $_bc);
      }
      $position = 0;
      end($_bc);
      $end_key = key($_bc);
      $src .= "<ol itemscope itemtype=\"http://schema.org/BreadcrumbList\" class=\"bread-crumb-list bread-crumb-list-{$_key}\">\n";
      foreach ($_bc as $key => $val) {
        if (!empty($val) && $val['url']) {
          $position++;
          $symbol = $this->args['symbol'];
          $current = '';
          $current_page = '';
          if ($key === $end_key) {
            $symbol = '';
            $current = ' class="current"';
            $current_page = $_page;
          }
          $src .= <<<EOD
    <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"{$current}>
      <a href="{$val['url']}" itemprop="item"><span itemprop="name">{$val['val']}</span></a>{$current_page}{$symbol}
      <meta itemprop="position" content="{$position}" />
    </li>

EOD;
        }
      }
      $src .= "</ol>\n";
      if ($this->args['multi'] === false) {
        break;
      }
    }
    $src = apply_filters('bb_get_bread_crumb_src', $src);
    return $src;
  }
}
