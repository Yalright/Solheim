const gulp = require("gulp");
const sass = require("gulp-sass");
const concat = require("gulp-concat");
const browserSync = require("browser-sync").create();
const uglify = require("gulp-uglify-es").default;
const sourcemaps = require("gulp-sourcemaps");
const gulpif = require("gulp-if");
const autoprefixer = require("gulp-autoprefixer");
const args = require("yargs").argv;

var fs = require("fs");
var json = JSON.parse(fs.readFileSync("./package.json"));
var gulppath = JSON.parse(fs.readFileSync("./gulppath.json"));

var paths = {
  sourceDir: "src",
  outputDir: "assets",
};

var buildpath = paths["sourceDir"];
var outputpath = paths["outputDir"];
var bootstrappath = "node_modules/bootstrap";

// Compile SCSS
function compileSASS() {
  return gulp
    .src([
      buildpath + "/scss/main.scss",
      buildpath + "/scss/main-admin.scss",
      buildpath + "/scss/editor-style.scss",
    ])
    .pipe(gulpif(args.env != "production", sourcemaps.init()))
    .pipe(sass({ outputStyle: "compressed" }))
    .pipe(autoprefixer())
    .pipe(gulpif(args.env != "production", sourcemaps.write()))
    .pipe(gulp.dest(outputpath + "/css/"))
    .pipe(browserSync.stream());
}

// Compile JS
function compileJS() {
  return gulp
    .src([buildpath + "/js/**/*.js"])
    .pipe(concat("scripts.js"))
    .pipe(gulpif(args.env === "production", uglify()))
    .pipe(gulp.dest(outputpath + "/js/"));
}

// Compile Init JS
function compileInitJS() {
  return gulp
    .src([buildpath + "/init/**/*.js"])
    .pipe(concat("init.js"))
    .pipe(gulpif(args.env === "production", uglify()))
    .pipe(gulp.dest(outputpath + "/js/"));
}

// Process Images
function imageProcess() {
  return gulp
    .src(buildpath + "/images/**/*")
    .pipe(gulp.dest(outputpath + "/images"));
}

// Process Fonts
function fontProcess() {
  return gulp
    .src(buildpath + "/fonts/**/*")
    .pipe(gulp.dest(outputpath + "/fonts"));
}

// Watch Task
gulp.task("watch", function () {
  gulp.watch([
    buildpath + "/scss/**/*.scss",
    "acf-blocks/**/*.scss",
    buildpath + "/js/**/*.js",
    "acf-blocks/**/*.php",
  ], { interval: 1000 }, compile);
});

// Watch + Reload Task
gulp.task("watch-reload", function () {
  // Initialize BrowserSync
  browserSync.init({
    proxy: gulppath.proxy,
  });

  // Watch PHP changes
  gulp.watch([
    "acf-blocks/**/*.php", 
    "partials/*.php",
    "search-filter/*.php",
    "functions/*.php",
    "*.php",
  ], { interval: 1000 }).on("change", browserSync.reload);

  // Watch SCSS changes
  gulp.watch([
    buildpath + "/scss/**/*.scss",
    "acf-blocks/**/*.scss",
  ], { interval: 1000 }, exportSASS);

  // Watch JS changes
  gulp.watch([
    buildpath + "/js/**/*.js",
  ], { interval: 1000 }, exportJS).on("done", browserSync.reload);

  // Watch image changes
  gulp.watch([
    buildpath + "/images/**/*.{png,gif,jpg,svg}",
  ], { interval: 1000 }, imageProcess);
});

// Task series and exports
const compile = gulp.series(
  compileJS,
  compileInitJS,
  compileSASS,
  gulp.parallel(imageProcess, fontProcess)
);

const exportSASS = gulp.series(compileSASS);
const exportJS = gulp.series(compileJS, compileInitJS);

// Exports
exports.compile = compile;
exports.exportSASS = exportSASS;
exports.exportJS = exportJS;
