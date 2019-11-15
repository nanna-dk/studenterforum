'use strict';
var gulp = require('gulp'),
  browserSync = require('browser-sync').create(),
  reload = browserSync.reload,
  plumber = require('gulp-plumber'),
  sass = require('gulp-sass'),
  concat = require('gulp-concat'),
  jshint = require("gulp-jshint"),
  uglify = require('gulp-uglify'),
  babel  = require('gulp-babel'),
  htmlmin = require('gulp-htmlmin'),
  rename = require("gulp-rename"),
  notify = require("gulp-notify"),
  autoprefixer = require("gulp-autoprefixer"),
  minifyJson = require('gulp-minify-inline-json'),
  pump = require('pump'),
  jsStylish = require('jshint-stylish'),
  svgmin = require('gulp-svgmin'),
  nunjucksRender = require('gulp-nunjucks-render'),
  // Get data from file
  pkg = require('./package.json');

  // Banner

// Sass compiling options:
var sassOptions = {
  errLogToConsole: true,
  outputStyle: 'compressed'
};

// project paths
var paths = {
  root: "./",
  dist: './dist',
  src: "./src",
  html: "./src/html/*.+(njk)",
  minCss: "./dist/css",
  minJs: "./dist/js",
  minImg: "./dist/img"
};

// List of ressource files to concatenate
var res = {
  bsJs: [
    'node_modules/jquery/dist/jquery.min.js',
    'node_modules/bootstrap/dist/js/bootstrap.min.js'
  ],
  customJs: [paths.src + '/js/custom.js'],
  cssSrc: [paths.src + '/scss/custom.scss'],
  minImg: paths.src + '/img/*.svg'
};

function serve(done) {
  browserSync.init({
    host: '127.0.0.1',
    port: 8080,
    open: 'external',
    proxy: '127.0.0.1:8080/personal-website/',
    index: 'index.php',
    online: true,
    notify: false
  });
  done();
}

function imgMin() {
  return gulp
    .src(res.minImg)
    .pipe(svgmin({
        plugins: [{
            cleanupIDs: false
        }, {
            removeUselessDefs: false
        }, {
            removeTitle: false
        }, {
            removeDimensions: false
        }, {
            removeViewBox: false
        }]
    }))
    .pipe(gulp.dest(paths.minImg));
}

// Compile styles
function styles() {
  return gulp
    .src(res.cssSrc)
    .pipe(plumber({errorHandler: notify.onError("Error: <%= error.message %>")}))
    .pipe(sass(sassOptions).on('error', sass.logError))
    .pipe(autoprefixer())
    .pipe(concat("style.min.css"))
    .pipe(gulp.dest(paths.minCss))
    .pipe(browserSync.stream());
}

// Lint scripts
function scriptsLint() {
  return gulp
    .src(res.customJs)
    .pipe(plumber())
    .pipe(jshint())
    .pipe(jshint.reporter(jsStylish));
  //.pipe(jshint.reporter('fail'));
}

// Transpile, concatenate and minify scripts
function scripts() {
  var arrays = res.bsJs.concat(res.customJs);
  return gulp
    .src(arrays, { sourcemaps: true })
    .pipe(plumber())
    .pipe(concat("script.min.js"))
    .pipe(babel())
    .pipe(uglify())
    .pipe(gulp.dest(paths.minJs, { sourcemaps: '.' }))
    .pipe(browserSync.stream());
}

function renderHtml() {
  return gulp
  .src(paths.html)
  .pipe(nunjucksRender({
    path: ['./src/html/templates/'],
    data: {
      description: pkg.description,
      author: pkg.author
    }
  }))
  .pipe(htmlmin({
    removeComments: true,
    collapseWhitespace: true,
    minifyJS: true
  }))
  .pipe(minifyJson({
    mimeTypes: ['application/ld+json']
  }))
  .pipe(rename({
    extname: ".php"
  }))
  .pipe(gulp.dest(paths.root))
  .pipe(browserSync.stream());
}

function watch() {
  gulp
    .watch(res.minImg, imgMin)
    .on("change", reload);
  gulp
    .watch(res.cssSrc, styles)
    .on("change", reload);
  gulp
    .watch(res.customJs, gulp.series(scriptsLint, scripts))
    .on("change", reload);
  gulp
    .watch(paths.html, renderHtml)
    .on("change", reload);
}

gulp.task("js", gulp.series(scriptsLint, scripts));

var build = gulp.parallel(styles, "js", renderHtml, imgMin);
gulp.task('default', gulp.series(serve, watch, build));
