/**
 * 設定
 */
const cfg = {
  rootDir  : '', // 対象のトップディレクトリ
  srcDir   : 'src', // ソースファイルのディレクトリ
  scssDir  : 'scss', // 対象の scss ディレクトリ
  scssFiles: '**/*.scss', // 対象の scss ファイル
  jsDir    : 'js', // 対象の js ディレクトリ
  jsFiles  : '**/*.js', // 対象の js ファイル

  distDir  : 'assets', // 出力対象のディレクトリ
  cssDist  : 'css', // css の出力先ディレクトリ
  jsDist   : 'js', // js の出力先ディレクトリ
}

/**
 * モジュール
 */
const { src, dest, watch, parallel } = require('gulp')
const autoprefixer  = require('autoprefixer')
const sass          = require('gulp-dart-sass')
const notify        = require('gulp-notify')
const plumber       = require('gulp-plumber')
const postcss       = require('gulp-postcss')
const rename        = require('gulp-rename')
const path          = require('path')
const mergerules    = require('postcss-merge-rules')
const normcharset   = require('postcss-normalize-charset')
const smqueries     = require('postcss-sort-media-queries')
const webpack       = require('webpack')
const webpackStream = require('webpack-stream')

/**
 * パス
 */
const _files = {
  scss: path.join(__dirname, cfg.rootDir, cfg.srcDir, cfg.scssDir, cfg.scssFiles),
  js : path.join(__dirname, cfg.rootDir, cfg.srcDir, cfg.jsDir, cfg.jsFiles),
}
const _dist = {
  css: path.join(__dirname, cfg.rootDir, cfg.distDir, cfg.cssDist),
  js : path.join(__dirname, cfg.rootDir, cfg.distDir, cfg.jsDist),
}

/**
 * SCSS
 */
const ScssProd = () =>
  src(_files.scss)
    .pipe(plumber({ errorHandler: notify.onError('Error: <%= error.message %>') }))
    .pipe(sass.sync({
      outputStyle: 'compressed',
    }))
    .pipe(postcss([
      autoprefixer(),
      normcharset(),
      mergerules(),
      smqueries()
    ]))
    .pipe(dest(_dist.css))
ScssProd.displayName = 'Build SCSS';

/**
 * JS
 */
const webpackConfig = (_file) => {
  return {
    mode: 'production',
      output: {
      filename: `${_file.basename}.js`,
    },
    module: {
      rules: [{
        use: [{
          loader: 'babel-loader',
          options: {
            presets: ["@babel/preset-env"]
          }
        }]
      }]
    }
  }
}
const JsProd = done => {
  src(_files.js)
    .pipe(rename((_file) => {
      src(path.join(__dirname, cfg.rootDir, cfg.srcDir, cfg.jsDir, _file.basename + _file.extname))
        .pipe(webpackStream(webpackConfig(_file), webpack))
        .pipe(plumber({ errorHandler: notify.onError('Error: <%= error.message %>') }))
        .pipe(dest(_dist.js))
    }))
  done()
}
JsProd.displayName = 'Build JS'

/**
 * ウォッチ
 */
const WatchBuild = done => {
  watch(_files.scss, ScssProd)
  watch(_files.js, JsProd)
  done()
}
WatchBuild.displayName = 'Watch Build'

exports.build = parallel([ScssProd, JsProd])
exports.watch_build = WatchBuild
exports.build_scss = ScssProd
exports.build_js = JsProd
