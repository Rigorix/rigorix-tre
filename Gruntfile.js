module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    concat: {
      options: {
        separator: "\n\n", //add a new line after each file
        banner: "", //added before everything
        footer: "" //added after everything
      },
      dist: {
        // the files to concatenate
        src: [
          //include libs
          'app/assets/vendor/jquery-1.10.2.min.js',
          'app/assets/vendor/angular.min.js',
          'app/assets/vendor/angular-resource.min.js',
          'app/assets/vendor/angular-route.min.js',
          'app/assets/vendor/ui-bootstrap-tpls-0.10.1.js',
          'app/assets/vendor/bootstrap/js/bootstrap.min.js',
          'app/assets/vendor/moment.min.js',

          // App jss
          'app/app.dist.js'
        ],
        // the location of the resulting JS file
        dest: 'app/assets/dist/dist.js'
      }
    },

    less: {
      development: {
        options: {
          paths: ["app/assets/vendor/bootstrap/less"]
        },
        files: {
          "app/assets/dist/dist.common.css": "app/assets/less/common.less"
        }
      }
    },

//    'ftp-deploy': {
//      build: {
//        auth: {
//          host: 'ftp.rigorix.com',
//          port: 21,
//          authKey: 'tre_prod'
//        },
//        src: 'app/assets/dist',
//        dest: 'tre/app/assets/dist'
//      }
//    },

    git_ftp: {
      development: {
        options: {
          'hostFile':'.gitftppass',
          'host':'staging'
        }
      }
    },

    coffee: {
      compileBare: {
        options: {
          bare: true
        },
        files: {
//          'path/to/result.js': 'path/to/source.coffee', // 1:1 compile
          'app/app.dist.js': ['app/**/*.coffee', 'app/*.coffee'] // compile and concat into single file
        }
      }
    },



    watch: {
      scripts: {
//        files: ['app/controllers/*.js', 'app/filters/*.js', 'app/services/*.js', 'app/directives/*.js', 'app/*.js', 'app/assets/vendor/**/*.js', 'app/assets/**/*.less', 'api/index.php', 'api/dm/*.php'],
        files: ['app/**/*.coffee', 'app/assets/**/*.less'],
        tasks: ['dev'],
        options: {
          interrupt: true
        }
      }
    }

  });

//  grunt.loadNpmTasks('grunt-git-ftp');
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-watch');
//  grunt.loadNpmTasks('grunt-ftp-deploy');
  grunt.loadNpmTasks('grunt-contrib-less');
  grunt.loadNpmTasks('grunt-contrib-coffee');

  // Default task(s).
  grunt.registerTask('dev', ['coffee:compileBare', 'concat:dist', 'less:development']);
  grunt.registerTask('deploy:staging', ['git_ftp:development']);
  grunt.registerTask('prod', ['concat:dist', 'less:development', 'ftp-deploy:build']);

};