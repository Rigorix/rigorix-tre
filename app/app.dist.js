var Rigorix, RigorixServices, SocialLoginUrl;

Rigorix = angular.module("Rigorix", ["ngRoute", "RigorixServices", "ui.bootstrap"]);

RigorixServices = angular.module("RigorixServices", ["ngResource"]);

SocialLoginUrl = "http://tre.rigorix.com/social_login.php";

Rigorix.config(function($routeProvider) {
  $routeProvider.when("/", {
    templateUrl: "app/templates/pages/home.page.html",
    controller: "Home"
  });
  $routeProvider.when("/home", {
    templateUrl: "app/templates/pages/home.page.html",
    controller: "Home"
  });
  $routeProvider.when("/logout", {
    templateUrl: "app/templates/pages/home.page.html",
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
    templateUrl: "app/templates/pages/regolamento.page.html"
  });
  $routeProvider.when("/riconoscimenti", {
    templateUrl: "app/templates/pages/riconoscimenti.page.html"
  });
  $routeProvider.when("/partners", {
    templateUrl: "app/templates/pages/partners.page.html"
  });
  return $routeProvider.otherwise({
    templateUrl: "app/templates/pages/lost.html"
  });
});

var RigorixConfig, RigorixStorage;

RigorixConfig = {
  updateTime: 10000,
  deletedUsernameQuery: "__DELETED__",
  messagesPerPage: 15
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
  return $scope.isCurrentPage = function(page) {
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

Rigorix.controller("AreaPersonale.Sfide", function($scope, SfideService, $route) {
  $scope.isLoading = true;
  $scope.pages = ['sfide_da_giocare', 'in_attesa_di_risposta', 'archivio'];
  $scope.$on("currentuser:update", function(event, userObject) {
    return $scope.sfideDaGiocare = userObject.sfide_da_giocare;
  });
  $scope.loadSfide = function() {
    if ($scope.sectionPage === "sfide_da_giocare") {
      $scope.isLoading = false;
    }
    $scope.sfideDaGiocare = $scope.currentUser.sfide_da_giocare;
    if ($scope.sectionPage === 'archivio') {
      $scope.sfideArchivio = SfideService.getArchivioSfide({
        limit_start: 0,
        limit_count: 15
      }, $scope.isLoading = false);
    }
    if ($scope.sectionPage === 'in_attesa_di_risposta') {
      return $scope.sfideInAttesaDiRisposta = SfideService.getSfidePending($scope.isLoading = false);
    }
  };
  $scope.loadSfide();
  return $scope.reload = function() {
    return $route.reload();
  };
});

Rigorix.controller("AreaPersonale.Impostazioni", function($scope) {
  $scope.isLoading = true;
  return $scope.pages = ['dati_utente', 'rigorix_mascotte', 'cancellazione_utente'];
});

Rigorix.controller("GamePlay", function($scope, $timeout, $rootScope, $modal, SfideService) {
  $scope.rows = [
    {
      index: 0
    }, {
      index: 1
    }, {
      index: 2
    }, {
      index: 3
    }, {
      index: 4
    }
  ];
  $scope.matrix = {
    '0': {
      tiro: false,
      parata: false
    },
    '1': {
      tiro: false,
      parata: false
    },
    '2': {
      tiro: false,
      parata: false
    },
    '3': {
      tiro: false,
      parata: false
    },
    '4': {
      tiro: false,
      parata: false
    }
  };
  $scope.submitButtonLabel = $scope.sfida.id_sfida !== false ? "Rispondi" : "Lancia";
  $scope.id_utente_avversario = $scope.sfida.id_sfida !== false ? $scope.sfida.id_sfidante : $scope.sfida.id_avversario;
  $scope.randomPlaySet = function() {
    var _this = this;
    return $timeout(function() {
      var randParata, randTiro, row, _i, _len, _ref, _results;
      _ref = _this.rows;
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        row = _ref[_i];
        randTiro = Math.ceil(Math.random(0, 1) * 3) - 1;
        randParata = Math.ceil(Math.random(0, 1) * 3) - 1;
        $("#gameSetBox_tiro_" + row.index + " .game-tile[value=" + randTiro + "]").click();
        _results.push($("#gameSetBox_parata_" + row.index + " .game-tile[value=" + randParata + "]").click());
      }
      return _results;
    }, 0);
  };
  $scope.resetPlaySet = function() {
    var index, value, _ref, _results;
    $('.game-tile').removeClass("active");
    _ref = $scope.matrix;
    _results = [];
    for (index in _ref) {
      value = _ref[index];
      $scope.matrix[index].tiro = false;
      _results.push($scope.matrix[index].parata = false);
    }
    return _results;
  };
  $scope.submitSfida = function() {
    var index, value, _ref;
    _ref = $scope.matrix;
    for (index in _ref) {
      value = _ref[index];
      if (value.tiro === false || value.parata === false) {
        return alert("errore, compila tutto");
      }
    }
    return this.sendSfida();
  };
  return $scope.sendSfida = function() {
    var index, matrix, row, value, _ref;
    $rootScope.$broadcast("show:loading");
    matrix = {};
    _ref = $scope.matrix;
    for (index in _ref) {
      value = _ref[index];
      row = Number(index) + 1;
      matrix['tiro' + row] = value.tiro;
      matrix['parata' + row] = value.parata;
    }
    return SfideService.sendSfida({
      value: $scope.sfida.id_sfida,
      sfida_matrix: JSON.stringify(matrix),
      sfida: $scope.sfida
    }, function(json) {
      $rootScope.$broadcast("hide:loading");
      if (json.status === "success") {
        Modals.success({
          title: "titolo",
          text: "testo"
        });
        return alert("Sfida inserita correttamente");
      } else {
        return alert("Errore " + json.error_code);
      }
    });
  };
});

Rigorix.controller("GamePlay.Tile", function($scope, $element) {
  $scope.tileValue = false;
  $scope.GamePlay = $scope.$parent.$parent;
  $scope.subject = $scope.tileType === "parata" ? "portiere" : "pallone";
  $scope.dir = {
    sx: $scope.tileType === "parata" ? "Sx" : "",
    dx: $scope.tileType === "parata" ? "Dx" : ""
  };
  return $scope.setTileValue = function(value) {
    $scope.tileValue = value;
    $element.find('.game-tile').removeClass("active");
    $element.find('.game-tile[value=' + value + ']').addClass('active');
    return $scope.GamePlay.matrix[$scope.row.index][$scope.tileType] = value;
  };
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
  $scope.sfida.id_avversario = $scope.id_avversario;
  $scope.sfida.id_utente = $scope.currentUser.id_utente;
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
  if ($scope.sfida.stato < 2) {
    $scope.risultatoLabel = 'ongoing';
  }
  $scope.hasActiveButton = true;
  switch ($scope.sfida.stato) {
    case "0":
      $scope.statoButton = 'lancia_sfida';
      $scope.statoButtonIcon = 'send';
      break;
    case "1":
      $scope.statoButton = $scope.sfida.id_sfidante === User.id_utente ? 'lanciata' : 'rispondi';
      $scope.statoButtonIcon = $scope.statoButton === 'lanciata' ? 'send' : 'share-alt';
      $scope.hasActiveButton = $scope.statoButton === 'rispondi';
      break;
    case "2":
      $scope.statoButton = 'vedi_sfida';
      $scope.statoButtonIcon = 'eye-open';
      break;
    case "3":
      $scope.statoButton = 'vinta_a_tavolino';
      break;
    default:
      $scope.statoButton = $scope.sfida.stato;
      $scope.statoButtonIcon = '';
      $scope.hasActiveButton = false;
  }
  return $scope.doClickSfida = function() {
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

Rigorix.controller("Main", function($scope, $modal, $rootScope, AuthService, RigorixUI) {
  var _this = this;
  $scope.siteTitle = "Website title";
  $scope.userLogged = false;
  $scope.$on("$routeChangeStart", function(event, next, current) {
    if (next.$$route.originalPath === "/logout") {
      return $scope.$emit("LOGOUT");
    }
  });
  $scope.$on("modal:open", function(event, obj) {
    return $scope.modalClass = obj.modalClass;
  });
  $scope.$on("modal:close", function() {
    return $scope.modalClass = '';
  });
  $scope.$on("show:loading", function() {
    return $(".rigorix-loading").addClass("show");
  });
  $scope.$on("hide:loading", function() {
    return $(".rigorix-loading").removeClass("show");
  });
  $scope.$on("user:logout", function() {
    var User;
    User = false;
    $scope.currentUser = null;
    return $scope.userLogged = false;
  });
  $scope.$on("*", function(ev, $rootScope) {
    return $rootScope.$broadcast("event:received", ev);
  });
  $scope.onUserLogout = function() {
    return $rootScope.$broadcast('user:logout');
  };
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
        $scope.currentUser = json;
        return $rootScope.$broadcast("currentuser:update", json);
      });
    }, RigorixConfig.updateTime);
  }
});

