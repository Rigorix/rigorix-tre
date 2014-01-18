// Generated by CoffeeScript 1.6.3
Rigorix.controller("AreaPersonale", function($scope, $routeParams, $location) {
  $scope.sections = ['utente', 'sfide', 'impostazioni', 'messaggi'];
  $scope.section = $routeParams.section;
  $scope.sectionPage = $routeParams.sectionPage;
  if ($scope.section == null) {
    $location.path("/area-personale/utente");
  }
  return $scope.isCurrentPage = function(page, $first) {
    if ($routeParams.sectionPage != null) {
      return $routeParams.sectionPage === page;
    } else {
      return $first;
    }
  };
});

Rigorix.controller("AreaPersonale.Utente", function($scope, AppService) {
  $scope.isLoading = true;
  $scope.pages = ['palmares'];
  $scope.rewards = AppService.getBadges(function() {
    $(".game-loader").remove();
    return $scope.isLoading = false;
  });
  return $scope.userHasBadge = function(reward) {
    var badge, ret, _i, _len, _ref;
    ret = false;
    if (reward.tipo === 'badge') {
      _ref = $scope.currentUser.rewards.badges;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        badge = _ref[_i];
        if (reward.key_id === badge.key_id) {
          ret = true;
        }
      }
    }
    return ret;
  };
});

Rigorix.controller("AreaPersonale.Sfide", function($scope, SfideService) {
  $scope.isLoading = true;
  $scope.pages = ['sfide_da_giocare', 'in_attesa_di_risposta', 'archivio'];
  $scope.sfideDaGiocare = $scope.currentUser.sfide_da_giocare;
  $scope.sfideArchivio = [];
  if ($scope.sectionPage === 'archivio') {
    return $scope.sfideArchivio = SfideService.getArchivioSfide({
      limit_start: 0,
      limit_count: 15
    }, $scope.isLoading = false);
  }
});

Rigorix.controller("AreaPersonale.Impostazioni", function($scope) {
  $scope.isLoading = true;
  return $scope.pages = ['dati_utente', 'rigorix_mascotte', 'cancellazione_utente'];
});
