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

const path = require('path')

/**
 * ディレクトリ形成
 */
const _root = {
  src : path.join(__dirname, cfg.rootDir, cfg.srcDir),
  dist: path.join(__dirname, cfg.rootDir, cfg.distDir),
}
const _src = {
  scss: path.resolve(_root.src, cfg.scssDir, cfg.scssFiles),
  js  : path.resolve(_root.src, cfg.jsDir, cfg.jsFiles),
}
const _dist = {
  css: path.resolve(_root.dist, cfg.cssDist),
  js : path.resolve(_root.dist, cfg.jsDist),
}

/**
 * モジュール
 */
const { src, dest, parallel } = require('gulp')
const babel        = require('gulp-babel')
const sass         = require('gulp-dart-sass')
const notify       = require('gulp-notify')
const plumber      = require('gulp-plumber')
const postcss      = require('gulp-postcss')
const progeny      = require('gulp-progeny')
const terser       = require('gulp-terser')
const autoprefixer = require('autoprefixer')
const mergerules   = require('postcss-merge-rules')
const normcharset  = require('postcss-normalize-charset')
const smqueries    = require('postcss-sort-media-queries')

/**
 * SCSS
 */
const ScssProd = () =>
  src(_src.scss)
    .pipe(plumber({ errorHandler: notify.onError('Error: <%= error.message %>') }))
    .pipe(progeny())
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
const JsProd = () =>
  src(_src.js)
    .pipe(plumber({ errorHandler: notify.onError('Error: <%= error.message %>') }))
    .pipe(babel({ "presets": ["@babel/preset-env"] }))
    .pipe(terser())
    .pipe(dest(_dist.js))
JsProd.displayName = 'Build JS'

exports.build = parallel([ScssProd, JsProd])
exports.build_scss = ScssProd
exports.build_js = JsProd