Rigorix.controller('Messages', function($scope, AppService, $modal) {
  var _this = this;
  $scope.messages = AppService.getMessages({
    count: RigorixConfig.messagesPerPage
  });
  $scope.openMessage = function(message) {
    return $modal.open({
      templateUrl: '/app/templates/modals/message.html',
      controller: 'Message.Modal',
      resolve: {
        message: function() {
          return message;
        }
      }
    });
  };
  $scope.page = 1;
  $scope.currentPage = 1;
  $scope.totMessages = Number($scope.currentUser.totMessages);
  $scope.$watch('currentPage', function(newVal, oldVal) {
    return console.log("watch currentPages newval", newVal);
  });
  return $scope.pageChanged = function(page) {
    return console.log("onSelectPage", page);
  };
});

Rigorix.controller('Message.Modal', function($scope, $modal, $modalInstance, $rootScope, message) {
  $rootScope.$broadcast("modal:open", {
    controller: 'Message.Modal',
    modalClass: 'modal-read-message'
  });
  $scope.message = message;
  $modalInstance.result.then(function() {
    return true;
  }, function() {
    return $rootScope.$broadcast("modal:close");
  });
  $scope.cancel = function() {
    return $modalInstance.dismiss();
  };
  return console.log("Load message", message);
});

Rigorix.factory("Modals", function($scope, $modal) {
  return {
    success: function(content) {
      return $modal.open({
        templateUrl: '/app/templates/modals/success.html',
        controller: 'Modals.Success',
        resolve: {
          content: function() {
            return content;
          }
        }
      });
    }
  };
});

