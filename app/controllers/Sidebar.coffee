Rigorix.controller "Sidebar", ['$scope', 'Api', '$rootScope', ($scope, Api)->

  Api.call "get", "users/top/10",
    success: (json)->
      $scope.topUsers = json.data

]