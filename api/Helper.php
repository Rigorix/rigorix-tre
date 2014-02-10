<?php

function hasAuth ($id_utente) {
  return ( $_SESSION['rigorix']['user'] !== false && $id_utente == $_SESSION['rigorix']['user']->id_utente);
}

function getParams() {
  $postdata = file_get_contents("php://input");
  return json_decode($postdata);
}

function getUserObjectExtended($id_utente) { global $dm_utente, $dm_messaggi, $dm_sfide, $dm_rewards;
//  $UserObject                   = $dm_utente->getObjUtenteById($id_utente);
//  $UserObject->db_object        = $dm_utente->getObjUtenteById($id_utente);
//  $UserObject->messages         = $dm_messaggi->getArrObjMessaggiUnread ($id_utente);
//  $UserObject->totMessages      = $dm_messaggi->getCountUnbannedMessages ( $id_utente );
//  $UserObject->badges           = $dm_utente->getArrayObjectQueryCustom ("select * from rewards, sfide_rewards where sfide_rewards.id_utente = $id_utente and rewards.id_reward = sfide_rewards.id_reward and rewards.tipo = 'badge'");
//  $UserObject->sfide_da_giocare = $dm_sfide->getSfideDaGiocareByUtente ( $id_utente );
//  $UserObject->rewards          = $dm_rewards->getRewardsObjectByIdUtente ( $id_utente );
//  $UserObject->picture          = sanitizeUserPicture($UserObject->picture);
//
//  return $UserObject;

  $obj = new stdClass();
  $obj->db_object           = Users::find($id_utente)->toArray();
  $obj->messages            = Messages::receiver($id_utente)->unread()->get()->toArray();
  $obj->totMessages         = Messages::receiver($id_utente)->count();
  $obj->badges              = $dm_utente->getArrayObjectQueryCustom ("select * from rewards, sfide_rewards where sfide_rewards.id_utente = $id_utente and rewards.id_reward = sfide_rewards.id_reward and rewards.tipo = 'badge'");
  $obj->sfide_da_giocare    = Sfide::receivedBy($id_utente)->unplayed()->get()->toArray();
  $obj->rewards             = $dm_rewards->getRewardsObjectByIdUtente ( $id_utente );
  $obj->picture             = sanitizeUserPicture(Users::find($id_utente)->picture);

  return (object) array_merge((array) $obj, (array) Users::find($id_utente)->toArray());
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

function retCorrTiroParata( $valInDb )
{
  $res = '';
  if (strlen($valInDb)==1){
    $res = $valInDb-1;
  } else {
    $res = ($valInDb[0]-1).($valInDb[1]-1);
  }
  if ($res == '33' || $res == 33) $res = 3;
  if ($res == '22' || $res == 22) $res = 2;
  if ($res == '11' || $res == 11) $res = 1;
  if ($res == '00' || $res == 00) $res = 0;
  return $res;
}


?>