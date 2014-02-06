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
        tasks: ["dev:script"],
        options: {
          interrupt: true
        }
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
          "app/assets/temp/angular.app.main.js": ["app/app.coffee"],
          "app/assets/temp/angular.app.config.js": ["app/config.coffee"],
          "app/assets/temp/angular.app.js": ["app/controllers/*.coffee", "app/directives/*.coffee", "app/filters/*.coffee", "app/services/*.coffee"]
        }
      }
    },
    concat: {
      options: {
        separator: "\n\n"
      },
      script: {
        src: ["app/assets/dist/dependencies/jquery.js", "app/assets/dist/dependencies/angular.js", "app/assets/dist/dependencies/*.js", "app/assets/temp/angular.app.main.js", "app/assets/temp/angular.app.config.js", "app/assets/temp/angular.app.js"],
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
    git_ftp: {
      development: {
        options: {
          hostFile: ".gitftppass",
          host: "staging"
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
  grunt.loadNpmTasks("grunt-contrib-concat");
  grunt.loadNpmTasks("grunt-concurrent");
  grunt.loadNpmTasks("grunt-contrib-watch");
  grunt.loadNpmTasks("grunt-contrib-less");
  grunt.loadNpmTasks("grunt-contrib-coffee");
  grunt.loadNpmTasks("grunt-contrib-clean");
  grunt.loadNpmTasks("grunt-bower-task");
  grunt.renameTask("bower", "bowerInstall");
  grunt.loadNpmTasks("grunt-bower");
  grunt.registerTask("dev", ["concurrent:dev"]);
  grunt.registerTask("dev:script", ["coffee:compileBare", "concat:script", "clean:temp"]);
  grunt.registerTask("dev:less", ["less:development", "concat:css", "clean:temp"]);
  grunt.registerTask("dev:dependencies", ["bowerInstall", "bower"]);
  grunt.registerTask("dev:build", ["clean:dependencies", "bowerInstall", "bower", "coffee:compileBare", "concat:script", "less:development", "concat:css", "clean:temp"]);
  grunt.registerTask("deploy:staging", ["git_ftp:development"]);
  return grunt.registerTask('prod', ['concat:dist', 'less:development', 'ftp-deploy:build']);
};
