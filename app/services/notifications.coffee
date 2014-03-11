Rigorix.service "notify", ['$rootScope', ($rootScope)->
  $rootScope.notifications = [] if !$rootScope.notifications?

  defaults:
    text: ""
    animation: "bounceInDown" #"slideInDown"
    severity: "info"
    icon: 'info-circle'
    timeout: 7000

  getConfigObject: (arg)->
    if typeof arg is "string" then text: arg else arg

  addNotification: (args)->
    obj = angular.copy @defaults
    obj = $.extend obj, @getConfigObject(args)

    $rootScope.notifications.push obj
    console.log "$rootScope.notifications 2", $rootScope.notifications

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