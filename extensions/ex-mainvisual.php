<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Description: 固定ページ用メインビジュアル設定の追加
 */

/**
 * 投稿
 */
if (is_admin() && current_user_can('edit_pages')) {
  add_action('load-post.php', 'call_bb_mainvisual_post_meta');
  add_action('load-post-new.php', 'call_bb_mainvisual_post_meta');
}

function call_bb_mainvisual_post_meta() {
  new bbMainvisualPostMeta();
}

class bbMainvisualPostMeta
{
  public function __construct() {
    add_action('admin_head', 'admin_extend_css_init');
    add_action('add_meta_boxes', array($this, 'add_meta_box'));
    add_action('save_post', array($this, 'save_mainvisual_post_meta'));
  }

  public function add_meta_box($post_type) {
    // [filter] bb_mainvisual_post_type
    $post_types = apply_filters('bb_mainvisual_post_type', array('post', 'page'));
    if (in_array($post_type, $post_types)) {
      add_meta_box(
        'bb_mainvisual',
        'メインビジュアル画像',
        array($this, 'mainvisual_post_meta'),
        $post_type,
        'side',
        'default'
      );
    }
  }

  // 追加ボックス
  public function mainvisual_post_meta() {
    global $post;
    wp_enqueue_script('bb-theme-admin-js', get_template_directory_uri() . '/admin/bb-theme-admin.js');
    wp_enqueue_media();
    $bb_mv_id = get_post_meta($post->ID, 'bb_mainvisual', true);
    ?>
<div id="bb-config-edit" class="bb-config-edit-box">
  <fieldset id="bb-mainvisual" class="bb-media-upload">
    <div class="media-title">メインビジュアル画像</div>
    <div class="input-group">
      <?php
      if (!empty($bb_mv_id)) {
        $tmp_img = wp_get_attachment_image_src($bb_mv_id, 'large');
        $bb_mv_img = '<img src="' . $tmp_img[0] . '" alt="">';
      } else {
        $bb_mv_id  = '';
        $bb_mv_img = '';
      }
      ?>
      <div class="image-view"><?php echo $bb_mv_img; ?></div>
      <input type="hidden" name="bb_mainvisual" class="image-id" value="<?php echo $bb_mv_id; ?>">
      <input type="button" name="select" value="画像を選択" class="button-secondary">
      <input type="button" name="reset" value="キャンセル" class="button button-secondary">
      <input type="button" name="delete" value="削除" class="button-secondary">
    </div>
    <p class="notes">ページタイトルの背景としてブロックの横幅に合わせて設定されますので、十分に幅のある画像を用意してください</p>
  </fieldset>
</div>
    <?php
  }

  // 保存・削除
  public function save_mainvisual_post_meta($post_id) {
    $_name = 'bb_mainvisual';
    $new_img = isset($_POST[$_name]) ? $_POST[$_name] : null;
    $old_img = get_post_meta($post_id, $_name, true);
    if ($old_img !== $new_img) {
      if ($new_img) {
        update_post_meta($post_id, $_name, $new_img);
      } else {
        delete_post_meta($post_id, $_name, $old_img);
      }
    }
  }
}



/**
 * カテゴリー
 */
if (is_admin() && current_user_can('edit_pages')) {
  add_action('load-edit-tags.php', 'call_bb_mainvisual_term_meta');
  // 新規カテゴリーを追加時の ajax 処理
  add_action('check_ajax_referer', 'call_bb_mainvisual_term_meta');
}

function call_bb_mainvisual_term_meta() {
  new bbMainvisualTermMeta();
}

class bbMainvisualTermMeta
{
  public function __construct() {
    $taxonomy = get_current_screen()->taxonomy;
    add_action('admin_head', 'admin_extend_css_init');
    add_action($taxonomy . '_add_form_fields', array($this, 'mainvisual_add_form_fields'));
    add_action($taxonomy . '_edit_form_fields', array($this, 'mainvisual_edit_form_fields'), 10, 2);
    add_action('create_term', array($this, 'save_mainvisual_term_meta'), 10, 3);
    add_action('edit_term', array($this, 'save_mainvisual_term_meta'), 10, 3);
  }

  // 新規
  public function mainvisual_add_form_fields($taxonomy) {
    wp_enqueue_script('bb-theme-admin-js', get_template_directory_uri() . '/admin/bb-theme-admin.js');
    wp_enqueue_media();
    $bb_mv_id  = '';
    $bb_mv_img = '';
    ?>
<div class="form-field mainvisual-term-wrap bb-config-edit-box" id="bb-config-edit">
  <label>メインビジュアル画像</label>
  <div id="bb-mainvisual">
    <div id="bb-upload-image">
      <div class="image-view"><?php echo $bb_mv_img; ?></div>
      <input type="hidden" name="bb_mainvisual" class="image-id" id="tag-mainvisual" value="<?php echo $bb_mv_id; ?>">
      <input type="button" name="select" value="画像を選択" class="button-secondary">
      <input type="button" name="delete" value="削除" class="button-secondary">
      <p>ページタイトルの背景としてブロックの横幅に合わせて設定されますので、十分に幅のある画像を用意してください。</p>
    </div>
  </div>
</div>
    <?php
  }

  // 編集
  public function mainvisual_edit_form_fields($tag, $taxonomy = null) {
    wp_enqueue_script('bb-theme-admin-js', get_template_directory_uri() . '/admin/bb-theme-admin.js');
    wp_enqueue_media();
    $bb_mv_id = get_term_meta($tag->term_id, 'bb_mainvisual', true);
    ?>
<tr id="bb-config-edit" class="form-field term-image-wrap bb-config-edit-box">
  <th scope="row">
    <label for="select-image">メインビジュアル画像</label>
  </th>
  <td>
    <div id="bb-mainvisual">
      <div id="bb-upload-image">
        <?php
        if (!empty($bb_mv_id)) {
          $tmp_img = wp_get_attachment_image_src($bb_mv_id, 'large');
          $bb_mv_img = '<img src="' . $tmp_img[0] . '" alt="">';
        } else {
          $bb_mv_id  = '';
          $bb_mv_img = '';
        }
        ?>
        <div class="image-view"><?php echo $bb_mv_img; ?></div>
        <input type="hidden" name="bb_mainvisual" class="image-id" id="tag-mailvisual" value="<?php echo $bb_mv_id; ?>">
        <input type="button" name="select" value="画像を選択" class="button-secondary" id="select-image">
        <input type="button" name="delete" value="削除" class="button-secondary">
        <p class="description">ページタイトルの背景としてブロックの横幅に合わせて設定されますので、十分に幅のある画像を用意してください。</p>
      </div>
    </div>
  </td>
</tr>
    <?php
  }

  // 保存・削除
  public function save_mainvisual_term_meta($term_id, $tt_id, $taxonomy) {
    $_name = 'bb_mainvisual';
    $new_img = isset($_POST[$_name]) ? $_POST[$_name] : null;
    $old_img = get_term_meta($term_id, $_name, true);
    if ($old_img !== $new_img) {
      if ($new_img) {
        if (!$old_img) {
          add_term_meta($term_id, $_name, $new_img);
        } else {
          update_term_meta($term_id, $_name, $new_img);
        }
      } else {
        delete_term_meta( $term_id, $_name, $old_img);
      }
    }
  }
}
