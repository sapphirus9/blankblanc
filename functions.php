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
    // タイトルタグのセパレータ
    'title_separator'     => '｜',
    // タイトルに併記するキャッチフレーズ（デフォルトは一般設定のキャッチフレーズ）
    'title_catchphrase'   => get_bloginfo('description'),
    // アーカイブページのタイトル接尾辞
    'title_suffix'        => 'の一覧',
    // アイキャッチ画像サイズ（幅[数値], 高さ[数値], 切り出し[true/false]）
    'post_thumbnail'      => array(1024, 786, true),
    // アーカイブページのサムネイル画像サイズ（幅[数値], 高さ[数値], 切り出し[true/false]）
    'archive_thumbnail'   => array(300, 300, true),
    // 記事抜粋時の省略表記
    'excerpt_more'        => ' &#x22ef;',
    // 記事抜粋の文字数
    'excerpt_length'      => 110,
    // RSS の記事出力文字数
    'excerpt_length_rss'  => 200,
    // 年月日個別フォーマット（年,月,日）
    'date_format'         => array('Y年', 'n月', 'j日'),
    // 投稿・固定ページの MORE テキスト表示
    'more_text'           => ' &#x22ef; 続きを読む',
    // 日本語タイトル時のスラッグにIDを使って自動設定する（true/false）
    'use_auto_slug'       => true,
    // 自動設定するスラッグの接頭辞（無指定は post_type）
    'auto_post_slug'      => '',
    // ロゴイメージ画像（デフォルトは {theme_name}/img/logo.png）
    'logo_image'          => get_template_directory_uri() . '/img/logo.png',
    // ロゴのサイズ（幅[数値], 高さ[数値]）
    'logo_size'           => array(),
    // ロゴの alt 出力（デフォルトはブログ名）
    'logo_alt'            => get_bloginfo('name'),
    // メインビジュアル画像
    'mv_image'            => '',
    // コピーライトの接頭辞（デフォルトは無指定）
    'copyright_prefix'    => '',
    // コピーライトの接尾時（デフォルトは無指定）
    'copyright_suffix'    => '',
    // サイト開始年（コピーライト箇所で使用）
    'start_year'          => date_i18n('Y'),
    // コピーライトの表示テキスト（デフォルトはブログ名）
    'copyright_text'      => get_bloginfo('name') . '.',
    // カノニカル出力の有無（true/false）
    'output_canonical'    => true,
    // 子テーマ利用時に親テーマの CSS ファイルを読み込む（true/false）
    'with_parent_css'     => true,
    // 子テーマ利用時に親テーマの function.js ファイルを読み込む（true/false）
    'with_parent_script'  => true,
    // モバイル時に使用するウィジェットの指定（#global-nav はグローバルナビ）
    'mobile_nav'          => array('#global-nav', '#header-nav'),
    // モバイル時のスライドナビの方向（左：false/右：true）
    'mobile_nav_position' => false,
    // モバイル時フッターに追加するウィジェットの指定
    'mobile_nav_footer'   => array(),
    // 除外対象のカテゴリ ID（カテゴリーウィジェット・フィード等） [extension]
    'exclude_cat_id'      => '',
    // 投稿ページのカテゴリー毎のパンくず表示（true/false） [extension]
    'bread_crumb_multi'   => false,
    // 絵文字を無効化
    'disable_emoji'       => true,
    // body に body_class を追加
    'add_body_class'      => false,
    // タクソノミー（カテゴリー・タグ等）のレイアウトタイプ（list/tiles）
    'taxonomy_layout'     => 'list',
    // トップページメインビジュアル（Home または Front Page が対象）
    'mv_home_image'       => get_template_directory_uri() . '/img/img-hero.jpg',
    // トップページメインビジュアル内コンテンツ（Home または Front Page が対象）
    'mv_home_content'     => '',
  );
}


/**
 * ファンクション追加・変更等は子テーマまたはこの下に記述してください
 */


/**
 * ファンクションの読み込み
 */
require_once __DIR__ . '/functions/config.php';
