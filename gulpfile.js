//
// Paths
//
var source = 'src/DSXI/Assets/',
    assets = 'web/assets/',
    production = false,

    // append on actions as required
    files =
    [
        // Main Site
        {
            type: 'sass',
            task: 'main-scss',
            watch: 'scss/main/**/*.scss',
            process: 'scss/main/main.scss',
            saveas: 'main.min.css',
        },{
            type: 'js',
            task: 'main-js',
            watch: 'js/main/*.js',
            process: 'js/main/*.js',
            saveas: 'main.min.js',
        },
    ];


// --------------------------------------------------------------------------------
//
// DO NOT CHANGE ANYTHING BELOW HERE UNLESS YOU MUST!!
// - Josh
//
// --------------------------------------------------------------------------------

//
// Plugins
//
var gulp = require('gulp'),
    rename = require('gulp-rename'),
    filesize = require('gulp-filesize'),
    watch = require('gulp-watch'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify'),
    sass = require('gulp-ruby-sass'),
    csscomb = require('gulp-csscomb'),
    autoprefixer = require('gulp-autoprefixer'),
    minifycss = require('gulp-minify-css'),
    babel = require('gulp-babel'),
    util = require('gulp-util'),
    gcmq = require('gulp-group-css-media-queries'),
    color = require('gulp-color'),
    params = require('yargs').argv,
    package = require('./package.json');

//
// Compile SASS
//
function compileSASS(input, filename, options)
{
    input = source + input;
    util.log('Processing: ', color(input, 'YELLOW'), '-->', color(assets + filename, 'YELLOW'));

    var chain = sass(input)
        .on('error', util.log)
        .pipe(csscomb())
        .pipe(autoprefixer({
            browsers: ['last 2 versions', 'Explorer 9'],
        }))
        .on('error', util.log)
        .pipe(filesize())
        .pipe(gcmq())
        .pipe(minifycss({
            debug: true,
            keepSpecialComments: 0
        }))
        .on('error', util.log)
        .pipe(rename(filename))
        .pipe(filesize())
        .pipe(gulp.dest(assets))
        .on('finish', function() {
            util.log('Saved to:', color(assets + filename, 'GREEN'))
        });

    return chain;
}

//
// Compile JavaScript
//
function compileJS(input, filename, options)
{
    input = source + input;
    util.log('Processing: ', color(input, 'YELLOW'), '-->', color(assets + filename, 'YELLOW'));

    var chain = gulp.src(input)
        .pipe(concat(input))
        .on('error', util.log);

    // options passed to the compile
    if (options) {
        // if to babel
        if (typeof options.babel !== 'undefined') {
            chain.pipe(babel())
        }

        if (production && typeof options.minify !== 'undefined') {
            chain.pipe(uglify());
        }
    }

    chain.pipe(rename(filename))
        .pipe(gulp.dest(assets))
        .pipe(filesize())
        .on('finish', function() {
            util.log('Saved to:', color(assets + filename, 'GREEN'))
        });

    return chain;
}

//
// Some basic info
//
util.log('');
util.log(color('[ START ]', 'RED'));
util.log('Source:', color(source, 'YELLOW'));
util.log('Assets:', color(assets, 'YELLOW'));

if (params.env == 'production') {
    util.log('Running for production environment');
    production = true;
} else {
    util.log('Running for dev environment');
}

//
// Generate tasks
//
util.log('');
util.log(color('[ CREATE WATCH TASKS ]', 'RED'));
for(var i in files)
{
    (function(i) {
        var file = files[i];
        util.log('Create Task:', color(file.task, 'YELLOW'), 'watches:', color(file.process, 'GREEN'));

        gulp.task(file.task, function()
        {
            util.log('');

            if (file.type == 'sass') {
                return compileSASS(file.process, file.saveas);
            }

            if (file.type == 'js') {
                return compileJS(file.process, file.saveas, {
                    babel: true,
                    minify: true,
                });
            }
        });
    })(i);
}

//
// Default watch task
//
gulp.task('default', function()
{
    util.log('');
    util.log(color('[ CREATE DEFAULT TASK ]', 'RED'));

    for(var i in files)
    {
        (function(i) {
            var file = files[i];
            util.log('Watch File:', color(file.watch, 'YELLOW'), '-->', color(file.task, 'GREEN'));

            gulp.watch(source + file.watch, [file.task]);
        })(i);
    }
});

//
// Dist task does all task
//
gulp.task('dist', function()
{
    util.log('');
    util.log(color('[ CREATE DIST TASK ]', 'RED'));

    for(var i in files)
    {
        (function(i) {
            var file = files[i];
            util.log('Default Task:', color(file.task, 'YELLOW'));

            gulp.start(file.task);
        })(i);
    }
});
