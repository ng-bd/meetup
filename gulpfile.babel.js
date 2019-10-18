import gulp from 'gulp';
import autoprefixer from 'gulp-autoprefixer';
import babel from 'gulp-babel';
import concat from 'gulp-concat';
import csscomb from 'gulp-csscomb';
import csso from 'gulp-csso';
import header from 'gulp-header';
import htmlmin from 'gulp-htmlmin';
import plumber from 'gulp-plumber';
import sass from 'gulp-sass';
import sourcemaps from 'gulp-sourcemaps';
import uglify from 'gulp-uglify';
import del from 'del';
import browserSync from 'browser-sync';
var reload = browserSync.reload;

var mode = require('gulp-mode')();

sass.compiler = require('node-sass');

var pkg = require('./package.json');
var banner = [
  '/**',
  ' * Zafree v<%= pkg.version %> - <%= pkg.description %>',
  ' * @link <%= pkg.homepage %>',
  ' * @copyright 2019-<%= new Date().getFullYear() %> <%= pkg.author %>',
  ' * @license <%= pkg.license %>',
  ' */',
  ''
].join('\n');

var helpers = [ 
  'build/js/smooth-scroll.polyfills.min.js',
  'build/js/impetus.js',
  'build/js/init.js'
];

var _dirProduction = 'HTML';

const paths = {
  scripts: {
    src: helpers,
    dest: 'assets/scripts/',
    destProd: _dirProduction+'/assets/scripts/'
  },
  styles: {
    src: 'build/sass/main.sass',
    dest: 'assets/styles/',
    watch: 'build/sass/**/*.sass',
    destProd: _dirProduction+'/assets/styles/'
  },
  fonts: {
    src: 'assets/fonts/**/*',
    destProd: _dirProduction+'/assets/fonts'
  },
  images: {
    src: 'assets/images/**/*',
    destProd: _dirProduction+'/assets/images'
  },
  icons: {
    src: 'assets/icons/**/*',
    destProd: _dirProduction+'/assets/icons'
  }
};
 
/*
 * For small tasks you can export arrow functions
 */
// export const clean = () => del([ paths.styles.dest, paths.scripts.dest, paths.fonts.destProd, _dirProduction ]);
export const clean = () => del([ paths.styles.dest, _dirProduction ]);

/*
 * You can also declare named functions and export them as tasks
 */
export function scripts() {
  return gulp.src(paths.scripts.src, { sourcemaps: true })
    .pipe(babel({
      presets: ['@babel/env']
    }))
    .pipe(concat('main.js'))
    .pipe(header(banner, { pkg : pkg } )) 
    .pipe(gulp.dest(paths.scripts.dest))
    .pipe(mode.production(uglify()))
    .pipe(header(banner, { pkg : pkg } )) 
    .pipe(mode.production(gulp.dest(paths.scripts.destProd)))
    .pipe(browserSync.stream()); 
}

export function styles() { 
  return gulp.src(paths.styles.src) 
    .pipe(mode.development(sourcemaps.init())) 
    .pipe(sass()) 
    .pipe(plumber()) 
    .pipe(autoprefixer({ 
      cascade: false 
    })) 
    .pipe(csscomb()) 
    .pipe(header(banner, { pkg : pkg } )) 
    .pipe(mode.development(sourcemaps.write('.'))) 
    .pipe(gulp.dest(paths.styles.dest)) 
    .pipe(mode.production(csso())) 
    .pipe(header(banner, { pkg : pkg } )) 
    .pipe(mode.production(gulp.dest(paths.styles.destProd)))
    .pipe(browserSync.stream()); 
} 

export function fonts() {
  return gulp.src(paths.fonts.src)
    .pipe(gulp.dest(paths.fonts.destProd));
}

export function images() {
  return gulp.src(paths.images.src)
    .pipe(gulp.dest(paths.images.destProd));
}

export function icons() {
  return gulp.src(paths.icons.src)
    .pipe(gulp.dest(paths.icons.destProd));
}
 
 /*
  * You could even use `export as` to rename exported tasks
  */
function watchFiles() {
  gulp.watch(paths.styles.watch, styles);
  gulp.watch(paths.scripts.src, scripts);
  gulp.watch('*.html').on("change", reload);
}
export { watchFiles as watch };

 /*
  * Let's create a server
  */
export function serve() {
  browserSync.init({
    server: {
        baseDir: "."
    },
    notify: false
  });
}
 
/*
 * You can still use `gulp.task`
 * for example to set task names that would otherwise be invalid
 */
const run = gulp.series(clean, gulp.parallel(scripts, styles, serve, watchFiles));
gulp.task('run', run);
 
/*
 * Export a default task
 */
export default run;
