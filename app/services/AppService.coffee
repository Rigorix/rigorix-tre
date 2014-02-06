RigorixServices.factory "AppService", ($resource)->

  $resource RigorixEnv.API_DOMAIN + ":param1/:param2/:param3",
    method: "GET"
    isArray: false
    params:
      param1: "@param1"
      param2: "@param2"
      param3: "@param3"
  ,

    getActiveUsers:
      method: "GET"
      params:
        param1: 'users'
        param2: "active"

    getTopUsers:
      method: "GET"
      params:
        param1: 'users'
        param2: "top"
        param3: "10"

    getCampioneSettimana:
      method: "GET"
      params:
        param1: 'users'
        param2: "campione"
        param3: "settimana"

    getUserByUsername:
      method: "GET"
      params:
        param1: 'users'
        param2: 'username'

    getUserById:
      method: 'GET'
      params:
        param1: 'users'



    doLogout:
      method: "POST"
      params:
        param1: 'user'
        param2: "logout"






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


    postReply:
      method: "POST"
      params:
        param1: 'message'
        param2: 'reply'



#
#    General
#

    getUserParameter:
      method: "GET"
      params:
        param1: 'users'

#
#    Messages
#
    putMessageRead:
      method: "PUT"
      params:
        param1: 'messages'
        param2: '@id_message'
        param3: 'read'

    deleteMessage:
      method: "DELETE"
      params:
        param1: 'message'


