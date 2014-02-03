<?php

function hasAuth ($id_utente) {
  return ( $_SESSION['rigorix']['user'] !== false && $id_utente == $_SESSION['rigorix']['user']->id_utente);
}

function sanitizeUsersPicture( $users ) {
  global $rigorix_url, $pictures_url;

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