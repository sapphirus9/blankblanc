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
    $content = apply_filters('bb_table_of_contents_src', $content);
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


/**
 * 個別の目次設定
 */
function call_bb_table_of_contents_meta() {
  new bbTableOfCOntentsMeta();
}
if (is_admin() && current_user_can('edit_pages')) {
  add_action('load-post.php', 'call_bb_table_of_contents_meta');
  add_action('load-post-new.php', 'call_bb_table_of_contents_meta');
}

class bbTableOfCOntentsMeta
{
  private $meta_key = 'bb_table_of_contents';
  public function __construct() {
    add_action('add_meta_boxes', array($this, 'add_meta_box'));
    add_action('save_post', array($this, 'save_post_meta'));
  }

  public function add_meta_box($post_type) {
    // [filter] bb_table_of_contents
    $post_types = apply_filters('bb_table_of_contents', array('post', 'page'));
    if (in_array($post_type, $post_types)) {
      add_meta_box(
        $this->meta_key,
        '目次設定',
        array($this, 'table_of_contents_meta'),
        $post_type,
        'side',
        'default'
      );
    }
  }

  // 追加ボックス
  public function table_of_contents_meta() {
    global $post;
    global $bb_theme_config;
    if (!$meta_key = get_post_meta($post->ID, $this->meta_key, true)) {
      $meta_key = array('toc_individual' => false);
    }
    $meta_key = filter_var($meta_key, FILTER_CALLBACK, array(
      'options' => function ($val) {
        if (is_numeric($val)) return is_float($val) ? (float) $val : (int) $val;
        if ($val === 'true') return true;
        if ($val === 'false') return false;
        return $val;
    }));
    $meta_key = array_merge($bb_theme_config['toc_config'], $meta_key);
    ?>
<fieldset class="bb-confirm-changes">
  <div class="group" style="margin: 5px 0 2px;">
    <input type="hidden" name="<?php echo $this->meta_key; ?>[toc_individual]" value="false">
    <input name="<?php echo $this->meta_key; ?>[toc_individual]" type="checkbox" class="post-format" id="bb-toc-active" value="true"<?php if ($meta_key['toc_individual'] == 'true') echo ' checked'; ?>>
    <label for="bb-toc-active" style="font-weight: bold;">個別に目次を設定</label>
  </div>
  <div class="individual-active" style="margin-top: 12px; border-top: solid 1px #dcdcde;">
    <div class="group" style="margin: 10px 0 15px;">
      <input type="hidden" name="<?php echo $this->meta_key; ?>[toc_active]" value="false">
      <input name="<?php echo $this->meta_key; ?>[toc_active]" type="checkbox" class="post-format" id="bb-toc-active" value="true"<?php if ($meta_key['toc_active'] == 'true') echo ' checked'; ?>>
      <label for="bb-toc-active">目次を表示</label>
    </div>
    <div class="group" style="margin: 15px 0;">
      <label for="bb-toc-title" style="margin-right: .4em;">タイトル</label>
      <input name="<?php echo $this->meta_key; ?>[toc_title]" type="text" class="post-format" id="bb-toc-title" value="<?php echo $meta_key['toc_title']; ?>">
    </div>
    <div class="group" style="margin: 15px 0;">
      <div style="display: inline-block; margin-bottom: 2px;">除外する見出し</div>
      <div class="group">
        <input type="hidden" name="<?php echo $this->meta_key; ?>[toc_hidden]" value="false">
        <?php $headings = array('h1', 'h2', 'h3', 'h4', 'h5', 'h6');
          foreach ($headings as $heading) :
            $toc_hidden = $meta_key['toc_hidden']; ?>
          <div style="display: inline-block; vertical-align: top; margin-right: .25em;">
            <input name="<?php echo $this->meta_key; ?>[toc_hidden][]" type="checkbox" class="post-format" id="bb-toc-hidden-<?php echo $heading; ?>" value="<?php echo $heading; ?>"<?php if (is_array($toc_hidden) && array_search($heading, $toc_hidden) !== false) echo ' checked'; ?> style="margin-right: .05em;">
            <label for="bb-toc-hidden-<?php echo $heading; ?>"><?php echo $heading; ?></label>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="group" style="margin: 15px 0 0;">
      <label for="bb-toc-position" style="margin-right: .4em;">挿入場所</label>
      <input name="<?php echo $this->meta_key; ?>[toc_position]" type="number" class="post-format" id="bb-toc-position" value="<?php echo $meta_key['toc_position']; ?>" style="width: 25%; padding-right: 0;">
    </div>
    <p style="margin: 5px 0 0;">ボディ最上部:0／ボディ最下部:-1／x番目の見出し前:1~</p>
  </div>
</fieldset>
    <?php
  }

  // 保存・削除
  public function save_post_meta($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
      return $post_id;
    }
    if (empty($_POST[$this->meta_key]) || $_POST[$this->meta_key]['toc_individual'] == 'false') {
      delete_post_meta($post_id, $this->meta_key, '');
    } else {
      update_post_meta($post_id, $this->meta_key, $_POST[$this->meta_key]);
    }
  }
}
