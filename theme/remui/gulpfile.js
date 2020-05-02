// Gulp
var gulp = require('gulp');

// Sass/CSS stuff
var sass = require('gulp-sass');
var concat = require('gulp-concat');
var prefix = require('gulp-autoprefixer');
var minifycss = require('gulp-minify-css');
var exec  = require('gulp-exec');
var notify = require("gulp-notify");
var rename = require('gulp-rename');
const babel = require('gulp-babel');
const sourcemaps = require('gulp-sourcemaps');

// JS stuff
const minify = require('gulp-minify');

var jssrc = './amd/src/*.js';

function copyFiles(src, dest)
{
    return gulp.src(src)
    .pipe(gulp.dest(dest))
}

gulp.task('styles', function() {
    return gulp.src('scss/preset/default.scss')
    .pipe(sass({
        outputStyle: 'compressed'
    }))
    .pipe(rename('remui-min.css'))
    .pipe(gulp.dest('./style/'));
});

gulp.task('compress', function(done) {
    gulp.src(jssrc)
    .pipe(sourcemaps.init())
    .pipe(babel({ presets: [["@babel/preset-env"]] }))
    .pipe(minify({
        ext:{
           min:'.min.js'
        },
        noSource: true,
        ignoreFiles: []
    }))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('./amd/build'));
    // Task code here.
    done();
});

// gulp.task('copyRequired', gulp.series('copyvendor'));

gulp.task('purge', gulp.series(function() {
    return gulp.src('.')
    .pipe(exec('php '+__dirname+'/../../admin/cli/purge_caches.php'))
    .pipe(notify('Purged All'))
}));

gulp.task('purgejs', gulp.series(function() {
    return gulp.src('.')
    .pipe(exec('php '+__dirname+'/../../admin/cli/purge_caches.php --js=true'))
    .pipe(notify('Purged JS'))
}));

gulp.task('purgelang', gulp.series(function() {
    return gulp.src('.')
    .pipe(exec('php '+__dirname+'/../../admin/cli/purge_caches.php --lang=true'))
    .pipe(notify('Purged Language Packs'))
}));


gulp.task('dist-remuicss', gulp.series('styles', 'purge'));

gulp.task('watch', function(done) {
    var watcher = gulp.watch('./amd/src/*.js', gulp.series('compress', 'purgejs'));
    watcher.on('change', function(obj){
        jssrc = obj;
        return gulp.series('compress');
    });
    gulp.watch([
        './scss/**/*.scss',
        './scss/**/*.css'
    ], gulp.series('styles', 'purge'));
    gulp.watch([
        './scss/**/*.scss',
        './scss/**/*.css',
        './lang/**/*.php',
        './templates/**/*'
    ], gulp.series('purge'));
    gulp.watch('./amd/build/*.js', gulp.series('purgejs'));
});

gulp.task('default', gulp.series('styles', 'compress', 'purge', 'watch'));
