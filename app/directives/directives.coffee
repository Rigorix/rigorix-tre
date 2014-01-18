Rigorix.directive "refreshStateOnLoad", ()->
  (scope, element, attrs) ->
    false
#
#    if scope.$first
#      backgroundSrc = scope.$parent.pictures[parseInt(Math.random()*scope.$parent.pictures.length)].src
#    if scope.$last
#      # If is last image, build the catalogue
#      scope.$parent.buildCatalogue()


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