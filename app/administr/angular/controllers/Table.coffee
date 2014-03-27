RigorixAdmin.controller "Table", ["$scope", "$http", "$element", "$q", "$route", "$location", ($scope, $http, $element, $q, $route, $location)->

  $scope.tableName = $route.current.params.table
  $scope.temp = []

  $scope.getFieldContent = (fieldName, data, row)->

    if $scope.schema.fields[fieldName]?
      conf = $scope.schema.fields[fieldName]
      return switch conf.type

        when "index" then "#"+data
        when "date" then moment(data).format("Do MMM YY")
        when "color" then '<div class="type-color" style="background: '+data+';">'+data+'</div>'
        when "userpicture" then '<img src="'+data+'" class="user-picture" />'
        when "relation"
          relationId = fieldName+'_'+row[$scope.schema.index]+'_promise';
          $http.get("/api/relations/"+conf.relation.table+"/"+data+"/"+conf.relation.showField).then (json)->
            angular.element('#' + relationId).html json.data

          return '<div id="'+relationId+'">Loading...</div>'

#        when "custom"
#          tpl = $compile(conf.template)($scope)
#          tpl
        when "boolean"
          if data in [0, "0", false, "false"] then "false" else "true"

    data

]


#-----------------------------------------------------------------------------------------------------------------------


RigorixAdmin.controller "TableRow", ["$scope", "$location", "$resource", "$route", ($scope, $location, $resource, $route)->

  $scope.doEdit = ->
    $location.path "/tables/" + $scope.tableName + "/edit/" + $scope.row[$scope.schema.index]

  $scope.doDelete = ->
    if confirm "delete"
      entry = $resource "/api/tables/"+$scope.tableName+"/"+$scope.row[$scope.schema.index],
        method      : "GET"
        isArray     : false
      entry.get().$delete()
      $route.reload()

]


#-----------------------------------------------------------------------------------------------------------------------


RigorixAdmin.controller "TableTh", ["$scope", "$element", ($scope, $element)->

  $scope.def = $scope.schema.fields[$scope.th]

  if $scope.def?
    $scope.fieldContent = if $scope.def.name? then $scope.def.name else $scope.th

    if $scope.def.hidden is true
      $element.parent("th").remove()

  else
    $scope.fieldContent = $scope.th

]