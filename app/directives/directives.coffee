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


Rigorix.directive "user", ()->
  restrict: 'E'
  templateUrl: '/app/templates/directives/user.html'
  controller: 'User'
  scope:
    id_utente: "=idUtente"


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
      element.find(".fa").removeClass "mrs" if attrs.iconMargin is 'false'


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


Rigorix.directive "loading", ->
  restrict: 'E'
  templateUrl: '/app/templates/partials/inline-loading.html'
  controller: 'Directive.InlineLoader'
  scope:
    text: "="
    icon: "@customIcon"


#-----------------------------------------------------------------------------------------------------------------------


Rigorix.directive "waitFor", ->
  link: (scope, element, attrs) ->
    Rigorix.Storage.waiters[attrs.waitFor] =
      status: false

    element.addClass "is-waiting"
    element.append $('<div class="loader">Caricamento ...</div>')

    scope.$watch attrs.waitFor, (newValue)->
      if newValue? and newValue isnt false
        element.removeClass("is-waiting").addClass "has-finish-waiting"
        do element.find(".loader").remove
        Rigorix.Storage.waiters[attrs.waitFor].status = true


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