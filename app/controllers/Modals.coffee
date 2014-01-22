Rigorix.controller "Modals", ($scope, $modal)->

  ModalInstance = ($scope, $modalInstance, items)->

    $scope.ok = () ->
      alert "ok"
      $modalInstance.close $scope.selected.item

    $scope.cancel = ()->
      alert "cancel"
      $modalInstance.dismiss 'cancel'


Rigorix.controller "Modals.Sfida", ($scope, $modal, $modalInstance, sfida)->

  $scope.sfida = sfida

  $scope.ok = ->
    do $modalInstance.close #I can pass watever I choose from the front end if needed

  $scope.cancel = ->
    do $modalInstance.dismiss



#Rigorix.controller "Modals.Sfida.Instance", ($scope, $modal)->
#
#  ModalInstanceCtrl = ($scope, $modalInstance, items) ->
#
#    $scope.items = items;
#    $scope.selected =
#      item: $scope.items[0]
#
#
#    $scope.ok = () ->
#      $modalInstance.close $scope.selected.item
#
#
#    $scope.cancel = ()->
#      $modalInstance.dismiss 'cancel'