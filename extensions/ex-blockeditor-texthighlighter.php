<?php
/**
 * ブロックエディターにマーカー機能を追加
 */
function add_blockeditor_text_highlighter() {
  $build = '/extensions/blockeditor/text-highlighter/build';
  $asset = include get_template_directory() . $build . '/index.asset.php';
  wp_register_script(
    'text-highlighter',
    get_template_directory_uri() . $build . '/index.js',
    $asset['dependencies'],
    $asset['version'],
  );
  register_block_type(
    'blankblanc/text-highlighter',
    array(
      'editor_script' => 'text-highlighter',
    )
  );
}
add_action('init', 'add_blockeditor_text_highlighter');
