var gulp = require('gulp');
var cssmin = require('gulp-cssmin');
var rename = require('gulp-rename');

gulp.task('hello', function() {
    console.log('First Task');
})

function defaultTask(cb) {
    // place code for your default task here
    cb();
}

exports.default = defaultTask