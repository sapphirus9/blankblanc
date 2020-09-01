<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Description: テーマ用設定ファンクション (config)
 */

/**
 * WordPress のバージョンを確認
 */
if (version_compare($GLOBALS['wp_version'], '4.7', '<')) {
  function bb_wp_version_notice() {
    printf('<div class="error"><p>BlankBlanc は WordPress 4.7 未満の動作を保証しておりません。<br>現在ご利用中のバージョンは <strong>%s</strong> です。WordPress のアップグレードをご検討ください。</p></div>', $GLOBALS['wp_version']);
  }
  add_action('admin_notices', 'bb_wp_version_notice');
}



/**
 * PHP のバージョンを確認
 */
if (version_compare(phpversion(), '5.6.20', '<')) {
  function bb_php_version_notice() {
    printf('<div class="error"><p>BlankBlanc は PHP 5.6.20 未満の動作を保証しておりません。<br>現在ご利用中のバージョンは <strong>%s</strong> です。PHP のアップグレードをご検討ください。</p></div>', phpversion());
  }
  add_action('admin_notices', 'bb_php_version_notice');
}



/**
 * テーマオプションを設定
 */
function bb_setup_theme_config() {
  global $bb_theme_config, $bb_theme_default;
  $bb_theme_config = $bb_theme_default = bb_config_default();
  if (function_exists('bb_config')) {
    $bb_theme_config = $bb_theme_default = array_merge($bb_theme_default, bb_config());
  }
  if ($load_config = get_option('blankblanc_config_values')) {
    $bb_theme_config = $load_config + $bb_theme_default;
  }
  if (is_admin()) {
    require_once dirname(__DIR__) . '/admin/bb-theme-option.php';
    require_once dirname(__DIR__) . '/admin/bb-theme-taxonomy-option.php';
  }
}
add_action('after_setup_theme', 'bb_setup_theme_config');



/**
 * ファンクションの読み込み
 */
require_once __DIR__ . '/core.php';
require_once __DIR__ . '/basis.php';
require_once __DIR__ . '/extensions.php';
