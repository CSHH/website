module.exports = function(grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        concat: {
            js: {
                options: {
                    separator: ';'
                },
                files: [
                    {src: ['assets/js/{main,cookies,modals,themes}.js'], dest: 'www/js/main-front.js'},
                    {src: ['assets/js/{main,ckeditor-config}.js'], dest: 'www/js/main-admin.js'}
                ]
            },

            css: {
                files: [
                    {src: ['assets/css/{common,front}.css'], dest: 'www/css/main-front.css'},
                    {src: ['assets/css/{common,admin}.css'], dest: 'www/css/main-admin.css'}
                ]
            }
        },

        uglify: {
            files: [
                {src: ['<%= concat.js.files[0].dest %>'], dest: 'www/js/main-front.min.js'},
                {src: ['<%= concat.js.files[1].dest %>'], dest: 'www/js/main-admin.min.js'}
            ]
        },

        cssmin: {
            files: [
                {src: ['<%= concat.css.files[0].dest %>'], dest: 'www/css/main-front.min.css'},
                {src: ['<%= concat.css.files[1].dest %>'], dest: 'www/css/main-admin.min.css'},
                {src: ['assets/css/themes/theme-fog.css'], dest: 'www/css/theme-fog.min.css'},
                {src: ['assets/css/themes/theme-otherworld.css'], dest: 'www/css/theme-otherworld.min.css'}
            ]
        },

        /*copy: {
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
        },*/

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
    //grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-watch');

    grunt.registerTask('default', ['jshint', 'concat', 'uglify', 'cssmin']);
};
