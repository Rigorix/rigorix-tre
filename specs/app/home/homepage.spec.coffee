HomeController = angular.element("[ng-controller=Home]").scope

describe "Home page", ->

  it "should have a specific controller", ->
    expect(HomeController).toBeDefined()

  it "should contain $scope.campione object", ->
    console.log "HomeController", angular.element("[ng-controller=Home]")
    expect(HomeController.updateResources).toBeDefined()

  describe "Sidebar", ->

    describe "User box", ->

      describe "Logged out", ->
