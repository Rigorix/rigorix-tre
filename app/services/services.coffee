RigorixServices.factory "MessageResource", ['$resource', ($resource)->

  $resource RigorixEnv.API_DOMAIN + "messages/:id_message",
    method      : "GET"
    isArray     : false
    params:
      id_mess     : "@id_mess"

]


##----------------------------------------------------------------------------------------------------------------------


RigorixServices.factory "UserService", ['$resource', ($resource)->

  $resource RigorixEnv.API_DOMAIN + "users/:id_utente/:parameter/:filter",
    method: "GET"
    isArray: false
    params:
      id_utente: User.id_utente
      parameter: "@parameter"
      filter: "@filter"

]