var Rigorix, RigorixServices, SocialLoginUrl;

Rigorix = angular.module("Rigorix", ["ngRoute", "RigorixServices", "ui.bootstrap"]);

RigorixServices = angular.module("RigorixServices", ["ngResource"]);

SocialLoginUrl = "http://tre.rigorix.com/social_login.php";

Rigorix.config(function($routeProvider) {
  $routeProvider.when("/", {
    templateUrl: "app/templates/home.page.html",
    controller: "Home"
  });
  $routeProvider.when("/home", {
    templateUrl: "app/templates/home.page.html",
    controller: "Home"
  });
  $routeProvider.when("/logout", {
    templateUrl: "app/templates/home.page.html",
    controller: "Home"
  });
  $routeProvider.when("/area-personale", {
    templateUrl: "app/templates/area-personale/page.html",
    controller: "AreaPersonale"
  });
  $routeProvider.when("/area-personale/:section", {
    templateUrl: "app/templates/area-personale/page.html",
    controller: "AreaPersonale"
  });
  $routeProvider.when("/area-personale/:section/:sectionPage", {
    templateUrl: "app/templates/area-personale/page.html",
    controller: "AreaPersonale"
  });
  $routeProvider.when("/regolamento", {
    templateUrl: "app/templates/regolamento.page.html"
  });
  $routeProvider.when("/riconoscimenti", {
    templateUrl: "app/templates/riconoscimenti.page.html"
  });
  $routeProvider.when("/partners", {
    templateUrl: "app/templates/partners.page.html"
  });
  return $routeProvider.otherwise({
    templateUrl: "app/templates/lost.html"
  });
});

var RigorixConfig, RigorixStorage;

RigorixConfig = {
  updateTime: 60000,
  deletedUsernameQuery: "__DELETED__"
};

RigorixStorage = {
  users: {}
};

