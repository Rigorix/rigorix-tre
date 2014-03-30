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
          "watch:jasmine"
        ]

    clean:
      temp: ['app/assets/temp', 'app/assets/bower_components']
      dependencies: ['app/assets/dist/dependencies']

    watch:
      scripts:
        files: [
          "app/**/*.coffee",
          "app/templates/**/*.html"
        ]
        tasks: ["dev:script"]

      less:
        files: [
          "app/assets/**/*.less"
        ]
        tasks: ["dev:less"]

      jasmine:
        files: [
          "specs/app/**/*.coffee"
        ]
        tasks: ["dev:jasmine"]

      options:
        interrupt: true

    coffee:
      compileBare:
        options:
          bare: true
        files:
          "app/assets/temp/angular.app.config.js": ["app/config.coffee"]
          "app/assets/temp/angular.app.main.js": ["app/app.coffee"]
          "app/assets/temp/angular.app.js": [
            "app/modules/*.coffee"
            "app/controllers/*.coffee"
            "app/directives/*.coffee"
            "app/filters/*.coffee"
            "app/services/*.coffee"
            "app/factories/*.coffee"
          ]

          "app/administr/dist/app.js": ["app/administr/app.coffee"]
          "app/administr/dist/angular.js": ["app/administr/angular/**/*.coffee"]

      compileJasmine:
        options:
          bare: true
        files:
          "specs/app/specs.js": ["specs/app/**/*.coffee"]

    uglify:
      dev:
        files:
          'app/assets/dist/app.min.js': ['app/assets/dist/app.js']

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
          "app/assets/dist/dependencies/*.css.css"
          "app/assets/temp/app.main.css"
          "css/*.css"
        ]
        dest: "app/assets/dist/app.css"

    less:
      development:
        options:
#          paths: ["app/assets/bower_components/bootstrap/less"]
#          sourceMap: true
#          sourceMapFilename: "app/assets/dist/app.css.map"
#          sourceMapRootpath: "app/assets/dist/app.css.map"
          compress: true

        files:
          "app/assets/temp/app.main.css": "app/assets/less/common.less"

    cssmin:
      add_banner:
        options:
          banner: '/* Rigorix css minified. Generated: <%= grunt.template.today("yyyy-mm-dd") %> */'
        files:
          'app/assets/dist/app.min.css': ['app/assets/dist/app.css']

    'ftp_upload': {
      build: {
        auth: {
          host: 'ftp.rigorix.com',
          port: 21,
          authKey: 'tre_prod'
        },
        src: './',
        dest: '/tre/'
        exclusions: [
          #Git
          '.git*'

          #root files
          './.bowerrc'
          './.env'
          './.idea'
          './.ftppass'
          './.gitftppass'
          './.gitignore'
          './.project'
          './.travis.yml'
          './bower.json'
          './Gruntfile.coffee'
          './Gruntfile.js'
          './package.json'
          './Procfile'
          './**/README.md'
          './**/.DS_Store'
          './rigorix.ssh'
          './rigorix.ssh.pub'

          #Administr
          './app/administr/app.coffee'
          './app/administr/angular'

          #App
          './app/assets/bower_components'
          './app/assets/css'
          './app/assets/dist/dependencies'
          './app/assets/js'
          './app/assets/less'
          './app/controllers'
          './app/directives'
          './app/filters'
          './app/services'
          './app/app.coffee'
          './app/config.coffee'
          './app/server.coffee'
          './app/templates'

          #Others
          './i/profile_picture'
          './log'
          './node_modules'
          './Opauth'
          './swf/rigorixGame.fla'
          './swf/rigorixGame_v3.fla'
          './to_be_deleted'
        ]
      }
    }

    bowerInstall:
      install: {}

    bower:
      dev:
        dest: "app/assets/dist/dependencies"

    ngtemplates:
      Rigorix:
        src: 'app/templates/**/*.html'
        dest: 'app/assets/js/rigorix-templates.js'
        options:
          htmlmin:
            collapseWhitespace: true
            collapseBooleanAttributes: true


    # Specs

    jasmine:
      pivotal:
        src: 'app/assets/dist/app.js'
        options:
          specs: 'specs/app/specs.js'
          host: "http://tre.rigorix.dev/"
          summary: true
#          helpers: '/specs/app/helpers.js'

    phpunit:
      classes:
        dir: 'specs/api/'
      options:
        bin: '/usr/local/bin/phpunit'
        bootstrap: 'specs/api/bootstrap.php'
        colors: true

  grunt.loadNpmTasks "grunt-contrib-uglify"
  grunt.loadNpmTasks "grunt-contrib-concat"
  grunt.loadNpmTasks "grunt-contrib-watch"
  grunt.loadNpmTasks "grunt-contrib-less"
  grunt.loadNpmTasks "grunt-contrib-coffee"
  grunt.loadNpmTasks "grunt-contrib-clean"
  grunt.loadNpmTasks "grunt-contrib-cssmin"
  grunt.loadNpmTasks "grunt-concurrent"
  grunt.loadNpmTasks "grunt-ftp-upload"

  grunt.loadNpmTasks "grunt-angular-templates"

#  Bower task
  grunt.loadNpmTasks "grunt-bower-task"
  grunt.renameTask   "bower", "bowerInstall"
  grunt.loadNpmTasks "grunt-bower"

  grunt.loadNpmTasks "grunt-contrib-jasmine";
  grunt.loadNpmTasks "grunt-phpunit";


  # DEVELOPMENT tasks --------------------------------------------------------------------------------------------------
  grunt.registerTask "dev", [ "concurrent:dev" ]
  grunt.registerTask "dev:script", [
    "coffee:compileBare"
    "ngtemplates"
    "concat:script"
    "uglify:dev"
    "clean:temp"
  ]
  grunt.registerTask "dev:less", [
    "less:development"
    "concat:css"
    "cssmin"
    "clean:temp"
  ]
  grunt.registerTask "dev:bower", [
    "bowerInstall"
    "bower"
  ]
  grunt.registerTask "dev:update", [
    "clean:dependencies"
    "bowerInstall"
    "bower"
    "ngtemplates"
    "dev:script"
    "dev:less"
  ]
  grunt.registerTask "specs", [
    "coffee:compileJasmine"
    "jasmine:pivotal"
    "phpunit"
  ]

  # STAGING / PRODUCTION tasks -----------------------------------------------------------------------------------------


  grunt.registerTask "deploy:staging", ["specs", "dev:update", "ftp_upload"]

#  grunt.registerTask('deploy:production', ['dev:update', 'ftp_upload']);