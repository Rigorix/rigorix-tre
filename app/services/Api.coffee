RigorixServices.factory "Api", ($resource, $http)->

  call: (method, url, params)->
    url = url.substr 1, url.length-1 if url[0] is "/"
    promise = $http[method](RigorixEnv.API_DOMAIN + url, params)
    promise.then params.success, params.error if params? and params.success?

  get: (url, params)->
#    TODO: Impreve this!!
    $resource RigorixEnv.API_DOMAIN + url,
      method: "GET"
      isArray: false

  auth: (params)->
    promise = $http.get(RigorixEnv.OAUTH_URL + params.provider)
    promise.then params.success

  logout: (id_utente)->
    $http.post RigorixEnv.API_DOMAIN + "users/" + id_utente + "/logout/"
