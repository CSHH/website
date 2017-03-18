module.exports = function(grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        concat: {
            dist: {
                files: [
                    {
                        options: {
                            separator: ';'
                        },
                        src: [
                            'node_modules/jquery/dist/jquery.js',
                            'node_modules/lightbox2/dist/js/lightbox.js',
                            'node_modules/nette-forms/src/assets/netteForms.js',
                            'node_modules/nette.ajax.js/nette.ajax.js',
                            'assets/js/cookies.js',
                            'assets/js/modals.js',
                            'assets/js/themes.js',
                            'assets/js/main.js'
                        ],
                        dest: 'www/js/main-front.js'
                    }, {
                        options: {
                            separator: ';'
                        },
                        src: [
                            'node_modules/ckeditor/ckeditor.js',
                            'node_modules/jquery/dist/jquery.js',
                            'node_modules/nette-forms/src/assets/netteForms.js',
                            'node_modules/nette.ajax.js/nette.ajax.js',
                            'assets/js/ckeditor-config.js',
                            'assets/js/main.js'
                        ],
                        dest: 'www/js/main-admin.js'
                    }, {
                        src: [
                            'node_modules/lightbox2/dist/css/lightbox.css',
                            'assets/css/common.css',
                            'assets/css/front.css',
                            '!assets/css/theme-*.css'
                        ],
                        dest: 'www/css/main-front.css'
                    }, {
                        src: [
                            'assets/css/common.css',
                            'assets/css/admin.css'
                        ],
                        dest: 'www/css/main-admin.css'
                    }
                ]
            }
        },
        uglify: {
            dist: {
                files: {
                    'www/js/main-front.min.js': ['<%= concat.dist.files[0].dest %>'],
                    'www/js/main-admin.min.js': ['<%= concat.dist.files[1].dest %>']
                }
            }
        },
        cssmin: {
            dist: {
                files: {
                    'www/css/main-front.min.css': ['<%= concat.dist.files[2].dest %>'],
                    'www/css/main-admin.min.css': ['<%= concat.dist.files[3].dest %>'],
                    'www/css/theme-fog.min.css': ['assets/css/theme-fog.css'],
                    'www/css/theme-otherworld.min.css': ['assets/css/theme-otherworld.css']
                }
            }
        },
        copy: {
            lightboxImages: {
                files: [
                    {
                        expand: true,
                        cwd: 'node_modules/lightbox2/dist/images/',
                        src: ['*'],
                        dest: 'www/images/'
                    }, {
                        expand: true,
                        cwd: 'assets/css/',
                        src: ['theme-*.css'],
                        dest: 'www/css/'
                    }
                ]
            }
        },
        jshint: {
            files: ['Gruntfile.js', 'assets/js/*.js']
        },
        watch: {
            files: ['<%= jshint.files %>'],
            tasks: ['jshint']
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
