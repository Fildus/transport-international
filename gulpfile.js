let gulp = require('gulp');
let sass = require('gulp-sass');
let concat = require('gulp-concat');
let minify = require('gulp-minify');
let cleanCSS = require('gulp-clean-css');
let uglifycss = require('gulp-uglifycss');
let plumber = require('gulp-plumber');
let image = require('gulp-image');
let livereload = require('gulp-livereload');

livereload({start: true});

gulp.task('sassFront', function () {
    livereload.reload();
    return gulp.src(['./assets/css/front/style.scss'])
        .pipe(sass().on('error', sass.logError))
        .pipe(cleanCSS({compatibility: 'edge'}))
        .pipe(uglifycss({
            "maxLineLen": 80,
            "uglyComments": true
        }))
        .pipe(gulp.dest('./public/build/css/front'));
});

gulp.task('sassBack', function () {
    livereload.reload();
    return gulp.src(['./assets/css/back/style.scss'])
        .pipe(sass().on('error', sass.logError))
        .pipe(cleanCSS({compatibility: 'edge'}))
        .pipe(uglifycss({
            "maxLineLen": 80,
            "uglyComments": true
        }))
        .pipe(gulp.dest('./public/build/css/back'));
});

gulp.task('sassBootstrap', function () {
    livereload.reload();
    return gulp.src(['./assets/css/bootstrap.min.css'])
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

gulp.task('imageFront', function () {
    livereload.reload();
    gulp.src('./assets/img/front/**/*')
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
        .pipe(gulp.dest('./public/build/img/front'));
});

gulp.task('imageBack', function () {
    livereload.reload();
    gulp.src('./assets/img/back/**/*')
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
        .pipe(gulp.dest('./public/build/img/back'));
});

gulp.task('default', function () {
    gulp.run(['sassFront', 'sassBack', 'scriptsCompact', 'scriptsScattered', 'imageFront', 'imageBack', 'sassBootstrap'])
});

gulp.task('watch', function () {
    gulp.watch('./assets/css/**/*', ['sassFront', 'sassBack']);
    gulp.watch('./assets/img/front/**/*', ['imageFront']);
    gulp.watch('./assets/img/back/**/*', ['imageBack']);
    gulp.watch('./assets/js/compact/**/*', ['scriptsCompact']);
    gulp.watch('./assets/js/scattered/**/*', ['scriptsScattered']);
});