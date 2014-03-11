Rigorix.service "notify", ['$rootScope', ($rootScope)->
  $rootScope.notifications = [] if !$rootScope.notifications?

  defaults:
    text: ""
    animation: "bounceInDown"
    severity: "info"
    icon: 'info-circle'
    timeout: 7000

  animateDefaults:
    animations: ['bounce','flash','pulse','rubberBand','shake','swing','tada','wobble','bounceIn','bounceInDown','bounceInLeft','bounceInRight','bounceInUp','bounceOut','bounceOutDown','bounceOutLeft','bounceOutRight','bounceOutUp','fadeIn','fadeInDown','fadeInDownBig','fadeInLeft','fadeInLeftBig','fadeInRight','fadeInRightBig','fadeInUp','fadeInUpBig','fadeOut','fadeOutDown','fadeOutDownBig','fadeOutLeft','fadeOutLeftBig','fadeOutRight','fadeOutRightBig','fadeOutUp','fadeOutUpBig','flip','flipInX','flipInY','flipOutX','flipOutY','lightSpeedIn','lightSpeedOut','rotateIn','rotateInDownLeft','rotateInDownRight','rotateInUpLeft','rotateInUpRight','rotateOut','rotateOutDownLeft','rotateOutDownRight','rotateOutUpLeft','rotateOutUpRight','slideInDown','slideInLeft','slideInRight','slideOutLeft','slideOutRight','slideOutUp','hinge','rollIn','rollOut']
    after: -> false
    before: -> false

  getConfigObject: (arg)->
    if typeof arg is "string" then text: arg else arg

  extendDefaults: (custom, defaults)->
    obj = angular.copy defaults
    obj = $.extend obj, custom

  addNotification: (args)->
    obj = @extendDefaults @getConfigObject(args), @defaults

    $rootScope.notifications.push obj
    $rootScope.$broadcast "new:notification", obj

  animate: (element, animation, config)->
    config = @extendDefaults config, @animateDefaults

    el = $(element).removeClass "animated " + config.animations.join(" ")

    do config.before
    el.addClass("animated " + animation).one "webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", ->
      el.removeClass "animated " + animation
      do config.after

  show: (args)->
    @addNotification args

  success: (args)->
    conf = @getConfigObject args
    conf.severity = "success"
    @addNotification conf

  danger: (args)->
    conf = @getConfigObject args
    conf.severity = "danger"
    @addNotification conf

  error: (args)->
    conf = @getConfigObject args
    conf.severity = "danger"
    conf.icon = "exclamation-circle"
    @addNotification conf

  warn: (args)->
    conf = @getConfigObject args
    conf.severity = "warning"
    conf.icon = "warning"
    @addNotification conf

]