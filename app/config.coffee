RigorixConfig =
  updateTime: 60000
  deletedUsernameQuery: "__DELETED__"
  messagesPerPage: 15
  userPicturePath: "/i/profile_picture/"

RigorixStorage =
  users: {}

authComplete = ()->
#  window.RigorixAuth.close()
  window.location.refresh()
