<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Description: テーマオプション管理画面
 */

/**
 * 外観メニューにテーマの基本設定項目を追加
 */
function blankblanc_config_edit() {
  global $bb_theme_default;
  //postデータを取得 & bool変換
  $error = null;
  if (isset($_POST['blankblanc_config_values'])) {
    $config_post = array_map(function($val) {
      if ($val === 'true') {
        return true;
      } elseif ($val === 'false') {
        return false;
      } else {
        return $val;
      }
    }, $_POST['blankblanc_config_values']);
    check_admin_referer('blankblanc_config_nonce');
    if (isset($config_post['reset_config'])) { // 初期状態に戻す
      delete_option('blankblanc_config_values');
      $config_values = $bb_theme_default;
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
        $config_values['exclude_cat_id'] = str_replace(' ', '', $config_values['exclude_cat_id']);
        update_option('blankblanc_config_values', wp_unslash($config_values));
      }
    }
  } else { // 初期値値をセット
    if (!$config_values = get_option('blankblanc_config_values')) {
      $config_values = $bb_theme_default;
    }
  }
  wp_enqueue_script('bb-theme-admin-js', get_template_directory_uri() . '/admin/bb-theme-admin.js', array('jquery-ui-sortable', 'jquery-touch-punch', 'jquery-ui-tabs'));
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
?>
<div class="wrap">
  <h1>テーマオプション</h1>
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
        <?php submit_button('初期状態に戻す', 'button-reset', 'blankblanc_config_values[reset_config]', false, array('id' => 'reset-config')); ?>
      </fieldset>

      <div id="nav-tabs">
        <ul class="nav-tab-wrapper">
          <li><a href="#tab-1" class="nav-tab nav-tab-active">共通</a></li>
          <li><a href="#tab-2" class="nav-tab">ロゴ</a></li>
          <?php if (function_exists('call_bb_mainvisual_term_meta')) : ?>
            <li><a href="#tab-4" class="nav-tab">メインビジュアル</a></li>
          <?php endif; ?>
          <li><a href="#tab-3" class="nav-tab">モバイル</a></li>
        </ul>

        <!-- tab-1 -->
        <div id="tab-1">
          <fieldset class="post-thumbnail">
            <div class="col-left">
              <div class="label-title">アイキャッチ画像</div>
              <div class="note">アイキャッチ画像の使用サイズと画像の切り出し方法を設定します。<br>
              幅または高さを無指定にするとアイキャッチを利用しません。</div>
            </div>
            <div class="col-right">
              <div class="group">
                <label for="bb-config-post-thumbnail-1" class="prefix">幅</label>
                <input type="number" name="blankblanc_config_values[post_thumbnail][0]" id="bb-config-post-thumbnail-1" class="s-num" value="<?php echo $config_values['post_thumbnail'][0]; ?>">
                <label for="bb-config-post-thumbnail-1">px</label>
              </div>
              <div class="group">
                <label for="bb-config-post-thumbnail-2" class="prefix">高さ</label>
                <input type="number" name="blankblanc_config_values[post_thumbnail][1]" id="bb-config-post-thumbnail-2" class="s-num" value="<?php echo $config_values['post_thumbnail'][1]; ?>">
                <label for="bb-config-post-thumbnail-2">px</label>
              </div>
              <div class="group">
                <label for="bb-config-post-thumbnail-3" class="prefix">画像切り出し</label>
                <?php
                $select = $config_values['post_thumbnail'][2];
                if (is_bool($select)) {
                  $select = var_export($select, true);
                }
                ?>
                <select name="blankblanc_config_values[post_thumbnail][2]" id="bb-config-post-thumbnail-3">
                  <option value="false"<?php if ($select === 'false') echo ' selected'; ?>>縮小 (false)</option>
                  <option value="true"<?php if ($select === 'true') echo ' selected'; ?>>切り出し (true)</option>
                  <option value="left,top"<?php if ($select === 'left,top') echo ' selected'; ?>>左／上 (left,top)</option>
                  <option value="center,top"<?php if ($select === 'center,top') echo ' selected'; ?>>中／上 (center,top)</option>
                  <option value="right,top"<?php if ($select === 'right,top') echo ' selected'; ?>>右／上 (right,top)</option>
                  <option value="left,center"<?php if ($select === 'left,center') echo ' selected'; ?>>左／中 (left,center)</option>
                  <option value="right,center"<?php if ($select === 'right,center') echo ' selected'; ?>>右／中 (right,center)</option>
                  <option value="left,bottom"<?php if ($select === 'left,bottom') echo ' selected'; ?>>左／下 (left,bottom)</option>
                  <option value="center,bottom"<?php if ($select === 'center,bottom') echo ' selected'; ?>>中／下 (center,bottom)</option>
                  <option value="right,bottom"<?php if ($select === 'right,bottom') echo ' selected'; ?>>右／下 (right,bottom)</option>
                </select>
              </div>
              <div class="default">初期値: 幅 <?php _echo(@$bb_theme_default['post_thumbnail'][0], '', 'px'); ?> ／高さ <?php _echo(@$bb_theme_default['post_thumbnail'][1], '', 'px'); ?> ／画像切り出し <?php _echo(@$bb_theme_default['post_thumbnail'][2]); ?></div>
            </div>
          </fieldset>

          <fieldset class="archive-thumbnail">
            <div class="col-left">
              <div class="label-title">一覧ページのサムネイル画像<em>※必須</em></div>
              <div class="note">サムネイル画像の使用サイズと画像の切り出し方法を設定します。</div>
            </div>
            <div class="col-right">
              <div class="group">
                <label for="bb-config-archive-thumbnail-1" class="prefix">幅</label>
                <input type="number" name="blankblanc_config_values[archive_thumbnail][0]" id="bb-config-archive-thumbnail-1" class="s-num" value="<?php echo $config_values['archive_thumbnail'][0]; ?>">
                <label for="bb-config-archive-thumbnail-1">px</label>
              </div>
              <div class="group">
                <label for="bb-config-archive-thumbnail-2" class="prefix">高さ</label>
                <input type="number" name="blankblanc_config_values[archive_thumbnail][1]" id="bb-config-archive-thumbnail-2" class="s-num" value="<?php echo $config_values['archive_thumbnail'][1]; ?>">
                <label for="bb-config-archive-thumbnail-2">px</label>
              </div>
              <div class="group">
                <label for="bb-config-archive-thumbnail-3" class="prefix">画像切り出し</label>
                <?php
                $select = $config_values['archive_thumbnail'][2];
                if (is_bool($select)) {
                  $select = var_export($select, true);
                }
                ?>
                <select name="blankblanc_config_values[archive_thumbnail][2]" id="bb-config-archive-thumbnail-3">
                  <option value="false"<?php if ($select === 'false') echo ' selected'; ?>>縮小 (false)</option>
                  <option value="true"<?php if ($select === 'true') echo ' selected'; ?>>切り出し (true)</option>
                  <option value="left,top"<?php if ($select === 'left,top') echo ' selected'; ?>>左／上 (left,top)</option>
                  <option value="center,top"<?php if ($select === 'center,top') echo ' selected'; ?>>中／上 (center,top)</option>
                  <option value="right,top"<?php if ($select === 'right,top') echo ' selected'; ?>>右／上 (right,top)</option>
                  <option value="left,center"<?php if ($select === 'left,center') echo ' selected'; ?>>左／中 (left,center)</option>
                  <option value="right,center"<?php if ($select === 'right,center') echo ' selected'; ?>>右／中 (right,center)</option>
                  <option value="left,bottom"<?php if ($select === 'left,bottom') echo ' selected'; ?>>左／下 (left,bottom)</option>
                  <option value="center,bottom"<?php if ($select === 'center,bottom') echo ' selected'; ?>>中／下 (center,bottom)</option>
                  <option value="right,bottom"<?php if ($select === 'right,bottom') echo ' selected'; ?>>右／下 (right,bottom)</option>
                </select>
              </div>
              <div class="default">初期値: 幅 <?php _echo(@$bb_theme_default['archive_thumbnail'][0], '', 'px'); ?> ／高さ <?php _echo(@$bb_theme_default['archive_thumbnail'][1], '', 'px'); ?> ／画像切り出し <?php _echo(@$bb_theme_default['archive_thumbnail'][2]); ?></div>
            </div>
          </fieldset>

          <fieldset class="title-separator">
            <div class="col-left">
              <div class="label-title">title タグのセパレーター</div>
            </div>
            <div class="col-right">
              <div class="group">
                <input type="text" name="blankblanc_config_values[title_separator]" id="bb-config-title-separator" class="s-text" value="<?php echo esc_textarea($config_values['title_separator']); ?>">
              </div>
              <div class="default">初期値: <?php _echo(@$bb_theme_default['title_separator']); ?></div>
            </div>
          </fieldset>

          <fieldset class="title-catchphrase">
            <div class="col-left">
              <div class="label-title">title に併記するキャッチフレーズ</div>
              <div class="note">無指定の場合、一般設定のキャッチフレーズが適用されます。</div>
            </div>
            <div class="col-right">
              <div class="group group-full">
                <input type="text" name="blankblanc_config_values[title_catchphrase]" id="bb-config-title-catchphrase" class="l-text" value="<?php echo esc_textarea($config_values['title_catchphrase']); ?>">
              </div>
              <div class="default">初期値: <?php _echo(@$bb_theme_default['title_catchphrase']); ?></div>
            </div>
          </fieldset>

          <fieldset class="copyright-text">
            <div class="col-left">
              <div class="label-title">コピーライトの表記</div>
              <div class="note">テキストが無指定の場合、コピーライトは出力されません。</div>
            </div>
            <div class="col-right">
              <div class="group">
                <label for="bb-config-copyright-text-1" class="prefix">接頭辞</label>
                <input type="text" name="blankblanc_config_values[copyright_prefix]" id="bb-config-copyright-text-1" class="m-text" value="<?php echo $config_values['copyright_prefix']; ?>">
              </div>
              <div class="group">
                <label for="bb-config-copyright-text-2" class="prefix">開始年</label>
                <input type="number" name="blankblanc_config_values[start_year]" id="bb-config-copyright-text-2" class="s-num" value="<?php echo $config_values['start_year']; ?>">
              </div>
              <div class="group">
                <label for="bb-config-copyright-text-3" class="prefix">テキスト</label>
                <input type="text" name="blankblanc_config_values[copyright_text]" id="bb-config-copyright-text-3" class="m-text" value="<?php echo $config_values['copyright_text']; ?>">
              </div>
              <div class="default">初期値: 接頭辞 <?php _echo(@$bb_theme_default['copyright_prefix']); ?>／開始年 <?php _echo(@$bb_theme_default['start_year']); ?>／テキスト <?php _echo(@$bb_theme_default['copyright_text']); ?></div>
            </div>
          </fieldset>

          <fieldset class="title-suffix">
            <div class="col-left">
              <div class="label-title">一覧ページのタイトル接尾辞</div>
            </div>
            <div class="col-right">
              <div class="group">
                <input type="text" name="blankblanc_config_values[title_suffix]" id="bb-config-title-suffix" class="m-text" value="<?php echo esc_textarea($config_values['title_suffix']); ?>">
              </div>
              <div class="default">初期値: <?php _echo(@$bb_theme_default['title_suffix']); ?></div>
            </div>
          </fieldset>

          <fieldset class="excerpt-more">
            <div class="col-left">
              <div class="label-title">記事抜粋時の省略表記</div>
            </div>
            <div class="col-right">
              <div class="group">
                <input type="text" name="blankblanc_config_values[excerpt_more]" id="bb-config-excerpt-more" class="m-text" value="<?php echo esc_textarea($config_values['excerpt_more']); ?>">
              </div>
              <div class="default">初期値: <?php _echo(@$bb_theme_default['excerpt_more']); ?></div>
            </div>
          </fieldset>

          <fieldset class="excerpt-length">
              <div class="col-left">
                <div class="label-title">記事抜粋の文字数<em>※必須</em></div>
              </div>
              <div class="col-right">
                <div class="group">
                  <input type="number" name="blankblanc_config_values[excerpt_length]" id="bb-config-excerpt-length" class="s-num" value="<?php echo esc_attr($config_values['excerpt_length']); ?>">
                </div>
                <div class="default">初期値: <?php _echo(@$bb_theme_default['excerpt_length']); ?></div>
              </div>
          </fieldset>

          <fieldset class="excerpt-length-rss">
            <div class="col-left">
              <div class="label-title">RSS の記事出力文字数</div>
              <div class="note">無指定の場合、記事抜粋の文字数が適用されます。</div>
            </div>
            <div class="col-right">
              <div class="group">
                <input type="number" name="blankblanc_config_values[excerpt_length_rss]" id="bb-config-excerpt-length-rss" class="s-num" value="<?php echo esc_attr($config_values['excerpt_length_rss']); ?>">
              </div>
              <div class="default">初期値: <?php _echo(@$bb_theme_default['excerpt_length_rss']); ?></div>
            </div>
          </fieldset>

          <fieldset class="date-format">
            <div class="col-left">
              <div class="label-title">年月日個別フォーマット</div>
            </div>
            <div class="col-right">
              <div class="group">
                <label for="bb-config-date-format-1" class="prefix">年表示</label>
                <input type="text" name="blankblanc_config_values[date_format][0]" id="bb-config-date-format-1" class="s-text" value="<?php echo esc_textarea($config_values['date_format'][0]); ?>">
              </div>
              <div class="group">
                <label for="bb-config-date-format-2" class="prefix">月表示</label>
                <input type="text" name="blankblanc_config_values[date_format][1]" id="bb-config-date-format-2" class="s-text" value="<?php echo esc_textarea($config_values['date_format'][1]); ?>">
              </div>
              <div class="group">
                <label for="bb-config-date-format-3" class="prefix">日表示</label>
                <input type="text" name="blankblanc_config_values[date_format][2]" id="bb-config-date-format-3" class="s-text" value="<?php echo esc_textarea($config_values['date_format'][2]); ?>">
              </div>
              <div class="default">初期値: <?php _echo(implode('／', $bb_theme_default['date_format'])); ?></div>
            </div>
          </fieldset>

          <fieldset class="more-text">
            <div class="col-left">
              <div class="label-title">『続きを読む』の表記</div>
              <div class="note">投稿・固定ページの『続きを読む』の表記のしかたを設定します。</div>
            </div>
            <div class="col-right">
              <div class="group">
                <input type="text" name="blankblanc_config_values[more_text]" id="bb-config-more-text" class="m-text" value="<?php echo esc_textarea($config_values['more_text']); ?>">
              </div>
              <div class="default">初期値: <?php _echo(@$bb_theme_default['more_text']); ?></div>
            </div>
          </fieldset>

          <fieldset class="use-auto-slug">
            <div class="col-left">
              <div class="label-title">日本語タイトル時のスラッグ設定</div>
              <div class="note">投稿時に自動で設定される日本語スラッグを「接頭辞-ポスト ID（Ex. post-99）」に置き換えます。</div>
            </div>
            <div class="col-right">
              <div class="group">
                <input type="hidden" name="blankblanc_config_values[use_auto_slug]" value="false">
                <input type="checkbox" name="blankblanc_config_values[use_auto_slug]" id="bb-config-use-auto-slug" value="true"<?php if ($config_values['use_auto_slug']) echo ' checked'; ?>>
              </div>
              <div class="default">初期値: <?php _echo(@$bb_theme_default['use_auto_slug']); ?></div>
            </div>
          </fieldset>

          <fieldset class="auto-post-slug sub-field">
            <div class="col-left">
              <div class="label-title">設定するスラッグの接頭辞</div>
              <div class="note">無指定の時は {post_type} が接頭辞として使用されます。</div>
            </div>
            <div class="col-right">
              <div class="group">
                <input type="text" name="blankblanc_config_values[auto_post_slug]" id="bb-config-auto-post-slug" class="m-text" value="<?php echo esc_textarea($config_values['auto_post_slug']); ?>">
              </div>
              <div class="default">初期値: <?php _echo(@$bb_theme_default['auto_post_slug']); ?></div>
            </div>
          </fieldset>

          <?php // [extention] ex-exclude-categories.php ---
            if (class_exists('bbExcludeCategories')) : ?>
            <fieldset class="exclude-cat-id">
              <div class="col-left">
                <div class="label-title">除外対象のカテゴリー ID</div>
                <div class="note">特定のカテゴリーをカテゴリーウィジェットやフィード等の対象から除外します。（カンマ区切り）</div>
              </div>
              <div class="col-right">
                <div class="group group-full">
                  <input type="text" name="blankblanc_config_values[exclude_cat_id]" id="bb-config-exclude-cat-id" class="l-text" value="<?php echo esc_attr($config_values['exclude_cat_id']); ?>">
                </div>
                <div class="default">初期値: <?php _echo(@$bb_theme_default['exclude_cat_id']); ?></div>
              </div>
            </fieldset>
          <?php endif; // --- [extention] ?>

          <?php // [extention] ex-breadcrumb.php ---
            if (class_exists('bbBreadCrumb')) : ?>
            <fieldset class="bread-crumb-multi">
              <div class="col-left">
                <div class="label-title">カテゴリー毎（複数）の<br>パンくず表示</div>
                <div class="note">投稿ページにおいて、属するカテゴリーが複数指定の場合、カテゴリー毎（複数）のパンくずリストを表示します。</div>
              </div>
              <div class="col-right">
                <div class="group">
                  <input type="hidden" name="blankblanc_config_values[bread_crumb_multi]" value="false">
                  <input type="checkbox" name="blankblanc_config_values[bread_crumb_multi]" id="bb-config-bread-crumb-multi" value="true"<?php if ($config_values['bread_crumb_multi']) echo ' checked'; ?>>
                </div>
                <div class="default">初期値: <?php _echo(@$bb_theme_default['bread_crumb_multi']); ?></div>
              </div>
            </fieldset>
          <?php endif; // --- [extention] ?>

          <fieldset class="output-canonical">
            <div class="col-left">
              <div class="label-title">rel=canonical／prev／next 出力</div>
              <div class="note">プラグイン等を利用して設定を行う場合はチェックを外します。</div>
            </div>
            <div class="col-right">
              <div class="group">
                <input type="hidden" name="blankblanc_config_values[output_canonical]" value="false">
                <input type="checkbox" name="blankblanc_config_values[output_canonical]" id="bb-config-output-canonical" value="true"<?php if ($config_values['output_canonical']) echo ' checked'; ?>>
              </div>
              <div class="default">初期値: <?php _echo(@$bb_theme_default['output_canonical']); ?></div>
            </div>
          </fieldset>

          <?php if (is_child_theme()) : ?>
            <fieldset class="use-parent-css">
              <div class="col-left">
                <div class="label-title">親テーマの CSS を利用</div>
                <div class="note">子テーマを利用時に親テーマの CSS ファイル（style.css, style-mobile.css）を読み込みます。<br>
                ※指定しない場合は、親テーマの CSS を子テーマにコピーして利用してください。</div>
              </div>
              <div class="col-right">
                <div class="group">
                  <input type="hidden" name="blankblanc_config_values[with_parent_css]" value="false">
                  <input type="checkbox" name="blankblanc_config_values[with_parent_css]" id="bb-config-use-parent-css" value="true"<?php if ($config_values['with_parent_css']) echo ' checked'; ?>>
                </div>
                <div class="default">初期値: <?php _echo(@$bb_theme_default['with_parent_css']); ?></div>
              </div>
            </fieldset>
          <?php endif; ?>

          <?php if (is_child_theme()) : ?>
            <fieldset class="use-parent-script">
              <div class="col-left">
                <div class="label-title">親テーマのスクリプト（js）を利用</div>
                <div class="note">子テーマを利用時に親テーマの js/function.js, js/mobile-nav.js ファイルの読み込みます。<br>
                指定しない場合は、親テーマの 各スクリプトファイルを子テーマの js ディレクトリーにコピーして利用してください。</div>
              </div>
              <div class="col-right">
                <div class="group">
                  <input type="hidden" name="blankblanc_config_values[with_parent_script]" value="false">
                  <input type="checkbox" name="blankblanc_config_values[with_parent_script]" id="bb-config-use-parent-script" value="true"<?php if ($config_values['with_parent_script']) echo ' checked'; ?>>
                </div>
                <div class="default">初期値: <?php _echo(@$bb_theme_default['with_parent_script']); ?></div>
              </div>
            </fieldset>
          <?php endif; ?>

          <fieldset class="disable-emoji">
            <div class="col-left">
              <div class="label-title">絵文字の無効化</div>
              <div class="note">絵文字に関連する js, css, dns-prefetch を無効化します。</div>
            </div>
            <div class="col-right">
              <div class="group">
                <input type="hidden" name="blankblanc_config_values[disable_emoji]" value="false">
                <input type="checkbox" name="blankblanc_config_values[disable_emoji]" id="bb-config-disable-emoji" value="true"<?php if ($config_values['disable_emoji']) echo ' checked'; ?>>
              </div>
              <div class="default">初期値: <?php _echo(@$bb_theme_default['disable_emoji']); ?></div>
            </div>
          </fieldset>
        </div>
        <!-- /tab-1 -->

        <!-- tab-2 -->
        <div id="tab-2">
          <fieldset id="bb-logo-image" class="bb-media-upload">
            <div class="col-left">
              <div class="media-title">ロゴ画像</div>
              <div class="label-title">ロゴ画像の設定</div>
            </div>
            <div class="col-right">
              <div class="input-group">
                <?php
                $nouse = (empty($config_values['logo_image']) || $config_values['logo_image'] == -1) ? true : false;
                if ($logo_image = wp_get_attachment_image_src($config_values['logo_image'], 300)) {
                  $img = $logo_image[0];
                } else {
                  $img = $bb_theme_default['logo_image'];
                }
                ?>
                <div class="image-view">
                  <?php if ($img && !$nouse) : ?>
                    <img src="<?php echo $img; ?>" alt="">
                  <?php else : ?>
                    <span class="no-image">選択された画像はありません</span>
                  <?php endif; ?>
                </div>
                <input type="hidden" name="blankblanc_config_values[logo_image]" class="image-id" value="<?php echo $config_values['logo_image']; ?>">
                <input type="hidden" name="default-image" value="<?php echo $bb_theme_default['logo_image']; ?>">
                <input type="button" name="select" value="ロゴ画像を選択" class="button button-secondary">
                <input type="button" name="reset" value="キャンセル" class="button button-secondary">
                <?php if (!$nouse) : ?>
                  <input type="button" name="delete" value="削除する" class="button button-secondary">
                <?php endif; ?>
                <?php if ((isset($logo_image[0]) || $nouse) && $bb_theme_default['logo_image']) : ?>
                  <input type="button" name="default" value="初期設定画像に戻す" class="button button-secondary">
                <?php endif; ?>
              </div>
            </div>
          </fieldset>

          <fieldset class="logo-alt">
            <div class="col-left">
              <div class="label-title">ロゴイメージの alt</div>
              <div class="note">画像を設定していない場合、ロゴの代わりにこのテキストが適用されます。</div>
            </div>
            <div class="col-right">
              <div class="group group-full">
                <input type="text" name="blankblanc_config_values[logo_alt]" id="bb-config-logo-alt" class="l-text" value="<?php echo esc_textarea($config_values['logo_alt']); ?>">
                <div class="default">初期値: <?php _echo(@$bb_theme_default['logo_alt']); ?></div>
              </div>
            </div>
          </fieldset>

          <fieldset class="logo-size">
            <div class="col-left">
              <div class="label-title">ロゴイメージサイズ</div>
              <div class="note">img に width, height を設定できますが、CSS の指定が優先されます。</div>
            </div>
            <div class="col-right">
              <?php
              if (empty($config_values['logo_size'])) {
                $config_values['logo_size'] = array('', '');
              }
              ?>
              <div class="group">
                <label for="bb-config-logo-size-1" class="prefix">幅</label>
                <input type="number" name="blankblanc_config_values[logo_size][0]" id="bb-config-logo-size-1" class="s-num" value="<?php echo $config_values['logo_size'][0]; ?>">
                <label for="bb-config-logo-size-1">px</label>
              </div>
              <div class="group">
                <label for="bb-config-logo-size-2" class="prefix">高さ</label>
                <input type="number" name="blankblanc_config_values[logo_size][1]" id="bb-config-logo-size-2" class="s-num" value="<?php echo $config_values['logo_size'][1]; ?>">
                <label for="bb-config-logo-size-2">px</label>
              </div>
              <div class="default">初期値: 幅 <?php _echo(@$bb_theme_default['logo_size'][0], '', 'px'); ?> ／高さ <?php _echo(@$bb_theme_default['logo_size'][1], '', 'px'); ?></div>
            </div>
          </fieldset>
        </div>
        <!-- /tab-2 -->

        <!-- tab-3 -->
        <div id="tab-3">
          <fieldset class="mobile-nav">
            <?php
            global $wp_registered_sidebars, $wp_registered_widgets;
            $before_nav = has_nav_menu('global_nav') ? array('global-nav' => array('global-nav')) : array();
            $after_nav = has_nav_menu('header_nav') ? array('header-nav' => array('header-nav')) : array();
            $active_widgets = array_merge(
              $before_nav,
              wp_get_sidebars_widgets(),
              $after_nav
            );
            unset($active_widgets['wp_inactive_widgets']);
            $li_chkd = $li_none = array();
            foreach ($active_widgets as $widget_group_id => $widget_group) {
              if (empty($widget_group)) {
                continue;
              }
              foreach ($widget_group as $widget_id) {
                if ($widget_id == 'global-nav') {
                  $widget_name = 'グローバルナビゲーション';
                  $sidebar_name = 'メインメニュー';
                } elseif ($widget_id == 'header-nav') {
                  $widget_name = 'ヘッダーナビゲーション';
                  $sidebar_name = 'ヘッダー内';
                } else {
                  $sidebar_name = @$wp_registered_sidebars[$widget_group_id]['name'];
                  $reg_widget = @$wp_registered_widgets[$widget_id];
                  $widget = get_option($reg_widget['classname']);
                  if (!$widget_name = esc_attr(@$widget[$reg_widget['params'][0]['number']]['title'])) {
                    $widget_name = esc_attr__($reg_widget['name']);
                  }
                }
                if ($sidebar_name && $widget_name) {
                  if (isset($config_values['mobile_nav']) && in_array('#' . $widget_id, $config_values['mobile_nav'])) {
                    $key = array_search('#' . $widget_id, $config_values['mobile_nav']);
                    $li_chkd[$key] = "<input type=\"checkbox\" name=\"blankblanc_config_values[mobile_nav][]\" id=\"bb-widget_{$widget_id}\" value=\"#{$widget_id}\" checked>\n"
                    . "<label for=\"bb-widget_{$widget_id}\"><span class=\"widgets-name\">{$widget_name}</span><span class=\"sidebar-name\">（{$sidebar_name}）</span></label>\n";
                  } else {
                    $li_none[] = "<input type=\"checkbox\" name=\"blankblanc_config_values[mobile_nav][]\" id=\"bb-widget_{$widget_id}\" value=\"#{$widget_id}\">\n"
                    . "<label for=\"bb-widget_{$widget_id}\"><span class=\"widgets-name\">{$widget_name}</span><span class=\"sidebar-name\">（{$sidebar_name}）</span></label>\n";
                  }
                }
              }
            }
            ksort($li_chkd);
            $li_arr = array_merge($li_chkd, $li_none);
            ?>
            <div class="col-left">
              <div class="label-title">モバイルメニュー</div>
              <div class="note">選択されたウィジェットはモバイル時のスライドメニューとして登録されます。<br>
              ドラッグ&amp;ドロップで表示順を並べ替えできます。</div>
            </div>
            <div class="col-right">
              <div class="input-group">
                <ol id="activate-mobile-nav">
                  <?php foreach ($li_arr as $li) : ?>
                    <li><?php echo $li; ?></li>
                  <?php endforeach; ?>
                </ol>
              </div>
            </div>
          </fieldset>
        </div>
        <!-- /tab-3 -->

        <!-- tab-4 -->
        <div id="tab-4">
          <?php // [extention] extensions/ex-mainvisual.php ---
            if (function_exists('call_bb_mainvisual_term_meta')) : ?>
            <fieldset id="bb-mainvisual" class="bb-media-upload">
              <div class="col-left">
                <div class="media-title">共通メインビジュアル画像</div>
                <div class="label-title">共通メインビジュアルの設定</div>
                <div class="note">メインビジュアルの指定が無いページで使用されます。<br>
                未設定の場合、指定が無いページにメインビジュアルは表示されません。</div>
              </div>
              <div class="col-right">
                <div class="input-group">
                  <?php
                  $nouse = (empty($config_values['mv_image']) || $config_values['mv_image'] == -1) ? true : false;
                  if ($mv_image = wp_get_attachment_image_src($config_values['mv_image'], 600)) {
                    $img = $mv_image[0];
                  } else {
                    $img = $bb_theme_default['mv_image'];
                  }
                  ?>
                  <div class="image-view">
                    <?php if ($img && !$nouse) : ?>
                      <img src="<?php echo $img; ?>" alt="">
                    <?php else : ?>
                      <span class="no-image">選択された画像はありません</span>
                    <?php endif; ?>
                  </div>
                  <input type="hidden" name="blankblanc_config_values[mv_image]" class="image-id" value="<?php echo $config_values['mv_image']; ?>">
                  <input type="hidden" name="default-image" value="<?php echo $bb_theme_default['mv_image']; ?>">
                  <input type="button" name="select" value="画像を選択" class="button button-secondary">
                  <input type="button" name="reset" value="キャンセル" class="button button-secondary">
                  <?php if (!$nouse) : ?>
                    <input type="button" name="delete" value="削除する" class="button button-secondary">
                  <?php endif; ?>
                  <?php if ((isset($mv_image[0]) || $nouse) && $bb_theme_default['mv_image']) : ?>
                    <input type="button" name="default" value="初期設定画像に戻す" class="button button-secondary">
                  <?php endif; ?>
                </div>
              </div>
            </fieldset>

            <fieldset id="bb-mainvisual-home" class="bb-media-upload">
              <div class="col-left">
                <div class="label-title">トップページ用メインビジュアル</div>
                <div class="note">Home または Front Page が対象です。</div>
              </div>
              <div class="col-right">
                <div class="input-group">
                  <?php
                  $nouse = (empty($config_values['mv_home_image']) || $config_values['mv_home_image'] == -1) ? true : false;
                  if ($mv_home_image = wp_get_attachment_image_src($config_values['mv_home_image'], 600)) {
                    $img = $mv_home_image[0];
                  } else {
                    $img = $bb_theme_default['mv_home_image'];
                  }
                  ?>
                  <div class="image-view">
                    <?php if ($img && !$nouse) : ?>
                      <img src="<?php echo $img; ?>" alt="">
                    <?php else : ?>
                      <span class="no-image">選択された画像はありません</span>
                    <?php endif; ?>
                  </div>
                  <input type="hidden" name="blankblanc_config_values[mv_home_image]" class="image-id" value="<?php echo $config_values['mv_home_image']; ?>">
                  <input type="hidden" name="default-image" value="<?php echo $bb_theme_default['mv_home_image']; ?>">
                  <input type="button" name="select" value="画像を選択" class="button button-secondary">
                  <input type="button" name="reset" value="キャンセル" class="button button-secondary">
                  <?php if (!$nouse) : ?>
                    <input type="button" name="delete" value="削除する" class="button button-secondary">
                  <?php endif; ?>
                  <?php if ((isset($mv_home_image[0]) || $nouse) && $bb_theme_default['mv_home_image']) : ?>
                    <input type="button" name="default" value="初期設定画像に戻す" class="button button-secondary">
                  <?php endif; ?>
                </div>
              </div>
            </fieldset>

            <fieldset class="bb-mainvisual-home-content">
              <div class="col-left">
                <div class="label-title">トップページ用<br>メインビジュアル内コンテンツ</div>
                <div class="note">Home または Front Page が対象です。<br>
                class="mv-title-content" 以下での css 追加を前提としています。</div>
              </div>
              <div class="col-right">
                <?php wp_editor(
                  wp_unslash($config_values['mv_home_content']),
                  'bb_config_mv_home_content',
                  array(
                    'textarea_name' => 'blankblanc_config_values[mv_home_content]',
                    'editor_height' => 200,
                    'teeny'         => true,
                  )
                ); ?>
              </div>
            </fieldset>
          <?php endif; // --- [extention] ?>
        </div>
        <!-- /tab-4 -->
      </div>

      <hr>
      <fieldset class="submit-btn submit-btn-bottom">
        <?php submit_button('設定を保存', 'primary', 'blankblanc-config-save-2', false); ?>
        <?php submit_button('初期状態に戻す', 'button-reset', 'blankblanc_config_values[reset_config]', false, array('id' => 'reset-config-2')); ?>
      </fieldset>
    </form>
  </div>
</div>
<?php
}

// 管理画面に css を追加
function admin_extend_css_init() {
  $css = get_template_directory_uri() . '/admin/bb-theme-admin.css';
  echo "<link rel='stylesheet' href='{$css}' type='text/css' media='all'>\n";
}

function add_blankblanc_config_edit() {
  $page = add_theme_page('テーマオプション', 'テーマオプション', 'edit_themes', 'blankblanc_config_edit', 'blankblanc_config_edit');
  add_action('admin_head-' . $page, 'admin_extend_css_init');
}
add_action('admin_menu', 'add_blankblanc_config_edit');
