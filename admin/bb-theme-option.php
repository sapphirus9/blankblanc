<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Description: テーマオプション管理画面
 */

/**
 * 外観メニューにテーマの基本設定項目を追加
 */
function call_blankblanc_config() {
  new blankblancConfig();
}
add_action('init', 'call_blankblanc_config');

class blankblancConfig
{
  public $config_values;
  public $bb_theme_default;

  public function __construct() {
    global $bb_theme_default;
    $this->bb_theme_default = $bb_theme_default;
    add_action('admin_menu', array($this, 'add_blankblanc_config_edit'));
  }

  public function blankblanc_config_edit() {
    $bb_theme_default = $this->bb_theme_default;
    //postデータを取得 & numeric/bool変換
    $error = null;
    if (isset($_POST['blankblanc_config_values'])) {
      $config_post = bb_string_type_filter($_POST['blankblanc_config_values']);
      // 年をstringに変更
      if (isset($config_post['copyright']['start_year'])) {
        $config_post['copyright']['start_year'] = (string) $config_post['copyright']['start_year'];
      }
      check_admin_referer('blankblanc_config_nonce');
      if (isset($config_post['reset_config'])) { // 初期状態に戻す
        $config_values = $bb_theme_default;
        update_option('blankblanc_config_values', wp_unslash($config_values));
      } else { //postデータをセット
        unset($config_post['reset_config']);
        if (!$config_post['archive_thumbnail'][0]) {
          $error = '【一覧ページのサムネイル画像】の『幅』が指定されていません';
        }
        if (!$config_post['archive_thumbnail'][1]) {
          $error = '【一覧ページのサムネイル画像】の『高さ』が指定されていません';
        }
        if (!$config_post['excerpt_length']) {
          $error = '【記事抜粋の文字数】が指定されていません';
        }
        if ($error) {
          if (!$config_values = get_option('blankblanc_config_values')) {
            $config_values = $bb_theme_default;
          }
        } else {
          $config_values = array_merge($bb_theme_default, $config_post);
          // 設定が空の場合の処理
          $check_empty_config = array(
            'mobile_nav'
          );
          foreach ($check_empty_config as $value) {
            if (empty($config_post[$value])) {
              $config_values['mobile_nav'] = null;
            }
          }
          $config_values['exclude_cat_id'] = str_replace(' ', '', $config_values['exclude_cat_id']);
          update_option('blankblanc_config_values', wp_unslash($config_values));
        }
      }
    } else { // 初期値値をセット
      if (!$config_values = get_option('blankblanc_config_values')) {
        $config_values = $bb_theme_default;
        update_option('blankblanc_config_values', wp_unslash($config_values));
      }
    }
    wp_enqueue_script('bb-theme-admin-js', get_template_directory_uri() . '/admin/assets/js/bb-theme-admin.js', array('jquery-ui-sortable', 'jquery-touch-punch', 'jquery-ui-tabs'));
    wp_enqueue_media();

    // echo
    function _echo($val, $prefix = '', $suffix = '') {
      if (is_bool($val)) {
        $val = var_export($val, true);
      } elseif (empty($val)) {
        $val = '指定なし';
      } else {
        $val = $prefix . ' ' . esc_html(str_replace(' ', '&nbsp;', $val)) . ' ' . $suffix;
      }
      echo $val;
    }
    $this->config_values = $config_values;
  ?>
  <div class="wrap">
    <h1>テーマオプション<small> (バージョン <?php echo $config_values['theme_version']; ?>)</small></h1>
    <?php if (isset($_POST['blankblanc_config_values']['reset_config'])) : ?>
      <div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible">
        <p><strong>設定を初期化しました</strong></p>
      </div>
    <?php elseif ($error) : ?>
      <div id="setting-error-settings_updated" class="error settings-error notice is-dismissible">
        <p><strong><?php echo esc_html($error); ?></strong></p>
      </div>
    <?php elseif (isset($_POST['blankblanc_config_values'])) : ?>
      <div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible">
        <p><strong>設定を保存しました</strong></p>
      </div>
    <?php endif; ?>
    <div id="bb-config-edit">
      <form method="post">
        <fieldset class="submit-btn submit-btn-top">
          <?php wp_nonce_field('blankblanc_config_nonce'); ?>
          <?php submit_button('設定を保存', 'primary', 'blankblanc-config-save', false); ?>
          <?php submit_button('初期設定値に戻す', 'button-reset', 'blankblanc_config_values[reset_config]', false, array('id' => 'reset-config')); ?>
        </fieldset>

        <div id="nav-tabs">
          <ul class="nav-tab-wrapper">
            <li><a href="#tab-1" class="nav-tab nav-tab-active">共通1</a></li>
            <li><a href="#tab-2" class="nav-tab">共通2</a></li>
            <li><a href="#tab-3" class="nav-tab">ロゴ</a></li>
            <?php if (function_exists('call_bb_table_of_contents')) : ?>
              <li><a href="#tab-6" class="nav-tab">目次</a></li>
            <?php endif; ?>
            <?php if (function_exists('call_bb_mainvisual_term_meta')) : ?>
              <li><a href="#tab-5" class="nav-tab">メインビジュアル</a></li>
            <?php endif; ?>
            <li><a href="#tab-4" class="nav-tab">モバイル</a></li>
            <li><a href="#tab-7" class="nav-tab">トップページ</a></li>
          </ul>

          <!-- tab-1 -->
          <div id="tab-1">
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-post-thumbnail.php'; ?>
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-archive-thumbnail.php'; ?>
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-title-separator.php'; ?>
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-title-catchphrase.php'; ?>
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-copyright-text.php'; ?>
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-title-suffix.php'; ?>
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-excerpt-more.php'; ?>
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-excerpt-length.php'; ?>
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-excerpt-length-rss.php'; ?>
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-date-format.php'; ?>
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-more-text.php'; ?>
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-taxonomy-layout.php'; ?>
          </div>
          <!-- /tab-1 -->

          <!-- tab-2 -->
          <div id="tab-2">
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-use-auto-slug.php'; ?>
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-exclude-cat-id.php'; // [extention] ?>
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-bread-crumb-multi.php'; // [extention] ?>
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-output-canonical.php'; ?>
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-use-parent-css.php'; ?>
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-use-parent-script.php'; ?>
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-disable-emoji.php'; ?>
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-image-link-target.php'; ?>
          </div>
          <!-- /tab-2 -->

          <!-- tab-3 -->
          <div id="tab-3">
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-logo-image.php'; ?>
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-siteicon.php'; ?>
          </div>
          <!-- /tab-3 -->

          <!-- tab-4 -->
          <div id="tab-4">
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-mobile-nav-position.php'; ?>
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-mobile-nav.php'; ?>
          </div>
          <!-- /tab-4 -->

          <!-- tab-5 -->
          <div id="tab-5">
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-mainvisual-common.php'; ?>
          </div>
          <!-- /tab-5 -->

          <!-- tab-6 -->
          <div id="tab-6">
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-table-of-contents.php'; ?>
          </div>
          <!-- /tab-6 -->

          <!-- tab-7 -->
          <div id="tab-7">
            <?php if (function_exists('call_bb_mainvisual_term_meta')) : ?>
              <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-mainvisual-home.php'; ?>
            <?php endif; ?>
            <?php if (function_exists('call_bb_page_layout_select')) : ?>
              <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-homepage-column.php'; ?>
            <?php endif; ?>
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-homepage-articles.php'; ?>
          </div>
          <!-- /tab-7 -->
        </div>

        <hr>
        <fieldset class="submit-btn submit-btn-bottom">
          <?php submit_button('設定を保存', 'primary', 'blankblanc-config-save-2', false); ?>
          <?php submit_button('初期設定値に戻す', 'button-reset', 'blankblanc_config_values[reset_config]', false, array('id' => 'reset-config-2')); ?>
        </fieldset>
      </form>
    </div>
  </div>
  <?php
  }

