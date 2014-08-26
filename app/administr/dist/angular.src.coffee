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
RigorixAdmin.controller "Navigation", ["$scope", "$location", ($scope, $location)->

  $scope.activeItem = $location.$$path

  $scope.$on "$routeChangeStart", (event, next, current)->
    $scope.activeItem = $location.$$path

    console.log "activeItem=", $scope.activeItem

]
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
RigorixAdmin.controller "TableCell", ['$scope', '$rootScope', '$element', '$sce', ($scope, $rootScope, $element, $sce)->

  $scope.data = $scope.row[$scope.th]
  $scope.def = $scope.schema.fields[$scope.th]

  if $scope.def?
    if $scope.def.hidden is true
      $element.parent("td").remove()

  $scope.fieldContent = $scope.getFieldContent $scope.th, $scope.data, $scope.row
  $scope.fieldContent = $sce.trustAsHtml $scope.fieldContent.toString()

]
RigorixAdmin.controller "TableEdit", ["$scope", "$http", "$route", "$location", "$resource", ($scope, $http, $route, $location, $resource)->

  $scope.tableName = $route.current.params.table
  $scope.index = if $route.current.params.id? then parseInt $route.current.params.id, 10 else 0
  $scope.action = if $scope.index isnt 0 then "edit" else "create"

  $scope.backToList = ($event)->
    do $event.preventDefault
    $location.path "/tables/" + $scope.tableName

  $scope.doUpdate = ->
    $scope.data.$save table: $scope.tableName, index: $scope.index, (data)->
      console.log "Done saving", data

  $scope.resource = $resource "/api/tables/:table/:index",
    method  : "GET"
    isArray : false
    table   : "@table"
    index   : "@index"

  $scope.data = $scope.resource.get( table: $scope.tableName, index: $scope.index)

  console.log "data", $scope.data

#  if RigorixAdmin.tables[$scope.tableName]?
#    $scope.data = row for row in RigorixAdmin.tables[$scope.tableName] when row[RigorixAdmin.schemas[$scope.tableName].index] is $scope.index
#
#  else
#    $http.get("/api/tables/"+$scope.tableName+"/"+$scope.index).success (json)->
#      $scope.data = json

]
RigorixAdmin.controller "Tables", ['$scope', '$http', '$route', '$location', ($scope, $http, $route, $location)->

  $scope.tableName = $route.current.params.table
  $scope.schema = "loading"

  $scope.doAdd = ->
    $location.path "/tables/" + $scope.tableName + "/create"

  $http.get("/app/administr/schema/"+$scope.tableName+".json").then (schema)->
    $scope.schema = schema.data
    RigorixAdmin.schemas[$scope.tableName] = $scope.schema
  , ()->
    $scope.schema = false

  $http.get("/api/tables/"+$scope.tableName).success (table)->
    if table.length > 0
      $scope.table =
        header: []
        content: []
      for k,v of table[0]
        $scope.table.header.push k

      $scope.table.content = table
      RigorixAdmin.tables[$scope.tableName] = table

]
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
RigorixAdmin.filter "locationToTitle", ->
  (input)->
    title = input.split("/").join("")
    title.substring(0,1).toUpperCase()+title.substring(1)


RigorixAdmin.filter "logFileToPath", ->
  (input)->
    input.split(" ").join("_") + "_log.txt"


RigorixAdmin.filter "capitalize", ->
  (input) ->
    input.substring(0,1).toUpperCase()+input.substring(1)