var gulp = require('gulp');
var sass = require('gulp-sass');
var cleanCSS = require('gulp-clean-css');
var header = require('gulp-header');
var fs = require('fs');

var pkg = JSON.parse(fs.readFileSync('./package.json'));

var cssbanner = ['/*',
  'Theme Name:          <%= pkg.themename %>',
  'Description:         <%= pkg.description %>',
  'Author:              <%= pkg.author %>',
  'Version:             <%= pkg.version %>',
  'Theme URI:           <%= pkg.homepage %>',
  'GitHub Theme URI:    <%= pkg.homepage %>',
  'License:             <%= pkg.license %>',
  'License URI:         <%= pkg.licenseuri %>',
  'Template:            generatepress',
  '*/',
  ''].join('\n');

// Compile Our Sass
gulp.task('sass', function() {
    return gulp.src('scss/style.scss')
        .pipe(sass({includePaths:['./scss']}))
        .pipe(gulp.dest('css'))
        .pipe(cleanCSS())
		.pipe(header(cssbanner, { pkg : pkg } ))
		.pipe(gulp.dest('./'));
});

// Watch Files For Changes
gulp.task('watch', function() {
    gulp.watch('scss/**/*.scss', ['css']);
    gulp.watch('package.json', ['css'])
});

// Default Task
gulp.task('default', ['css', 'watch']);