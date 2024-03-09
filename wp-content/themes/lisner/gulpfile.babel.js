/*
 |--------------------------------------------------------------------------
 | Gulpfile Asset Management
 |--------------------------------------------------------------------------
 |
 */
'use strict';

// Load plugins
var gulp = require('gulp'),
    plumber = require('gulp-plumber'),
    gutil = require('gulp-util'),
    stylus = require('gulp-stylus'),
    minifycss = require('gulp-clean-css'),
    rename = require('gulp-rename'),
    livereload = require('gulp-livereload'),
    lr = require('tiny-lr'),
    server = lr(),
    notify = require('gulp-notify'),
    rucksack = require('rucksack-css'),
    sourcemaps = require('gulp-sourcemaps'),
    autoprefixer = require('autoprefixer'),
    zip = require('gulp-zip'),
    extract = require('gulp-style-extract');

import postcss from 'gulp-postcss';
import postcssPresetEnv from 'postcss-preset-env';
// Styles
gulp.task('styles', function () {
    return gulp.src('assets/styles/stylus/*.styl')
        .pipe(plumber(function (error) {
            gutil.log(gutil.colors.red(error.message));
            this.emit('end');
        }))
        .pipe(stylus())
        .pipe(postcss([rucksack()]))
        .pipe(postcss([
            postcssPresetEnv(/* options */)
        ]))
        .pipe(sourcemaps.init())
        .pipe(postcss([autoprefixer()]))
        .pipe(gulp.dest('./'))
        .pipe(rename({suffix: '.min'}))
        .pipe(minifycss())
        .pipe(sourcemaps.write('.'))
        .pipe(livereload(server))
        .pipe(gulp.dest('./'))
    //.pipe(notify({message: 'Styles task complete'}))
});

gulp.task('default', gulp.series(['styles']));

// Watch
gulp.task('watch', function () {
    livereload.listen();

    // Watch .scss files
    gulp.watch('assets/styles/stylus/**/*.styl', gulp.series(['styles'])).on('change', function (file) {
        gutil.log(gutil.colors.green('Styles changed successfully' + ' (' + file.path + ')'));
    });

});

// prepare and zip required plugins
gulp.task('zip-plugins', function () {
    gulp.src('./../../plugins/{lisner-core,lisner-core/**}')
        .pipe(zip('lisner-core.zip'))
        .pipe(gulp.dest('./lib'));
    gulp.src('./../../plugins/{pebas-paid-listings,pebas-paid-listings/**}')
        .pipe(zip('pebas-paid-listings.zip'))
        .pipe(gulp.dest('./lib'));
    gulp.src('./../../plugins/{pebas-claim-listings,pebas-claim-listings/**}')
        .pipe(zip('pebas-claim-listings.zip'))
        .pipe(gulp.dest('./lib'));
    gulp.src('./../../plugins/{pebas-mega-menu,pebas-mega-menu/**}')
        .pipe(zip('pebas-mega-menu.zip'))
        .pipe(gulp.dest('./lib'));
    gulp.src('./../../plugins/{pebas-report-listings,pebas-report-listings/**}')
        .pipe(zip('pebas-report-listings.zip'))
        .pipe(gulp.dest('./lib'));
    gulp.src('./../../plugins/{pebas-review-listings,pebas-review-listings/**}')
        .pipe(zip('pebas-review-listings.zip'))
        .pipe(gulp.dest('./lib'));
    gulp.src('./../../plugins/{pebas-bookmark-listings,pebas-bookmark-listings/**}')
        .pipe(zip('pebas-bookmark-listings.zip'))
        .pipe(gulp.dest('./lib'));
    gulp.src('./../../plugins/{pebas-listing-coupons,pebas-listing-coupons/**}')
        .pipe(zip('pebas-listing-coupons.zip'))
        .pipe(gulp.dest('./lib'));
    gulp.src('./../../plugins/{pebas-listing-events,pebas-listing-events/**}')
        .pipe(zip('pebas-listing-events.zip'))
        .pipe(gulp.dest('./lib'));
    gulp.src('./../../plugins/{pebas-bookings-extension,pebas-bookings-extension/**}')
        .pipe(zip('pebas-bookings-extension.zip'))
        .pipe(gulp.dest('./lib'));
    gulp.src('./../../plugins/{lisner-listings-import-add-on,lisner-listings-import-add-on/**}')
        .pipe(zip('lisner-listings-import-add-on.zip'))
        .pipe(gulp.dest('./lib'));
    return gulp.src('./../../plugins/{js_composer,js_composer/**}')
        .pipe(zip('js_composer.zip'))
        .pipe(gulp.dest('./lib'))
        .pipe(notify({message: 'The plugins are zipped'}));
});

// prepare theme for themeforest submission
gulp.task('zip-theme', function () {
    return gulp.src(['./../{lisner,lisner/**}', '!./{node_modules,node_modules/**}'])
        .pipe(zip('lisner.zip'))
        .pipe(gulp.dest('./'))
        .pipe(notify({message: 'Theme has been zipped'}));
});
gulp.task('themeforest', gulp.series(['styles', 'zip-plugins', 'zip-theme']));
