/**
 * 設定
 */
const cfg = {
  mode     : 'production', // development または production

  rootDir  : '', // 対象のトップディレクトリ
  srcDir   : 'src', // ソースファイルのディレクトリ
  scssDir  : 'scss', // 対象の scss ディレクトリ
  scssFiles: '**/*.scss', // 対象の scss ファイル

  distDir  : '', // 出力対象のディレクトリ
  cssDist  : 'css', // css の出力先ディレクトリ
  mapDist  : '', // map の出力先ディレクトリ（css 内）
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
}
const _dist = {
  css: path.resolve(_root.dist, cfg.cssDist),
}
const mapDir = path.resolve(_root.dist, cfg.mapDist)
const _map = {
  scss: path.relative(path.resolve(_root.dist, cfg.cssDist), path.resolve(_root.src, cfg.scssDir)),
}

/**
 * モジュール
 */
const { src, dest, parallel } = require('gulp')
const cache        = require('gulp-cached')
const notify       = require('gulp-notify')
const plumber      = require('gulp-plumber')
const postcss      = require('gulp-postcss')
const progeny      = require('gulp-progeny')
const sass         = require('gulp-sass')(require('sass'))
const sourcemaps   = require('gulp-sourcemaps')
const autoprefixer = require('autoprefixer')
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
    .pipe(cache('scss'))
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
    .pipe(sourcemaps.write(cfg.mapDist, {
      includeContent: false,
      sourceRoot: _map.scss,
    }))
    .pipe(dest(_dist.css))

// mode: production
const ScssProd = () =>
  src(_src.scss)
    .pipe(plumber({ errorHandler: notify.onError('Error: <%= error.message %>') }))
    .pipe(cache('scss'))
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

exports.build = parallel(cfg.mode == 'production' ? ScssProd : ScssDev)
