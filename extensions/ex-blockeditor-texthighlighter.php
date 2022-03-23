<?php
/**
 * ブロックエディターにマーカー機能を追加
 */
function ex_texthighliter_init() {
  wp_register_style(
    'ex-texthighliter',
    get_template_directory_uri() . '/admin/assets/css/editor-style.css'
  );
  wp_register_script(
    'ex-texthighliter',
    get_template_directory_uri() . '/admin/assets/js/ex-blockeditor-texthighliter.js',
    array('wp-rich-text', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-keycodes')
  );
  register_block_type(
    'blankblanc/block',
    array(
      'editor_script' => 'ex-texthighliter',
      'editor_style'  => 'ex-texthighliter',
    )
  );
}
add_action('enqueue_block_editor_assets', 'ex_texthighliter_init');
