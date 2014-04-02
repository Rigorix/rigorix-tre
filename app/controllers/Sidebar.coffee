Rigorix.controller "Sidebar", ['$scope', 'Api', '$rootScope', ($scope, Api)->

  $scope.topUsers = []
  $scope.topUsersLoaded = false

  Api.call "get", "users/top/10",
    success: (json)->
      $scope.topUsers = json.data
      $scope.topUsersLoaded = true

]