RigorixServices.factory "MessageResource", ['$resource', ($resource)->

  $resource RigorixEnv.API_DOMAIN + "messages/:id_message",
    method      : "GET"
    isArray     : false
    params:
      id_mess     : "@id_mess"

]