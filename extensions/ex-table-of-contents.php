<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Description: 目次を追加
 */

function call_bb_table_of_contents() {
  global $bb_theme_config;
  if ($bb_theme_config['use_toc']) {
    if (is_singular() && $bb_theme_config['toc_config']['toc_active'] !== false) {
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
    /**
     * @param array $args
     *        toc_active 目次挿入 bool
     *        toc_title 目次タイトル string
     *        toc_hidden 除外する見出し array (h1~h6)
     *        toc_prefix アンカーIDに付加する文字列 string
     *        toc_position 挿入場所 int (ボディ最上部:0 ボディ最下部:-1 x番目の見出し前:1~)
     */
    $this->args = array_merge(array(
      'toc_active'   => true,
      'toc_title'    => 'Contents',
      'toc_hidden'   => array(),
      'toc_prefix'   => 'Index-',
      'toc_position' => 1,
    ), $GLOBALS['bb_theme_config']['toc_config']);
    add_filter('the_content', array($this, 'generate_table_of_contents'), 10);
    add_shortcode($this->shortcode, array($this, 'bb_table_of_contents'));
  }

  public function generate_table_of_contents($content) {
    $idx = 0;
    $toc = '';
    $content = preg_replace_callback('/<(h[1-6])(.*?)>(.*?)(<\/h[1-6]>)/s', function ($matches) use (&$idx, &$toc) {
      if (empty($this->args['toc_hidden']) || array_search(strtolower($matches[1]), $this->args['toc_hidden']) === false) {
        $idx++;
        if (preg_match('/.*\sid=[\'|"](.*?)[\'|"].*/', $matches[2], $_id)) {
          $id = $_id[1];
          $hsrc = $matches[0];
        } else {
          $id = $this->args['toc_prefix'] . $idx;
          $hsrc = "<{$matches[1]} id=\"{$id}\"$matches[2]>$matches[3]$matches[4]";
        }
        $toc .= "\n    <li class=\"bb-toc-{$matches[1]}\"><a href=\"#{$id}\">{$matches[3]}</a></li>";
      } else {
        $idx++;
        $hsrc = $matches[0];
      }
      if ($idx === $this->args['toc_position']) {
        $hsrc = '[' . $this->shortcode . ']' . $hsrc;
      }
      return $hsrc;
    }, $content);
    if ($toc) {
      $title = esc_html($this->args['toc_title']);
      $this->table_of_contents = <<< EOD
  <div class="bb-toc-block">
    <p class="bb-toc-title">{$title}<span class="bb-toc-toggle"></span></p>
    <ul class="bb-toc-list">{$toc}
    </ul>
  </div>

  EOD;
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
