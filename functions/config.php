<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Description: テーマ用設定ファンクション (config)
 */

/**
 * WordPress のバージョンを確認
 */
if (version_compare($GLOBALS['wp_version'], '5.5', '<')) {
  function bb_wp_version_notice() {
    printf('<div class="error"><p>BlankBlancはWordPress 5.5未満の動作を保証しておりません。<br>現在ご利用中のバージョンは<strong>%s</strong>です。WordPressのアップグレードをご検討ください。</p></div>', $GLOBALS['wp_version']);
  }
  add_action('admin_notices', 'bb_wp_version_notice');
}


/**
 * PHP のバージョンを確認
 */
if (version_compare(phpversion(), '5.6.20', '<')) {
  function bb_php_version_notice() {
    printf('<div class="error"><p>BlankBlancはPHP 5.6.20未満の動作を保証しておりません。<br>現在ご利用中のバージョンは<strong>%s</strong>です。PHPのアップグレードをご検討ください。</p></div>', phpversion());
  }
  add_action('admin_notices', 'bb_php_version_notice');
}


/**
 * テーマオプションを設定
 */
define('BB_THEME_DIR', __DIR__);
define('BB_ADMIN_DIR', dirname(__DIR__) . '/admin');

function bb_setup_theme_config() {
  global $bb_theme_config, $bb_theme_default;
  $bb_theme_default = bb_config_default();
  if (function_exists('bb_config')) {
    // 子テーマの設定を登録
    $bb_theme_default = array_merge($bb_theme_default, bb_config());
  }
  if ($load_config = get_option('blankblanc_config_values')) {
    // アップデートチェック（バージョンが異なる場合はDBを更新）
    if (!isset($load_config['theme_version']) || $load_config['theme_version'] != $bb_theme_default['theme_version']) {
      $bb_theme_config = array_merge($bb_theme_default, $load_config);
      update_option('blankblanc_config_values', wp_unslash($bb_theme_config));
      return;
    }
    $bb_theme_config = $load_config;
  } else {
    // DBにテーマオプションがない場合は登録
    $bb_theme_config = $bb_theme_default;
    update_option('blankblanc_config_values', wp_unslash($bb_theme_default));
  }
  define('VERSION_PARAM', $bb_theme_config['version_param']);
}
add_action('after_setup_theme', 'bb_setup_theme_config');


/**
 * ファンクションの読み込み
 */
if (is_admin()) {
  require_once BB_ADMIN_DIR . '/bb-theme-admin.php';
}
require_once BB_THEME_DIR . '/core.php';
require_once BB_THEME_DIR . '/basis.php';
require_once BB_THEME_DIR . '/extensions.php';


/**
 * ウィジェットのブロックエディターを停止
 * ※プラグイン等で一部不具合がでるため暫定的な処置
 */
function temporary_theme_support() {
  remove_theme_support('widgets-block-editor');
}
add_action('after_setup_theme', 'temporary_theme_support');
