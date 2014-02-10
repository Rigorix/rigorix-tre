RigorixServices.factory "Api", ($resource, $http)->

  call: (method, url, params)->
    $http[method](RigorixEnv.API_DOMAIN + url, params)

    .success (response)->
        response = angular.fromJson(response) if params.getRawData isnt true
        params.success(response) if params.success?

    .error ->
        params.error(arguments[0]) if params.error?