<?php


Flight::map("needsAuth", function () {
  $auth_token = Flight::request()->cookies['auth_token'] ? Flight::request()->cookies['auth_token'] : Flight::request()->query->auth_token;
  $auth_id = Flight::request()->cookies['auth_id'] ? Flight::request()->cookies['auth_id'] : Flight::request()->query->auth_id;

  if (UserToken::where("id_utente", "=", $auth_id)->where("token", "=", $auth_token)->get()->count() == 0)
    Flight::halt(403, "Auth token not valid. Needs to authenticate");

  Flight::set("auth_id", $auth_id);
  Flight::set("auth_token", $auth_token);
});

//----------------------------------------------------------------------------------------------------------------------

Flight::map("needsPermission", function($id_utente) {
  $auth_id = Flight::request()->cookies['auth_id'] ? Flight::request()->cookies['auth_id'] : Flight::request()->query->auth_id;

  if ($id_utente != $auth_id)
    Flight::halt(403, "Auth token not valid. Needs to authenticate");
});

//----------------------------------------------------------------------------------------------------------------------

Flight::map("needsPermissionToSfida", function ($id_sfida) {
  $auth_id = Flight::request()->cookies['auth_id'] ? Flight::request()->cookies['auth_id'] : Flight::request()->query->auth_id;
  if (Sfide::whereRaw("id_sfida = $id_sfida and (id_sfidante = $auth_id or id_sfidato = $auth_id) and stato != 2")->get()->count() == 0)
    Flight::halt(403, "User doesn't have permissions to change this sfida");
});

//----------------------------------------------------------------------------------------------------------------------

Flight::map("needsPermissionToSeeSfida", function ($id_sfida) {
  $auth_id = Flight::request()->cookies['auth_id'] ? Flight::request()->cookies['auth_id'] : Flight::request()->query->auth_id;

  if (Sfide::whereRaw("id_sfida = $id_sfida and (id_sfidante = $auth_id or id_sfidato = $auth_id) and stato = 2")->get()->count() == 0)
    Flight::halt(403, "User doesn't have permissions to see this sfida");
});