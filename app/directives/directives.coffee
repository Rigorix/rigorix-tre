# Refresh for area personale tabs, is triggered once route change and check the default tab and open it
Rigorix.directive "refreshStateOnLoad", ['$timeout', '$location', (timer, location)->
    link: (scope, element, attrs, ctrl) ->
      doRefresh = ()->
        window.location.href = $(element).find("li:first a").attr "href" if $(element).find("li.active").size() is 0

      timer doRefresh, 0
  ]


#-----------------------------------------------------------------------------------------------------------------------


Rigorix.directive "onSfidaLoad", ['$timeout', (timer)->
    link: (scope, element, attrs)->
      checkDeletedUser = ()->
        if $(element).find(".deleted").size() isnt 0
          element.addClass "deleted-user"
          $(element).find(".deleted").html $(element).find(".deleted").html().replace(RigorixConfig.deletedUsernameQuery, "")

      timer checkDeletedUser, 200
  ]


#-----------------------------------------------------------------------------------------------------------------------


Rigorix.directive "onListaSfideLoad", ()->
  (scope, element, attrs)->
    scope.__sfide = scope[attrs.onListaSfideLoad]
    scope.$on "user:update", ->
      scope.__sfide = scope[attrs.onListaSfideLoad]


#-----------------------------------------------------------------------------------------------------------------------


Rigorix.directive "beautifyDate", ()->
  restrict: 'E'
  templateUrl: '/app/templates/directives/beautify-date.html'
  scope:
    date_string: "@date"
    inline: "="


#-----------------------------------------------------------------------------------------------------------------------


Rigorix.directive "username", ()->
  restrict: 'E'
  templateUrl: '/app/templates/directives/username.html'
  controller: 'Username'
  scope:
    id_utente       : "=idUtente"
    with_picture    : "@withPicture"
    with_punteggio  : "@withPunteggio"


#-----------------------------------------------------------------------------------------------------------------------


Rigorix.directive "usernameWithThumb", (AppService)->
  restrict: 'E'
  templateUrl: '/app/templates/directives/username-with-thumb.html'
  link: (scope, element, attr) ->

    if attr.idUtente? and attr.username and attr.picture
      console.log "Vai"
      scope.userObject =
        id_utente: attr.id_utente
        username: attr.username
        picture: attr.picture
      RigorixStorage.users[attr.idUtente] = scope.userObject
    else
      if RigorixStorage.users[attr.idUtente]? and RigorixStorage.users[attr.idUtente].picture?
        console.log "trovato, ma: ", RigorixStorage.users[attr.idUtente]
        scope.userObject = RigorixStorage.users[attr.idUtente]
      else
        AppService.getUserById
          param2: attr.idUtente
        ,
        (json)->
          console.log "Aggiunto con immagine", json
          scope.userObject = json
          RigorixStorage.users[attr.idUtente] = json


#-----------------------------------------------------------------------------------------------------------------------


Rigorix.directive "listaSfide", ()->
  restrict: 'E'
  templateUrl: '/app/templates/lista-sfide.html'
  scope:
    sfide: "="


#-----------------------------------------------------------------------------------------------------------------------


Rigorix.directive "icon", ->
  link: (scope, element, attr)->
    $(element).prepend $('<span class="glyphicon glyphicon-'+attr.icon+' mrs"></span>')


#-----------------------------------------------------------------------------------------------------------------------


Rigorix.directive "gameTile", ->
  restrict: 'E'
  templateUrl: '/app/templates/game/tile.html'
  require: 'Game'
  scope:
    row: "="
    tileType: "="
    matrix: "@"


#-----------------------------------------------------------------------------------------------------------------------


Rigorix.directive "setLoader", ['RigorixUI', '$timeout', '$rootScope', (RigorixUI, $timeout, $rootScope)->
  link: (scope, element, attr)->

    RigorixUI.updateLoader attr.setLoader

    if attr.setLoader is '100'
      $timeout ()=>
        $rootScope.$broadcast "hide:loading"
        $rootScope.$broadcast "app:loaded"
      , 300
]


##-----------------------------------------------------------------------------------------------------------------------
#
#
#Rigorix.directive "wysiwyg", ->
#  require: '?ngModel'
#  restrict: 'E'
#  link: (scope, el, attr, ngModel) ->
#    scope.redactor = el.redactor
#      focus: false
#      callback: (o)->
#        o.setCode scope.content
#        el.keydown ()->
#          console.log(o.getCode())
#          scope.$apply(ngModel.$setViewValue o.getCode())



#-----------------------------------------------------------------------------------------------------------------------
#
#
#Rigorix.directive "backgroundColor", ->
#  link: (scope, el, attr) ->
#    console.log "scope", scope
#    el.css "background-color", attr.backgroundColor
#
#
##-----------------------------------------------------------------------------------------------------------------------
#
