<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Description: テーマオプション管理画面
 */

/**
 * 外観メニューにテーマの基本設定項目を追加
 */
if (current_user_can('edit_themes')) {
  add_action('init', 'call_blankblanc_config');
}

function call_blankblanc_config() {
  new blankblancConfig();
}

class blankblancConfig
{
  public $config_values;
  public $bb_theme_default;

  public function __construct() {
    global $bb_theme_default;
    $this->bb_theme_default = $bb_theme_default;
    add_action('admin_menu', array($this, 'add_blankblanc_config_edit'));
  }

  // 仕様変更による確認とメッセージ
  private function specification_change() {
    $error = array();
    // date_format ->
    if (!isset($this->config_values['date_format'][3])) {
      $this->config_values['date_format'][3] = $this->bb_theme_default['date_format'][3];
      $error[] = 'date_format';
    }
    // <- date_format
    // copyright ->
    if (isset($this->config_values['copyright_prefix'])) {
      $this->config_values['copyright']['prefix'] = $this->config_values['copyright_prefix'];
      $error[] = 'copyright';
    }
    if (isset($this->config_values['start_year'])) {
      $this->config_values['copyright']['start_year'] = $this->config_values['start_year'];
      $error[] = 'copyright';
    }
    if (isset($this->config_values['copyright_text'])) {
      $this->config_values['copyright']['text'] = $this->config_values['copyright_text'];
      $error[] = 'copyright';
    }
    if (isset($this->config_values['copyright_suffix'])) {
      $this->config_values['copyright']['suffix'] = $this->config_values['copyright_suffix'];
      $error[] = 'copyright';
    }
    // <- copyright
    // auto_post_slug ->
    if (isset($this->config_values['use_auto_slug'])) {
      $this->config_values['ja_auto_post_slug']['rewrite'] = $this->config_values['use_auto_slug'];
      $error[] = 'ja_auto_post_slug';
    }
    if (isset($this->config_values['auto_post_slug'])) {
      $this->config_values['ja_auto_post_slug']['prefix'] = $this->config_values['auto_post_slug'];
      $error[] = 'ja_auto_post_slug';
    }
    // <- auto_post_slug
    if (!empty($error)) {
      $msg = '';
      if (array_search('date_format', $error) !== false) {
        $msg .= "・<strong>「年月日個別フォーマット」</strong>について仕様を変更しています。\n";
      }
      if (array_search('copyright', $error) !== false) {
        $msg .= "・<strong>「コピーライト」</strong>について仕様を変更しています。\n";
      }
      if (array_search('ja_auto_post_slug', $error) !== false) {
        $msg .= "・<strong>「日本語タイトル時のスラッグ設定」</strong>について仕様を変更しています。\n";
      }
      $msg .= "上記を反映するためには、一度<strong>「設定を保存」</strong>を行って<strong>更新</strong>してください。";
  ?>
    <div id="setting-error-settings_updated" class="error settings-error notice is-dismissible">
      <p><?php echo nl2br($msg); ?></p>
    </div>
  <?php
      return true;
    }
    return false;
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
          $bb_theme_default['mobile_nav'] = array(); // モバイルメニュー
          if (empty($config_post['mobile_nav'])) {
            $config_post['mobile_nav'] = array();
          }
          $bb_theme_default['mobile_nav_footer'] = array(); // モバイルフッターメニュー
          if (empty($config_post['mobile_nav_footer'])) {
            $config_post['mobile_nav_footer'] = array();
          }
          $bb_theme_default['toc_config']['toc_hidden'] = array(); // 目次から除外する見出し
          $config_values = array_replace_recursive($bb_theme_default, $config_post);
          $config_values['exclude_cat_id'] = str_replace(' ', '', $config_values['exclude_cat_id']);
          update_option('blankblanc_config_values', wp_unslash($config_values));
        }
      }
    } else { // 初期値をセット
      if (!$config_values = get_option('blankblanc_config_values')) {
        $config_values = $bb_theme_default;
        update_option('blankblanc_config_values', wp_unslash($config_values));
      }
    }
    wp_register_script('bb-theme-admin', get_template_directory_uri() . '/admin/assets/js/bb-theme-admin.js', array('jquery-ui-sortable', 'jquery-touch-punch', 'jquery-ui-tabs'), $config_values['theme_version']);
    wp_enqueue_script('bb-theme-admin');
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
    if ($this->specification_change()) {
      $config_values = $this->config_values;
    }
    $theme_info = wp_get_theme();
    if (is_child_theme()) {
      $theme = [
        'name' => $theme_info->parent()->get('Name'),
        'version_child'  => $theme_info->get('Version'),
        'version_parent' => $theme_info->parent()->get('Version'),
      ];
    } else {
      $theme = [
        'name' => $theme_info->get('Name'),
        'version_child'  => '',
        'version_parent' => $theme_info->get('Version'),
      ];
    }
    $theme_link = <<< HTML
    <a href="https://github.com/sapphirus9/blankblanc" target="_blank">{$theme['name']}<svg height="32" aria-hidden="true" viewBox="0 0 16 16" version="1.1" width="32" data-view-component="true" class="octicon octicon-mark-github v-align-middle color-fg-default">
      <path d="M8 0c4.42 0 8 3.58 8 8a8.013 8.013 0 0 1-5.45 7.59c-.4.08-.55-.17-.55-.38 0-.27.01-1.13.01-2.2 0-.75-.25-1.23-.54-1.48 1.78-.2 3.65-.88 3.65-3.95 0-.88-.31-1.59-.82-2.15.08-.2.36-1.02-.08-2.12 0 0-.67-.22-2.2.82-.64-.18-1.32-.27-2-.27-.68 0-1.36.09-2 .27-1.53-1.03-2.2-.82-2.2-.82-.44 1.1-.16 1.92-.08 2.12-.51.56-.82 1.28-.82 2.15 0 3.06 1.86 3.75 3.64 3.95-.23.2-.44.55-.51 1.07-.46.21-1.61.55-2.33-.66-.15-.24-.6-.83-1.23-.82-.67.01-.27.38.01.53.34.19.73.9.82 1.13.16.45.68 1.31 2.69.94 0 .67.01 1.3.01 1.49 0 .21-.15.45-.55.38A7.995 7.995 0 0 1 0 8c0-4.42 3.58-8 8-8Z"></path>
    </svg></a>
