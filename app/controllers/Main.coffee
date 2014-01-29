Rigorix.controller "Main", ($scope, $modal, $rootScope, AuthService, RigorixUI) ->

  $scope.siteTitle = "Website title"
  $scope.userLogged = false

  $scope.$on "$routeChangeStart", (event, next, current)->
    if next.$$route.originalPath == "/logout"
      $scope.$emit "LOGOUT"

  $scope.$on "modal:open", (event, obj)->
    $scope.modalClass = obj.modalClass

  $scope.$on "modal:close", ->
    $scope.modalClass = ''

  $scope.$on "show:loading", ->
    $(".rigorix-loading").addClass "show"

  $scope.$on "hide:loading", ->
    $(".rigorix-loading").removeClass "show"

  $scope.$on "user:logout", ->
    User = false
    $scope.currentUser = null
    $scope.userLogged = false

  $scope.$on "*", (ev, $rootScope)->
    $rootScope.$broadcast "event:received", ev


#-----------------------------------------------------------------------------------------------------------------------


  $scope.onUserLogout = ->
    $rootScope.$broadcast 'user:logout'


#-----------------------------------------------------------------------------------------------------------------------



  if User isnt false
    $scope.userLogged = true
    $scope.currentUser = User

    AuthService.get { action: "game", value: "status"}, (json)=>
      $scope.currentUser = json

    setInterval ()=>
      AuthService.get { action: "game", value: "status"}, (json)=>
        $scope.currentUser = json
        $rootScope.$broadcast "currentuser:update", json

    , RigorixConfig.updateTime
