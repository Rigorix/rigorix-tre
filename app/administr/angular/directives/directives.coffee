RigorixAdmin.directive "icon", ->
  link: (scope, element, attr)->
    angular.element(element).prepend angular.element('<span style="margin-right: 7px" class="glyphicon glyphicon-'+attr.icon+'"></span>')


#-----------------------------------------------------------------------------------------------------------------------
