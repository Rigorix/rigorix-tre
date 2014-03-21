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
    $http.get(RigorixEnv.API_DOMAIN + url, params)

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

    if RigorixStorage.users[id_utente]?
      deferred.resolve RigorixStorage.users[id_utente]
    else
      @call "get", "users/" + id_utente + "/basic",
        success: (json)->
          RigorixStorage.users[id_utente] = json.data
          deferred.resolve json.data

        error: (json)->
          RigorixStorage.users[id_utente] = id_utente: 0
          deferred.resolve RigorixStorage.users[id_utente]

    deferred.promise

  getUser: (id_utente)->
    deferred = do $q.defer

    if RigorixStorage.users[id_utente]? and RigorixStorage.users[id_utente].badges?
      deferred.resolve RigorixStorage.users[id_utente]
    else
      @call "get", "users/" + id_utente,
        success: (json)->
          RigorixStorage.users[id_utente] = json.data
          deferred.resolve json.data

        error: (json)->
          RigorixStorage.users[id_utente] = id_utente: 0
          deferred.resolve RigorixStorage.users[id_utente]

    deferred.promise

]