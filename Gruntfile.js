module.exports = function(grunt) {
    // Project configuration.
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        less: {
            default: {
                files: {
                    'symfony/web/css/compiled/bootstrap.css': ['less/bootstrap.less']
                },
                options: {
                    compress: false,
                    // LESS source maps
                    sourceMap: true,
                    sourceMapFilename: 'symfony/web/css/compiled/bootstrap.css.map',
                    sourceMapURL: '/css/compiled/bootstrap.css.map',
                    sourceMapBasepath: 'symfony/web/css/compiled'
                }
            }
        },
        cssmin: {
            options: {
                report: 'gzip'
            },
            target: {
                files: [{
                    expand: true,
                    cwd: 'symfony/web/css/compiled',
                    src: ['*.css', '!*.min.css'],
                    dest: 'symfony/web/css/compiled',
                    ext: '.min.css'
                }]
            }
        },
        watch: {
            options: {
                livereload: true
            },
            less: {
                files: ['less/*.less'],
                tasks: ['less', 'cssmin'],
            }
        }
    });

    // Load the plugin that provides the "less" task.
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-watch');

    // Default task(s).
    grunt.registerTask('default', ['less', 'cssmin', 'watch']);
    grunt.registerTask('build', ['less', 'cssmin']);

};
