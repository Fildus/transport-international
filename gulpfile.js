let gulp = require('gulp');
let sass = require('gulp-sass');
let concat = require('gulp-concat');
let minify = require('gulp-minify');
let cleanCSS = require('gulp-clean-css');
let uglifycss = require('gulp-uglifycss');
let plumber = require('gulp-plumber');
let image = require('gulp-image');
let clean = require('gulp-clean');
let livereload = require('gulp-livereload');

livereload({start: true});

gulp.task('sass', function () {
    livereload.reload();
    return gulp.src(['./assets/css/*.scss', './assets/css/*.css'])
        .pipe(sass().on('error', sass.logError))
        .pipe(cleanCSS({compatibility: 'edge'}))
        .pipe(uglifycss({
            "maxLineLen": 80,
            "uglyComments": true
        }))
        .pipe(gulp.dest('./public/build/css'));
});

gulp.task('scriptsCompact', function () {
    livereload.reload();
    return gulp.src('./assets/js/compact/**/*')
        .pipe(plumber())
        .pipe(concat('compact.js'))
        .pipe(minify())
        .pipe(gulp.dest('./public/build/js'));
});

gulp.task('scriptsScattered', function () {
    livereload.reload();
    return gulp.src('./assets/js/scattered/**/*')
        .pipe(plumber())
        .pipe(minify())
        .pipe(gulp.dest('./public/build/js'));
});

gulp.task('image', function () {
    livereload.reload();
    gulp.src('./assets/img/**/*')
        .pipe(image({
            pngquant: true,
            optipng: true,
            zopflipng: true,
            jpegRecompress: false,
            mozjpeg: true,
            guetzli: false,
            gifsicle: true,
            svgo: true,
            concurrent: 10,
            quiet: false //true
        }))
        .pipe(gulp.dest('./public/build/img'));
});

gulp.task('clean', function () {
    return gulp.src('./public/build/**/*', {read: false})
        .pipe(clean());
});

gulp.task('default', function () {
    gulp.run(['sass', 'scriptsCompact', 'scriptsScattered', 'image'])
});

gulp.task('watch', function () {
    gulp.watch('./assets/css/**/*', ['sass']);
    gulp.watch('./assets/img/**/*', ['image']);
    gulp.watch('./assets/js/compact/**/*', ['scriptsCompact']);
    gulp.watch('./assets/js/scattered/**/*', ['scriptsScattered']);
});

