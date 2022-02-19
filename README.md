# BlankBlanc
> **Original WordPress Theme**

ウェブサイト＋ブログを構築するためのベース用テーマとして、CSSによるデザインやレイアウトの組み込みやすさを主眼に置き、飾りなどを抑えたシンプルなレスポンシブデザイン対応のオリジナル無料テーマです。

カテゴリーやページ毎に設定したslug名と同じスタイルシートをテーマ内に用意することで、各々スタイルの調整を行うことができます。<br>
基本CSSではレスポンシブデザインとして、767pxでスマートフォン向けのレイアウト切り替えを設けています。<br>
<br>
<a href="https://user-images.githubusercontent.com/7519663/88454183-aff33280-cea8-11ea-8c75-87935530a8e2.jpg" target="_blank"><img src="https://user-images.githubusercontent.com/7519663/88454183-aff33280-cea8-11ea-8c75-87935530a8e2.jpg" title="フロントページ (front page)" width="30%"></a>&emsp;
<a href="https://user-images.githubusercontent.com/7519663/88457674-4e8c8d00-cec3-11ea-9026-f96248d70ea7.jpg" target="_blank"><img src="https://user-images.githubusercontent.com/7519663/88457674-4e8c8d00-cec3-11ea-9026-f96248d70ea7.jpg" title="一覧ページ (archive)" width="30%"></a>&emsp;
<a href="https://user-images.githubusercontent.com/7519663/88457677-51877d80-cec3-11ea-84dc-ddfa5ccfae1e.jpg" target="_blank"><img src="https://user-images.githubusercontent.com/7519663/88457677-51877d80-cec3-11ea-84dc-ddfa5ccfae1e.jpg" title="投稿ページ (single)" width="30%"></a>&emsp;
<br><br>
<a href="https://user-images.githubusercontent.com/7519663/88469932-90541c80-cf31-11ea-8bb6-711ba5ba2dca.jpg" target="_blank"><img src="https://user-images.githubusercontent.com/7519663/88469932-90541c80-cf31-11ea-8bb6-711ba5ba2dca.jpg" title="タイル状の一覧ページ［htmlクラス指定］ (archive)" width="30%"></a>&emsp;
<a href="https://user-images.githubusercontent.com/7519663/88457680-53e9d780-cec3-11ea-8e3f-3af63617e890.jpg" target="_blank"><img src="https://user-images.githubusercontent.com/7519663/88457680-53e9d780-cec3-11ea-8e3f-3af63617e890.jpg" title="テーマオプション" width="30%"></a>&emsp;
<a href="https://user-images.githubusercontent.com/7519663/88469918-6c90d680-cf31-11ea-85ea-4f81ac7f8795.jpg" target="_blank"><img src="https://user-images.githubusercontent.com/7519663/88469918-6c90d680-cf31-11ea-85ea-4f81ac7f8795.jpg" title="テーマオプション" width="30%"></a>&emsp;

