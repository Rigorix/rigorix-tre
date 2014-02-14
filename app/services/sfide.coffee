RigorixServices.factory "SfideService", ($resource)->

  $resource RigorixEnv.API_DOMAIN + "sfide/:filter/:value",
    filter: "@filter"
    value: "@value"
    isArray: false

  ,

    sendSfida:
      method: 'POST'
      params:
        filter: 'set'
        sfida_matrix: "@sfida_matrix"
        sfida: "@sfida"
