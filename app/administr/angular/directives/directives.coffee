RigorixAdmin.directive "icon", ->
  link: (scope, element, attr)->
    angular.element(element).prepend angular.element('<span style="margin-right: 7px" class="glyphicon glyphicon-'+attr.icon+'"></span>')


#-----------------------------------------------------------------------------------------------------------------------


RigorixAdmin.directive "adminCell", ->
  restrict: 'E'
  templateUrl: '/app/administr/templates/admin-table-cell.html'
  controller: 'TableCell'
  scope:
    data: "="
    column: "="
    table: "="