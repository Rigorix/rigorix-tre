Rigorix.filter "capitalize", ->
  (input, scope) ->
    input.substring(0,1).toUpperCase()+input.substring(1)

Rigorix.filter "varToTitle", ->
  (input) ->
    input = input.split("_").join(" ") if input.split("_").length > 1
    input.substring(0,1).toUpperCase()+input.substring(1)

Rigorix.filter "stringToDate", ->
  (input) ->
    moment(input)._d

Rigorix.filter "formatStringDate", ->
  (input) ->
    moment(input).format "Do MMM YYYY"

Rigorix.filter "length", ->
  (input) ->
    if input?
      if input.length? then input.length else Object.keys(input).length

Rigorix.filter "htmlToText", ->
  (input)->
    input = input.replace /<\/?[^>]+(>|$)/g, ""

Rigorix.filter 'unsafe', ['$sce', ($sce)->
  (input)->
    $sce.trustAsHtml input
]


Rigorix.directive "redactor", [ "$timeout", ($timeout) ->
    restrict: "A"
    require: "ngModel"
    link: (scope, element, attrs, ngModel) ->
      updateModel = (value) ->
        scope.$apply ->
          ngModel.$setViewValue value

      options = changeCallback: updateModel
      additionalOptions = (if attrs.redactor then scope.$eval(attrs.redactor) else {})
      editor = undefined
      $_element = angular.element(element)
      angular.extend options, additionalOptions

      $timeout ->
        editor = $_element.redactor(options)
        ngModel.$render()

      ngModel.$render = ->
        if angular.isDefined(editor)
          $timeout ->
            $_element.redactor "set", ngModel.$viewValue or ""
]