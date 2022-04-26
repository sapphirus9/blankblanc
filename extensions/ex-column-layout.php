<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Description: カラムレイアウトを設定
 */

function call_bb_page_layout_select() {
  new bbPageLayoutSelectMeta();
}
if (is_admin() && current_user_can('edit_pages')) {
  add_action('load-post.php', 'call_bb_page_layout_select');
  add_action('load-post-new.php', 'call_bb_page_layout_select');
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
        'カラムレイアウト',
        array($this, 'page_layout_select_meta'),
        $post_type,
        'side',
        'default'
      );
    }
  }

  public function column_layout() {
    return array(
      'default' => array(
        'value' => 'default',
        'label' => '共通設定',
      ),
      'twocolumn' => array(
        'value' => 'twocolumn',
        'label' => '2カラム',
      ),
      'onecolumn' => array(
        'value' => 'onecolumn',
        'label' => '1カラム幅固定',
      ),
      'fullwidth' => array(
        'value' => 'fullwidth',
        'label' => '1カラム全幅',
      ),
      'nowrapwidth' => array(
        'value' => 'nowrapwidth',
        'label' => '画面全幅',
      ),
    );
  }

  // 追加ボックス
  public function page_layout_select_meta() {
    global $post;
    ?>
<fieldset class="bb-confirm-changes">
  <?php
  global $bb_theme_config;
  $meta_data = get_post_meta($post->ID, $this->meta_key, true);
  if (empty($meta_data)) {
    $meta_data = 'default';
  }
  $layouts = $this->column_layout();
  foreach ($layouts as $select) :
    $checked = $select['value'] == $meta_data ? ' checked' : '';
    $default = $select['value'] == 'default' ? "（{$layouts[$bb_theme_config['column_layout']]['label']}）" : '';
  ?>
    <div class="group">
      <input name="<?php echo $this->meta_key; ?>" type="radio" class="post-format" id="bb-page-layout-<?php echo $select['value']; ?>" value="<?php echo $select['value']; ?>"<?php echo $checked; ?>>
      <label for="bb-page-layout-<?php echo $select['value']; ?>" class="post-format-icon"><?php echo $select['label']. $default; ?></label>
    </div>
  <?php endforeach; ?>
  <p class="note">画面全幅ではコンテンツカラムのwidthはauto、左右のpaddingは0になります</p>
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


/**
 * カテゴリー
 */
function call_bb_term_layout_select() {
  new bbTermLayoutSelectMeta();
}
if (is_admin() && current_user_can('edit_pages')) {
  add_action('load-edit-tags.php', 'call_bb_term_layout_select');
  // 新規カテゴリーを追加時の ajax 処理
  add_action('check_ajax_referer', 'call_bb_term_layout_select');
}

class bbTermLayoutSelectMeta
{
  private $meta_key = 'bb_term_layout_select';
  private $meta_info = array(
    'title' => 'カラムレイアウト',
    'note'  => '画面全幅ではコンテンツカラムのwidthはauto、左右のpaddingは0になります。',
  );
  public function __construct() {
    global $current_screen;
    if (!empty($current_screen->taxonomy)) {
      $taxonomy = $current_screen->taxonomy;
      add_action($taxonomy . '_add_form_fields', array($this, 'term_layout_add_form_fields'), 13);
      add_action($taxonomy . '_edit_form_fields', array($this, 'term_layout_edit_form_fields'), 13, 2);
    }
    add_action('create_term', array($this, 'save_term_layout_meta'), 10, 3);
    add_action('edit_term', array($this, 'save_term_layout_meta'), 10, 3);
  }

  // 保存・更新
  public function save_term_layout_meta($term_id, $tt_id, $taxonomy) {
    update_term_meta($term_id, $this->meta_key, $_POST[$this->meta_key]);
  }

  // 新規
  public function term_layout_add_form_fields() {
    ?>
<div class="form-field term-layout-wrap bb-term-field-add" id="bb-term-column">
  <label><?php echo $this->meta_info['title']; ?></label>
  <div class="bb-term-field-body">
    <?php $this->term_layout_html(); ?>
  </div>
</div>
    <?php
  }

  // 編集
  public function term_layout_edit_form_fields($tag, $taxonomy = null) {
    $meta_data = get_term_meta($tag->term_id, $this->meta_key, true);
    ?>
<div class="form-field term-layout-wrap bb-term-field-edit" id="bb-term-column">
  <table class="form-table">
    <tr class="form-field">
      <th scope="row">
        <label><?php echo $this->meta_info['title']; ?></label>
      </th>
      <td>
        <div class="bb-term-field-body">
          <?php $this->term_layout_html($meta_data); ?>
        </div>
      </td>
    </tr>
  </table>
</div>
    <?php
  }

  // HTMLブロック
  private function term_layout_html($meta_data = '') {
    global $bb_theme_config;
    if (empty($meta_data)) {
      $meta_data = 'default';
    }
    $_layouts = new bbPageLayoutSelectMeta();
    $layouts = $_layouts->column_layout();
    foreach ($layouts as $select) :
      $checked = $select['value'] == $meta_data ? ' checked' : '';
      $default = $select['value'] == 'default' ? "（{$layouts[$bb_theme_config['column_layout']]['label']}）" : '';
      $default = $select['value'] == 'default' ? "（{$layouts[$bb_theme_config['column_layout']]['label']}）" : '';
    ?>
  <div class="group">
    <input name="<?php echo $this->meta_key; ?>" type="radio" class="post-format" id="bb-term-layout-<?php echo $select['value']; ?>" value="<?php echo $select['value']; ?>"<?php echo $checked; ?>>
    <label for="bb-term-layout-<?php echo $select['value']; ?>" class="post-format-icon"><?php echo $select['label'] . $default; ?></label>
  </div>
    <?php endforeach; ?>
  <p class="note"><?php echo $this->meta_info['note']; ?></p>
  <?php
  }
}
