const { src, dest, series, watch, parallel } = require('gulp'),
  sass = require('gulp-sass')(require('sass')),
  postcss = require('gulp-postcss'),
  autoprefixer = require('autoprefixer'),
  cssnano = require('cssnano'),
  plumber = require('gulp-plumber'),
  browserSync = require('browser-sync'),
  sourcemaps = require('gulp-sourcemaps'),
  svgSymbols = require('gulp-svg-symbols'),
  imagemin = require("gulp-imagemin"),
  rename = require("gulp-rename"),
  concat = require("gulp-concat"),
  gulpif = require("gulp-if"),
  del = require("del"),
  uglify = require('gulp-uglify'),
  yargs = require("yargs"),
  path = require("path");

const PRODUCTION = yargs.argv.prod;  
const server = browserSync.create();
const url = 'https://konoba-cards.local/';
const paths = {
  styles: {
    src: ["./src/scss/*.scss", "./src/scss/**/*.scss"],
    dest: "./dist/css/"
  },
  blockStyles: {
    src: "./acf-blocks/**/*.scss"
  },
  scripts: {
    src: ["./src/js/*.js", "!./src/js/vendor/*.js", "!./src/js/min/*.js","./acf-blocks/*/assets/js/*.js"],
    dest: "./dist/js/"
  },
  img: {
    src: ["./src/img/*"],
    dest: "./dist/img/"
  },
  svg: {
    src: "./src/svg-sprite/*.svg",
    dest: "./dist/img/"
  },
  php: {
    src: "**/*.php"
  }
};

/* STYLES */
function styles() {
  return src(paths.styles.src[0])
    .pipe(plumber())
    .pipe(gulpif(!PRODUCTION, sourcemaps.init()))
    .pipe(sass().on('error', sass.logError))
    .pipe(postcss([ autoprefixer("last 2 version"), cssnano({ zindex: false, reduceIdents: false }) ]))
    .pipe(gulpif(!PRODUCTION, sourcemaps.write('./')))
    .pipe(gulpif(PRODUCTION, rename("main.min.css")))
    .pipe(dest(paths.styles.dest))
    .pipe(server.stream());
}

function blockStyles() {
  return src(paths.blockStyles.src)
    .pipe(plumber())
    .pipe(sass().on('error', sass.logError))
    .pipe(postcss([ autoprefixer("last 2 version"), cssnano({ zindex: false }) ]))
    .pipe(rename(function (file) {
      file.dirname = file.dirname.replace('scss', 'css');
    }))
    .pipe(dest('./acf-blocks'))
    .pipe(server.stream());
}

/* SCRIPTS */
function scripts() {
  return src(paths.scripts.src)
  .pipe(gulpif(!PRODUCTION, sourcemaps.init()))
  .pipe(concat("app.js"))
  .pipe(gulpif(PRODUCTION, uglify()))
  .pipe(gulpif(PRODUCTION, rename("app.min.js")))
  .pipe(gulpif(!PRODUCTION, sourcemaps.write('./')))
  .pipe(dest(paths.scripts.dest))
  .pipe(server.stream());
}

/* IMAGES */
function images() {
  return src(paths.img.src)
  .pipe(imagemin())
	.pipe(dest(paths.img.dest));
}

/* SVG */
function svg() {
  return src(paths.svg.src)
  .pipe(imagemin())
  .pipe(
    svgSymbols({
      templates: ["default-svg"],
      svgAttrs: {
        width: 0,
        height: 0,
        display: "none"
      }
    })
  )
  .pipe(rename("sprite.svg.php"))
  .pipe(dest(paths.svg.dest));
}

/* COPY */
export const copy = () => {
  return src(['./src/**/*','!./src/{images,js,scss,svg-sprite}','!./src/{images,js,scss,svg-sprite}/**/*'])
    .pipe(dest('./dist'));
}

export const copyVendor = () => {
  return src('./src/js/vendor/*')
  .pipe(dest('./dist/js/vendor'))
}

/* CLEAN */
export const clean = () => {
  return del(['./dist/']);
}

/* TASKS */ 
function reload(done) {
  server.reload();
  done();
}

function start(done) {
  server.init({
    proxy: url,
    open: true,
    ghostMode: false
  });
  done();
}

function watchForChanges() {
  watch(paths.styles.src, styles);
  watch(paths.php.src, reload);
  watch(paths.scripts.src, scripts);
  watch(paths.svg.src, series(svg, reload));
  watch(paths.img.src, series(images, reload));
  watch(paths.blockStyles.src, blockStyles);
  watch(['./src/**/*','!./src/{img,js,scss}','!./src/{img,js,scss}/**/*','./acf-blocks/**/*'], series(copy, reload));
  watch('./src/js/vendor/*', series(copyVendor, reload));
  watch('./theme.json', reload);
}

// exports.default = series(start, watchForChanges);
export const dev = series(clean, parallel(styles, images, blockStyles, svg, scripts, copy, copyVendor), start, watchForChanges)
export const nowatch = series(clean, parallel(styles, images, blockStyles, svg, scripts, copy, copyVendor))
export const build = series(clean, parallel(styles, images, blockStyles, svg, scripts, copy, copyVendor))
export default dev;