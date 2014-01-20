
# Refresh for area personale tabs, is triggered once route change and check the default tab and open it
Rigorix.directive "refreshStateOnLoad", ['$timeout', '$location', (timer, location)->
    link: (scope, element, attrs, ctrl) ->
      doRefresh = ()->
        window.location.href = $(element).find("li:first a").attr "href" if $(element).find("li.active").size() is 0

      timer doRefresh, 0
  ]

Rigorix.directive "onListaSfideLoad", ()->
  (scope, element, attrs)->
    scope.__sfide = scope[attrs.onListaSfideLoad]

Rigorix.directive "beautifyDate", ()->
  restrict: 'E'
  templateUrl: '/app/templates/directives/beautify-date.html'
  link: (scope, element, attr) ->
    scope.date = attr.date

Rigorix.directive "username", (UserService)->
  restrict: 'E'
  templateUrl: '/app/templates/directives/username.html'
  link: (scope, element, attr) ->
    if RigorixStorage.users[attr.idUtente]?
      scope.userObject = RigorixStorage.users[attr.idUtente]
    else
      UserService.getUsernameById
        filter: attr.idUtente
      ,
        (json)->
          scope.userObject = json
          RigorixStorage.users[attr.idUtente] = json