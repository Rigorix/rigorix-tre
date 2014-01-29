RigorixServices.factory "AppService", ($resource)->

  $resource RigorixEnv.API_DOMAIN + ":param1/:param2/:param3",
    param1: "@param1"
    param2: "@param2"
    param3: "@param3"
    isArray: false
  ,
    getBadges:
      method: 'GET'
      params:
        param1: 'badges'

    getMessages:
      method: 'GET'
      params:
        param1: 'messages'
        param2: User.id_utente

    getCountMessages:
      method: 'GET'
      params:
        param1: 'messages'
        param2: 'count'
        param3: User.id_utente
