<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Description: 管理画面：タクソノミー（カテゴリー・タグ等）にレイアウト選択を追加
 */

if (is_admin() && current_user_can('edit_pages')) {
  add_action('load-edit-tags.php', 'call_bb_taxonomy_options');
  // 新規カテゴリーを追加時の ajax 処理
  add_action('check_ajax_referer', 'call_bb_taxonomy_options');
}

function call_bb_taxonomy_options() {
  new bbTaxonomyLayoutOptions();
}

class bbTaxonomyLayoutOptions
{
  protected $meta_key = 'bb_taxonomy_option';
  public function __construct() {
    global $current_screen;
    if (!empty($current_screen->taxonomy)) {
      $taxonomy = $current_screen->taxonomy;
      add_action($taxonomy . '_add_form_fields', array($this, 'taxonomy_layout_add_form_fields'), 11);
      add_action($taxonomy . '_edit_form_fields', array($this, 'taxonomy_layout_edit_form_fields'), 11, 2);
    }
    add_action('create_term', array($this, 'save_taxonomy_layout'), 10, 3);
    add_action('edit_term', array($this, 'save_taxonomy_layout'), 10, 3);
  }

  // 保存・更新
  public function save_taxonomy_layout($term_id, $tt_id, $taxonomy) {
    update_term_meta($term_id, $this->meta_key, $_POST[$this->meta_key]);
  }

  /**
   * 表示タイプ：フォーム
   */
  private function taxonomy_layout_meta() {
    global $bb_theme_config;
    return array(
      'layout' => array(
        'label' => '表示タイプ',
        'value' => $bb_theme_config['taxonomy_layout'],
      ),
    );
  }

  // 新規
  public function taxonomy_layout_add_form_fields($taxonomy) {
    $meta_data = $this->taxonomy_layout_meta();
    ?>
<div class="form-field bb-term-layout">
  <label><?php echo $meta_data['layout']['label']; ?></label>
  <div class="bb-term-field-body">
    <?php $this->taxonomy_layout_html($meta_data); ?>
  </div>
</div>
    <?php
  }

  // 編集
  public function taxonomy_layout_edit_form_fields($tag, $taxonomy = null) {
    if (!$meta_data = get_term_meta($tag->term_id, $this->meta_key, true)) {
      $meta_data = array();
    }
    $meta_data = array_merge($this->taxonomy_layout_meta(), $meta_data);
    ?>
<table class="form-table">
  <tr class="form-field bb-term-layout">
    <th scope="row">
      <label><?php echo $meta_data['layout']['label']; ?></label>
    </th>
    <td>
      <div class="bb-term-field-body">
        <?php $this->taxonomy_layout_html($meta_data); ?>
      </div>
    </td>
  </tr>
</table>
    <?php
  }

  // HTMLブロック
  private function taxonomy_layout_html($meta_data) {
    ?>
<ul class="bb-confirm-changes">
  <li>
    <input type="radio" name="<?php echo $this->meta_key; ?>[layout][value]" value="list" id="bb-term-layout-1"<?php echo $meta_data['layout']['value'] == 'list' ? ' checked' : ''; ?>>
    <label for="bb-term-layout-1" class="box"><span class="dashicons dashicons-list-view"></span><span class="title">リスト表示</span></label>
  </li>
  <li>
    <input type="radio" name="<?php echo $this->meta_key; ?>[layout][value]" value="tiles" id="bb-term-layout-2"<?php echo $meta_data['layout']['value'] == 'tiles' ? ' checked' : ''; ?>>
    <label for="bb-term-layout-2" class="box"><span class="dashicons dashicons-grid-view"></span><span class="title">タイル表示</span></label>
  </li>
</ul>
<input type="hidden" name="<?php echo $this->meta_key; ?>[layout][label]" value="<?php echo $meta_data['layout']['label']; ?>">
    <?php
  }
}
