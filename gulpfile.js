const gulp = require('gulp');
const path = require('path');
const { deleteAsync } = require('del');


var requireDir = require('require-dir');
requireDir('./web/js/tasks');
const config = require('./web/js/config');

process.on('uncaughtException', function(err) {
  console.log(err.message, err.stack);
  process.exit(255);
});
function lazyRequireTask(path) {
    var args = [].slice.call(arguments, 1);
    return function(callback) {
        var task = require(path).apply(this, args);
        return task(callback);
    };
} 

gulp.task('minify-js-budget', lazyRequireTask('./web/js/tasks/minifyJs', {
    src:  config.projectJsBudget,
    dst: config.projectJsBudget + '/dist',
    publicDst: config.projectRoot + '/js/dist',  // from browser point of view
    manifest: path.join(config.projectJsBudget, 'scripts.json'),
    projectRoot: config.projectRoot
}));

gulp.task('minify-js-outlay', lazyRequireTask('./web/js/tasks/minifyJs', {
    src:  config.projectJsOutlay,
    dst: config.projectJsOutlay + '/dist',
    publicDst: config.projectJsOutlay + '/js/dist',  // from browser point of view
    manifest: path.join(config.projectJsOutlay, 'scripts.json'),
    projectRoot: config.projectRoot
}));

gulp.task('minify-js-template', lazyRequireTask('./web/js/tasks/minifyJs', {
    src:  config.projectJsTemplate,
    dst: config.projectJsTemplate + '/dist',
    publicDst: config.projectJsTemplate + '/js/dist',  // from browser point of view
    manifest: path.join(config.projectJsTemplate, 'scripts.json'),
    projectRoot: config.projectRoot
}));

gulp.task('minify-js-tag', lazyRequireTask('./web/js/tasks/minifyJs', {
    src:  config.projectJsTag,
    dst: config.projectJsTag + '/dist',
    publicDst: config.projectJsTag + '/js/dist',  // from browser point of view
    manifest: path.join(config.projectJsTag, 'scripts.json'),
    projectRoot: config.projectRoot
}));

gulp.task('cleanbudget:js', async function () {
  await deleteAsync([
      config.projectJsBudget + '/dist' +'/*'
    ], { force: true });
});

gulp.task('cleanoutlay:js', async function () {
  await deleteAsync([
      config.projectJsOutlay + '/dist' +'/*'
    ], { force: true });
});



gulp.task('test', function(cb) {
    console.log('test');
	cb();
//    console.log(config.projectCss);
});
//gulp --tasks
//galp [task]



