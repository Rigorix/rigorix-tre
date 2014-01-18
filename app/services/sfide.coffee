RigorixServices.factory "SfideService", ($resource)->

  $resource RigorixEnv.API_DOMAIN + "sfide/:filter/:value",
    filter: "@filter"
    value: "@value"
    isArray: true

  ,
    getArchivioSfide:
      method: 'GET'
      isArray: true
      params:
        filter: 'archivio'
        value: User.id_utente
