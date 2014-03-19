Rigorix.factory "Helpers", ->

  extendPromiseParams: (params)->
    params = {} unless params?
    if !params.success? then params.success = -> false
    if !params.error? then params.error = -> false
    params