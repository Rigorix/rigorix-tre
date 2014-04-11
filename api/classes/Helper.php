<?php

Flight::map("getEloquentObject", function ($name) {
  $name = strtolower($name);
  if ($name == "utente" || $name == "utenti")
    return Users;
  if ($name == "messaggi")
    return Messages;
  if ($name == "rewards")
    return Rewards;
  if ($name == "sfide")
    return Sfide;
  if ($name == "unsubscribe")
    return UsersUnsubscribe;
});

Flight::map("userExists", function ($params) {
  foreach($params as $field => $value) {
    if (!isset($users))
      $users = Users::where($field, "=", $value);
    else
      $users = $users->where($field, "=", $value);
  }
  return $users->get()->count() > 0;
});

Flight::map("needsAuth", function () {
  $auth_token = Flight::request()->cookies['auth_token'] ? Flight::request()->cookies['auth_token'] : Flight::request()->query->auth_token;
  $auth_id = Flight::request()->cookies['auth_id'] ? Flight::request()->cookies['auth_id'] : Flight::request()->query->auth_id;

  if (UserToken::where("id_utente", "=", $auth_id)->where("token", "=", $auth_token)->get()->count() == 0)
    Flight::halt(403, "Auth token not valid. Needs to authenticate");
  else {
    Flight::set("auth_id", $auth_id);
    Flight::set("auth_token", $auth_token);
  }
});

Flight::map("needsPermission", function($id_utente) {
  $auth_id = Flight::request()->cookies['auth_id'] ? Flight::request()->cookies['auth_id'] : Flight::request()->query->auth_id;

  if ($id_utente != $auth_id)
    Flight::halt(403, "Auth token not valid. Needs to authenticate");
});

Flight::map("needsPermissionToSfida", function ($id_sfida) {
  $auth_id = Flight::request()->cookies['auth_id'] ? Flight::request()->cookies['auth_id'] : Flight::request()->query->auth_id;
  if (Sfide::whereRaw("id = $id_sfida and (id_sfidante = $auth_id or id_sfidato = $auth_id) and stato != 2")->get()->count() == 0)
    Flight::halt(403, "User doesn't have permissions to change this sfida");
});

Flight::map("getValidUsername", function($username) {
  if (Flight::userExists(array("username" => $username)) )
    return Flight::getValidUsername ($username . rand(0, 9));
  else
    return $username;
});

Flight::map("getUserObjectExtended", function ($id_utente) {

  $messages = Messages::receiver($id_utente);
  $user     = Users::find($id_utente);

  $obj = new stdClass();
  $obj->db_object           = $user->toArray();
  $obj->messages            = $messages->unread()->orderBy('created_at', 'DESC')->get()->toArray();
  $obj->totMessages         = $user->messages->count();
  $obj->badges              = $user->badges()->toArray();
  $obj->has_new_badges      = $user->unseenBadges()->count();
  $obj->sfide_da_giocare    = Sfide::receivedBy($id_utente)->unplayed()->normal()->orderBy('dta_sfida', 'DESC')->get()->toArray();
  $obj->rewards             = $user->rewards->toArray();
  $obj->picture             = $user->picture;
  $obj->dead                = UsersUnsubscribe::user($id_utente)->get()->count() > 0;
  $original = Users::find($id_utente)->toArray();

//  var_dump(Messages::all());

  return (object) array_merge((array)$original, (array)$obj);
});

Flight::map("createUserPicture", function ( $picture, $username, $id ) {
  $pictureName = explode("/", $picture);
  $pictureName = $pictureName[count(explode("/", $picture))-1];
  $pictureName = explode("?", $pictureName);
  $pictureName = $pictureName[0];

  $fileName = Flight::get("env")->PROFILE_PICTURE_PATH . $username."_".$id."_".$pictureName;
  if (file_put_contents("{$_SERVER['DOCUMENT_ROOT']}{$fileName}", file_get_contents($picture)) )
    return $fileName;
  else
    return $picture;
});


function getParams() {
  $postdata = file_get_contents("php://input");
  return json_decode($postdata);
}