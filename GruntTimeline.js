'use strict';

module.exports = function (grunt) {

    // Load grunt tasks automatically
    require('load-grunt-tasks')(grunt);

    // Time how long tasks take. Can help when optimizing build times
    require('time-grunt')(grunt);

    // Define the configuration for all the tasks
    grunt.initConfig({

        // Watches files for changes and runs tasks based on the changed files
        watch: {
            gruntfile: {
                files: ['Gruntfile.js']
            }
        },

        less: {
            server: {
                files: {
                    'Resources/public/app/css/main.css': 'Resources/public/app/css/main.less'
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
                    'Gruntfile.js',
                    'Resources/public/app/js/{,*/}*.js'
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
                src: ['Resources/public/app/css/main.css']
            }
        },

        // Empties folders to start fresh
        clean: {
            dist: {
                files: [{
                    dot: true,
                    src: [
                        'Resources/public/.tmp',
                        'Resources/public/dist/{,*/}*',
                        'Resources/views/Common/dist'
                    ]
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
                    'Resources/public/dist/js/{,*/}*.js',
                    'Resources/public/dist/css/{,*/}*.css',
                    'Resources/public/dist/images/{,*/}*.{png,jpg,jpeg,gif,webp,svg}',
                    'Resources/public/dist/fonts/{,*/}*.{eot,otf,svg,ttf,woff}'
                ]
            }
        },

        // Reads HTML for usemin blocks to enable smart builds that automatically
        // concat, minify and revision files. Creates configurations in memory so
        // additional tasks can operate on them
        useminPrepare: {
            html: 'Resources/views/Common/app/base.html.twig',
            options: {
                dest: 'Resources/public',
                staging: 'Resources/public/.tmp',
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
            html: ['Resources/views/Common/dist/base.html.twig'],
            css: ['Resources/public/dist/css/{,*/}*.css'],
            options: {
                assetsDirs: ['Resources/public'],
                patterns: {
                    css: [
                        [
                            /url\(\s*['"]?([^"'\)\?#]+)/img,
                            'Replacing assets url( references with filerev summary',
                            function (m) {
                                var match = false;
                                var keySummary = m.replace('..', 'Resources/public/dist');
                                if (typeof(grunt.filerev.summary[keySummary]) === 'string') {
                                    match = grunt.filerev.summary[keySummary];
                                    match = match.replace('Resources/public/dist', '..');
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
                    cwd: 'Resources/public/app/images',
                    src: '{,*/}*.{png,jpg,jpeg,gif}',
                    dest: 'Resources/public/dist/images'
                }]
            }
        },

        svgmin: {
            dist: {
                files: [{
                    expand: true,
                    cwd: 'Resources/public/app/images',
                    src: '{,*/}*.svg',
                    dest: 'Resources/public/dist/images'
                }]
            }
        },

        // Copies remaining files to places other tasks can use
        copy: {
            dist: {
                files: [
                    {
                        expand: true,
                        dot: true,
                        cwd: 'Resources/public/app',
                        dest: 'Resources/public/dist',
                        src: [
                            '*.{ico,png,txt}',
                            '.htaccess',
                            'images/{,*/}*.{webp}',
                            'fonts/{,*/}*.*'
                        ]
                    },
                    {
                        expand: true,
                        cwd: 'Resources/views/Common/app',
                        dest: 'Resources/views/Common/dist',
                        src: ['base.html.twig']
                    },
                    {
                        expand: true,
                        cwd: 'Resources/public/.tmp/images',
                        dest: 'Resources/public/dist/images',
                        src: ['generated/*']
                    },
                    {
                        expand: true,
                        cwd: 'Resources/public/bower_components/bootstrap/dist/fonts',
                        dest: 'Resources/public/dist/fonts',
                        src: ['*']
                    },
                    {
                        expand: true,
                        cwd: 'Resources/public/bower_components/fontawesome/fonts',
                        dest: 'Resources/public/dist/fonts',
                        src: ['*']
                    }
                ]
            }
        },

        // creating symlinks for symfony 2 standards
        symlink: {
            options: {
                overwrite: true
            },
            dist: {
                src: 'Resources/public/dist',
                dest: '../../../../web/dist'
            },
            app: {
                src: 'Resources/public/app',
                dest: '../../../../web/app'
            },
            bower: {
                src: 'Resources/public/bower_components',
                dest: '../../../../web/bower_components'
            }
        },

        // Run some tasks in parallel to speed up the build process
        concurrent: {
            dist: [
                'imagemin',
                'svgmin'
            ],
            symlinks: [
                'symlink:dist',
                'symlink:app',
                'symlink:bower'
            ]
        }

    });

    grunt.registerTask('build', [
        'clean:dist',
        'less',
        'newer:jshint',
        'useminPrepare',
        'concurrent:dist',
        'concat',
        'copy:dist',
        'csslint',
        'cssmin',
        'uglify',
        'filerev',
        'usemin',
        'concurrent:symlinks'
    ]);

    grunt.registerTask('default', [
        'build'
    ]);

};

