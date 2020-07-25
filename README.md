# BlankBlanc
> WordPress Theme

ウェブサイト+ブログを構築するためのベース用テーマとして、オリジナルのデザインやレイアウトを組み込みやすさを主眼に置いた、飾りを抑えたシンプルな2カラムタイプの HTML5 テーマです。

カテゴリーやページ毎に設定した slug 名と同じスタイルシートをテーマ内に用意することで、各々スタイルの調整を行うことができます。<br>
基本 CSS ではレスポンシブデザインとして、767px でスマートフォン向けのレイアウト切り替えを設けています。

<br>

<a href="https://user-images.githubusercontent.com/7519663/88454183-aff33280-cea8-11ea-8c75-87935530a8e2.jpg"><img src="https://user-images.githubusercontent.com/7519663/88454183-aff33280-cea8-11ea-8c75-87935530a8e2.jpg" width="40%"></a>
&emsp;<a href="https://user-images.githubusercontent.com/7519663/88457674-4e8c8d00-cec3-11ea-9026-f96248d70ea7.jpg"><img src="https://user-images.githubusercontent.com/7519663/88457674-4e8c8d00-cec3-11ea-9026-f96248d70ea7.jpg" width="40%"></a>

<a href="https://user-images.githubusercontent.com/7519663/88457677-51877d80-cec3-11ea-84dc-ddfa5ccfae1e.jpg"><img src="https://user-images.githubusercontent.com/7519663/88457677-51877d80-cec3-11ea-84dc-ddfa5ccfae1e.jpg" width="40%"></a>
&emsp;<a href="https://user-images.githubusercontent.com/7519663/88457680-53e9d780-cec3-11ea-8e3f-3af63617e890.jpg"><img src="https://user-images.githubusercontent.com/7519663/88457680-53e9d780-cec3-11ea-8e3f-3af63617e890.jpg" width="40%"></a>

#### ～ ご利用の前に ～
- サイトに合わせて CSS 等でデザイン加工やレイアウトを行って利用されることを前提としていますので、完成されたデザインテーマを望まれる方の用途には向いておりません。
- また、SEO や AMP の対応、計測タグ、ソーシャル関連の設置などもプラグインでの利用を前提としています。<br>
- BlankBlanc を利用される際は親テーマのアップデートに対応できるよう、[子テーマ（BlankBlanc Child）](https://github.com/sapphirus9/blankblanc-child)を用いてカスタマイズすることをお勧めします。
- BlankBlanc は日本語向けテーマです。(Language: Japanese)


<br>

## 動作要件
* WordPress 4.7 以降
* PHP 5.6.20 以降

<br>

## 基本設定
### 初期値
[*functions.php*](https://github.com/sapphirus9/blankblanc/blob/master/functions.php) にある *bb_config_default()* で設定した値が使用されます。

### テーマオプション（WP 管理画面 > 外観）
各項目の初期値として *bb_config_default()* の設定値が設定されます。
ここで『設定を保存』すると初期値とは別にWP内に保存され、以降はここでの設定値が反映されます。

『初期状態に戻す』ボタンを押すと変更した項目はすべてクリアされ、*bb_config_default()* の設定値が再び有効になります。

#### 各項目と設定値
|項目|キー|タイプ|初期値|
|---|---|---|---|
|title タグのセパレーター|title_separator|string|｜|
|title に併記するキャッチフレーズ|title_catchphrase|string|一般設定のキャッチフレーズ|
|一覧ページのタイトル接尾辞|title_suffix|string|の一覧|
|アイキャッチ画像|post_thumbnail|array<br>幅[数値], 高さ[数値], 切り出し[true/false]|array(1024, 768, true)|
|一覧ページのサムネイル画像|archive_thumbnail|array<br>幅[数値], 高さ[数値], 切り出し[true/false]|array(300, 300, true)|
|記事抜粋時の省略表記|excerpt_more|string| &#x22ef;|
|記事抜粋の文字数|excerpt_length|numeric|110|
|RSS の記事出力文字数|excerpt_length_rss|numeric|200|
|年月日個別フォーマット|date_format|array<br>Y年, n月, j日|array('Y年', 'n月', 'j日')|
|『続きを読む』の表記|more_text|string| &#x22ef; 続きを読む|
|日本語タイトル時のスラッグ設定|use_auto_slug|bool|true|
|設定するスラッグの接頭辞|auto_post_slug|string|空|
|ロゴ画像の設定|logo_image|url|get_template_directory_uri() . '/img/logo.png'|
|ロゴイメージサイズ|logo_size|array<br>幅[数値], 高さ[数値]|array()|
|ロゴイメージの alt|logo_alt|string|get_bloginfo('name')|
|共通メインビジュアル画像|mv_image|url|空|
|コピーライトの表記（接頭辞）|copyright_prefix|string|空|
|コピーライトの表記（開始年）|start_year|string|date_i18n('Y')|
|コピーライトの表記（テキスト）|copyright_text|string|get_bloginfo('name')|
|rel=canonical／prev／next 出力|output_canonical|bool|true|
|親テーマの CSS を利用|with_parent_css|bool|true|
|親テーマのスクリプト（js）を利用|with_parent_script|bool|true|
|モバイルメニュー|mobile_nav|array<br>ウィジェット id など|array('#global-nav', '#header-nav')|
|モバイル時フッターに追加するウィジェットの指定|mobile_nav_footer|array<br>ウィジェット id など|array()|
|除外対象のカテゴリー ID|exclude_cat_id|string<br>カンマ区切りの ID 番号|空|
|カテゴリー毎（複数）のパンくず表示|bread_crumb_multi|bool|false|
|絵文字を無効化|disable_emoji|bool|true|
|body に body_class を追加|add_body_class|bool|false|
|トップページ用メインビジュアル|mv_home_image|url|空|
|トップページ用メインビジュアル内コンテンツ|mv_home_content|html|空|
<br>

## ライセンス
BlankBlanc のテーマに含まれるオリジナルについては、すべて **GPLv2 ライセンス**です。
- [GNU General Public License v2 or later](http://www.gnu.org/licenses/gpl-2.0.html)

### 使用ライブラリ等
以下のライブラリ等の使用に関しては、各々のライセンスに準じます。
* [jQuery](https://jquery.com/) - [MIT License](https://jquery.org/license/)
* [Material Design Icons](https://materialdesignicons.com/) - [SIL Open Font License 1.1](http://scripts.sil.org/cms/scripts/page.php?item_id=OFL_web)

<br>

###### 執筆途中です…
