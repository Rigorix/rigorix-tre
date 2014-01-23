RigorixServices.factory "SfideService", ($resource)->

  $resource RigorixEnv.API_DOMAIN + "sfide/:filter/:value",
    filter: "@filter"
    value: "@value"
    isArray: false

  ,
    getArchivioSfide:
      method: 'GET'
#      isArray: true
      params:
        filter: 'archivio'
        value: User.id_utente
    ,

    getSfidePending:
      method: 'GET'
#      isArray: true
      params:
        filter: 'pending'
        value: User.id_utente