#### ～ ご利用にあたり ～
- サイトに合わせてCSS等でデザイン加工やレイアウトを行って利用されることを前提としていますので、完成されたデザインテーマを望まれる方の用途には向いておりません。
- また、SEOやAMPの対応、計測タグ、ソーシャル関連の設置などもプラグインでの利用を前提としています。<br>
- BlankBlancを利用される際は親テーマのアップデートに対応できるよう、[子テーマ（BlankBlanc Child）](https://github.com/sapphirus9/blankblanc-child)を用いてカスタマイズすることをお勧めします。
- ウィジェットはブロックエディターには未対応のため、従来のウィジェット設定を利用してください。（widgets-block-editorにて暫定的にサポートを停止）
- メインビジュアル、目次機能は限定（試用的な）での公開とさせてただきます。
- BlankBlancは日本語向けテーマです。(Japanese Only)

## 動作要件
* WordPress 5.5以降
* PHP 5.6.20以降（原則WordPress本体の動作条件に準ずる）
* Chrome, Firefox, Edge, Safariは原則として最新バージョンの対応
* IE 11は一部非対応（おもにCSS）

## 基本設定
### 初期値
[*functions.php*](https://github.com/sapphirus9/blankblanc/blob/master/functions.php)にある*bb_config_default()*で設定した値が使用されます。

### テーマオプション（WP管理画面 > 外観）
各項目の初期値として*bb_config_default()*の設定値が設定されます。
ここで『設定を保存』すると初期値とは別にWP内に保存され、以降はここでの設定値が反映されます。

『初期状態に戻す』ボタンを押すと変更した項目はすべてクリアされ、*bb_config_default()*の設定値が再び有効になります。

#### 各項目と設定値
|項目|キー|タイプ|初期値|
|---|---|---|---|
|titleタグのセパレーター|title_separator|string|｜|
|titleに併記するキャッチフレーズ|title_catchphrase|string|一般設定のキャッチフレーズ|
|一覧ページのタイトル接尾辞|archive_title_suffix|string|の一覧|
|アイキャッチ画像|post_thumbnail|array<br>幅[数値], 高さ[数値], 切り出し[true/false]|array(1024, 768, true)|
|一覧ページのサムネイル画像|archive_thumbnail|array<br>幅[数値], 高さ[数値], 切り出し[true/false]|array(300, 300, true)|
|記事抜粋時の省略表記|excerpt_more|string|&#x22ef;|
|記事抜粋の文字数|excerpt_length|int|110|
|RSSの記事出力文字数|excerpt_length_rss|int|200|
|年月日個別フォーマット|date_format|array<br>Y年, n月, j日, セパレータ|array('Y年', 'n月', 'j日', '')|
|『続きを読む』の表記|more_text|string|&#x22ef; 続きを読む|
|日本語タイトル時のスラッグ設定|use_auto_slug|bool|true|
|設定するスラッグの接頭辞|auto_post_slug|string|空|
|ロゴ画像|logo_image|string (url)|blankblanc/assets/img/logo.png|
|ロゴ画像サイズ|logo_size|array<br>幅[数値], 高さ[数値]|array(230, 40)|
|ロゴ画像のalt|logo_alt|string|ブログ名|
|共通メインビジュアル画像|mv_image|string (url)|空|
|コピーライトの表記（接頭辞）|copyright_prefix|string|空|
|コピーライトの表記（接尾時）|copyright_suffix|string|空|
|コピーライトの表記（開始年）|start_year|string|date_i18n('Y')|
|コピーライトの表記（テキスト）|copyright_text|string|ブログ名|
|rel=canonical／prev／next出力|output_canonical|bool|true|
|親テーマのCSSを利用|with_parent_css|bool|true|
|親テーマのスクリプト（js）を利用|with_parent_script|bool|true|
|モバイルメニュー|mobile_nav|array<br>ウィジェットidなど|array('#global-nav', '#header-nav')|
|モバイル時のスライドナビの方向|mobile_nav_position|string<br>空（左）/right（右）|空|
|モバイル時フッターに追加するウィジェットの指定|mobile_nav_footer|array<br>ウィジェットidなど|array()|
|除外対象のカテゴリーID|exclude_cat_id|string<br>カンマ区切りのID番号|空|
|カテゴリー毎（複数）のパンくず表示|bread_crumb_multi|bool|false|
|絵文字を無効化|disable_emoji|bool|true|
|画像へのリンクはすべて別窓（_blank）として開く|image_link_target|bool|false|
|bodyにbody_classを追加|add_body_class|bool|false|
|共通の投稿一覧レイアウトタイプ|taxonomy_layout|string<br>list（リスト）/tiles（タイル）|list|
|トップページ用メインビジュアル|mv_home_image|string (url)|blankblanc/img/img-hero.jpg|
|トップページ用メインビジュアル内コンテンツ|mv_home_content|string (html)|空|
|トップページレイアウト|homepage_layout|array|※以下の配列用|
|+ カラムレイアウト|column|string<br>default（2カラム）/onecolumn（1カラム幅固定）/fullwidth（1カラム全幅）/nowrapwidth（画面全幅）|default|
|+ 投稿一覧レイアウトタイプ|articles|string<br>list（リスト）/tiles（タイル）|list|
|目次機能を有効化|use_toc|bool|true|
|目次設定|toc_config|array|※以下の配列用|
|+ 目次を表示|toc_active|bool|true|
|+ 目次を閉じた状態にする|toc_closed|bool|false|
|+ 目次タイトル|toc_title|string|Contents|
|+ 除外する見出し|toc_hidden|array<br>h1～h6|array('h1')|
|+ アンカーIDに付加する文字列|toc_prefix|string|Index-|
|+ 目次の挿入場所|toc_position|int<br>ボディ最上部: 0<br>ボディ最下部: -1<br>x番目の見出し出現前: 1~|1|
|ファビコンを設定|favicon|string (url)|空|
|サイトアイコンを設定|siteicon|string (url)|空|
|テーマ用CSS/JSのバージョンパラメータを別で指定（デフォルトはfalse）※ブラウザキャッシュ対策用|version_param|false<br>またはバージョン番号等|false|

## ライセンス
BlankBlancのテーマに含まれるオリジナルについては、すべて**GPLv2ライセンス**です。
- [GNU General Public License v2 or later](http://www.gnu.org/licenses/gpl-2.0.html)

### 使用ライブラリ等
以下のライブラリ等の使用に関しては、各々のライセンスに準じます。
* [jQuery](https://jquery.com/) - [MIT License](https://jquery.org/license/)
* [Material Design Icons](https://materialdesignicons.com/) - [SIL Open Font License 1.1](http://scripts.sil.org/cms/scripts/page.php?item_id=OFL_web)

## 付記
動作の不具合などが見つかるかもしれません。予めご了承ください。<br>
※現在、加筆途中です …
