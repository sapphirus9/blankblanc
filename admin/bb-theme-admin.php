<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Description: テーマ設定
 */

/**
 * 管理画面にテーマ設定用CSSを追加
 */
function admin_extend_css_init() {
  $css = get_template_directory_uri() . '/admin/assets/css/bb-theme-admin.css';
  echo "<link rel='stylesheet' href='{$css}' type='text/css' media='all'>\n";
}
add_action('admin_head', 'admin_extend_css_init');

/**
 * テーマオプション管理画面
 */
$bb_theme_option = BB_ADMIN_DIR . '/bb-theme-option.php';
if (file_exists($bb_theme_option)) {
  require_once $bb_theme_option;
}

/**
 * タクソノミー（カテゴリー・タグ等）に項目を追加
 */
$bb_theme_taxonomy_option = BB_ADMIN_DIR . '/bb-theme-taxonomy-option.php';
if (file_exists($bb_theme_taxonomy_option)) {
  require_once $bb_theme_taxonomy_option;
}

/**
 * テーマカスタマイザ
 */
$bb_theme_customizer = BB_ADMIN_DIR . '/bb-theme-customizer.php';
if (file_exists($bb_theme_customizer)) {
  require_once $bb_theme_customizer;
}