Rigorix.controller("Modals.Success", function($scope, $modal, $modalInstance, $rootScope, content) {
  $rootScope.$broadcast("modal:open", {
    controller: 'Modals.Success',
    modalClass: 'modal-success'
  });
  $modalInstance.result.then(function() {
    return true;
  }, function() {
    return $rootScope.$broadcast("modal:close");
  });
  return $scope.close = function() {
    return $modalInstance.dismiss();
  };
});

Rigorix.controller("Modals.Sfida", function($scope, $modal, $modalInstance, $rootScope, sfida) {
  $rootScope.$broadcast("modal:open", {
    controller: 'Modals.Sfida',
    modalClass: 'modal-play-sfida'
  });
  $scope.sfida = sfida != null ? sfida : {
    id_sfidante: $scope.currentUser.id_utente,
    id_avversario: user.id_utente,
    id_sfida: false
  };
  $modalInstance.result.then(function(selectedItem) {
    return true;
  }, function() {
    return $rootScope.$broadcast("modal:close");
  });
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
  $scope.doAuth = function(social) {
    var auth_url;
    auth_url = RigorixEnv.REMOTE + "/social_login.php?provider=" + social + "&origin=" + RigorixEnv.DOMAIN + "&return_to=" + RigorixEnv.REMOTE;
    return window.open(auth_url, "hybridauth_social_sing_on", "location=0,status=0,scrollbars=0,width=800,height=500");
  };
  return $scope.$emit("test", "event");
});

