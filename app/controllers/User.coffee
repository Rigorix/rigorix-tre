Rigorix.controller "User", ['$scope', '$rootScope', 'Api', ($scope, $rootScope, Api)->

  $scope.currentUser = $scope.$parent.currentUser;

  $scope.$watch "id_utente", (val)->
    if val isnt 0
      Api.getUser(val).then (userObject)->
        $scope.userObject = userObject

]