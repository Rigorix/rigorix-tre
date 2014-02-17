RigorixServices.factory "MessageResource", ($resource)->

  $resource RigorixEnv.API_DOMAIN + "messages/:id_message",
    method      : "GET"
    isArray     : false
    id_message  : "@id_message"

