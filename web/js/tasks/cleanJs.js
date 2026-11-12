const gulp = require('gulp');
const gp = require('gulp-load-plugins')();
const fs = require('fs');
const crypto = require('crypto');
const es = require('event-stream');
const path = require('path');
const { deleteAsync } = require('del');

const uglify = require('gulp-uglify');
const sourcemaps = require('gulp-sourcemaps');

// module.exports = function(options) {
	// return function() {
		// //const deletedFilePaths = await deleteAsync(['./' + options.dst + '/*.js']);
		// //const deletedDirectoryPaths = await deleteAsync(['temp', 'public']);

		// //deleteSync('./' + options.dst + '/*.js', {dot: true});
		// async function () {
			// await deleteAsync([
				// './' + options.dst + '/*.js'
			// ], { force: true });
		// };
    // };

// };

async function cleanJs(options) {
  const { version } = JSON.parse(fs.readFileSync('package.json', 'utf8'));
  console.log(version);
  await deleteAsync([
				'./' + options.dst + '/*.js'
			], { force: true });
}

exports.default = cleanJs;