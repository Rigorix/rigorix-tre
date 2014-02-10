Rigorix.controller "Main", ($scope, $modal, $rootScope, AuthService, UserServiceNew, $location) ->

  $scope.siteTitle = "Website title"
  $scope.userLogged = false
  $scope.currentUser = false

  $scope.doClick = (event)->
    $rootScope.$broadcast "rootevent:click",
      event: event

#  $scope.$on "$routeChangeStart", (event, next, current)->
#    alert "logout"
##    TODO: remove
#    if next.$$route.originalPath == "/logout"
#      $scope.$emit "LOGOUT"

  $scope.$on "modal:open", (event, obj)->
    $scope.modalClass = obj.modalClass

  $scope.$on "modal:close", ->
    setTimeout =>
      $scope.modalClass = ''
    , 500

  $scope.$on "show:loading", ->
    $(".rigorix-loading").addClass "show"

  $scope.$on "hide:loading", ->
    $(".rigorix-loading").removeClass "show"

  $scope.$on "user:logout", ->
    User = false
    $scope.currentUser = null
    $scope.userLogged = false
    $location.path "/"



#-----------------------------------------------------------------------------------------------------------------------


  $scope.doUserLogout = ->
    $rootScope.$broadcast 'user:logout'


#-----------------------------------------------------------------------------------------------------------------------


  if RigorixEnv.FAKE_LOGIN? and RigorixEnv.FAKE_LOGIN isnt false and (!User? or User is false)
    $scope.fakeUser = true
    User = {"db_object":{"id_utente":5780,"attivo":1,"social_provider":"google","social_uid":"115304495556673294617","social_url":"https:\/\/profiles.google.com\/115304495556673294617","username":"Paolo_Morettiaa","picture":"/i/pictures/Foto-paolo-sagri.jpg","nome":"Paolo","cognome":"Moretti","data_nascita":"1980-07-21","sesso":"M","email":"littl.ebrown@gmail.com","email_utente":"littlebrown@gmail.com","punteggio_settimana":0,"punteggio_totale":199,"dta_reg":"2013-03-05 18:30:17","stato":0,"colore_maglietta":"#ffffff","tipo_maglietta":3,"numero_maglietta":10,"colore_pantaloncini":"#000000","colore_calzini":"#ffffff","dta_activ":"2014-02-06 15:02:39","hobby":"Ecco il mio hobby!!!","frase":"Ã¨ sua, non mia","giocatore":"Pieretto","squadra":"Juventus ovviamente!","tipo_alert":0},"messages":[],"totMessages":3,"badges":[{"id_reward":"9","tipo":"badge","nome":"Chi ben comincia","descrizione":"Hai fatto la tua prima partita.<br \/>\r\nSperiamo di vederti arrivare in alto","key_id":"b_first_game","score":"0","active":"1","id_sfida_reward":"176","id_sfida":"97","id_utente":"5780","notifica":"1","timestamp":"2013-07-29 02:48:16"}],"sfide_da_giocare":[{"id_sfida":149,"tipo_sfida":0,"id_sfidante":5795,"id_sfidato":5780,"dta_sfida":"2014-01-12 15:53:39","dta_conclusa":"2014-02-04 02:21:43","stato":1,"id_vincitore":5780,"punti_sfidante":3,"punti_sfidato":8,"risultato":"3,4","notifica":0},{"id_sfida":175,"tipo_sfida":0,"id_sfidante":5780,"id_sfidato":5780,"dta_sfida":"2014-02-08 15:33:00","dta_conclusa":"0000-00-00 00:00:00","stato":1,"id_vincitore":0,"punti_sfidante":0,"punti_sfidato":0,"risultato":"","notifica":0}],"rewards":{"punti":[{"id_reward":"3","tipo":"punto","nome":"Prima partita del giorno","descrizione":"Ben svegliato! La prima partita del giorno conta, per questo ti regaliamo 5 punti.","key_id":"p_first_day_match","score":"5","active":"1"},{"id_reward":"3","tipo":"punto","nome":"Prima partita del giorno","descrizione":"Ben svegliato! La prima partita del giorno conta, per questo ti regaliamo 5 punti.","key_id":"p_first_day_match","score":"5","active":"1"},{"id_reward":"7","tipo":"punto","nome":"<strong>Noioso!<\/strong> 10 sfide allo stesso utente oggi","descrizione":"Vederti giocare 10 o pi&ugrave; partite con lo stesso utente ci annoia :( Ti togliamo 5 punti!","key_id":"p_10_matches_sameuser","score":"-5","active":"1"},{"id_reward":"7","tipo":"punto","nome":"<strong>Noioso!<\/strong> 10 sfide allo stesso utente oggi","descrizione":"Vederti giocare 10 o pi&ugrave; partite con lo stesso utente ci annoia :( Ti togliamo 5 punti!","key_id":"p_10_matches_sameuser","score":"-5","active":"1"},{"id_reward":"7","tipo":"punto","nome":"<strong>Noioso!<\/strong> 10 sfide allo stesso utente oggi","descrizione":"Vederti giocare 10 o pi&ugrave; partite con lo stesso utente ci annoia :( Ti togliamo 5 punti!","key_id":"p_10_matches_sameuser","score":"-5","active":"1"},{"id_reward":"7","tipo":"punto","nome":"<strong>Noioso!<\/strong> 10 sfide allo stesso utente oggi","descrizione":"Vederti giocare 10 o pi&ugrave; partite con lo stesso utente ci annoia :( Ti togliamo 5 punti!","key_id":"p_10_matches_sameuser","score":"-5","active":"1"},{"id_reward":"7","tipo":"punto","nome":"<strong>Noioso!<\/strong> 10 sfide allo stesso utente oggi","descrizione":"Vederti giocare 10 o pi&ugrave; partite con lo stesso utente ci annoia :( Ti togliamo 5 punti!","key_id":"p_10_matches_sameuser","score":"-5","active":"1"},{"id_reward":"7","tipo":"punto","nome":"<strong>Noioso!<\/strong> 10 sfide allo stesso utente oggi","descrizione":"Vederti giocare 10 o pi&ugrave; partite con lo stesso utente ci annoia :( Ti togliamo 5 punti!","key_id":"p_10_matches_sameuser","score":"-5","active":"1"},{"id_reward":"3","tipo":"punto","nome":"Prima partita del giorno","descrizione":"Ben svegliato! La prima partita del giorno conta, per questo ti regaliamo 5 punti.","key_id":"p_first_day_match","score":"5","active":"1"},{"id_reward":"3","tipo":"punto","nome":"Prima partita del giorno","descrizione":"Ben svegliato! La prima partita del giorno conta, per questo ti regaliamo 5 punti.","key_id":"p_first_day_match","score":"5","active":"1"},{"id_reward":"6","tipo":"punto","nome":"Stesso anno di nascita","descrizione":"Hai la stessa et&egrave; del tuo sfidante, ti regaliamo un punto per la lotta pari!","key_id":"p_same_age","score":"1","active":"1"},{"id_reward":"6","tipo":"punto","nome":"Stesso anno di nascita","descrizione":"Hai la stessa et&egrave; del tuo sfidante, ti regaliamo un punto per la lotta pari!","key_id":"p_same_age","score":"1","active":"1"},{"id_reward":"6","tipo":"punto","nome":"Stesso anno di nascita","descrizione":"Hai la stessa et&egrave; del tuo sfidante, ti regaliamo un punto per la lotta pari!","key_id":"p_same_age","score":"1","active":"1"},{"id_reward":"3","tipo":"punto","nome":"Prima partita del giorno","descrizione":"Ben svegliato! La prima partita del giorno conta, per questo ti regaliamo 5 punti.","key_id":"p_first_day_match","score":"5","active":"1"},{"id_reward":"6","tipo":"punto","nome":"Stesso anno di nascita","descrizione":"Hai la stessa et&egrave; del tuo sfidante, ti regaliamo un punto per la lotta pari!","key_id":"p_same_age","score":"1","active":"1"},{"id_reward":"6","tipo":"punto","nome":"Stesso anno di nascita","descrizione":"Hai la stessa et&egrave; del tuo sfidante, ti regaliamo un punto per la lotta pari!","key_id":"p_same_age","score":"1","active":"1"},{"id_reward":"6","tipo":"punto","nome":"Stesso anno di nascita","descrizione":"Hai la stessa et&egrave; del tuo sfidante, ti regaliamo un punto per la lotta pari!","key_id":"p_same_age","score":"1","active":"1"},{"id_reward":"6","tipo":"punto","nome":"Stesso anno di nascita","descrizione":"Hai la stessa et&egrave; del tuo sfidante, ti regaliamo un punto per la lotta pari!","key_id":"p_same_age","score":"1","active":"1"},{"id_reward":"6","tipo":"punto","nome":"Stesso anno di nascita","descrizione":"Hai la stessa et&egrave; del tuo sfidante, ti regaliamo un punto per la lotta pari!","key_id":"p_same_age","score":"1","active":"1"},{"id_reward":"6","tipo":"punto","nome":"Stesso anno di nascita","descrizione":"Hai la stessa et&egrave; del tuo sfidante, ti regaliamo un punto per la lotta pari!","key_id":"p_same_age","score":"1","active":"1"},{"id_reward":"6","tipo":"punto","nome":"Stesso anno di nascita","descrizione":"Hai la stessa et&egrave; del tuo sfidante, ti regaliamo un punto per la lotta pari!","key_id":"p_same_age","score":"1","active":"1"},{"id_reward":"6","tipo":"punto","nome":"Stesso anno di nascita","descrizione":"Hai la stessa et&egrave; del tuo sfidante, ti regaliamo un punto per la lotta pari!","key_id":"p_same_age","score":"1","active":"1"},{"id_reward":"6","tipo":"punto","nome":"Stesso anno di nascita","descrizione":"Hai la stessa et&egrave; del tuo sfidante, ti regaliamo un punto per la lotta pari!","key_id":"p_same_age","score":"1","active":"1"},{"id_reward":"6","tipo":"punto","nome":"Stesso anno di nascita","descrizione":"Hai la stessa et&egrave; del tuo sfidante, ti regaliamo un punto per la lotta pari!","key_id":"p_same_age","score":"1","active":"1"},{"id_reward":"7","tipo":"punto","nome":"<strong>Noioso!<\/strong> 10 sfide allo stesso utente oggi","descrizione":"Vederti giocare 10 o pi&ugrave; partite con lo stesso utente ci annoia :( Ti togliamo 5 punti!","key_id":"p_10_matches_sameuser","score":"-5","active":"1"},{"id_reward":"6","tipo":"punto","nome":"Stesso anno di nascita","descrizione":"Hai la stessa et&egrave; del tuo sfidante, ti regaliamo un punto per la lotta pari!","key_id":"p_same_age","score":"1","active":"1"},{"id_reward":"7","tipo":"punto","nome":"<strong>Noioso!<\/strong> 10 sfide allo stesso utente oggi","descrizione":"Vederti giocare 10 o pi&ugrave; partite con lo stesso utente ci annoia :( Ti togliamo 5 punti!","key_id":"p_10_matches_sameuser","score":"-5","active":"1"},{"id_reward":"6","tipo":"punto","nome":"Stesso anno di nascita","descrizione":"Hai la stessa et&egrave; del tuo sfidante, ti regaliamo un punto per la lotta pari!","key_id":"p_same_age","score":"1","active":"1"},{"id_reward":"7","tipo":"punto","nome":"<strong>Noioso!<\/strong> 10 sfide allo stesso utente oggi","descrizione":"Vederti giocare 10 o pi&ugrave; partite con lo stesso utente ci annoia :( Ti togliamo 5 punti!","key_id":"p_10_matches_sameuser","score":"-5","active":"1"},{"id_reward":"3","tipo":"punto","nome":"Prima partita del giorno","descrizione":"Ben svegliato! La prima partita del giorno conta, per questo ti regaliamo 5 punti.","key_id":"p_first_day_match","score":"5","active":"1"},{"id_reward":"3","tipo":"punto","nome":"Prima partita del giorno","descrizione":"Ben svegliato! La prima partita del giorno conta, per questo ti regaliamo 5 punti.","key_id":"p_first_day_match","score":"5","active":"1"},{"id_reward":"3","tipo":"punto","nome":"Prima partita del giorno","descrizione":"Ben svegliato! La prima partita del giorno conta, per questo ti regaliamo 5 punti.","key_id":"p_first_day_match","score":"5","active":"1"},{"id_reward":"3","tipo":"punto","nome":"Prima partita del giorno","descrizione":"Ben svegliato! La prima partita del giorno conta, per questo ti regaliamo 5 punti.","key_id":"p_first_day_match","score":"5","active":"1"},{"id_reward":"3","tipo":"punto","nome":"Prima partita del giorno","descrizione":"Ben svegliato! La prima partita del giorno conta, per questo ti regaliamo 5 punti.","key_id":"p_first_day_match","score":"5","active":"1"},{"id_reward":"3","tipo":"punto","nome":"Prima partita del giorno","descrizione":"Ben svegliato! La prima partita del giorno conta, per questo ti regaliamo 5 punti.","key_id":"p_first_day_match","score":"5","active":"1"}],"badges":[{"id_reward":"9","tipo":"badge","nome":"Chi ben comincia","descrizione":"Hai fatto la tua prima partita.<br \/>\r\nSperiamo di vederti arrivare in alto","key_id":"b_first_game","score":"0","active":"1"}]},"picture":"Foto-paolo-sagri.jpg","id_utente":5780,"attivo":1,"social_provider":"google","social_uid":"115304495556673294617","social_url":"https:\/\/profiles.google.com\/115304495556673294617","username":"Paolo_Morettiaa","nome":"Paolo","cognome":"Moretti","data_nascita":"1980-07-21","sesso":"M","email":"littl.ebrown@gmail.com","email_utente":"littlebrown@gmail.com","punteggio_settimana":0,"punteggio_totale":199,"dta_reg":"2013-03-05 18:30:17","stato":0,"colore_maglietta":"#ffffff","tipo_maglietta":3,"numero_maglietta":10,"colore_pantaloncini":"#000000","colore_calzini":"#ffffff","dta_activ":"2014-02-06 15:02:39","hobby":"Ecco il mio hobby!!!","frase":"Ã¨ sua, non mia","giocatore":"Pieretto","squadra":"Juventus ovviamente!","tipo_alert":0}

  $scope.updateUserObject = ->
    UserServiceNew.get
      id_utente: User.id_utente
    ,
      (json)=>
        json.picture = RigorixConfig.userPicturePath + json.picture if json.picture.indexOf "http" is -1
        $scope.currentUser = json
        $rootScope.$broadcast "user:update", json

  if User isnt false
    $scope.userLogged = true
    $scope.currentUser = User
    do $scope.updateUserObject

    setInterval ()=>
      do $scope.updateUserObject

    , RigorixConfig.updateTime