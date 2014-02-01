<?php
//error_reporting(E_ALL);
//ini_set( 'display_errors','1');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, PUT, OPTIONS');
header('Content-type: application/json');

require_once '../classes/fastjson.php';
require_once '../classes/core.php';
require_once 'flight/Flight.php';
require_once 'Helper.php';



/// NEW VERSION

/// UserServiceNEw
Flight::route('/user/@id_utente(/*)', function($id_utente) {
  if (!hasAuth($id_utente)):
    echo "{ 'error': 'not-authorized' }";
    die();
  else:
    return true;
  endif;
});

Flight::route('GET /user/@id_utente', function($id_utente) { global $dm_utente, $dm_messaggi, $dm_sfide, $dm_rewards;
  $UserObject                   = $dm_utente->getObjUtenteById($id_utente);
  $UserObject->messages         = $dm_messaggi->getArrObjMessaggiUnread ($id_utente);
  $UserObject->totMessages      = $dm_messaggi->getCountUnbannedMessages ( $id_utente );
  $UserObject->badges           = $dm_utente->getArrayObjectQueryCustom ("select * from rewards, sfide_rewards where sfide_rewards.id_utente = $id_utente and rewards.id_reward = sfide_rewards.id_reward and rewards.tipo = 'badge'");
  $UserObject->sfide_da_giocare = $dm_sfide->getSfideDaGiocareByUtente ( $id_utente );
  $UserObject->rewards          = $dm_rewards->getRewardsObjectByIdUtente ( $id_utente );
  $UserObject->picture          = sanitizeUserPicture($UserObject->picture);

  echo FastJSON::convert($UserObject);
});

Flight::route('GET /user/@id_utente/messages', function($id_utente) { global $dm_messaggi;

  $start = (isset($_GET['start_count'])) ? $_GET['start_count'] : 0;
  $count = (isset($_GET['count'])) ? $_GET['count'] : 15;
  $messaggi = $dm_messaggi->getFilteredUserUnbannedMessaggi ( $id_utente, $start, $count );
  echo FastJSON::convert( $messaggi );

});

















/// Badges
Flight::route('GET /badges', function($count) { global $dm_rewards;

  echo FastJSON::convert( $dm_rewards->getBadgeRewards () );

});



/// Sfide
Flight::route('GET /sfide/archivio/@id_utente', function($id_utente) { global $dm_sfide;

  $limit_start = isset($_GET['limit_start']) ? $_GET['limit_start'] : 0;
  $limit_count = isset($_GET['limit_count']) ? $_GET['limit_count'] : 10;
  $sfide = $dm_sfide->getArrayObjectQueryCustom ( "select * from sfida where stato >= 2 and stato != 3 and (id_sfidante = ".$id_utente." or id_sfidato = ".$id_utente.") order by dta_conclusa DESC limit $limit_start, $limit_count ");
  echo FastJSON::convert( $sfide );

});

Flight::route('GET /sfide/pending/@id_utente', function($id_utente) { global $dm_sfide;

  $sfide = $dm_sfide->getSfideAttiveUtente ( $id_utente );
  echo FastJSON::convert( $sfide );

});

Flight::route('POST /sfide/set/@id_sfida', function($id_sfida) { global $activity;

  $sfidaMatrix = json_decode($_GET['sfida_matrix']);
  $sfidaObject = json_decode($_GET['sfida']);

  $activityId = $activity->do_lancia_sfida( $sfidaMatrix, $sfidaObject );

  if ( $activity->has_error_range($activityId[0], $activityId[1]) )
    echo '{ "status": "error", "activity_id": "'.implode(",", $activityId).'", "error_code": "'.$activity->has_error_range($activityId[0], $activityId[1]).'" }';
  else
    echo '{ "status": "success", "activity_id": "'.implode(",", $activityId).'" }';

});



/// Messaggi
Flight::route('GET /messages/@id_utente', function($id_utente, $count) { global $dm_messaggi;

  $start = (isset($_GET['start_count'])) ? $_GET['start_count'] : 0;
  $count = (isset($_GET['count'])) ? $_GET['count'] : 15;
  $messaggi = $dm_messaggi->getFilteredUserUnbannedMessaggi ( $id_utente, $start, $count );
  echo FastJSON::convert( $messaggi );

});

Flight::route('GET /messages/count/@id_utente', function($id_utente) { global $dm_messaggi;

  $count = $dm_messaggi->getCountUnbannedMessages ( $id_utente );
  echo $count;

});

