<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Description: テーマ用設定ファンクション (config)
 */

/**
 * WordPress のバージョンを確認
 */
if (version_compare($GLOBALS['wp_version'], '5.7', '<')) {
  function bb_wp_version_notice() {
    printf('<div class="error"><p>BlankBlanc は WordPress 5.7 未満の動作を保証しておりません。<br>現在ご利用中のバージョンは <strong>%s</strong> です。WordPress のアップグレードをご検討ください。</p></div>', $GLOBALS['wp_version']);
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
  } else {
    // DBにテーマオプションがない場合は登録
    update_option('blankblanc_config_values', wp_unslash($bb_theme_config));
  }
  if (is_admin()) {
    require_once dirname(__DIR__) . '/admin/bb-theme-option.php';
    require_once dirname(__DIR__) . '/admin/bb-theme-taxonomy-option.php';
  }
  define('VERSION_PARAM', $bb_theme_config['version_param']);
}
add_action('after_setup_theme', 'bb_setup_theme_config');


/**
 * ファンクションの読み込み
 */
require_once __DIR__ . '/core.php';
require_once __DIR__ . '/basis.php';
require_once __DIR__ . '/extensions.php';


/**
 * ウィジェットのブロックエディターを停止
 * ※プラグイン等で一部不具合がでるため暫定的な処置
 */
function temporary_theme_support() {
  remove_theme_support('widgets-block-editor');
}
add_action('after_setup_theme', 'temporary_theme_support');
