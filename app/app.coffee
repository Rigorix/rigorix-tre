Rigorix = angular.module "Rigorix", ["ngRoute", "RigorixServices", "ui.bootstrap", 'colorpicker.module', 'ngSanitize', 'angularFileUpload']
RigorixServices = angular.module "RigorixServices", ["ngResource"]
SocialLoginUrl = "http://tre.rigorix.com/social_login.php"

Rigorix.config ['$routeProvider', ($routeProvider)->

# --- Main routes ---------------------------------------------------
  $routeProvider.when "/",
    templateUrl: "/app/templates/pages/home.page.html",
    controller: "Home"

  $routeProvider.when "/home",
    templateUrl: "/app/templates/pages/home.page.html",
    controller: "Home"

  $routeProvider.when "/logout",
    templateUrl: "/app/templates/pages/home.page.html",
    controller: "Home"

  $routeProvider.when "/first-login",
    templateUrl: "/app/templates/pages/first-login.page.html",
    controller: "FirstLogin"

  $routeProvider.when "/same-email",
    templateUrl: "/app/templates/pages/same-email.page.html",
    controller: "FirstLogin.SameEmail"

  $routeProvider.when "/access-denied",
    templateUrl: "/app/templates/pages/access-denied.page.html",
    controller: "AccessDenied"


# --- Area personale ------------------------------------------------
  $routeProvider.when "/area-personale",
    templateUrl: "/app/templates/area-personale/page.html",
    controller: "AreaPersonale"

  $routeProvider.when "/area-personale/:section",
    templateUrl: "/app/templates/area-personale/page.html",
    controller: "AreaPersonale"

  $routeProvider.when "/area-personale/:section/:sectionPage",
    templateUrl: "/app/templates/area-personale/page.html",
    controller: "AreaPersonale"


# --- Static pages ---------------------------------------------------
  $routeProvider.when "/regolamento",
    templateUrl: "/app/templates/pages/regolamento.page.html"

  $routeProvider.when "/riconoscimenti",
    templateUrl: "/app/templates/pages/riconoscimenti.page.html"

  $routeProvider.when "/partners",
    templateUrl: "app/templates/pages/partners.page.html"

  $routeProvider.otherwise
    templateUrl: "/app/templates/pages/lost.html"


# --- Realtime  ------------------------------------------------------
  $routeProvider.when "/realtime",
    templateUrl: "/app/templates/realtime/page.html"

]


# --- Initialize App -------------------------------------------------

Rigorix.Storage =
  users: {}
  waiters: {}

Rigorix.run ()->
  $("html").attr "incognito", true if RigorixEnv.INCOGNITO is true



