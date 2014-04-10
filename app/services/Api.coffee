RigorixServices.factory "Api", ['$resource', '$http', '$q', 'Helpers', ($resource, $http, $q, Helpers)->

  call: (method, url, params)->
    params = Helpers.extendApiParams params

    url = url.substr 1, url.length-1 if url[0] is "/"
    $http[method](RigorixEnv.API_DOMAIN + url, params).then(params.success, params.error)

  post: (url, params)->
    params = Helpers.extendApiParams params

    url = url.substr 1, url.length-1 if url[0] is "/"
    $http.post(RigorixEnv.API_DOMAIN + url, params).then(params.success, params.error)

  get: (url, params)->
    params = Helpers.extendApiParams params

    url = url.substr 1, url.length-1 if url[0] is "/"
    $http.get(RigorixEnv.API_DOMAIN + url, params).then(params.success, params.error)

  getResource: (url)->
#    TODO: Improve this!!
    $resource RigorixEnv.API_DOMAIN + url,
      method: "GET"
      isArray: false

  auth: (params)->
    promise = $http.get(RigorixEnv.OAUTH_URL + params.provider)
    promise.then params.success

  logout: (id_utente)->
    $http.post RigorixEnv.API_DOMAIN + "users/" + id_utente + "/logout/"

  getUserBasic: (id_utente)->
    deferred = do $q.defer

    if Rigorix.Storage.users[id_utente]?
      deferred.resolve Rigorix.Storage.users[id_utente]
    else
      @call "get", "users/" + id_utente + "/basic",
        success: (json)->
          Rigorix.Storage.users[id_utente] = json.data
          deferred.resolve json.data

        error: (json)->
          Rigorix.Storage.users[id_utente] = id_utente: 0
          deferred.resolve Rigorix.Storage.users[id_utente]

    deferred.promise

  getUser: (id_utente)->
    deferred = do $q.defer

    if Rigorix.Storage.users[id_utente]? and Rigorix.Storage.users[id_utente].badges?
      deferred.resolve Rigorix.Storage.users[id_utente]
    else
      @call "get", "users/" + id_utente,
        success: (json)->
          Rigorix.Storage.users[id_utente] = json.data
          deferred.resolve json.data

        error: (json)->
          Rigorix.Storage.users[id_utente] = id_utente: 0
          deferred.resolve Rigorix.Storage.users[id_utente]

    deferred.promise

]