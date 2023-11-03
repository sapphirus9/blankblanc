/**
 * 設定の読み込み
 */
const cfg = require('./config')

/**
 * モジュール
 */
const autoprefixer  = require('autoprefixer')
const bsync         = require('browser-sync')
const gulp          = require('gulp')
const sass          = require('gulp-sass')(require('sass'))
const esbuild       = require('gulp-esbuild')
const notify        = require('gulp-notify')
const plumber       = require('gulp-plumber')
const postcss       = require('gulp-postcss')
const rename        = require('gulp-rename')
const sourcemaps    = require('gulp-sourcemaps')
const minimist      = require('minimist')
const path          = require('path')
const mergerules    = require('postcss-merge-rules')
const normcharset   = require('postcss-normalize-charset')
const smqueries     = require('postcss-sort-media-queries')

/**
 * 引数でモード変更
 */
const params = minimist(process.argv.slice(2), {
  string: 'mode',
  alias: {
    m: 'mode',
  },
  default: {
    mode: cfg.mode,
  }
})
if (params.mode == 'dev') cfg.mode = 'development'
else if (params.mode == 'prod') cfg.mode = 'production'
else cfg.mode = params.mode

/**
 * パス生成
 */
const _src = {
  scss: path.join(path.dirname(__dirname), cfg.rootDir, cfg.srcDir, cfg.scssDir),
  esm : path.join(path.dirname(__dirname), cfg.rootDir, cfg.srcDir, cfg.esmDir),
}
const _files = {
  scss: path.join(_src.scss, cfg.scssFiles),
  esm : path.join(_src.esm, cfg.esmFiles),
}
const _dist = {
  css: path.join(path.dirname(__dirname), cfg.rootDir, cfg.distDir, cfg.cssDist),
  js : path.join(path.dirname(__dirname), cfg.rootDir, cfg.distDir, cfg.jsDist),
}
const _map = {
  scss: `./${path.relative(_dist.css, _src.scss)}`,
  esm : `./${path.relative(_dist.js, _src.esm)}`,
}
const _reload = [
  // path.join(path.dirname(__dirname), cfg.rootDir, '**/*.html'),
  // path.join(path.dirname(__dirname), cfg.rootDir, '**/*.php'),
  path.join(_dist.js, '**/*.js'),
]

/**
 * SCSS
 */
// mode: production
const ScssProd = () =>
  gulp.src(_files.scss)
    .pipe(plumber({ errorHandler: notify.onError('Error: <%= error.message %>') }))
    .pipe(sass.sync({
      outputStyle: 'compressed',
    }))
    .pipe(postcss([
      autoprefixer({ cascade: false }),
      normcharset(),
      mergerules(),
      smqueries()
    ]))
    .pipe(gulp.dest(_dist.css))
    .pipe(bsync.stream())

// mode: development
const ScssDev = () =>
  gulp.src(_files.scss)
    .pipe(plumber({ errorHandler: notify.onError('Error: <%= error.message %>') }))
    .pipe(sourcemaps.init())
    .pipe(sass.sync({
      outputStyle: 'compressed',
    }))
    .pipe(postcss([
      autoprefixer(),
      normcharset(),
      mergerules(),
      smqueries()
    ]))
    .pipe(sourcemaps.write(cfg.cssMap, {
      includeContent: false,
      sourceRoot: _map.scss,
    }))
    .pipe(gulp.dest(_dist.css))
    .pipe(bsync.stream())

/**
 * esbuild
 */
const EsBuild = done => {
  const lastRunResult = gulp.lastRun(EsBuild)
  const es = (_file) => {
    gulp.src(path.join(_src.esm, _file.basename + _file.extname))
      .pipe(plumber({ errorHandler: notify.onError('Error: <%= error.message %>') }))
      .pipe(esbuild({
        bundle: true,
        sourcemap: cfg.mode == 'production' ? false : true,
        minify: cfg.mode == 'production' ? true : false,
      }))
      .pipe(gulp.dest(_dist.js))
  }
  gulp.src(_files.esm, { since: lastRunResult })
    .pipe(rename(es))
    .pipe(bsync.stream())
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
    online: true,
    notify: true,
    ui: false,
    https: {
      cert: path.resolve(cfg.cert),
      key: path.resolve(cfg.key)
    }
  })
  done()
}

/**
 * ウォッチ
 */
const WatchProd = done => {
  gulp.watch(_files.scss, ScssProd)
  gulp.watch(_files.esm, EsBuild)
  done()
}
const WatchDev = done => {
  gulp.watch(_files.scss, ScssDev)
  gulp.watch(_files.esm, EsBuild)
  done()
}

/**
 * タスク登録
 */
// default
exports.default = cfg.mode == 'production'
  ? gulp.series(WatchProd, BrowserSync)
  : gulp.series(WatchDev, BrowserSync)
// build
exports.build = cfg.mode == 'production'
  ? gulp.parallel(ScssProd, EsBuild)
  : gulp.parallel(ScssDev, EsBuild)
// watch
exports.watch = cfg.mode == 'production'
  ? gulp.series(WatchProd)
  : gulp.series(WatchDev)
