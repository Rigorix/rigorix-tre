RigorixServices.factory "UserServiceNew", ['$resource', ($resource)->

  $resource RigorixEnv.API_DOMAIN + "users/:id_utente/:parameter/:filter",
    method: "GET"
    isArray: false
    params:
      id_utente: User.id_utente
      parameter: "@parameter"
      filter: "@filter"

]