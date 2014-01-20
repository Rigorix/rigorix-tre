Rigorix.controller "Main", ($scope, AuthService) ->

  $scope.siteTitle = "Website title"
  $scope.userLogged = false

  $scope.$on "$routeChangeStart", (event, next, current)->

    if next.$$route.originalPath == "/logout"
      $scope.$emit "LOGOUT"

  $scope.$on "LOGOUT", ->
    User = false
    $scope.currentUser = null
    $scope.userLogged = false

  if User isnt false
    $scope.userLogged = true
    $scope.currentUser = User

    AuthService.get { action: "game", value: "status"}, (json)=>
      $scope.currentUser = json

    setInterval ()=>
      AuthService.get { action: "game", value: "status"}, (json)=>
        $scope.currentUser = json

    , RigorixConfig.updateTime