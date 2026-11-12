const gulp = require('gulp');
const gp = require('gulp-load-plugins')();
const fs = require('fs');
const crypto = require('crypto');
const es = require('event-stream');
const path = require('path');


const uglify = require('gulp-uglify');
const sourcemaps = require('gulp-sourcemaps');

module.exports = function(options) {
	return function() {
		var versions = {};
		return gulp.src('./' + options.src + '/*.js')
		  .pipe(es.map(function(file, cb) {
			var version = crypto.createHash('md5').update(file.contents).digest('hex').substring(0, 8);
			var name = path.basename(file.path).slice(0, -3);
	//        if (process.env.NODE_ENV == 'production') {
			  file.path = file.path.replace(/\.js$/, '.' + version + '.js');
			  versions[name] = options.dst + '/' + path.basename(file.path);
	//        } else {
	//          versions[name] = options.publicDst + path.basename(file.path) + '?' + version;
	//        }
			console.log(versions);
			cb(null, file);
		  }))
		.pipe(uglify())
		.pipe(gulp.dest('./' + options.dst))
		.on('end', function() {
        console.log(options.manifest, versions);
        fs.writeFileSync('./' + options.manifest, JSON.stringify(versions));
      })
    };

};

