<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Description: 投稿ページにインライン JavaScript とスタイルシートを追加する
 */

if (is_admin() && current_user_can('edit_pages')) {
  add_action('load-post.php', 'call_bb_inline_css_js_post_meta');
  add_action('load-post-new.php', 'call_bb_inline_css_js_post_meta');
}

function call_bb_inline_css_js_post_meta() {
  new bbInlineCssJsPostMeta();
}

class bbInlineCssJsPostMeta
{
  public function __construct() {
    add_action('add_meta_boxes', array($this, 'add_meta_box'));
    add_action('save_post', array($this, 'save_post_meta'));
  }

  public function add_meta_box($post_type) {
    // [filter] bb_inline_css_js_post_type
    $post_types = apply_filters('bb_inline_css_js_post_type', array('post', 'page'));
    if (in_array($post_type, $post_types)) {
      // [css]
      add_meta_box(
        'bb_inline_css',
        'このページのスタイルシート',
        array($this, 'inline_css_post_meta'),
        $post_type,
        'advanced',
        'low'
      );
      // [js]
      add_meta_box(
        'bb_inline_js',
        'このページの JavaScript',
        array($this, 'inline_js_post_meta'),
        $post_type,
        'advanced',
        'low'
      );
    }
  }

  // 追加ボックス [css]
  public function inline_css_post_meta() {
    global $post;
    ?>
<div class="inline-textarea">
  <p>head タグ内 css ファイルの後に挿入されます</p>
  <textarea name="bb_inline_css_head" id="bb_inline_css_head" rows="7" cols="100"><?php echo get_post_meta($post->ID, 'bb_inline_css_head', true); ?></textarea>
</div>
    <?php
  }

  // 追加ボックス [js]
  public function inline_js_post_meta() {
    global $post;
    ?>
<div class="inline-textarea">
  <p>head タグ内 JavaScript ファイルの後に挿入されます</p>
  <textarea name="bb_inline_js_head" id="bb_inline_js_head" rows="7" cols="100"><?php echo get_post_meta($post->ID, 'bb_inline_js_head', true); ?></textarea>
  <p>body タグが閉じられる直前に挿入されます</p>
  <textarea name="bb_inline_js_body" id="bb_inline_js_body" rows="7" cols="100"><?php echo get_post_meta($post->ID, 'bb_inline_js_body', true); ?></textarea>
</div>
    <?php
  }

  // 保存・削除
  public function save_post_meta($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
      return $post_id;
    }
    $_names = array(
      'bb_inline_css_head',
      'bb_inline_js_head',
      'bb_inline_js_body',
    );
    foreach ($_names as $_name) {
      if (!empty($_POST[$_name])) {
        update_post_meta($post_id, $_name, $_POST[$_name]);
      } else {
        delete_post_meta($post_id, $_name, '');
      }
    }
  }
}
