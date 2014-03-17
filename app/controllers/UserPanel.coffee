Rigorix.controller "UserPanel", ['$rootScope', '$scope', 'notify', '$modal', ($rootScope, $scope, notify, $modal)->

  $scope.userPicture = $(".user-picture")
  $scope.notificationsCount = 0

  $scope.$on "new:notification", ->
    notify.animate $scope.userPicture, "swing"

  $scope.$on "new:user:notifications", ->
    notify.animate $scope.userPicture, "swing"

  $scope.$on "user:update", ->
    do $scope.checkNotifications

  $scope.doLanciaNewSfida = ->
    notify.animate ".lancia-sfida", "bounce"

    $modal.open
      templateUrl:  '/app/templates/modals/sfida.html',
      controller:    'Modals.NewSfida',
    false

  $scope.checkNotifications = ->
    actualNotifications = 0
    actualNotifications += $scope.currentUser.messages.length
    actualNotifications += $scope.currentUser.has_new_badges
    actualNotifications += $scope.currentUser.sfide_da_giocare.length

    $rootScope.$broadcast "new:user:notifications" if $scope.notificationsCount isnt 0 and actualNotifications > $scope.notificationsCount
    $scope.notificationsCount = actualNotifications

]