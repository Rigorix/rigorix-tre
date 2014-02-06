Rigorix = angular.module "Rigorix", ["ngRoute", "RigorixServices", "ui.bootstrap", 'textAngular', 'angular-redactor']
RigorixHasBeenStarted = true
RigorixServices = angular.module "RigorixServices", ["ngResource"]
SocialLoginUrl = "http://tre.rigorix.com/social_login.php"


Rigorix.config ($routeProvider)->

# --- Main routes ---------------------------------------------------
  $routeProvider.when "/",
    templateUrl: "app/templates/pages/home.page.html",
    controller: "Home"

  $routeProvider.when "/home",
    templateUrl: "app/templates/pages/home.page.html",
    controller: "Home"

  $routeProvider.when "/logout",
    templateUrl: "app/templates/pages/home.page.html",
    controller: "Home"


# --- Area personale ------------------------------------------------
  $routeProvider.when "/area-personale",
    templateUrl: "app/templates/area-personale/page.html",
    controller: "AreaPersonale"

  $routeProvider.when "/area-personale/:section",
    templateUrl: "app/templates/area-personale/page.html",
    controller: "AreaPersonale"

  $routeProvider.when "/area-personale/:section/:sectionPage",
    templateUrl: "app/templates/area-personale/page.html",
    controller: "AreaPersonale"


# --- Static pages ---------------------------------------------------
  $routeProvider.when "/regolamento",
    templateUrl: "app/templates/pages/regolamento.page.html"

  $routeProvider.when "/riconoscimenti",
    templateUrl: "app/templates/pages/riconoscimenti.page.html"

  $routeProvider.when "/partners",
    templateUrl: "app/templates/pages/partners.page.html"

  $routeProvider.otherwise
    templateUrl: "app/templates/pages/lost.html"


Rigorix.config ()->

  if RigorixEnv.INCOGNITO is true
    angular.element("body").append $('<link rel="stylesheet" type="text/css" media="all" href="/css/developing.css.wait" />')