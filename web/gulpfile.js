var gulp = require('gulp');
var cssScss = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var cleanCSS = require('gulp-clean-css');
var autoprefixer = require('gulp-autoprefixer');


gulp.task('scssConcat', () => {
    return gulp.src('scss/style.scss')
        .pipe(sourcemaps.init())
            .pipe(cssScss().on('error', cssScss.logError))
            .pipe(cleanCSS())
    	.pipe(autoprefixer({
            browsers: ['last 5 versions'],
            cascade: false
        }))
        .pipe(sourcemaps.write('./scss'))
        .pipe(gulp.dest('./'));
});



gulp.task('watch', function() {
    gulp.watch(['scss/*/*.scss'], gulp.series([
        'scssConcat',
    ]));
})

