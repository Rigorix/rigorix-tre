RigorixAdmin.controller "Logs", ['$scope', '$rootScope', '$http', '$location', '$routeParams', ($scope, $rootScope, $http, $location, $routeParams)->

  $http.get("/api/logs").success (logs)->
    $scope.logs = logs

  $scope.logfile = if $routeParams.logfile? then $routeParams.logfile else false

  if $scope.logfile isnt false
    $http.get("/api/logs/"+$scope.logfile).success (content)->
      $scope.logContent = content

  $scope.isActiveLogFile = (file)->
    ret = file.split(" ").join("_")+"_log.txt" is $scope.logfile
    ret

]