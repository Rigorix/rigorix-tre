<?php
error_reporting(0);
ini_set( 'display_errors','0');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, PUT, OPTIONS');
header('Content-type: application/json');

require_once '../classes/config.php';
require_once 'flight/Flight.php';
require_once '../dm/dm_generic_mysql.php';
require_once '../dm/dm_utente.php';
require_once '../dm/dm_messaggi.php';
require_once '../dm/dm_sfide.php';
require_once '../dm/dm_rewards.php';
require_once '../hybridauth/Hybrid/Auth.php';

// GET environment conf
$db           = new dm_generic_mysql( $db_conn, $db_name, $sql_debug );
$dm_utente    = new dm_utente( $db_conn, $db_name, $sql_debug );
$dm_messaggi  = new dm_messaggi( $db_conn, $db_name, $sql_debug );
$dm_sfide     = new dm_sfide( $db_conn, $db_name, $sql_debug );
$dm_rewards   = new dm_rewards( $db_conn, $db_name, $sql_debug );



/// Badges
Flight::route('GET /badges', function($count) { global $dm_rewards;

  $badges = $dm_rewards->getBadgeRewards ();
//  $encodedArray = array_map(utf8_encode, $badges);
//  utf8_encode($badges);
  echo html_entity_decode ( json_encode( $badges, JSON_FORCE_OBJECT ));
  die();


  echo "[";
  foreach ($badges as $badge) {
    echo json_encode( $badge );
    echo ",";
  }
  echo "]";

});



/// Sfide
Flight::route('GET /sfide/archivio/@id_utente', function($id_utente) { global $dm_sfide;

  $limit_start = isset($_GET['limit_start']) ? $_GET['limit_start'] : 0;
  $limit_count = isset($_GET['limit_count']) ? $_GET['limit_count'] : 10;
  $sfide = $dm_sfide->getArrayObjectQueryCustom ( "select * from sfida where stato >= 2 and stato != 3 and (id_sfidante = ".$id_utente." or id_sfidato = ".$id_utente.") order by dta_conclusa DESC limit $limit_start, $limit_count ");
  echo json_encode( $sfide );

});


/// Users
Flight::route('GET /users/all', function($count) { global $dm_utente;

  $users = $dm_utente->getRankingUtenti ( 100 );
  $users = sanitizeUsersPicture($users);
  echo json_encode( $users );

});

Flight::route('GET /users/top/@count', function($count) { global $dm_utente;

  $users = $dm_utente->getRankingUtenti ( $count );
  $users = sanitizeUsersPicture($users);
  echo json_encode( $users );

});

Flight::route('GET /users/active', function() { global $dm_utente;

  $users = $dm_utente->getUsernameOnline ();
  $users = sanitizeUsersPicture($users);
  echo json_encode( $users );

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
    echo json_encode( $user );
  }

});

/// AUTH
Flight::route('GET /auth/login', function($provider) {

  die();

});

Flight::route('GET /auth/@id_utente', function($id_utente) { global $dm_utente;

	$user = $dm_utente->getObjUtenteById($id_utente);
	echo json_encode($user);
});

Flight::route('GET /auth/@id_utente/game/status', function($id_utente) { global $dm_utente, $dm_messaggi, $dm_sfide, $dm_rewards;
  $UserObject = $dm_utente->getObjUtenteById($id_utente);
  $UserObject->messages = $dm_messaggi->getArrObjMessaggiUnread ($id_utente);
  $UserObject->badges = $dm_utente->getArrayObjectQueryCustom ("select * from rewards, sfide_rewards where sfide_rewards.id_utente = $id_utente and rewards.id_reward = sfide_rewards.id_reward and rewards.tipo = 'badge'");
  $UserObject->sfide_da_giocare = $dm_sfide->getSfideDaGiocareByUtente ( $id_utente );
  $UserObject->rewards = $dm_rewards->getRewardsObjectByIdUtente ( $id_utente );
  $UserObject->picture = sanitizeUserPicture($UserObject->picture);

  echo json_encode($UserObject);
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