Flight::route('POST /message/reply', function() { global $activity;

  $postdata = file_get_contents("php://input");
  $data = json_decode($postdata);

  $activity->do_reply_message($data);
  echo '{ "status": "success" }';

});



/// Users
Flight::route('GET /users/all', function($count) { global $dm_utente;

  $users = $dm_utente->getRankingUtenti ( 100 );
  $users = sanitizeUsersPicture($users);
  echo FastJSON::convert( $users );

});

Flight::route('GET /users/top/@count', function($count) { global $dm_utente;

  $users = $dm_utente->getRankingUtenti ( $count );
  $users = sanitizeUsersPicture($users);
  echo FastJSON::convert( $users );

});

Flight::route('GET /users/active', function() { global $dm_utente;

  $users = $dm_utente->getUsernameOnline ();
  $users = sanitizeUsersPicture($users);
  echo FastJSON::convert( $users );

});

Flight::route('GET /users/campione/settimana', function() { global $dm_utente;

  $best = $dm_utente->getIdUtenteWeekBest ();
  echo $best;

});



Flight::route('GET /users/@id_utente/@attribute', function($id_utente, $attribute) { global $dm_utente;

  $user = $dm_utente->getSingleObjectQueryCustom("SELECT $attribute FROM utente WHERE id_utente = " . $id_utente );
  if ($user === false)
    echo "{ 'username': '- sconosciuto -', 'id_utente': '$id_utente' }";
  else {
    $user->id_utente = $id_utente;
    if ($attribute == "username" && strpos($user->$attribute, "__DELETED__") !== false) {
      $user->$attribute = str_replace("__DELETED__", "", $user->$attribute);
      $user->deleted = true;
    } else
      $user->deleted = false;
    echo FastJSON::convert( $user );
  }

});

/// AUTH
Flight::route('POST /user/logout', function() {
  unset($_SESSION['rigorix']);

  echo "{ 'status': 'ok' }";
});

Flight::route('GET /auth/@id_utente', function($id_utente) { global $dm_utente;

	$user = $dm_utente->getObjUtenteById($id_utente);
	echo FastJSON::convert($user);
});

Flight::route('GET /auth/@id_utente/game/status', function($id_utente) { global $dm_utente, $dm_messaggi, $dm_sfide, $dm_rewards;
  $UserObject                   = $dm_utente->getObjUtenteById($id_utente);
  $UserObject->messages         = $dm_messaggi->getArrObjMessaggiUnread ($id_utente);
  $UserObject->totMessages      = $dm_messaggi->getCountUnbannedMessages ( $id_utente );
  $UserObject->badges           = $dm_utente->getArrayObjectQueryCustom ("select * from rewards, sfide_rewards where sfide_rewards.id_utente = $id_utente and rewards.id_reward = sfide_rewards.id_reward and rewards.tipo = 'badge'");
  $UserObject->sfide_da_giocare = $dm_sfide->getSfideDaGiocareByUtente ( $id_utente );
  $UserObject->rewards          = $dm_rewards->getRewardsObjectByIdUtente ( $id_utente );
  $UserObject->picture          = sanitizeUserPicture($UserObject->picture);

  echo FastJSON::convert($UserObject);
});



/// Users //////////////////////////////////////////////////////////////////////////////////////////////////////////////

  Flight::route('GET /users/@id_utente/@attribute', function($id_utente, $attribute) { global $dm_utente;
    $user = $dm_utente->getSingleObjectQueryCustom("SELECT $attribute FROM utente WHERE id_utente = " . $id_utente );
    if ($user === false)
      echo "{ '$attribute': 'unknown', 'id_utente': '$id_utente' }";
    else {
      $user->id_utente = $id_utente;
      if ($attribute == "username" && strpos($user->$attribute, "__DELETED__") !== false) {
        $user->$attribute = str_replace("__DELETED__", "", $user->$attribute);
        $user->deleted = true;
      } else
        $user->deleted = false;
      echo FastJSON::convert( $user );
    }
  });




/// Messages ///////////////////////////////////////////////////////////////////////////////////////////////////////////

  Flight::route('PUT /messages/@id_message/read', function($id_message) { global $dm_messaggi;
    $dm_messaggi->markAsReadById ($id_message);
    echo "{ 'status': 'ok' }";
  });

  Flight::route('DELETE /message/@id_message', function($id_message) { global $dm_messaggi;
    $dm_messaggi->removeMessaggio ($id_message);
    echo '{ "status": "ok" }';
  });



Flight::start();



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

?>