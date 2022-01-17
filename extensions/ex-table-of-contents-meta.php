<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Description: 投稿ページ／固定ページの目次設定
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
    $meta_key = array_merge($bb_theme_config['toc_config'], $meta_key);
    ?>
<fieldset class="bb-confirm-changes">
  <div class="bb-toc-block-header activate-block-header">
    <input type="hidden" name="<?php echo $this->meta_key; ?>[toc_individual]" value="false">
    <input name="<?php echo $this->meta_key; ?>[toc_individual]" type="checkbox" class="post-format" id="bb-toc-activate" value="true"<?php if ($meta_key['toc_individual'] === true) echo ' checked'; ?>>
    <label for="bb-toc-activate">個別に目次を設定</label>
  </div>
  <div class="bb-toc-block-body activate-block-body<?php if ($meta_key['toc_individual'] === true) echo ' active'; ?>">
    <div class="group">
      <input type="hidden" name="<?php echo $this->meta_key; ?>[toc_active]" value="false">
      <input name="<?php echo $this->meta_key; ?>[toc_active]" type="checkbox" class="post-format" id="bb-toc-active" value="true"<?php if ($meta_key['toc_active'] === true) echo ' checked'; ?>>
      <label for="bb-toc-active">目次を表示</label>
    </div>
    <div class="group">
      <input type="hidden" name="<?php echo $this->meta_key; ?>[toc_closed]" value="false">
      <input name="<?php echo $this->meta_key; ?>[toc_closed]" type="checkbox" class="post-format" id="bb-toc-closed" value="true"<?php if ($meta_key['toc_closed'] === true) echo ' checked'; ?>>
      <label for="bb-toc-closed">目次を閉じた状態にする</label>
    </div>
    <div class="group">
      <label for="bb-toc-title" class="label-left">タイトル</label>
      <input name="<?php echo $this->meta_key; ?>[toc_title]" type="text" class="post-format" id="bb-toc-title" value="<?php echo $meta_key['toc_title']; ?>">
    </div>
    <div class="group">
      <div class="label-block">除外する見出し</div>
      <div class="col">
        <input type="hidden" name="<?php echo $this->meta_key; ?>[toc_hidden]" value="false">
        <?php $headings = array('h1', 'h2', 'h3', 'h4', 'h5', 'h6');
          foreach ($headings as $heading) :
            $toc_hidden = $meta_key['toc_hidden']; ?>
          <div class="col-item">
            <input name="<?php echo $this->meta_key; ?>[toc_hidden][]" type="checkbox" class="post-format" id="bb-toc-hidden-<?php echo $heading; ?>" value="<?php echo $heading; ?>"<?php if (is_array($toc_hidden) && array_search($heading, $toc_hidden) !== false) echo ' checked'; ?>>
            <label for="bb-toc-hidden-<?php echo $heading; ?>"><?php echo $heading; ?></label>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="group">
      <label for="bb-toc-position" class="label-left">挿入場所</label>
      <input name="<?php echo $this->meta_key; ?>[toc_position]" type="number" class="post-format" id="bb-toc-position" value="<?php echo $meta_key['toc_position']; ?>">
    </div>
    <p class="note">ボディ最上部:0／ボディ最下部:-1／x番目の見出し前:1~</p>
  </div>
</fieldset>
    <?php
  }

  // 保存・削除
  public function save_post_meta($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
      return $post_id;
    }
    $meta_key = !empty($_POST[$this->meta_key]) ? bb_string_type_filter($_POST[$this->meta_key]) : null;
    if (empty($meta_key) || $meta_key['toc_individual'] === false) {
      delete_post_meta($post_id, $this->meta_key, '');
    } else {
      update_post_meta($post_id, $this->meta_key, $meta_key);
    }
  }
}
