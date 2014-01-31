RigorixServices.factory "UserService", ($resource)->

  $resource RigorixEnv.API_DOMAIN + "users/:filter/:value",
    filter: "@filter"
    value: "@value"
    isArray: false
  ,

    getTopUsers:
      method: "GET"
      params:
        filter: "top"
        value: "10"

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














