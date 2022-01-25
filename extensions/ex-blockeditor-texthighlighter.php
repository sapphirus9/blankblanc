<?php
/**
 * ブロックエディターにマーカー機能を追加
 */
function ex_texthighliter_init() {
  wp_enqueue_style('ex-texthighliter-css', get_template_directory_uri() . '/admin/assets/css/editor-style.css');
  if (is_admin()) {
    wp_enqueue_script(
      'ex-texthighliter-js',
      get_template_directory_uri() . '/admin/assets/js/ex-blockeditor-texthighliter.js',
      array('lodash', 'wp-rich-text','wp-element','wp-components', 'wp-blocks', 'wp-editor', 'wp-keycodes'),
      null,
      true
    );
  }
}
add_action('enqueue_block_editor_assets', 'ex_texthighliter_init');
