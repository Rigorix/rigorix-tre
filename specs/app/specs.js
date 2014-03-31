var RigorixEnv;

RigorixEnv = {
  DOMAIN: "http://tre.rigorix.dev/",
  API_DOMAIN: "http://tre.rigorix.dev/api/",
  OAUTH_URL: "http://tre.rigorix.com/Opauth/",
  REMOTE: "http://tre.rigorix.com",
  TOKEN_SECRET: "8tw7i3y2403r78owaydhi89tyh34iue",
  AUTH_TOKEN_VALIDITY: 7,
  ADV: false,
  USE_MINIFIED: false,
  PROFILE_PICTURE_PATH: "/i/profile_picture/",
  PROFILE_PICTURE_MAX_SIZE: 850000,
  UPDATE_USER_TIME: 20,
  INCOGNITO: false,
  FAKE_LOGIN: false,
  SHOW_LOGS: false,
  LOG_DIR: "/log/"
};

var HomeController;

HomeController = angular.element("[ng-controller=Home]").scope;

describe("Home page", function() {
  it("should have a specific controller", function() {
    return expect(HomeController).toBeDefined();
  });
  it("should contain $scope.campione object", function() {
    return expect(true).toBe(true);
  });
  return describe("Sidebar", function() {
    return describe("User box", function() {
      return describe("Logged out", function() {});
    });
  });
});

describe("Rigorix Module", function() {
  return it("should have the right name", function() {
    return expect(Rigorix.name).toBe("Rigorix");
  });
});
