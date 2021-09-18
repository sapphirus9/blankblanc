/**
 * 設定
 */
const cfg = {
  proxy    : 'https://wp.localhost',
  host     : '192.168.1.31', // IPアドレス等
  mode     : 'production', // development または production

  rootDir  : '', // 対象のトップディレクトリ
  srcDir   : 'src', // ソースファイルのディレクトリ
  scssDir  : 'scss', // 対象の scss ディレクトリ
  scssFiles: '**/*.scss', // 対象の scss ファイル
  es6Dir   : 'js', // 対象の es6 ディレクトリ
  es6Files : '**/*.es6', // 対象の es6 ファイル
  tscDir   : 'js', // 対象の ts ディレクトリ
  tscFiles : '**/*.ts', // 対象の ts ファイル

  distDir  : '', // 出力対象のディレクトリ
  cssDist  : 'css', // css の出力先ディレクトリ
  cssMap   : '', // css map の出力先ディレクトリ
  jsDist   : 'js', // es6|ts の出力先ディレクトリ
  jsMap    : '', // js map の出力先ディレクトリ
}

const path = require('path')

/**
 * リロード対象のファイル
 */
const reloadFiles = [
  path.resolve(__dirname, '**/*.html'),
  path.resolve(__dirname, '**/*.php'),
  path.resolve(__dirname, cfg.rootDir, cfg.srcDir, '**/*.js'),
]

/**
 * ディレクトリ形成
 */
const _root = {
  src : path.join(__dirname, cfg.rootDir, cfg.srcDir),
  dist: path.join(__dirname, cfg.rootDir, cfg.distDir),
}
const _src = {
  scss: path.resolve(_root.src, cfg.scssDir, cfg.scssFiles),
  es6 : path.resolve(_root.src, cfg.es6Dir, cfg.es6Files),
  tsc : path.resolve(_root.src, cfg.tscDir, cfg.tscFiles),
}
const _dist = {
  css: path.resolve(_root.dist, cfg.cssDist),
  es6: path.resolve(_root.dist, cfg.jsDist),
  tsc: path.resolve(_root.dist, cfg.jsDist),
}
const _map = {
  scss: './' + path.relative(path.resolve(_root.dist, cfg.cssDist), path.resolve(_root.src, cfg.scssDir)),
  es6 : './' + path.relative(path.resolve(_root.dist, cfg.jsDist), path.resolve(_root.src, cfg.es6Dir)),
  tsc : './' + path.relative(path.resolve(_root.dist, cfg.jsDist), path.resolve(_root.src, cfg.tscDir)),
}

/**
 * モジュール
 */
const { src, dest, watch, parallel } = require('gulp')
const babel        = require('gulp-babel')
const sass         = require('gulp-dart-sass')
const notify       = require('gulp-notify')
const plumber      = require('gulp-plumber')
const postcss      = require('gulp-postcss')
const progeny      = require('gulp-progeny')
const sourcemaps   = require('gulp-sourcemaps')
const typescript   = require('gulp-typescript')
const uglify       = require('gulp-uglify')
const autoprefixer = require('autoprefixer')
const bsync        = require('browser-sync').create()
const mergerules   = require('postcss-merge-rules')
const normcharset  = require('postcss-normalize-charset')
const smqueries    = require('postcss-sort-media-queries')

/**
 * SCSS
 */
// mode: development
const ScssDev = () =>
  src(_src.scss)
    .pipe(plumber({ errorHandler: notify.onError('Error: <%= error.message %>') }))
    .pipe(progeny())
    .pipe(sourcemaps.init())
    .pipe(sass.sync({
      outputStyle: 'expanded',
      indentType: 'space',
      indentWidth: 2,
    }))
    .pipe(postcss([
      autoprefixer({ cascade: false }),
      mergerules(),
      smqueries()
    ]))
    .pipe(sourcemaps.write(cfg.cssMap, {
      includeContent: false,
      sourceRoot: _map.scss,
    }))
    .pipe(dest(_dist.css))
    .pipe(bsync.stream())

// mode: production
const ScssProd = () =>
  src(_src.scss)
    .pipe(plumber({ errorHandler: notify.onError('Error: <%= error.message %>') }))
    .pipe(progeny())
    .pipe(sass.sync({
      outputStyle: 'compressed',
    }))
    .pipe(postcss([
      autoprefixer({ cascade: false }),
      normcharset(),
      mergerules(),
      smqueries()
    ]))
    .pipe(dest(_dist.css))
    .pipe(bsync.stream())

/**
 * TypeScript
 */
// mode: development
const TscDev = () =>
  src(_src.tsc)
    .pipe(plumber({ errorHandler: notify.onError('Error: <%= error.message %>') }))
    .pipe(sourcemaps.init())
    .pipe(typescript()).js
    .pipe(sourcemaps.write(cfg.jsMap, {
      includeContent: false,
      sourceRoot: _map.tsc,
    }))
    .pipe(dest(_dist.tsc))

// mode: production
const TscProd = () =>
  src(_src.tsc)
    .pipe(plumber({ errorHandler: notify.onError('Error: <%= error.message %>') }))
    .pipe(typescript()).js
    .pipe(uglify())
    .pipe(dest(_dist.tsc))

/**
 * Babel
 */
// mode: development
const BabelDev = () =>
  src(_src.es6)
    .pipe(plumber({ errorHandler: notify.onError('Error: <%= error.message %>') }))
    .pipe(sourcemaps.init())
    .pipe(babel({
      presets: [['@babel/preset-env', { modules: false }]]
    }))
    .pipe(sourcemaps.write(cfg.jsMap, {
      includeContent: false,
      sourceRoot: _map.es6,
    }))
    .pipe(dest(_dist.es6))

// mode: production
const BabelProd = () =>
  src(_src.es6)
    .pipe(plumber({ errorHandler: notify.onError('Error: <%= error.message %>') }))
    .pipe(babel({
      presets: [['@babel/preset-env', { modules: false }]]
    }))
    .pipe(uglify())
    .pipe(dest(_dist.es6))

/**
 * リロード
 */
const Reload = done => {
  bsync.reload()
  done()
}

/**
 * ブラウザ同期
 */
const BrowserSync = done => {
  bsync.init({
    host: cfg.host,
    proxy: cfg.proxy,
    open: false,
    online: false,
    notify: true,
    ui: false,
    https: {
      key: '/root/.ssl/_wildcard.d.localhost+6-key.pem',
      cert: '/root/.ssl/_wildcard.d.localhost+6.pem',
    },
  })
  done()
}

/**
 * ウォッチ
 */
const WatchDev = done => {
  watch(_src.scss, ScssDev)
  watch(_src.es6, BabelDev)
  watch(_src.tsc, TscDev)
  watch(reloadFiles, Reload)
  done()
}
const WatchProd = done => {
  watch(_src.scss, ScssProd)
  watch(_src.es6, BabelProd)
  watch(_src.tsc, TscProd)
  watch(reloadFiles, Reload)
  done()
}

/**
 * 実行
 */
exports.default = parallel(cfg.mode == 'production' ? WatchProd : WatchDev, BrowserSync)
exports.development = parallel(WatchDev, BrowserSync)
exports.production = parallel(WatchProd, BrowserSync)
