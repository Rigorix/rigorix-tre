Rigorix.factory "Helpers", ->

  extendApiParams: (params)->
    params = {} unless params?

    if !params.success? then params.success = -> false
    if !params.error? then params.error = -> false

#    params.auth_user_id = User.id_utente
    params