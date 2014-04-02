Rigorix.controller "Pages.Riconoscimenti", ['$scope', 'Api', '$sce', ($scope, Api, $sce)->

  Api.get "riconoscimenti",
    success: (json) ->
      $scope.riconoscimenti = json.data

  $scope.getTrustedContent = (content)->
    $sce.trustAsHtml content.toString()

]