  public function add_blankblanc_config_edit() {
    add_theme_page('テーマオプション', 'テーマオプション', 'edit_themes', 'blankblanc_config_edit', array($this, 'blankblanc_config_edit'));
  }

  // 変更の確認
  public function has_modified($key = '', $add_class = '') {
    if (empty($key)) {
      return;
    }
    $keys = explode('.', $key);
    if (!empty($add_class)) {
      $add_class = " {$add_class}";
    }
    $key_class = $keys[0];
    $modified = '';
    if (isset($keys[1])) {
      $key_class = $key_class . ' ' . $keys[1];
      if (is_array($this->bb_theme_default[$keys[0]][$keys[1]])) {
        ksort($this->bb_theme_default[$keys[0]][$keys[1]]);
        ksort($this->config_values[$keys[0]][$keys[1]]);
      }
      if ($this->bb_theme_default[$keys[0]][$keys[1]] !== $this->config_values[$keys[0]][$keys[1]]) {
        $modified = ' config-modified';
      }
    } else {
      if (is_array($this->bb_theme_default[$keys[0]])) {
        ksort($this->bb_theme_default[$keys[0]]);
        ksort($this->config_values[$keys[0]]);
      }
      if ($this->bb_theme_default[$keys[0]] !== $this->config_values[$keys[0]]) {
        $modified = ' config-modified';
      }
    }
    echo " class=\"{$key_class}{$add_class}{$modified}\"";
  }
}
