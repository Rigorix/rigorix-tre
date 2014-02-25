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
#          "githooks"
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
      less:
        files: [
          "app/assets/**/*.less"
        ]
        tasks: ["dev:less"]
      options:
        interrupt: true

    coffee:
      compileBare:
        options:
#          sourceMap: true
          bare: true
        files:
          "app/assets/temp/angular.app.config.js": ["app/config.coffee"]
          "app/assets/temp/angular.app.main.js": ["app/app.coffee"]
          "app/assets/temp/angular.app.js": [
            "app/controllers/*.coffee"
            "app/directives/*.coffee"
            "app/filters/*.coffee"
            "app/services/*.coffee"
          ]

    concat:
      options:
        separator: ";\n\n" #add a new line after each file
        banner: '/*! <%= pkg.name %> - v<%= pkg.version %> - ' +
        '<%= grunt.template.today("yyyy-mm-dd") %> */'
      script:
        src: [
          "app/assets/dist/dependencies/jquery.js"
          "app/assets/dist/dependencies/angular.js"
          "app/assets/dist/dependencies/*.js"
          "app/assets/temp/angular.app.main.js"
          "app/assets/temp/angular.app.config.js"
          "app/assets/temp/angular.app.js"
          "app/assets/js/*.js"
        ]
        dest: "app/assets/dist/app.js"

      css:
        src: [
          "app/assets/css/*.css"
          "app/assets/temp/app.main.css"
#          "app/assets/bower_components/angular-bootstrap-colorpicker/css/colorpicker.css"
          "css/*.css"
        ]
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

    uglify: {
      dev: {
        options: {
#          beautify: true
        },
        files: {
          'app/assets/dist/app.min.js': ['app/assets/dist/app.js']
        }
      }
    }

    git_ftp:
      development:
        options:
          hostFile: ".gitftppass"
          host: "staging"

#    githooks:
#        all:
#          options:
#            hashbang: '#!/bin/sh'
#            template: './node_modules/grunt-githooks/templates/shell.hb'
#            startMarker: '## LET THE FUN BEGIN'
#            endMarker: '## PARTY IS OVER'
#
#          'post-commit': 'git_ftp:development'

    bowerInstall:
      install: {}

    bower:
      dev:
        dest: "app/assets/dist/dependencies"


  grunt.loadNpmTasks('grunt-contrib-uglify');
  #  grunt.loadNpmTasks('grunt-ftp-deploy');
#  grunt.loadNpmTasks "grunt-git-ftp"
  grunt.loadNpmTasks "grunt-contrib-concat"
#  grunt.loadNpmTasks "grunt-githooks"
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
#    "uglify:dev"
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


  grunt.registerTask "deploy:staging", ["dev:build", "git_ftp:development"]
  grunt.registerTask('prod', ['concat:dist', 'less:development', 'ftp-deploy:build']);