HTML;
  ?>
  <div class="wrap" id="blankblanc-theme-options">
    <h1>
      <span class="column-title"><span class="title">テーマオプション</span><small>バージョン: <?php echo $theme['version_parent']; ?><?php if (!empty($theme['version_child'])) echo "（子テーマ: {$theme['version_child']}）"; ?></small></span>
      <span class="column-theme-name"><?php echo $theme_link; ?></span>
    </h1>
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
    <form method="post" id="force-reset">
        <?php wp_nonce_field('blankblanc_config_nonce'); ?>
        <div class="message">このテキストが表示されている場合、設定値に誤りがある可能性あります。<br>
        以下のボタンより初期設定に戻してみてください。</div>
        <?php submit_button('強制的に初期設定に戻す', 'button--force-reset', 'blankblanc_config_values[reset_config]', false, array('id' => 'reset-config')); ?>
    </form>
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
            <li><a href="#tab-8" class="nav-tab">共通2</a></li>
            <li><a href="#tab-2" class="nav-tab">共通3</a></li>
            <li><a href="#tab-3" class="nav-tab">ロゴ</a></li>
            <?php if (function_exists('call_bb_table_of_contents')) : ?>
              <li><a href="#tab-6" class="nav-tab">目次</a></li>
            <?php endif; ?>
            <?php if (function_exists('call_bb_mainvisual_term_meta')) : ?>
              <li><a href="#tab-5" class="nav-tab">メインビジュアル</a></li>
            <?php endif; ?>
            <li><a href="#tab-7" class="nav-tab">トップページ</a></li>
            <li><a href="#tab-4" class="nav-tab">モバイル</a></li>
            <li><a href="#tab-9" class="nav-tab">設定一覧</a></li>
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
          </div>
          <!-- /tab-1 -->

          <!-- tab-2 -->
          <div id="tab-2">
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-use-auto-slug.php'; ?>
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-exclude-cat-id.php'; ?>
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-bread-crumb-multi.php'; ?>
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-output-canonical.php'; ?>
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-use-parent-css.php'; ?>
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-use-parent-script.php'; ?>
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-disable-emoji.php'; ?>
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-add-body-class.php'; ?>
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
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-mobile-footer-nav.php'; ?>
          </div>
          <!-- /tab-4 -->

          <!-- tab-5 -->
          <div id="tab-5">
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-mainvisual-common.php'; ?>
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-mainvisual-size.php'; ?>
          </div>
          <!-- /tab-5 -->

          <!-- tab-6 -->
          <div id="tab-6">
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-table-of-contents.php'; ?>
          </div>
          <!-- /tab-6 -->

          <!-- tab-7 -->
          <div id="tab-7">
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-mainvisual-home.php'; ?>
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-homepage-column.php'; ?>
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-homepage-articles.php'; ?>
          </div>
          <!-- /tab-7 -->

          <!-- tab-8 -->
          <div id="tab-8">
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-column-layout.php'; ?>
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-taxonomy-layout.php'; ?>
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-fixed-global-nav.php'; ?>
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-fixed-widget.php'; ?>
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-image-link-target.php'; ?>
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-loading-screen.php'; ?>
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-image-fade-in.php'; ?>
            <?php require_once dirname(__DIR__) . '/admin/fieldset/inc-cookie-banner.php'; ?>
          </div>
          <!-- /tab-8 -->

          <!-- tab-9 -->
          <div id="tab-9">
            <?php $this->config_list(); ?>
          </div>
          <!-- /tab-9 -->
        </div>

        <hr>
        <fieldset class="submit-btn submit-btn-bottom">
          <?php submit_button('設定を保存', 'primary', 'blankblanc-config-save-2', false); ?>
        </fieldset>
      </form>
    </div>
  </div>
  <script>
    document.querySelector('#force-reset').style.display = 'none';
  </script>
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
        if (empty($this->config_values[$keys[0]][$keys[1]])) {
          $this->config_values[$keys[0]][$keys[1]] = array();
        }
        ksort($this->bb_theme_default[$keys[0]][$keys[1]]);
        ksort($this->config_values[$keys[0]][$keys[1]]);
      }
      if ($this->bb_theme_default[$keys[0]][$keys[1]] !== wp_unslash($this->config_values[$keys[0]][$keys[1]])) {
        $modified = ' config-modified';
      }
    } else {
      if (is_array($this->bb_theme_default[$keys[0]])) {
        ksort($this->bb_theme_default[$keys[0]]);
        if (empty($this->config_values[$keys[0]])) {
          $this->config_values[$keys[0]] = array();
        }
        ksort($this->config_values[$keys[0]]);
      }
      if ($this->bb_theme_default[$keys[0]] !== wp_unslash($this->config_values[$keys[0]])) {
        $modified = ' config-modified';
      }
    }
    echo " class=\"{$key_class}{$add_class}{$modified}\"";
  }

  // 設定一覧
  private function config_list($current = '', $level = 1, $child = '', $default = '') {
    if (empty($current)) {
      $current = $this->config_values; // 現在の設定
      $default = $this->bb_theme_default; // 初期設定
    }
    $src = array();
    $tab = str_repeat("\t", $level);
    foreach ($current as $key => $value) {
      if (is_array($value)) {
        // 変更のある項目のチェック
        $modified_flag = false;
        $modified_key = $key;
        $default_value = $default[$key];
        $current_value = $value;
        ksort($default_value);
        ksort($current_value);
        if (serialize($default_value) != serialize(wp_unslash($current_value))) {
          $modified_flag = true;
          $modified_key = sprintf("<strong class=\"modified-key\">'%s'</strong>", $key);
        }
        if (empty($value)) {
          $array_empty = $modified_flag ? '<strong class="modified">array()</strong>' : 'array()';
          array_push($src, "{$tab}'{$modified_key}' => {$array_empty},\n");
        } else {
          array_push($src, "{$tab}'{$modified_key}' => array(\n");
          array_push($src, $this->config_list($value, $level + 1, 'child', $default[$key]));
          array_push($src, "{$tab}),\n");
        }
      } else {
        $_default = isset($default[$key]) ? $default[$key] : ''; //デフォルト値を取得
        $modified = $_default !== wp_unslash($value) ? true : false;
        // 現在の設定値の型変更
        if (is_bool($value)) {
          $value = $value ? 'true' : 'false';
        } elseif (is_string($value)) {
          $value = "'{$value}'";
        }
        // デフォルト設定値の型変更
        if (is_bool($_default)) {
          $_default = $_default ? 'true' : 'false';
        } elseif (is_string($_default)) {
          $_default = "'{$_default}'";
        }
        // 変更値を強調
        $value = esc_html(wp_unslash($value));
        $_value = $modified ? sprintf("<strong class=\"modified\">%s</strong>", $value) : $value;
        array_push($src, (is_numeric($key) ? "{$tab}{$_value}" : "{$tab}'{$key}' => {$_value}") . ",\n");
      }
    }
    if (empty($child)) {
      array_unshift($src, "array(\n");
      array_push($src, ');');
    }
    $src = implode('', $src);
    if ($child) {
      return $src;
    } else {
      echo <<<EOD
<fieldset class="config-list">
  <div class="label-title">現在の設定一覧を配列表示</div>
  <div class="note">初期値から変更の箇所は<strong class="modified">赤字</strong>で表示されます。</div>
  <div class="list-block">
    <pre contenteditable="true" spellcheck="false">{$src}</pre>
  </div>
</fieldset>

EOD;
    }
  }
}
