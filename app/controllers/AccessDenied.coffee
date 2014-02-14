Rigorix.controller "AccessDenied", ($scope, $modal, $rootScope)->

  $scope.doConfirmUnsubscription = ->
    $modal.open
      templateUrl:  '/app/templates/modals/modal.html',
      controller:    'AccessDenied.Modal',
      resolve:
        data: ->
          title: "Attenzione"
          text: "Sei sicuro di voler confermare la disiscrizione del tuo utente?"
          buttons: [
              label: "Conferma"
              class: "btn-danger"
              callback: (event)->
                angular.element(event.currentTarget).scope().doConfirm()
            ,
              label: "Annulla"
              callback: (event)->
                angular.element(event.currentTarget).scope().cancel()
          ]

  $scope.doDiscardUnsubscription = ->


#-----------------------------------------------------------------------------------------------------------------------


Rigorix.controller "AccessDenied.Modal", ($scope, $modal, $modalInstance, $rootScope, data)->

  $rootScope.$broadcast "modal:open",
    controller: 'AccessDenied.Modal'
    modalClass: 'modal-confirm'

  $scope.data = data

  $modalInstance.result.then () ->
    true
  , ()->
    $rootScope.$broadcast "modal:close"

  $scope.doConfirm = ->
    alert "Sviluppare su tre.rigorix.com la funzionalita con la mail che arriva e il codice."

  $scope.cancel = ->
    do $modalInstance.dismiss
    $rootScope.$broadcast "modal:close"