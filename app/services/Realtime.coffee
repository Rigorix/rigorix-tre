Rigorix.factory "RealtimeService", ['$resource', ($resource)->

  sfida:  $resource RigorixEnv.API_DOMAIN + "realtime/sfida/:id_sfida",         id_sfida: "@id_sfida"
  tiri:   $resource RigorixEnv.API_DOMAIN + "realtime/sfida/:id_sfida/tiri",    id_sfida: "@id_sfida"
  parate: $resource RigorixEnv.API_DOMAIN + "realtime/sfida/:id_sfida/parate",  id_sfida: "@id_sfida"

  result: $resource RigorixEnv.API_DOMAIN + "realtime/sfida/:id_sfida/result",  id_sfida: "@id_sfida"

]