RigorixConfig =
  updateTime: RigorixEnv.UPDATE_USER_TIME * 1000
  deletedUsernameQuery: "__DELETED__"
  messagesPerPage: 15
  userPicturePath: "/i/profile_picture/"
  token: $.cookie "auth_token"

  safeLocations: ["/same-email", "/regolamento", "/riconoscimenti"]

RigorixStorage =
  users: {}

if !console?
  console =
    log: ->
      false