# Refresh for area personale tabs, is triggered once route change and check the default tab and open it
Rigorix.directive "refreshStateOnLoad", ['$timeout', '$location', (timer, location)->
    link: (scope, element, attrs, ctrl) ->
      doRefresh = ()->
        window.location.href = $(element).find("li:first a").attr "href" if $(element).find("li.active").size() is 0

      timer doRefresh, 0
  ]

Rigorix.directive "onSfidaLoad", ['$timeout', (timer)->
    link: (scope, element, attrs)->
      checkDeletedUser = ()->
        if $(element).find(".deleted").size() isnt 0
          element.addClass "deleted-user"
          $(element).find(".deleted").html $(element).find(".deleted").html().replace(RigorixConfig.deletedUsernameQuery, "")

      timer checkDeletedUser, 200
  ]

Rigorix.directive "onListaSfideLoad", ()->
  (scope, element, attrs)->
    scope.__sfide = scope[attrs.onListaSfideLoad]

Rigorix.directive "beautifyDate", ()->
  restrict: 'E'
  templateUrl: '/app/templates/directives/beautify-date.html'
  scope:
    date_string: "@sfidaDate"

Rigorix.directive "username", (UserService)->
  restrict: 'E'
  templateUrl: '/app/templates/directives/username.html'
  link: (scope, element, attr) ->
    if RigorixStorage.users[attr.idUtente]?
      scope.userObject = RigorixStorage.users[attr.idUtente]
      scope.userObject.deleted = scope.userObject.username.indexOf(RigorixConfig.deletedUsernameQuery) isnt -1
    else
      UserService.getUsernameById
        filter: attr.idUtente
      ,
        (json)->
          scope.userObject = json
          scope.userObject.deleted = json.username.indexOf(RigorixConfig.deletedUsernameQuery) isnt -1
          RigorixStorage.users[attr.idUtente] = json


Rigorix.directive "gameTile", ->
  restrict: 'E'
  templateUrl: '/app/templates/game/tile.html'
  require: 'Game'
  scope:
    row: "="
    tileType: "="
    matrix: "@"