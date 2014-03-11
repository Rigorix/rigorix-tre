# Refresh for area personale tabs, is triggered once route change and check the default tab and open it
Rigorix.directive "refreshStateOnLoad", ['$timeout', '$location', (timer, location)->
    link: (scope, element, attrs, ctrl) ->
      doRefresh = ()->
        window.location.href = $(element).find("li:first a").attr "href" if $(element).find("li.active").size() is 0

      timer doRefresh, 0
  ]


#-----------------------------------------------------------------------------------------------------------------------


Rigorix.directive "onSfidaLoad", ['$timeout', (timer)->
    link: (scope, element, attrs)->
      checkDeletedUser = ()->
        if $(element).find(".deleted").size() isnt 0
          element.addClass "deleted-user"
          $(element).find(".deleted").html $(element).find(".deleted").html().replace(RigorixConfig.deletedUsernameQuery, "")

      timer checkDeletedUser, 200
  ]


#-----------------------------------------------------------------------------------------------------------------------


Rigorix.directive "onListaSfideLoad", ()->
  (scope, element, attrs)->
    scope.__sfide = scope[attrs.onListaSfideLoad]
    scope.$on "user:update", ->
      scope.__sfide = scope[attrs.onListaSfideLoad]


#-----------------------------------------------------------------------------------------------------------------------


Rigorix.directive "beautifyDate", ()->
  restrict: 'E'
  templateUrl: '/app/templates/directives/beautify-date.html'
  scope:
    date_string: "@date"
    inline: "="


#-----------------------------------------------------------------------------------------------------------------------


Rigorix.directive "username", ()->
  restrict: 'E'
  templateUrl: '/app/templates/directives/username.html'
  controller: 'Username'
  scope:
    id_utente         : "=idUtente"
    with_picture      : "@withPicture"
    with_punteggio    : "@withPunteggio"
    tooltip_placement : "@tooltipPlacement"
    disabled          : "@"


#-----------------------------------------------------------------------------------------------------------------------


Rigorix.directive "listaSfide", ()->
  restrict: 'E'
  templateUrl: '/app/templates/directives/lista-sfide.html'
  scope:
    sfide: "="


#-----------------------------------------------------------------------------------------------------------------------


Rigorix.directive "icon", ->
  link: (scope, element, attrs)->
    attrs.$observe 'icon', (iconName)->
      element.prepend $('<span class="fa fa-'+iconName.toLowerCase()+' mrs"></span>')


#-----------------------------------------------------------------------------------------------------------------------


Rigorix.directive "gameTile", ->
  restrict: 'E'
  templateUrl: '/app/templates/game/tile.html'
  require: 'Game'
  scope:
    row: "="
    tileType: "="
    matrix: "@"


#-----------------------------------------------------------------------------------------------------------------------


Rigorix.directive "setLoader", ['RigorixUI', '$timeout', '$rootScope', (RigorixUI, $timeout, $rootScope)->
  link: (scope, element, attr)->

    RigorixUI.updateLoader attr.setLoader

    if attr.setLoader is '100'
      $timeout ()=>
        $rootScope.$broadcast "hide:loading"
        $rootScope.$broadcast "app:loaded"
      , 300
]


#-----------------------------------------------------------------------------------------------------------------------


Rigorix.directive "loading", ->
  restrict: 'E'
  templateUrl: '/app/templates/partials/inline-loading.html'
  controller: 'Directive.InlineLoader'
  scope:
    text: "="
    icon: "@customIcon"


#-----------------------------------------------------------------------------------------------------------------------


Rigorix.directive "notificationTimeout", ['$rootScope', ($rootScope)->

  dismissNotification = (scope, element, anim)->
    if anim? then element.addClass anim else element.addClass "bounceOutUp"
    element.one "webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", ->
      $rootScope.notifications = (notification for notification in $rootScope.notifications when notification isnt scope.notification)
      do element.remove

  (scope, element)->
    if scope.notification.timeout
      element.removeClass scope.animation

      element.on "click", =>
        dismissNotification scope, element, "hinge"

      setTimeout =>
        dismissNotification scope, element
      , scope.notification.timeout

]

##-----------------------------------------------------------------------------------------------------------------------
#
#
#Rigorix.directive "wysiwyg", ->
#  require: '?ngModel'
#  restrict: 'E'
#  link: (scope, el, attr, ngModel) ->
#    scope.redactor = el.redactor
#      focus: false
#      callback: (o)->
#        o.setCode scope.content
#        el.keydown ()->
#          console.log(o.getCode())
#          scope.$apply(ngModel.$setViewValue o.getCode())



#-----------------------------------------------------------------------------------------------------------------------
#
#
#Rigorix.directive "backgroundColor", ->
#  link: (scope, el, attr) ->
#    console.log "scope", scope
#    el.css "background-color", attr.backgroundColor
#
#
##-----------------------------------------------------------------------------------------------------------------------
#
