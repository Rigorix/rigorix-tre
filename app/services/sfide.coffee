RigorixServices.factory "SfideService", ($resource)->

  $resource RigorixEnv.API_DOMAIN + "sfide/:filter/:value",
    filter: "@filter"
    value: "@value"
    isArray: false

  ,
    getArchivioSfide:
      method: 'GET'
      params:
        filter: 'archivio'
        value: User.id_utente
    ,

    getSfidePending:
      method: 'GET'
      params:
        filter: 'pending'
        value: User.id_utente

    ,

    sendSfida:
      method: 'POST'
      params:
        filter: 'set'
        sfida_matrix: "@sfida_matrix"
        sfida: "@sfida"
