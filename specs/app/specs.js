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



describe("Rigorix Module", function() {
  it("module should be defined", function() {
    return expect(Rigorix).toBeDefined();
  });
  it("should be able to load a controller", function() {
    angular.mock.module('Rigorix');
    return expect(Rigorix.MainCtrl).toBeDefined();
  });
  it("should have env parameters", function() {
    return expect(RigorixEnv).toBeDefined();
  });
  return it("should have a User object", function() {
    return expect(User).toBeDefined();
  });
});
