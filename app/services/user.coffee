RigorixServices.factory "UserService", ($resource)->

  $resource RigorixEnv.API_DOMAIN + "users/:filter/:value",
    filter: "@filter"
    value: "@value"
    isArray: true
  ,
    getActiveUsers:
      method: "GET"
      isArray: true
      params:
        filter: "active"

    getTopUsers:
      method: "GET"
      isArray: true
      params:
        filter: "top"
        value: "10"

    getCampioneSettimana:
      method: "GET"
      isArray: false
      params:
        filter: "campione"
        value: "settimana"

    getUsernameById:
      method: 'GET'
      isArray: false
      params:
        filter: User.id_utente
        value: 'username'


RigorixServices.factory "AppService", ($resource)->

  $resource RigorixStorage.API_DOMAIN + ":param1/:param2/:param3",
    param1: "@param1"
    param2: "@param2"
    param3: "@param3"
    isArray: true
  ,
    getBadges:
      method: 'GET'
      isArray: true
      params:
        param1: 'badges'