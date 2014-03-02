<?php

function hasAuth ($id_utente) {
  return ( $_SESSION['rigorix']['user'] !== false && $id_utente == $_SESSION['rigorix']['user']->id_utente);
}

function getParams() {
  $postdata = file_get_contents("php://input");
  return json_decode($postdata);
}

function getUserObjectExtended($id_utente) {

  $obj = new stdClass();
  $obj->db_object           = Users::find($id_utente)->toArray();
  $obj->messages            = Messages::receiver($id_utente)->unread()->get()->toArray();
  $obj->totMessages         = Messages::receiver($id_utente)->count();
  $obj->badges              = Users::find($id_utente)->badges()->toArray();
  $obj->has_new_badges      = Users::find($id_utente)->badges()->count();
  $obj->sfide_da_giocare    = Sfide::receivedBy($id_utente)->unplayed()->get()->toArray();
  $obj->rewards             = Users::find($id_utente)->rewards->toArray();
  $obj->picture             = Users::find($id_utente)->picture;
  $obj->dead                = UsersUnsubscribe::user($id_utente)->get()->count() > 0;

  $original = Users::find($id_utente)->toArray();

  return (object) array_merge((array)$original, (array)$obj );
}

function createUserPicture ( $picture, $username, $id )
{
  $pictureName = explode("/", $picture)[count(explode("/", $picture))-1];
  $pictureName = explode("?", $pictureName)[0];
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