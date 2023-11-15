<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Description: テーマ用設定ファンクション (config)
 */

/**
 * 直アクセスの対策
 */
function bb_theme_check() {
  return;
}


/**
 * WordPress のバージョンを確認
 */
if (version_compare($GLOBALS['wp_version'], '6.0', '<')) {
  function bb_wp_version_notice() {
    printf('<div class="error"><p>BlankBlancはWordPress 6.0未満の動作を保証しておりません。<br>現在ご利用中のバージョンは<strong>%s</strong>です。WordPressのアップグレードをご検討ください。</p></div>', $GLOBALS['wp_version']);
  }
  add_action('admin_notices', 'bb_wp_version_notice');
}


/**
 * PHP のバージョンを確認
 */
if (version_compare(phpversion(), '7.4', '<')) {
  function bb_php_version_notice() {
    printf('<div class="error"><p>BlankBlancはPHP 7.4未満の動作を保証しておりません。<br>現在ご利用中のバージョンは<strong>%s</strong>です。PHPのアップグレードをご検討ください。</p></div>', phpversion());
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
    $bb_theme_default['mobile_nav'] = array();
    $bb_theme_default['mobile_nav_footer'] = array();
    $bb_theme_default['toc_config']['toc_hidden'] = array();
    $bb_theme_default = array_replace_recursive($bb_theme_default, bb_config());
  }
  if ($load_config = get_option('blankblanc_config_values')) {
    // アップデートチェック（バージョンが異なる場合はDBを更新）
    if (!isset($load_config['theme_version']) || $load_config['theme_version'] != $bb_theme_default['theme_version']) {
      unset($load_config['theme_version']);
      $bb_theme_default['mobile_nav'] = array();
      $bb_theme_default['mobile_nav_footer'] = array();
      $bb_theme_default['toc_config']['toc_hidden'] = array();
      $bb_theme_config = array_replace_recursive($bb_theme_default, $load_config);
      update_option('blankblanc_config_values', wp_unslash($bb_theme_config));
    } else {
      $bb_theme_config = $load_config;
    }
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
require_once BB_THEME_DIR . '/core.php';
require_once BB_THEME_DIR . '/basis.php';
require_once BB_THEME_DIR . '/extensions.php';
require_once BB_ADMIN_DIR . '/bb-theme-admin.php';


/**
 * ウィジェットのブロックエディターを停止
 * ※一部不具合が発生（プラグイン等含め）するため暫定的な処置
 */
function temporary_theme_support() {
  remove_theme_support('widgets-block-editor');
}
add_action('after_setup_theme', 'temporary_theme_support');