Rigorix.controller("AreaPersonale", function($scope, $routeParams, $location) {
  $scope.sections = ['utente', 'sfide', 'impostazioni', 'messaggi'];
  $scope.section = $routeParams.section;
  $scope.sectionPage = $routeParams.sectionPage;
  $scope.onClickAreaPersonaleSection = function(sec) {
    return $scope.$emit("areapersonale:change:section", sec);
  };
  if ($scope.section == null) {
    $location.path("/area-personale/utente");
  }
  return $scope.isCurrentPage = function(page, $first) {
    if ($routeParams.sectionPage != null) {
      return $routeParams.sectionPage === page;
    } else {
      return false;
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
  if ($scope.sectionPage === 'archivio') {
    $scope.sfideArchivio = SfideService.getArchivioSfide({
      limit_start: 0,
      limit_count: 15
    }, $scope.isLoading = false);
  }
  if ($scope.sectionPage === 'in_attesa_di_risposta') {
    $scope.sfideInAttesaDiRisposta = SfideService.getSfidePending($scope.isLoading = false);
  }
  if ($scope.sectionPage === "sfide_da_giocare") {
    return $scope.isLoading = false;
  }
});

Rigorix.controller("AreaPersonale.Impostazioni", function($scope) {
  $scope.isLoading = true;
  return $scope.pages = ['dati_utente', 'rigorix_mascotte', 'cancellazione_utente'];
});

Rigorix.controller("Header", function($scope) {
  return console.log("Header controller");
});

Rigorix.controller("Home", function($scope, UserService) {
  var _this = this;
  $scope.updateResources = function() {
    $scope.campione = UserService.getCampioneSettimana();
    return $scope.activeUsers = UserService.getActiveUsers();
  };
  $scope.updateResources();
  return setInterval(function() {
    return $scope.updateResources();
  }, 60000);
});

Rigorix.controller("ListaSfide", function($scope) {
  return true;
});

Rigorix.controller("ListaSfide.Sfida", function($scope, $modal) {
  $scope.id_avversario = $scope.sfida.id_sfidante === User.id_utente ? $scope.sfida.id_sfidato : $scope.sfida.id_sfidante;
  $scope.risultato = $scope.sfida.risultato.split(",");
  if (!moment($scope.sfida.dta_sfida).isValid()) {
    $scope.sfida.dta_sfida = false;
  }
  if (!moment($scope.sfida.dta_conclusa).isValid()) {
    $scope.sfida.dta_conclusa = false;
  }
  if ($scope.currentUser.id_utente === $scope.sfida.id_vincitore) {
    $scope.punti = 3;
    $scope.risultatoLabel = "won";
  } else if ($scope.sfida.id_vincitore === 0) {
    $scope.punti = 1;
    $scope.risultatoLabel = "draw";
  } else {
    $scope.punti = 0;
    $scope.risultatoLabel = "lose";
  }
  switch ($scope.sfida.stato) {
    case "0":
      $scope.statoButton = 'lancia_sfida';
      break;
    case "1":
      $scope.statoButton = $scope.sfida.id_sfidante === User.id_utente ? 'lanciata' : 'rispondi';
      break;
    case "2":
      $scope.statoButton = 'vedi_sfida';
      break;
    case "3":
      $scope.statoButton = 'vinta_a_tavolino';
      break;
    default:
      $scope.statoButton = $scope.sfida.stato;
  }
  return $scope.doClickSfida = function(action) {
    return $modal.open({
      templateUrl: '/app/templates/modals/sfida.html',
      controller: 'Modals.Sfida',
      resolve: {
        sfida: function() {
          return $scope.sfida;
        }
      }
    });
  };
});

Rigorix.controller("Main", function($scope, $modal, AuthService) {
  var _this = this;
  $scope.siteTitle = "Website title";
  $scope.userLogged = false;
  $scope.$on("$routeChangeStart", function(event, next, current) {
    if (next.$$route.originalPath === "/logout") {
      return $scope.$emit("LOGOUT");
    }
  });
  $scope.$on("LOGOUT", function() {
    var User;
    User = false;
    $scope.currentUser = null;
    $scope.userLogged = false;
    return alert("logout");
  });
  if (User !== false) {
    $scope.userLogged = true;
    $scope.currentUser = User;
    AuthService.get({
      action: "game",
      value: "status"
    }, function(json) {
      return $scope.currentUser = json;
    });
    return setInterval(function() {
      return AuthService.get({
        action: "game",
        value: "status"
      }, function(json) {
        return $scope.currentUser = json;
      });
    }, RigorixConfig.updateTime);
  }
});

Rigorix.controller("Modals", function($scope, $modal) {
  var ModalInstance;
  return ModalInstance = function($scope, $modalInstance, items) {
    $scope.ok = function() {
      alert("ok");
      return $modalInstance.close($scope.selected.item);
    };
    return $scope.cancel = function() {
      alert("cancel");
      return $modalInstance.dismiss('cancel');
    };
  };
});

Rigorix.controller("Modals.Sfida", function($scope, $modal, $modalInstance, sfida) {
  $scope.sfida = sfida;
  $scope.ok = function() {
    return $modalInstance.close();
  };
  return $scope.cancel = function() {
    return $modalInstance.dismiss();
  };
});

Rigorix.controller("Sidebar", function($scope, UserService) {
  $scope.topUsers = [];
  UserService.getTopUsers(function(users) {
    return $scope.topUsers = users;
  });
  return $scope.doAuth = function(social) {
    var auth_url;
    auth_url = RigorixEnv.REMOTE + "/social_login.php?provider=" + social + "&origin=" + RigorixEnv.DOMAIN + "&return_to=" + RigorixEnv.REMOTE;
    return window.open(auth_url, "hybridauth_social_sing_on", "location=0,status=0,scrollbars=0,width=800,height=500");
  };
});

Rigorix.directive("refreshStateOnLoad", [
  '$timeout', '$location', function(timer, location) {
    return {
      link: function(scope, element, attrs, ctrl) {
        var doRefresh;
        doRefresh = function() {
          if ($(element).find("li.active").size() === 0) {
            return window.location.href = $(element).find("li:first a").attr("href");
          }
        };
        return timer(doRefresh, 0);
      }
    };
  }
]);

Rigorix.directive("onSfidaLoad", [
  '$timeout', function(timer) {
    return {
      link: function(scope, element, attrs) {
        var checkDeletedUser;
        checkDeletedUser = function() {
          if ($(element).find(".deleted").size() !== 0) {
            element.addClass("deleted-user");
            return $(element).find(".deleted").html($(element).find(".deleted").html().replace(RigorixConfig.deletedUsernameQuery, ""));
          }
        };
        return timer(checkDeletedUser, 200);
      }
    };
  }
]);

Rigorix.directive("onListaSfideLoad", function() {
  return function(scope, element, attrs) {
    return scope.__sfide = scope[attrs.onListaSfideLoad];
  };
});

Rigorix.directive("beautifyDate", function() {
  return {
    restrict: 'E',
    templateUrl: '/app/templates/directives/beautify-date.html',
    scope: {
      date_string: "@sfidaDate"
    }
  };
});

Rigorix.directive("username", function(UserService) {
  return {
    restrict: 'E',
    templateUrl: '/app/templates/directives/username.html',
    link: function(scope, element, attr) {
      if (RigorixStorage.users[attr.idUtente] != null) {
        scope.userObject = RigorixStorage.users[attr.idUtente];
        return scope.userObject.deleted = scope.userObject.username.indexOf(RigorixConfig.deletedUsernameQuery) !== -1;
      } else {
        return UserService.getUsernameById({
          filter: attr.idUtente
        }, function(json) {
          scope.userObject = json;
          scope.userObject.deleted = json.username.indexOf(RigorixConfig.deletedUsernameQuery) !== -1;
          return RigorixStorage.users[attr.idUtente] = json;
        });
      }
    }
  };
});

Rigorix.filter("capitalize", function() {
  return function(input, scope) {
    return input.substring(0, 1).toUpperCase() + input.substring(1);
  };
});

Rigorix.filter("varToTitle", function() {
  return function(input) {
    input = input.split("_").join(" ");
    return input.substring(0, 1).toUpperCase() + input.substring(1);
  };
});

Rigorix.filter("stringToDate", function() {
  return function(input) {
    var date;
    console.log("stringToDate", input);
    date = new Date(input);
    return date;
  };
});



RigorixServices.factory("AuthService", function($resource) {
  return $resource(RigorixEnv.API_DOMAIN + "auth/:id_utente/:action/:value", {
    id_utente: User.id_utente,
    action: "@action",
    value: "@value",
    isArray: false
  });
});

RigorixServices.factory("SfideService", function($resource) {
  return $resource(RigorixEnv.API_DOMAIN + "sfide/:filter/:value", {
    filter: "@filter",
    value: "@value",
    isArray: false
  }, {
    getArchivioSfide: {
      method: 'GET',
      params: {
        filter: 'archivio',
        value: User.id_utente
      }
    },
    getSfidePending: {
      method: 'GET',
      params: {
        filter: 'pending',
        value: User.id_utente
      }
    }
  });
});

RigorixServices.factory("UserService", function($resource) {
  return $resource(RigorixEnv.API_DOMAIN + "users/:filter/:value", {
    filter: "@filter",
    value: "@value",
    isArray: false
  }, {
    getActiveUsers: {
      method: "GET",
      params: {
        filter: "active"
      }
    },
    getTopUsers: {
      method: "GET",
      params: {
        filter: "top",
        value: "10"
      }
    },
    getCampioneSettimana: {
      method: "GET",
      params: {
        filter: "campione",
        value: "settimana"
      }
    },
    getUsernameById: {
      method: 'GET',
      params: {
        filter: User.id_utente,
        value: 'username'
      }
    },
    getBadges: {
      method: "GET",
      params: {
        filter: User.id_utente,
        value: 'badges'
      }
    }
  });
});

RigorixServices.factory("AppService", function($resource) {
  return $resource(RigorixEnv.API_DOMAIN + ":param1/:param2/:param3", {
    param1: "@param1",
    param2: "@param2",
    param3: "@param3",
    isArray: false
  }, {
    getBadges: {
      method: 'GET',
      params: {
        param1: 'badges'
      }
    }
  });
});
