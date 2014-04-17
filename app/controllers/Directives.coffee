Rigorix.controller "Directive.InlineLoader", ['$scope', ($scope)->
  $scope.icon = "gear" if !$scope.icon?
  $scope.text = "Caricamento in corso..." if !$scope.text?

]

Rigorix.controller "Directive.Countdown", ['$scope', '$element', ($scope, $element)->
  $element.addClass "countdown"

  $scope.countdownFn = ->
    setTimeout =>
      $scope.seconds--
      $scope.$apply()
    , 1000

  $scope.restart = ->
    $scope.seconds = $scope.startSeconds if $scope.startSeconds?

  $scope.$watch "seconds", ->
    if $scope.seconds?
      $element.empty().append $scope.seconds

      $scope.startSeconds = $scope.seconds if !$scope.startSeconds?

      if $scope.seconds > 0
        do $scope.countdownFn
      else
        do $scope.$parent.$parent[$scope.on_finish] if $scope.on_finish?
        $scope.seconds = $scope.startSeconds if $scope.loop isnt "false"

]