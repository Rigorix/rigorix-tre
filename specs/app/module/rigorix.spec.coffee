describe "Rigorix Module", ->

  $compile = false
  $rootScope = false

  beforeEach ->
    module("Rigorix")
    inject (_$compile_, _$rootScope_) ->
      $compile = _$compile_
      $rootScope = _$rootScope_

