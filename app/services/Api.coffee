RigorixServices.factory "Api", ($resource, $http)->

  call: (method, url, params)->
    $http[method](RigorixEnv.API_DOMAIN + url, params)

    .success (response)->
        response = angular.fromJson(response) if params.getRawData isnt true
        params.success(response) if params.success?

    .error ->
        params.error(arguments[0], arguments[1], arguments[2], arguments[3]) if params.error?


  auth: (params)->
    $http.get(RigorixEnv.OAUTH_URL + params.provider)
      .success (response)->
        params.success(response) if params.success?

  logout: (id_utente)->
    $http.post RigorixEnv.API_DOMAIN + "users/" + id_utente + "/logout/"
