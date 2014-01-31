RigorixServices.factory "UserServiceNew", ($resource)->

  $resource RigorixEnv.API_DOMAIN + "user/:id_utente/:parameter/:filter",
    id_utente: User.id_utente
    parameter: "@parameter"
    filter: "@filter"
    isArray: false


#
#  ,
#    getActiveUsers:
#      method: "GET"
#      params:
#        filter: "active"
#
#    getTopUsers:
#      method: "GET"
#      params:
#        filter: "top"
#        value: "10"
#
#    getCampioneSettimana:
#      method: "GET"
#      params:
#        filter: "campione"
#        value: "settimana"
#
#    getUsernameById:
#      method: 'GET'
#      params:
#        filter: User.id_utente
#        value: 'username'
#
#    getBadges:
#      method: "GET"
#      params:
#        filter: User.id_utente
#        value: 'badges'
#
#
#
#    deleteMessage:
#      method: "DELETE"
#      params:
#        filter: 'message'
#        value: '@value'
#
#
#
#
#
#    putMessageRead:
#      method: "PUT"
#      params:
#        filter: 'message'
#        value: '@value'
#
#
#
#
#
#
#    doLogout:
#      method: "POST"
#      params:
#        filter: "logout"
