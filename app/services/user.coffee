RigorixServices.factory "UserService", ($resource)->

  $resource RigorixEnv.API_DOMAIN + "users/:filter/:value",
    filter: "@filter"
    value: "@value"
    isArray: false
  ,
    getActiveUsers:
      method: "GET"
      params:
        filter: "active"

    getTopUsers:
      method: "GET"
      params:
        filter: "top"
        value: "10"

    getCampioneSettimana:
      method: "GET"
      params:
        filter: "campione"
        value: "settimana"

    getUsernameById:
      method: 'GET'
      params:
        filter: User.id_utente
        value: 'username'

    getBadges:
      method: "GET"
      params:
        filter: User.id_utente
        value: 'badges'



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