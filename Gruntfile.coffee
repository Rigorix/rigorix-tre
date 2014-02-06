module.exports = (grunt) ->
  grunt.initConfig
    pkg: grunt.file.readJSON("package.json")

    concurrent:
      options:
        logConcurrentOutput: true
      dev:
        tasks: [
          "watch:scripts"
          "watch:less"
        ]

    clean:
      temp: ['app/assets/temp']
      dependencies: ['app/assets/dist/dependencies']

    watch:
      scripts:
        files: [
          "app/**/*.coffee"
#          "app/assets/dist/dependencies/*.js"
#          "app/assets/**/*.less"
        ]
        tasks: ["dev:script"]
        options:
          interrupt: true
      less:
        files: [
          "app/assets/**/*.less"
        ]
        tasks: ["dev:less"]
      options:
        interrupt: true
#      dependencies:
#        files: ["app/assets/bower_components/**/*"]
#        tasks: ["dev:dependencies"]
#        options:
#          interrupt: true

    coffee:
      compileBare:
        options:
          bare: true
        files:
          "app/assets/temp/angular.app.main.js": ["app/app.coffee"]
          "app/assets/temp/angular.app.config.js": ["app/config.coffee"]
          "app/assets/temp/angular.app.js": [
            "app/controllers/*.coffee"
            "app/directives/*.coffee"
            "app/filters/*.coffee"
            "app/services/*.coffee"
          ]

    concat:
      options:
        separator: "\n\n" #add a new line after each file
#        banner: "/// START ///-----------------------------------" #added before everything
#        footer: "/// END ///-----------------------------------" #added after everything
      script:
        src: [
          #include libs
#          "app/assets/vendor/jquery-1.10.2.min.js"
#          "app/assets/vendor/angular.js"
#          "app/assets/vendor/angular-resource.js"
#          "app/assets/vendor/angular-route.js"
#          "app/assets/vendor/angular-sanitize.js"
#          "app/assets/vendor/bootstrap/js/bootstrap.min.js"
#          "app/assets/vendor/extensions/**/*.js"

          "app/assets/dist/dependencies/jquery.js"
          "app/assets/dist/dependencies/angular.js"
          "app/assets/dist/dependencies/*.js"
          # App jss
          "app/assets/temp/angular.app.main.js"
          "app/assets/temp/angular.app.config.js"
          "app/assets/temp/angular.app.js"

#          "app/assets/dist/dependencies/jquery.js"
#          "app/assets/vendor/angular.js"
#          "app/assets/vendor/angular-resource.js"
#          "app/assets/vendor/angular-route.js"
#          "app/assets/vendor/angular-sanitize.js"
#          "app/assets/dist/dependencies/bootstrap.js"
#          "app/assets/dist/dependencies/angular-bootstrap-colorpicker.js"
#          "app/assets/dist/dependencies/pines-notify.js"
#          "app/assets/dist/dependencies/angular-pines-notify.js"
#          "app/assets/dist/dependencies/moment.js"
#          "app/assets/dist/dependencies/font-awesome.js"
#          "app/assets/vendor/extensions/textAngular.min.js"
#          "app/assets/vendor/extensions/notify.js"
#          "app/assets/vendor/extensions/ui-bootstrap-tpls-0.10.1.js"

          #          'app/assets/vendor/ui-bootstrap-tpls-0.10.1.js',
          #          'app/assets/vendor/moment.min.js',


        ]

      # the location of the resulting JS file
        dest: "app/assets/dist/app.js"

      css:

      # the files to concatenate
        src: [

          #include libs
          "app/assets/css/*.css"
          "app/assets/temp/app.main.css"
          "css/*.css"
#          'css/ui/jquery-ui-1.8.1.custom.css'
        ]

      # the location of the resulting JS file
        dest: "app/assets/dist/app.css"

    less:
      development:
        options:
          paths: ["app/assets/bower_components/bootstrap/less"]

        files:
          "app/assets/temp/app.main.css": "app/assets/less/common.less"


  #    'ftp-deploy': {
  #      build: {
  #        auth: {
  #          host: 'ftp.rigorix.com',
  #          port: 21,
  #          authKey: 'tre_prod'
  #        },
  #        src: 'app/assets/dist',
  #        dest: 'tre/app/assets/dist'
  #      }
  #    },

  #    uglify: {
  #      dev: {
  #        options: {
  #          beautify: true
  #        },
  #        files: {
  #          'app/assets/css/common.min.css': ['app/assets/css/*.css', 'app/assets/css/dist.common.css']
  #        }
  #      }
  #    },
    git_ftp:
      development:
        options:
          hostFile: ".gitftppass"
          host: "staging"



    bowerInstall:
      install: {}

    bower:
      dev:
        dest: "app/assets/dist/dependencies"


  #  grunt.loadNpmTasks('grunt-contrib-uglify');
  #  grunt.loadNpmTasks('grunt-ftp-deploy');
  #  grunt.loadNpmTasks('grunt-git-ftp');
  grunt.loadNpmTasks "grunt-contrib-concat"
  grunt.loadNpmTasks "grunt-concurrent"
  grunt.loadNpmTasks "grunt-contrib-watch"
  grunt.loadNpmTasks "grunt-contrib-less"
  grunt.loadNpmTasks "grunt-contrib-coffee"
  grunt.loadNpmTasks "grunt-contrib-clean"
  grunt.loadNpmTasks "grunt-bower-task"
  grunt.renameTask "bower", "bowerInstall"
  grunt.loadNpmTasks "grunt-bower"

  # DEVELOPMENT tasks --------------------------------------------------------------------------------------------------
  grunt.registerTask "dev", [ "concurrent:dev" ]
  grunt.registerTask "dev:script", [
    "coffee:compileBare"
    "concat:script"
    "clean:temp"
  ]
  grunt.registerTask "dev:less", [
    "less:development"
    "concat:css"
    "clean:temp"
  ]
  grunt.registerTask "dev:dependencies", [
    "bowerInstall"
    "bower"
  ]
  grunt.registerTask "dev:build", [
    "clean:dependencies"
    "bowerInstall"
    "bower"
    "coffee:compileBare"
    "concat:script"
    "less:development"
    "concat:css"
    "clean:temp"
  ]

  # PRODUCTION tasks ---------------------------------------------------------------------------------------------------


  grunt.registerTask "deploy:staging", ["git_ftp:development"]

  grunt.registerTask('prod', ['concat:dist', 'less:development', 'ftp-deploy:build']);