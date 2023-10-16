/**
 * 設定
 */
module.exports = {
  proxy    : 'https://wp.localhost',
  cert     : '/home/sapphirus/.ssl/local-domain.crt', // httpsの場合、証明書ファイル
  key      : '/home/sapphirus/.ssl/local-domain.key', // httpsの場合、秘密鍵ファイル
  host     : '192.168.1.32', // IPアドレス等
  mode     : 'production', // developmentまたはproduction

  rootDir  : '', // 対象のトップディレクトリ
  srcDir   : 'src', // ソースファイルのディレクトリ
  scssDir  : 'scss', // 対象のscssディレクトリ
  scssFiles: '**/*.scss', // 対象のscssファイル
  esmDir    : 'js', // 対象のesm(js)ディレクトリ
  esmFiles  : '**/*.js', // 対象のesm(js)ファイル

  distDir  : 'assets', // 出力対象のディレクトリ
  cssDist  : 'css', // cssの出力先ディレクトリ
  cssMap   : 'css', // css mapの出力先ディレクトリ
  jsDist   : 'js', // jsの出力先ディレクトリ
  jsMap    : 'js', // js mapの出力先ディレクトリ
}
