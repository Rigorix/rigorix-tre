module.exports = function(config){

  config.set({
    basePath : '/',
    files : [
      'specs/app/specs.js'
    ],
    autoWatch : false,
    browsers : ['Chrome'],
    frameworks: ['jasmine']
  });

};