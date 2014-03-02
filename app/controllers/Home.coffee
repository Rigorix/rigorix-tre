Rigorix.controller "Home", ['$scope', 'Api', ($scope, Api) ->

  $scope.campione = false

  $scope.updateResources = ->
    Api.call "get", "users/active",
      success: (json)->
        $scope.activeUsers = json.data

    $scope.campione = false

    Api.call "get", "users/champion/week",
      success: (champion)->
        $scope.campione = champion.data

      error: (message, status)->
        if status is 404
          $scope.campione = false

  do $scope.updateResources

  setInterval ()=>
    do $scope.updateResources
  , 60000

]