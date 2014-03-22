var HomeController;

HomeController = angular.element("[ng-controller=Home]").scope;

describe("Home page", function() {
  it("should have a specific controller", function() {
    return expect(HomeController).toBeDefined();
  });
  it("should contain $scope.campione object", function() {
    console.log("HomeController", angular.element("[ng-controller=Home]"));
    return expect(HomeController.updateResources).toBeDefined();
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
