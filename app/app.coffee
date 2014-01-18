Rigorix = angular.module "Rigorix", ["ngRoute", "RigorixServices"]

RigorixServices = angular.module "RigorixServices", ["ngResource"]
SocialLoginUrl = "http://tre.rigorix.com/social_login.php"

Rigorix.config ($routeProvider)->


# --- Main routes ---------------------------------------------------
  $routeProvider.when "/",
    templateUrl: "app/templates/home.page.html",
    controller: "Home"

  $routeProvider.when "/home",
    templateUrl: "app/templates/home.page.html",
    controller: "Home"

  $routeProvider.when "/logout",
    templateUrl: "app/templates/home.page.html",
    controller: "Home"


# --- Area personale ------------------------------------------------
  $routeProvider.when "/area-personale",
    templateUrl: "app/templates/areapersonale.page.html",
    controller: "AreaPersonale"

  $routeProvider.when "/area-personale/:section",
    templateUrl: "app/templates/areapersonale.page.html",
    controller: "AreaPersonale"

  $routeProvider.when "/area-personale/:section/:sectionPage",
    templateUrl: "app/templates/areapersonale.page.html",
    controller: "AreaPersonale"


# --- Static pages ---------------------------------------------------
  $routeProvider.when "/regolamento",
    templateUrl: "app/templates/regolamento.page.html"

  $routeProvider.when "/riconoscimenti",
    templateUrl: "app/templates/riconoscimenti.page.html"

  $routeProvider.when "/partners",
    templateUrl: "app/templates/partners.page.html"

  $routeProvider.otherwise
    templateUrl: "app/templates/lost.html"
