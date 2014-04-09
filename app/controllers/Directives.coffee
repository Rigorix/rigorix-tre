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

  $scope.$watch "seconds", ->
    if $scope.seconds?
      $element.empty().append $scope.seconds

      if $scope.seconds > 0
        do $scope.countdownFn
      else
        do $scope.$parent.$parent[$scope.on_finish] if $scope.on_finish?

]