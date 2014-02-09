RigorixServices.factory "UserServiceNew", ($resource)->

  $resource RigorixEnv.API_DOMAIN + "users/:id_utente/:parameter/:filter",
    id_utente: User.id_utente
    parameter: "@parameter"
    filter: "@filter"
    isArray: false