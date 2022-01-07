<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Description: 目次を追加
 */

function call_bb_table_of_contents() {
  global $bb_theme_config;
  if ($bb_theme_config['use_toc']) {
    if ($bb_theme_config['toc_config']['toc_active'] !== false) {
      new bbTableOfContents();
    }
  }
}
add_action('wp', 'call_bb_table_of_contents');

class bbTableOfContents
{
  private $table_of_contents;
  private $args;
  private $shortcode = 'bb_table_of_contents';

  public function __construct() {
    global $bb_theme_config, $post;
    /**
     * @param array $args
     *        toc_active 目次挿入 bool
     *        toc_closed 目次を閉じた状態にする bool
     *        toc_title 目次タイトル string
     *        toc_hidden 除外する見出し array (h1~h6)
     *        toc_prefix アンカーIDに付加する文字列 string
     *        toc_position 挿入場所 int (ボディ最上部:0 ボディ最下部:-1 x番目の見出し前:1~)
     */
    if (!$meta_key = get_post_meta($post->ID, 'bb_table_of_contents', true)) {
      $meta_key = array();
    }
    $this->args = array_merge($bb_theme_config['toc_config'], $meta_key);
    if ($this->args['toc_active'] !== false) {
      add_filter('the_content', array($this, 'generate_table_of_contents'), 10);
      add_shortcode($this->shortcode, array($this, 'bb_table_of_contents'));
    }
  }

  public function generate_table_of_contents($content) {
    // moreで分割がある場合、前半部分の目次を無効化
    global $more;
    if ($more === 0) {
      return $content;
    }
    $cnt = 0;
    $idx = 0;
    $toc = '';
    $content = preg_replace_callback('/<(h[1-6])(.*?)>(.*?)(<\/h[1-6]>)/s', function ($matches) use (&$cnt, &$idx, &$toc) {
      if (empty($this->args['toc_hidden']) || array_search(strtolower($matches[1]), $this->args['toc_hidden']) === false) {
        $cnt++;
        if (preg_match('/.*\sid=[\'|"](.*?)[\'|"].*/', $matches[2], $_id)) {
          $id = $_id[1];
          $hsrc = $matches[0];
        } else {
          $idx++;
          $id = $this->args['toc_prefix'] . $idx;
          $hsrc = "<{$matches[1]} id=\"{$id}\"{$matches[2]}>{$matches[3]}{$matches[4]}";
        }
        $pat = array(
          '/<a.*?>(.*?)<\/a>/',
          '/<img.*?>/',
        );
        $rep = array(
          '$1',
          '',
        );
        $matches_3 = preg_replace($pat, $rep, $matches[3]);
        $toc .= "\n    <li class=\"bb-toc-{$matches[1]}\"><a href=\"#{$id}\">{$matches_3}</a></li>";
      } else {
        $cnt++;
        $hsrc = $matches[0];
      }
      if ($cnt === $this->args['toc_position']) {
        $hsrc = '[' . $this->shortcode . ']' . $hsrc;
      }
      return $hsrc;
    }, $content);
    if ($toc) {
      $title = esc_html($this->args['toc_title']);
      $changed = $this->args['toc_closed'] ? ' changed' : '';
      $this->table_of_contents = <<< EOD
  <div class="bb-toc-block{$changed}">
    <div class="bb-toc-header">
      <div class="bb-toc-header-inner">
        <p class="bb-toc-title">{$title}</p>
        <div class="bb-toc-toggle"><span class="btn-symbol"></span></div>
      </div>
    </div>
    <div class="bb-toc-body">
      <ul class="bb-toc-body-inner">{$toc}
      </ul>
    </div>
  </div>

  EOD;
    $this->table_of_contents = apply_filters('bb_table_of_contents_src', $this->table_of_contents);
    }
    if ($this->args['toc_position'] === 0) {
      return '[' . $this->shortcode . ']' . $content;
    } elseif ($this->args['toc_position'] === -1) {
      return $content . '[' . $this->shortcode . ']';
    } else {
      return $content;
    }
  }

  // shortcode
  public function bb_table_of_contents() {
    return $this->table_of_contents;
  }
}
