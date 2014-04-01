describe "Rigorix Module", ->

  it "module should be defined", ->
    expect(Rigorix).toBeDefined()

#  it "should have app controllers", ->
#    controllers = ['AccessDenied', 'AreaPersonale', 'Directives', 'FirstLogin', 'GamePlay', 'Header', 'Home', 'Jasmine', 'ListaSfide', 'Main', 'Messages', 'Modals', 'Sidebar', 'User', 'Username', 'UserPanel']
#
#    for controller in controllers
#      found = false
#      for injected in Rigorix._invokeQueue when injected[0] is "$controllerProvider"
#        return found = true if injected[2][0] is controller
#
#      expect(found).toBe true

  it "should be able to load a controller", ->
    angular.mock.module('Rigorix')

    expect(Rigorix.MainCtrl).toBeDefined()

  it "should have env parameters", ->
    expect(RigorixEnv).toBeDefined()

  it "should have a User object", ->
    expect(User).toBeDefined()

