Rigorix.controller "UserPanel", ['$rootScope', '$scope', 'notify', '$modal', '$location', ($rootScope, $scope, notify, $modal, $location)->

  $scope.userPicture = $(".user-picture")
  $scope.notificationsCount = 0

#  Events
  $scope.$on "new:notification", ->
    notify.animate $scope.userPicture, "swing"

  $scope.$on "new:user:notifications", ->
    notify.animate $scope.userPicture, "swing"

  $scope.$on "user:update", ->
    do $scope.checkNotifications

  $scope.$on "messages:deleted", ->
    do $scope.updateUserObject

#  Methods
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