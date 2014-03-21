RigorixConfig =
  updateTime: if User? and User.id_utente then 60000 else 60000
  deletedUsernameQuery: "__DELETED__"
  messagesPerPage: 15
  userPicturePath: "/i/profile_picture/"
  token: $.cookie "auth_token"

RigorixStorage =
  users: {}

if !console?
  console =
    log: ->
      false