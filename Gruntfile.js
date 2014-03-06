// Generated by CoffeeScript 1.6.3
module.exports = function(grunt) {
  grunt.initConfig({
    pkg: grunt.file.readJSON("package.json"),
    concurrent: {
      options: {
        logConcurrentOutput: true
      },
      dev: {
        tasks: ["watch:scripts", "watch:less"]
      }
    },
    clean: {
      temp: ['app/assets/temp'],
      dependencies: ['app/assets/dist/dependencies']
    },
    watch: {
      scripts: {
        files: ["app/**/*.coffee"],
        tasks: ["dev:script"]
      },
      less: {
        files: ["app/assets/**/*.less"],
        tasks: ["dev:less"]
      },
      options: {
        interrupt: true
      }
    },
    coffee: {
      compileBare: {
        options: {
          bare: true
        },
        files: {
          "app/assets/temp/angular.app.config.js": ["app/config.coffee"],
          "app/assets/temp/angular.app.main.js": ["app/app.coffee"],
          "app/assets/temp/angular.app.js": ["app/controllers/*.coffee", "app/directives/*.coffee", "app/filters/*.coffee", "app/services/*.coffee"],
          "app/administr/dist/app.js": ["app/administr/app.coffee"],
          "app/administr/dist/angular.js": ["app/administr/angular/**/*.coffee"]
        }
      }
    },
    concat: {
      options: {
        separator: ";\n\n",
        banner: '/*! <%= pkg.name %> - v<%= pkg.version %> - ' + '<%= grunt.template.today("yyyy-mm-dd") %> */'
      },
      script: {
        src: ["app/assets/dist/dependencies/jquery.js", "app/assets/dist/dependencies/angular.js", "app/assets/dist/dependencies/*.js", "app/assets/temp/angular.app.main.js", "app/assets/temp/angular.app.config.js", "app/assets/temp/angular.app.js", "app/assets/js/*.js"],
        dest: "app/assets/dist/app.js"
      },
      css: {
        src: ["app/assets/css/*.css", "app/assets/temp/app.main.css", "css/*.css"],
        dest: "app/assets/dist/app.css"
      }
    },
    less: {
      development: {
        options: {
          paths: ["app/assets/bower_components/bootstrap/less"]
        },
        files: {
          "app/assets/temp/app.main.css": "app/assets/less/common.less"
        }
      }
    },
    'ftp_upload': {
      build: {
        auth: {
          host: 'ftp.rigorix.com',
          port: 21,
          authKey: 'tre_prod'
        },
        src: './',
        dest: '/tre/',
        exclusions: ['.git*', './.bowerrc', './.env', './.idea', './.ftppass', './.gitftppass', './.gitignore', './.project', './.travis.yml', './bower.json', './Gruntfile.coffee', './Gruntfile.js', './package.json', './Procfile', './**/README.md', './**/.DS_Store', './rigorix.ssh', './rigorix.ssh.pub', './app/administr/app.coffee', './app/administr/angular', './app/assets/bower_components', './app/assets/css', './app/assets/dist/dependencies', './app/assets/js', './app/assets/less', './app/controllers', './app/directives', './app/filters', './app/services', './app/app.coffee', './app/config.coffee', './app/server.coffee', './i/profile_picture', './log', './node_modules', './Opauth', './swf/rigorixGame.fla', './swf/rigorixGame_v3.fla', './to_be_deleted']
      }
    },
    uglify: {
      dev: {
        options: {},
        files: {
          'app/assets/dist/app.min.js': ['app/assets/dist/app.js']
        }
      }
    },
    bowerInstall: {
      install: {}
    },
    bower: {
      dev: {
        dest: "app/assets/dist/dependencies"
      }
    }
  });
  grunt.loadNpmTasks("grunt-contrib-uglify");
  grunt.loadNpmTasks("grunt-ftp-upload");
  grunt.loadNpmTasks("grunt-contrib-concat");
  grunt.loadNpmTasks("grunt-concurrent");
  grunt.loadNpmTasks("grunt-contrib-watch");
  grunt.loadNpmTasks("grunt-contrib-less");
  grunt.loadNpmTasks("grunt-contrib-coffee");
  grunt.loadNpmTasks("grunt-contrib-clean");
  grunt.loadNpmTasks("grunt-bower-task");
  grunt.renameTask("bower", "bowerInstall");
  grunt.loadNpmTasks("grunt-bower");
  grunt.loadNpmTasks("grunt-contrib-jasmine");
  grunt.registerTask("dev", ["concurrent:dev"]);
  grunt.registerTask("dev:script", ["coffee:compileBare", "concat:script", "uglify:dev", "clean:temp"]);
  grunt.registerTask("dev:less", ["less:development", "concat:css", "clean:temp"]);
  grunt.registerTask("dev:dependencies", ["bowerInstall", "bower"]);
  grunt.registerTask("dev:build", ["coffee:compileBare", "concat:script", "less:development", "concat:css", "clean:temp"]);
  grunt.registerTask("deploy:staging", ["dev:build", "ftp_upload"]);
  return grunt.registerTask('prod', ['concat:dist', 'less:development', 'ftp-deploy:build']);
};
