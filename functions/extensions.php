<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Description: テーマ用設定ファンクション (config)
 */

/**
 * 追加機能の読み込み
 */
function bb_add_extensions() {
  if (is_admin()) {
    locate_template('extensions/ex-tinymce.php', true);
    locate_template('extensions/ex-inline-css-js.php', true);
    locate_template('extensions/ex-column-layout.php', true);
    locate_template('extensions/ex-blockeditor-texthighlighter.php', true);
  }
  locate_template('extensions/ex-mainvisual.php', true);
  locate_template('extensions/ex-exclude-categories.php', true);
  locate_template('extensions/ex-breadcrumb.php', true);
  locate_template('extensions/ex-table-of-contents.php', true);
  locate_template('extensions/ex-table-of-contents-meta.php', true);
}
add_action('after_setup_theme', 'bb_add_extensions');
