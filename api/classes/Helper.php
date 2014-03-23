<?php

function needsAuth () {
  $auth_token = Flight::request()->cookies['auth_token'] ? Flight::request()->cookies['auth_token'] : Flight::request()->query->auth_token;
  $auth_id = Flight::request()->cookies['auth_id'] ? Flight::request()->cookies['auth_id'] : Flight::request()->query->auth_id;

  if (UserToken::where("id_utente", "=", $auth_id)->where("token", "=", $auth_token)->get()->count() == 0)
    Flight::halt(403, "Auth token not valid. Needs to authenticate");
}

function needsPermission ($id_utente) {
  $auth_id = Flight::request()->cookies['auth_id'] ? Flight::request()->cookies['auth_id'] : Flight::request()->query->auth_id;

  if ($id_utente != $auth_id)
    Flight::halt(403, "Auth token not valid. Needs to authenticate");
}

function getParams() {
  $postdata = file_get_contents("php://input");
  return json_decode($postdata);
}

function getUserObjectExtended($id_utente) {

  $user = Users::find($id_utente);
  $obj = new stdClass();
  $obj->db_object           = $user->toArray();
  $obj->messages            = Messages::receiver($id_utente)->unread()->orderBy('created_at', 'DESC')->get()->toArray();
  $obj->totMessages         = Messages::receiver($id_utente)->count();
  $obj->badges              = $user->badges()->toArray();
  $obj->has_new_badges      = $user->unseenBadges()->count();
  $obj->sfide_da_giocare    = Sfide::receivedBy($id_utente)->unplayed()->get()->toArray();
  $obj->rewards             = $user->rewards->toArray();
  $obj->picture             = $user->picture;
  $obj->dead                = UsersUnsubscribe::user($id_utente)->get()->count() > 0;

  $original = Users::find($id_utente)->toArray();

  return (object) array_merge((array)$original, (array)$obj );
}

function createUserPicture ( $picture, $username, $id )
{
  $pictureName = explode("/", $picture);
  $pictureName = $pictureName[count(explode("/", $picture))-1];
  $pictureName = explode("?", $pictureName);
  $pictureName = $pictureName[0];

  if (file_put_contents("{$_SERVER['DOCUMENT_ROOT']}/i/profile_picture/{$username}_{$id}_{$pictureName}", file_get_contents($picture)) )
    return "/i/profile_picture/{$username}_{$id}_{$pictureName}";
  else
    return $picture;
}

function sanitizeUsersPicture( $users ) {
  $sanitized = array();
  if ( count($users) > 0 ):
    foreach ($users as $user) {
      if (isset($user->picture)) {
        $user->picture = sanitizeUserPicture ($user->picture);
        array_push($sanitized, $user);
      }
    }
  endif;
  return $users;
}

function sanitizeUserPicture ($picture) {
  if ( $picture == "" )
    $picture_uri = '/i/default-user-picture.png';
  else if ( strpos($picture, "http") === 0 )
    $picture_uri = $picture;
  else
    $picture_uri =  '/i/profile_picture/' . $picture; 

  return $picture_uri;
}