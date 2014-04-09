Rigorix.factory "RealtimeService", ['$resource', ($resource)->

  sfida:  $resource RigorixEnv.API_DOMAIN + "realtime/sfida/:id", id: "@id"
  tiri:   $resource RigorixEnv.API_DOMAIN + "realtime/sfida/:id/tiri", id: "@id"
  parate: $resource RigorixEnv.API_DOMAIN + "realtime/sfida/:id/parate", id: "@id"

]