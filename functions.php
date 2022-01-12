<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Description: テーマのためのファンクション
 */

/**
 * 設定
 * テーマオプションの初期設定値
 */
function bb_config_default() {
  return array(
    // タイトルタグのセパレータ [string]
    'title_separator'     => '｜',
    // タイトルに併記するキャッチフレーズ（デフォルトは一般設定のキャッチフレーズ） [string]
    'title_catchphrase'   => get_bloginfo('description'),
    // アーカイブページのタイトル接尾辞 [string]
    'title_suffix'        => 'の一覧',
    // アイキャッチ画像サイズ（幅[数値], 高さ[数値], 切り出し[true/false]） [array]
    'post_thumbnail'      => array(1024, 786, true),
    // アーカイブページのサムネイル画像サイズ（幅[数値], 高さ[数値], 切り出し[true/false]） [array]
    'archive_thumbnail'   => array(300, 300, true),
    // 記事抜粋時の省略表記 [string]
    'excerpt_more'        => ' &#x22ef;',
    // 記事抜粋の文字数 [int]
    'excerpt_length'      => 110,
    // RSSの記事出力文字数 [int]
    'excerpt_length_rss'  => 200,
    // 年月日個別フォーマット（年,月,日,セパレータ） [array]
    'date_format'         => array('Y年', 'n月', 'j日', ''),
    // 投稿・固定ページのMOREテキスト表示 [string]
    'more_text'           => ' &#x22ef; 続きを読む',
    // 日本語タイトル時のスラッグにIDを使って自動設定する [bool]
    'use_auto_slug'       => true,
    // 自動設定するスラッグの接頭辞（無指定はpost_type） [string]
    'auto_post_slug'      => '',
    // ロゴイメージ画像（デフォルトは{theme_name}/img/logo.png） [string(url)]
    'logo_image'          => get_template_directory_uri() . '/assets/img/logo.png',
    // ロゴのサイズ（幅[数値], 高さ[数値]） [array]
    'logo_size'           => array(230, 40),
    // ロゴのalt出力（デフォルトはブログ名） [string]
    'logo_alt'            => get_bloginfo('name'),
    // メインビジュアル画像 [string(url)]
    'mv_image'            => '',
    // コピーライトの接頭辞（デフォルトは無指定） [string]
    'copyright_prefix'    => '',
    // コピーライトの接尾時（デフォルトは無指定） [string]
    'copyright_suffix'    => '',
    // サイト開始年（コピーライト箇所で使用） [string]
    'start_year'          => date_i18n('Y'),
    // コピーライトの表示テキスト（デフォルトはブログ名） [string]
    'copyright_text'      => get_bloginfo('name') . '.',
    // カノニカル出力の有無 [bool]
    'output_canonical'    => true,
    // 子テーマ利用時に親テーマのCSSファイルを読み込む [bool]
    'with_parent_css'     => true,
    // 子テーマ利用時に親テーマのfunctions.jsファイルを読み込む [bool]
    'with_parent_script'  => true,
    // モバイル時に使用するウィジェットの指定（#global-navはグローバルナビ） [array]
    'mobile_nav'          => array('#global-nav', '#header-nav'),
    // モバイル時のスライドナビの方向（左：無指定/右：right） [string]
    'mobile_nav_position' => '',
    // モバイル時フッターに追加するウィジェットの指定 [array]
    'mobile_nav_footer'   => array(),
    // 除外対象のカテゴリID（カテゴリーウィジェット・フィード等） [string] *extension
    'exclude_cat_id'      => '',
    // 投稿ページのカテゴリー毎のパンくず表示 [bool] *extension
    'bread_crumb_multi'   => false,
    // 絵文字を無効化 [bool]
    'disable_emoji'       => true,
    // 画像へのリンクはすべて別窓（_blank）として開く [bool]
    'image_link_target'   => false,
    // bodyにbody_classを追加 [bool]
    'add_body_class'      => false,
    // タクソノミー（カテゴリー・タグ等）のレイアウトタイプ（list/tiles） [string]
    'taxonomy_layout'     => 'list',
    // トップページメインビジュアル（HomeまたはFront Pageが対象） [string(url)]
    'mv_home_image'       => get_template_directory_uri() . '/assets/img/img-hero.jpg',
    // トップページメインビジュアル内コンテンツ（HomeまたはFront Pageが対象） [string]
    'mv_home_content'     => '',
    // テーマ用CSS/JSのバージョンパラメータを別で指定（デフォルトはfalse）※ブラウザキャッシュ対策用 [bool/string]
    'version_param'       => false,
    // 目次機能を有効化 [bool] *extension
    'use_toc'             => true,
    // 目次設定 [array]
    'toc_config'          => array(
      // 目次を表示 [bool]
      'toc_active'     => true,
      // 目次を閉じた状態にする [bool]
      'toc_closed'     => false,
      // 目次タイトル [string]
      'toc_title'      => 'Contents',
      // 除外する見出し（h1~h6） [array]
      'toc_hidden'     => array('h1'),
      // アンカーIDに付加する文字列（Index-数字） [string]
      'toc_prefix'     => 'Index-',
      // 目次の挿入場所（ボディ最上部:0 ボディ最下部:-1 x番目の見出し前:1~） [int]
      'toc_position'   => 1,
    ),
  );
}


/**
 * ファンクション追加・変更等は子テーマまたはこの下に記述してください
 */


/**
 * ファンクションの読み込み
 */
require_once __DIR__ . '/functions/config.php';
