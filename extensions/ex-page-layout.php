<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Description: 投稿ページ／固定ページのレイアウトを設定
 */

if (is_admin() && current_user_can('edit_pages')) {
  add_action('load-post.php', 'call_bb_page_layout_select');
  add_action('load-post-new.php', 'call_bb_page_layout_select');
}

function call_bb_page_layout_select() {
  new bbPageLayoutSelectMeta();
}


class bbPageLayoutSelectMeta
{
  private $meta_key = 'bb_page_layout_select';
  public function __construct() {
    add_action('add_meta_boxes', array($this, 'add_meta_box'));
    add_action('save_post', array($this, 'save_post_meta'));
  }

  public function add_meta_box($post_type) {
    // [filter] bb_page_layout_select
    $post_types = apply_filters('bb_page_layout_select', array('post', 'page'));
    if (in_array($post_type, $post_types)) {
      add_meta_box(
        'page_layout_select',
        'ページレイアウト',
        array($this, 'page_layout_select_meta'),
        $post_type,
        'side',
        'default'
      );
    }
  }

  // 追加ボックス
  public function page_layout_select_meta() {
    global $post;
    $layouts = array(
      array(
        'id'     => 'twocolumn',
        'value' => 'default',
        'label'  => '2カラム（デフォルト）',
      ),
      array(
        'id'     => 'onecolumn',
        'value' => 'onecolumn',
        'label'  => '1カラム幅固定',
      ),
      array(
        'id'     => 'fullwidth',
        'value' => 'fullwidth',
        'label'  => '1カラム全幅',
      ),
      array(
        'id'     => 'nowrapwidth',
        'value' => 'nowrapwidth',
        'label'  => '画面全幅',
      ),
    );
    ?>
<fieldset class="bb-confirm-changes">
  <?php
  $_meta_key = get_post_meta($post->ID, $this->meta_key, true);
  foreach ($layouts as $select) :
  $checked = ((empty($_meta_key) && $select['value'] == 'default') || $_meta_key == $select['value']) ? 'checked' : '';
  ?>
    <div class="group" style="margin: 5px 0;">
      <input name="<?php echo $this->meta_key; ?>" type="radio" class="post-format" id="bb-page-layout-<?php echo $select['id']; ?>" value="<?php echo $select['value']; ?>" <?php echo $checked; ?>>
      <label for="bb-page-layout-<?php echo $select['id']; ?>" class="post-format-icon"><?php echo $select['label']; ?></label>
    </div>
  <?php endforeach; ?>
</fieldset>
    <?php
  }

  // 保存・削除
  public function save_post_meta($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
      return $post_id;
    }
    if (empty($_POST[$this->meta_key]) || $_POST[$this->meta_key] == 'default') {
      delete_post_meta($post_id, $this->meta_key, '');
    } else {
      update_post_meta($post_id, $this->meta_key, $_POST[$this->meta_key]);
    }
  }
}
