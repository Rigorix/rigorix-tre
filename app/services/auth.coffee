RigorixServices.factory "AuthService", ($resource)->

  $resource RigorixEnv.API_DOMAIN + "auth/:id_utente/:action/:value",
    id_utente: User.id_utente
    action: "@action"
    value: "@value"
    isArray: true

