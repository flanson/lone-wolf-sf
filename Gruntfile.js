'use strict';

module.exports = function (grunt) {

    // Load grunt tasks automatically
    require('load-grunt-tasks')(grunt);

    // Time how long tasks take. Can help when optimizing build times
    require('time-grunt')(grunt);

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        // Watches files for changes and runs tasks based on the changed files
        watch: {
            options: {
                debounceDelay: 1000
            },
            less: {
                //tasks: ['clean:css','less'],
                tasks: ['less'],
                files: ['app/Resources/public/app/css/**/*.less']
            },
            gruntfile: {
                files: ['Gruntfile.js']
            },
            jshint: {
                tasks: ['jshint'],
                files: ['app/Resources/public/app/js/**/*.js']
            //},
            //images: {
            //    tasks: ['newer:imagemin'],
            //    files: ['app/Resources/public/app/img/**/*.{png,jpg,jpeg,gif}']
            }
        },

        // Compile less files into css file
        less: {
            dist: {
                files: {
                    "app/Resources/public/app/css/main.css": "app/Resources/public/app/css/main.less"
                }
            }
        },

        // Make sure code styles are up to par and there are no obvious mistakes
        jshint: {
            options: {
                jshintrc: '.jshintrc',
                reporter: require('jshint-stylish')
            },
            all: {
                src: [
                    //'Gruntfile.js',
                    'app/Resources/public/app/js/*.js'
                ]
            }
        },

        // Make sure code styles are up to par and there are no obvious mistakes
        csslint: {
            options: {
                csslintrc: '.csslintrc'
            },
            strict: {
                options: {
                    import: 2
                },
                src: ['app/Resources/public/app/css/main.css']
            }
        },

        // Empties folders to start fresh
        clean: {
            web: {
                files: [{
                    src: [
                        'web/app/**/*',
                        'web/dist/**/*',
                        'web/bower_components/**/*'
                    ]
                }]
            },
            dist: {
                files: [{
                    src: [
                        'app/Resources/public/dist/**/*',
                        'app/Resources/views/dist'
                    ]
                }]
            },
            temp: {
                files: [{
                    dot: true,
                    src: ['app/Resources/public/.tmp']
                }]
            }
        },

        // Renames files for browser caching purposes
        filerev: {
            options: {
                encoding: 'utf8',
                algorithm: 'md5',
                length: 16
            },
            dist: {
                src: [
                    'app/Resources/public/dist/js/**/*.js',
                    'app/Resources/public/dist/css/**/*.css',
                    //'app/Resources/public/dist/img/**/*.{png,jpg,jpeg,gif,svg}',
                    'app/Resources/public/dist/fonts/**/*.{eot,otf,svg,ttf,woff}'
                ]
            }
        },

        // Reads HTML for usemin blocks to enable smart builds that automatically
        // concat, minify and revision files. Creates configurations in memory so
        // additional tasks can operate on them
        useminPrepare: {
            html: 'app/Resources/views/app/base.html.twig',
            //html: 'src/LoneWolfAppBundle/Resources/views/base.html.twig',
            options: {
                dest: 'app/Resources/public',
                staging: 'app/Resources/public/.tmp',
                flow: {
                    html: {
                        steps: {
                            js: ['concat', 'uglifyjs'],
                            css: ['cssmin']
                        },
                        post: {}
                    }
                }
            }
        },

        // Performs rewrites based on filerev and the useminPrepare configuration
        usemin: {
            html: ['app/Resources/views/dist/base.html.twig'],
            css: ['app/Resources/public/dist/css/**/*.css'],
            options: {
                assetsDirs: ['app/Resources/public'],
                patterns: {
                    css: [
                        [
                            /url\(\s*['"]?([^"'\)\?#]+)/img,
                            'Replacing assets url( references with filerev summary',
                            function (m) {
                                var match = false;
                                var keySummary = m.replace('..', 'app/Resources/public/dist');
                                if (typeof(grunt.filerev.summary[keySummary]) === 'string') {
                                    match = grunt.filerev.summary[keySummary];
                                    match = match.replace('app/Resources/public/dist', '..');
                                }
                                if (match) {
                                    grunt.filerev.summary[m] = match;
                                    //grunt.log.writeln('ok: '+match);
                                } else {
                                    grunt.log.writeln('ko: '+m);
                                }
                                return match ? match : m;
                            }
                        ]
                    ]
                }
            }
        },

        imagemin: {
            dist: {
                files: [{
                    expand: true,
                    cwd: 'app/Resources/public/app/img',
                    src: '**/*.{png,jpg,jpeg,gif}',
                    dest: 'app/Resources/public/dist/img'
                }]
            }
        },

        // creating symlinks for symfony 2 standards
        symlink: {
            options: {
                overwrite: true
            },
            dist: {
                src: 'app/Resources/public/dist',
                dest: 'web/dist'
            },
            bower: {
                src: 'app/Resources/public/bower_components',
                dest: 'web/bower_components'
            }
        },

        // Copies remaining files to places other tasks can use (for app_dev.php use with symlinks)
        copy: {
            dist: {
                files: [
                    {
                        expand: true,
                        dot: true,
                        cwd: 'app/Resources/public/app',
                        dest: 'app/Resources/public/dist',
                        src: [
                            '**/*.{ico,txt}',
                            '.htaccess',
                            'fonts/**/*.*'
                        ]
                    },
                    {
                        expand: true,
                        cwd: 'app/Resources/views/app',
                        dest: 'app/Resources/views/dist',
                        src: ['base.html.twig']
                    },
                    {
                        expand: true,
                        cwd: 'app/Resources/public/bower_components/bootstrap/dist/fonts',
                        dest: 'app/Resources/public/dist/fonts',
                        src: ['*']
                    },
                    {
                        expand: true,
                        cwd: 'app/Resources/public/bower_components/fontawesome/fonts',
                        dest: 'app/Resources/public/dist/fonts',
                        src: ['*']
                    }
                ]
            },
            winSymlink: {
                files: [
                    {
                        expand: true,
                        cwd: 'app/Resources/public/app',
                        dest: 'web/app',
                        src: [
                            'js/**/*.*',
                            'img/**/*.*',
                            '*.*',
                            'css/*.css'
                        ]
                    },
                    {
                        expand: true,
                        cwd: 'app/Resources/public/dist',
                        dest: 'web/dist',
                        src: ['**/*.*']
                    },
                    {
                        expand: true,
                        cwd: 'app/Resources/public/bower_components',
                        dest: 'web/bower_components',
                        src: ['**/*.*']
                    }
                ]
            }
        }
    });

    grunt.registerTask('default', ['buildProduction']);

    grunt.registerTask('build', [
        'clean',
        'less',
        'newer:jshint',
        'useminPrepare',
        'newer:imagemin',
        'concat',
        'copy:dist',
        'csslint',
        'cssmin',
        'uglify',
        'filerev',
        'usemin',
        'clean:temp',
        'copy:winSymlink'
    ]);

    grunt.registerTask('buildProduction', [
        'clean',
        'less',
        'newer:jshint',
        'useminPrepare',
        'newer:imagemin',
        'concat',
        'copy:dist',
        'csslint',
        'cssmin',
        'uglify',
        'filerev',
        'usemin',
        'clean:temp',
        'symlink'
    ]);
};
