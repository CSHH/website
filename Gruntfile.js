module.exports = function(grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        concat: {
            js: {
                options: {
                    separator: ';\n\n\n'
                },
                files: [
                    {src: ['assets/js/{main,ckeditor-config,cookies,modals,themes}.js'], dest: 'www/js/main-front.js'},
                    {src: ['assets/js/{main,ckeditor-config}.js'], dest: 'www/js/main-admin.js'},
                    {
                        src: [
                            'node_modules/jquery/dist/jquery.js',
                            'node_modules/nette-forms/src/assets/netteForms.js',
                            'node_modules/nette.ajax.js/nette.ajax.js',
                            'node_modules/lightbox2/dist/js/lightbox.js'
                        ],
                        dest: 'www/libs/js/libs-front.js'
                    }, {
                        src: [
                            'node_modules/jquery/dist/jquery.js',
                            'node_modules/nette-forms/src/assets/netteForms.js',
                            'node_modules/nette.ajax.js/nette.ajax.js'
                        ],
                        dest: 'www/libs/js/libs-admin.js'
                    }
                ]
            },

            css: {
                files: [
                    {src: ['assets/css/{common,front}.css'], dest: 'www/css/main-front.css'},
                    {src: ['assets/css/{common,admin}.css'], dest: 'www/css/main-admin.css'},
                    {src: 'node_modules/lightbox2/dist/css/lightbox.css', dest: 'www/libs/css/libs-front.css'}
                ]
            }
        },

        uglify: {
            front: {
                files: [
                    {src: ['<%= concat.js.files[0].dest %>'], dest: 'www/js/main-front.min.js'},
                    {src: ['<%= concat.js.files[2].dest %>'], dest: 'www/libs/js/libs-front.min.js'}
                ]
            },
            admin: {
                files: [
                    {src: ['<%= concat.js.files[1].dest %>'], dest: 'www/js/main-admin.min.js'},
                    {src: ['<%= concat.js.files[3].dest %>'], dest: 'www/libs/js/libs-admin.min.js'}
                ]
            }
        },

        cssmin: {
            front: {
                files: [
                    {src: ['<%= concat.css.files[0].dest %>'], dest: 'www/css/main-front.min.css'},
                    {src: ['<%= concat.css.files[2].dest %>'], dest: 'www/libs/css/libs-front.min.css'}
                ]
            },
            admin: {
                files: [
                    {src: ['<%= concat.css.files[1].dest %>'], dest: 'www/css/main-admin.min.css'}
                ]
            },
            'theme-fog': {
                files: [
                    {src: ['assets/css/themes/theme-fog.css'], dest: 'www/css/theme-fog.min.css'}
                ]
            },
            'theme-otherworld': {
                files: [
                    {src: ['assets/css/themes/theme-otherworld.css'], dest: 'www/css/theme-otherworld.min.css'}
                ]
            }
        },

        copy: {
            'lightbox-images': {
                files: [
                    {
                        expand: true,
                        cwd: 'node_modules/lightbox2/dist/images/',
                        src: ['*'],
                        dest: 'www/libs/images/'
                    }
                ]
            },
            'theme-stylesheets': {
                files: [
                    {
                        expand: true,
                        cwd: 'assets/css/themes/',
                        src: ['*'],
                        dest: 'www/css/'
                    }
                ]
            },
            ckeditor: {
                files: [
                    {
                        expand: true,
                        cwd: 'node_modules/',
                        src: ['ckeditor/**'],
                        dest: 'www/libs/'
                    }
                ]
            }
        },

        jshint: {
            files: ['Gruntfile.js', 'assets/js/*.js']
        },

        watch: {
            js: {
                files: ['<%= jshint.files %>'],
                tasks: ['jshint', 'concat:js', 'uglify']
            },
            css: {
                files: ['assets/css/**'],
                tasks: ['concat:css', 'copy:theme-stylesheets', 'cssmin']
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-watch');

    grunt.registerTask('default', ['jshint', 'concat', 'uglify', 'cssmin', 'copy']);
};