Rigorix.controller("Username", function($scope, $rootScope, $modal) {
  return $scope.doLanciaSfida = function() {
    $rootScope.$broadcast("sfida:lancia", $scope.user);
    return $modal.open({
      templateUrl: '/app/templates/modals/sfida.html',
      controller: 'Modals.Sfida',
      resolve: {
        sfida: function() {
          return {
            id_sfidante: $scope.currentUser.id_utente,
            id_avversario: $scope.user.id_utente,
            id_sfida: false
          };
        }
      }
    });
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
    scope.__sfide = scope[attrs.onListaSfideLoad];
    return scope.$on("currentuser:update", function() {
      return scope.__sfide = scope[attrs.onListaSfideLoad];
    });
  };
});

Rigorix.directive("beautifyDate", function() {
  return {
    restrict: 'E',
    templateUrl: '/app/templates/directives/beautify-date.html',
    scope: {
      date_string: "@date",
      inline: "="
    }
  };
});

Rigorix.directive("username", function(UserService) {
  return {
    restrict: 'E',
    templateUrl: '/app/templates/directives/username.html',
    link: function(scope, element, attr) {
      if (RigorixStorage.users[attr.idUtente] != null) {
        return scope.userObject = RigorixStorage.users[attr.idUtente];
      } else {
        return UserService.getUsernameById({
          filter: attr.idUtente
        }, function(json) {
          scope.userObject = json;
          return RigorixStorage.users[attr.idUtente] = json;
        });
      }
    }
  };
});

Rigorix.directive("icon", function() {
  return {
    link: function(scope, element, attr) {
      return $(element).prepend($('<span class="glyphicon glyphicon-' + attr.icon + ' mrs"></span>'));
    }
  };
});

Rigorix.directive("gameTile", function() {
  return {
    restrict: 'E',
    templateUrl: '/app/templates/game/tile.html',
    require: 'Game',
    scope: {
      row: "=",
      tileType: "=",
      matrix: "@"
    }
  };
});

Rigorix.directive("setLoader", [
  'RigorixUI', '$timeout', '$rootScope', function(RigorixUI, $timeout, $rootScope) {
    return {
      link: function(scope, element, attr) {
        var _this = this;
        RigorixUI.updateLoader(attr.setLoader);
        if (attr.setLoader === '100') {
          return $timeout(function() {
            return $rootScope.$broadcast("hide:loading");
          }, 300);
        }
      }
    };
  }
]);

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
    date = new Date(input);
    return date;
  };
});

Rigorix.filter("formatStringDate", function() {
  return function(input) {
    return moment(input).format("Do MMM YYYY");
  };
});

Rigorix.filter("localizeMonth", function() {
  var months;
  months = {
    Jan: 'Gen',
    Feb: 'Feb',
    Mar: 'Mar',
    Apr: 'Apr',
    May: 'Mag',
    Jun: 'Giu',
    Jul: 'Lug',
    Aug: 'Ago',
    Sep: 'Set',
    Oct: 'Ott',
    Nov: 'Nov',
    Dec: 'Dic'
  };
  return function(input) {
    return months[input];
  };
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
    },
    getMessages: {
      method: 'GET',
      params: {
        param1: 'messages',
        param2: User.id_utente
      }
    },
    getCountMessages: {
      method: 'GET',
      params: {
        param1: 'messages',
        param2: 'count',
        param3: User.id_utente
      }
    }
  });
});

Rigorix.service("RigorixUI", [
  "$modal", function($modal) {
    var _this = this;
    this.loader = angular.element('.rigorix-loading');
    return {
      updateLoader: function(percentage) {
        return _this.loader.find(".progress-bar").css("width", percentage + "%");
      }
    };
  }
]);

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
    },
    sendSfida: {
      method: 'POST',
      params: {
        filter: 'set',
        sfida_matrix: "@sfida_matrix",
        sfida: "@sfida"
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
