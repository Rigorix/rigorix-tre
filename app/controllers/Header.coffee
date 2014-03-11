Rigorix.controller "Header", ['$scope', '$rootScope', '$location', 'notify', ($scope, $rootScope, $location, notify) ->

  $scope.showUserPopout = false;

  $scope.$on "rootevent:click", (ev, args)->
    $scope.showUserPopout = false if $(args.event.target).parents(".user-container").size() is 0

#  $scope.$on "new:user:notifications", ->
#    alert "header know about new notifications"
#
#  $scope.$on "same:user:notifications", ->
#    alert "header know about no new notifications"

  $scope.doClickLogo = ->
    $location.path "/"

  $scope.toggleVisibility = ->
    $scope.showUserPopout = !$scope.showUserPopout
    if $scope.showUserPopout is true
      notify.animate ".user-container", "fadeInDown"

]