<?php
//error_reporting(E_ALL);
//ini_set( 'display_errors','1');
error_reporting(0);
ini_set( 'display_errors','0');

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, PUT, OPTIONS');
header('Content-type: application/json');

require_once 'database.php';
require_once '../classes/fastjson.php';
//require_once '../classes/core.php';
require_once 'flight/Flight.php';
require_once 'Helper.php';

require_once 'user.class.php';
require_once 'messages.class.php';
require_once 'rewards.class.php';
require_once 'sfide.class.php';
require_once 'error.class.php';

// TO BE REMOVED
  require_once __DIR__ . '/../dm/dm_generic_mysql.php';
  require_once __DIR__ . '/../dm/dm_utente.php';
  require_once __DIR__ . '/../dm/dm_sfide.php';
  require_once __DIR__ . '/../dm/dm_messaggi.php';
  require_once __DIR__ . '/../dm/dm_rewards.php';
  $db_conn = mysql_pconnect ("localhost", "root", "");
  mysql_select_db ( "rigorix_tre" );
  $db		 		    = new dm_generic_mysql  ( $db_conn, "rigorix_tre", false );
  $dm_utente 		= new dm_utente         ( $db_conn, "rigorix_tre", false );
  $dm_sfide 		= new dm_sfide          ( $db_conn, "rigorix_tre", false );
  $dm_messaggi	= new dm_messaggi       ( $db_conn, "rigorix_tre", false );
  $dm_rewards		= new dm_rewards        ( $db_conn, "rigorix_tre", false );

  require_once( __DIR__ . '/../classes/user.context.php' );
  require_once( __DIR__ . '/../classes/activities.context.php' );
  $activity = new activities();



/// UserServiceNEw
//Flight::route('/user/@id_utente(/*)', function($id_utente) {
//  if (!hasAuth($id_utente)):
//    echo "{ 'error': 'not-authorized' }";
//    die();
//  else:
//    return true;
//  endif;
//});

/// User ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
// GETS -----

Flight::route('GET /users/active', function() {
  echo Users::active()->get();
});

Flight::route('GET /users/bysocial/@uid', function($uid) {
  $result = Users::findBySocialId($uid)->get();
  if ( $result->count() == 0)
    Flight::notFound();
  else
    echo Users::findBySocialId($uid)->get()->first();

});

Flight::route('GET /users/champion/@period', function($period) {
  if ($period == "week")
    $sfide = Sfide::lastWeek()->done();
  if ($period == "month")
    $sfide = Sfide::lastMonth()->done();
  if ($period == "day")
    $sfide = Sfide::today()->done();

  if ( $sfide != null):
    $usersPoints = array();
    foreach ( $sfide->get() as $sfida) {
      if ( !array_key_exists($sfida->id_sfidante, $usersPoints))
        $usersPoints[$sfida->id_sfidante] = 0;
      $usersPoints[$sfida->id_sfidante] += $sfida->punti_sfidante;

      if ( !array_key_exists($sfida->id_sfidato, $usersPoints))
        $usersPoints[$sfida->id_sfidato] = 0;
      $usersPoints[$sfida->id_sfidato] += $sfida->punti_sfidato;
    }

    $bestUser = 0;
    $bestUserPunteggio = 0;
    foreach ( $usersPoints as $id_utente => $punteggio ) {
      if ( $punteggio > $bestUserPunteggio ) {
        $bestUser = $id_utente;
        $bestUserPunteggio = $punteggio;
      }
    }
    $best = new stdClass();
    $best->userObject = Users::find($bestUser)->toArray();
    $best->punteggio = $bestUserPunteggio;
    echo FastJSON::convert($best);

  else:
    echo '{ "id_utente": 0 }';
  endif;
});

Flight::route('GET /users/top/@count', function($count) { global $dm_utente;
//  echo Users::top()->take($count)->get();

  $users = $dm_utente->getRankingUtenti ( $count );
  $users = sanitizeUsersPicture($users);
  echo FastJSON::convert( $users );
});

Flight::route('GET /users/@id_utente', function($id_utente) {
  Flight::json ( getUserObjectExtended($id_utente) );
});

Flight::route('GET /user/@id_utente/messages', function($id_utente) {
  echo  Users::find($id_utente)->messages->toJson();
});

Flight::route('GET /user/@id_utente/messages/sent', function($id_utente) {
  echo  Users::find($id_utente)->sentMessages->toJson();
});

Flight::route('GET /users/@id_utente/rewards', function($id_utente) {
  echo Users::find($id_utente)->rewards()->toJson();
});

Flight::route('GET /users/@id_utente/sfide/dagiocare', function($id_utente) {
  echo Sfide::receivedBy($id_utente)->unplayed()->get();
});

// POSTS -----

Flight::route('POST /users/create', function() {
  try {
    $newUser                  = new Users;
    $newUser->attivo          = 0;
    $newUser->social_provider = $_POST['provider'];
    $newUser->social_uid      = $_POST['id'];
    $newUser->social_url      = $_POST['link'];
    $newUser->username        = str_replace(" ", "_", $_POST['name']);
    $newUser->picture         = $_POST['image'];
    $newUser->nome            = $_POST['nome'];
    $newUser->cognome         = $_POST['cognome'];
    $newUser->sesso           = substr($_POST['gender'], 0, 1);
    $newUser->email           = $_POST['email'];

    $newUser->save();

    Flight::json(array("id_utente" => $newUser->id_utente));
  } catch (Exception $e) {
    Flight::error($e);
  }
});

Flight::route('POST /users/delete', function() {
  $data = getParams();
  $user = $data->user;

  if ( UsersUnsubscribe::user($user->id_utente)->count() == 0 ):
    $unsubscribe = new UsersUnsubscribe;
    $unsubscribe->id_utente = $user->id_utente;
    $unsubscribe->stato = 0;
    $unsubscribe->conf_code = md5( $id_utente . $user->username . "unsubscribe_utente_key" );
    $unsubscribe->save();
    echo '{ "status": "success", "id_utente" : '.$user->id_utente.'}';
  else:
    echo '{ "status": "error", "id_utente" : '.$user->id_utente.'}';
  endif;
});

Flight::route('POST /users/@id_utente', function($id_utente) { global $dm_utente;
  $postdata = file_get_contents("php://input");
  $data = json_decode($postdata);
  $dbObject = $dm_utente->makeInDbObject($data->db_object, true);

  $dm_utente->updateObject('utente', $dbObject, array( "id_utente" => $id_utente));

  echo FastJSON::convert(getUserObjectExtended($id_utente));
});





/// Users //////////////////////////////////////////////////////////////////////////////////////////////////////////////





//Flight::route('GET /users/username/@username', function($username) { global $dm_utente;
//  $users = $dm_utente->getUsersByUsernameQuery ( $username, false );
//  $users = sanitizeUsersPicture($users);
//  echo FastJSON::convert( $users );
//});
//
//Flight::route('GET /users/@id_utente', function($id_utente) { global $dm_utente;
//  $user = $dm_utente->getSingleObjectQueryCustom("SELECT * FROM utente WHERE id_utente = " . $id_utente );
//  if ($user === false)
//    echo "{ 'user': 'unknown', 'id_utente': '$id_utente' }";
//  else {
//    $user->picture = sanitizeUserPicture($user->picture);
//    echo FastJSON::convert( $user );
//  }
//});
//
//Flight::route('GET /users/@id_utente/@attribute', function($id_utente, $attribute) { global $dm_utente;
//  $user = $dm_utente->getSingleObjectQueryCustom("SELECT $attribute FROM utente WHERE id_utente = " . $id_utente );
//  if ($user === false)
//    echo "{ '$attribute': 'unknown', 'id_utente': '$id_utente' }";
//  else {
//    $user->id_utente = $id_utente;
//    if ($attribute == "username" && strpos($user->$attribute, "__DELETED__") !== false) {
//      $user->$attribute = str_replace("__DELETED__", "", $user->$attribute);
//      $user->deleted = true;
//    } else
//      $user->deleted = false;
//    echo FastJSON::convert( $user );
//  }
//});




/// Messages ///////////////////////////////////////////////////////////////////////////////////////////////////////////

Flight::route('POST /messages/new', function() { global $dm_messaggi;
  $postdata = file_get_contents("php://input");
  $data = json_decode($postdata);
  $message = $data->message;
  $message->id_receiver = $message->receiver->id_utente;
  unset($message->receiver);

  $message = $dm_messaggi->makeInDbObject($message, true);

  $dm_messaggi->pushMessage ($message);
  echo "{ 'status': 'ok' }";
});

Flight::route('PUT /messages/@id_message/read', function($id_message) { global $dm_messaggi;
  $dm_messaggi->markAsReadById ($id_message);
  echo "{ 'status': 'ok' }";
});

Flight::route('DELETE /message/@id_message', function($id_message) { global $dm_messaggi;
  $dm_messaggi->removeMessaggio ($id_message);
  echo '{ "status": "ok" }';
});




/// GAME ///////////////////////////////////////////////////////////////////////////////////////////////////////////////

Flight::route('GET /sfida/@id_sfida/xml', function($id_sfida) { global $dm_sfide, $dm_utente;

  header('Content-type: text/xml');

  $objUtente = $_SESSION['rigorix']['user'];
  $objFullSfida = $dm_sfide->getFullObjSfidaById( $id_sfida );
  $objUtenteSfidante = $dm_utente->getObjUtenteById ( $objFullSfida->id_sfidante );
  $objUtenteSfidato = $dm_utente->getObjUtenteById( $objFullSfida->id_sfidato );

  echo '<?xml version="1.0" encoding="UTF-8"?>
    <game>
        <settings delayAfterShoot_time="2000" totalShots="10" shooter="player1" firstShooter="player1" keeper="player2" firstKeeper="player2" transitionTime=".6" currentShoot="1" />
        <players>
            <player name="'.$objUtenteSfidante->username.'" number="'.$objUtenteSfidante->numero_maglietta.'" whatcher="'.(($objUtenteSfidante->id_utente==$objUtente->id_utente) ? "true" : "false").'">
                <skin calzini="'.str_replace("#", "0x",$objUtenteSfidante->colore_calzini).'" maglia="'.str_replace("#", "0x",$objUtenteSfidante->colore_maglietta).'" pantaloni="'.str_replace("#", "0x",$objUtenteSfidante->colore_pantaloncini).'" tipoMaglia="'.$objUtenteSfidante->tipo_maglietta.'"/>
                <shoots>
                    <shoot target="'.retCorrTiroParata($objFullSfida->SFIDANTE->tiri->o1).'" />
                    <shoot target="'.retCorrTiroParata($objFullSfida->SFIDANTE->tiri->o2).'" />
                    <shoot target="'.retCorrTiroParata($objFullSfida->SFIDANTE->tiri->o3).'" />
                    <shoot target="'.retCorrTiroParata($objFullSfida->SFIDANTE->tiri->o4).'" />
                    <shoot target="'.retCorrTiroParata($objFullSfida->SFIDANTE->tiri->o5).'" />
                </shoots>
                <keeps>
                    <keep target="'.retCorrTiroParata($objFullSfida->SFIDANTE->parate->o1).'" />
                    <keep target="'.retCorrTiroParata($objFullSfida->SFIDANTE->parate->o2).'" />
                    <keep target="'.retCorrTiroParata($objFullSfida->SFIDANTE->parate->o3).'" />
                    <keep target="'.retCorrTiroParata($objFullSfida->SFIDANTE->parate->o4).'" />
                    <keep target="'.retCorrTiroParata($objFullSfida->SFIDANTE->parate->o5).'" />
                </keeps>
            </player>
            <player name="'.$objUtenteSfidato->username.'" number="'.$objUtenteSfidato->numero_maglietta.'" whatcher="'.(($objUtenteSfidato->id_utente==$objUtente->id_utente) ? "true" : "false").'">
                <skin calzini="'.str_replace("#", "0x",$objUtenteSfidato->colore_calzini).'" maglia="'.str_replace("#", "0x",$objUtenteSfidato->colore_maglietta).'" pantaloni="'.str_replace("#", "0x",$objUtenteSfidato->colore_pantaloncini).'" tipoMaglia="'.$objUtenteSfidato->tipo_maglietta.'"/>
                <shoots>
                    <shoot target="'.retCorrTiroParata($objFullSfida->SFIDATO->tiri->o1).'" />
                    <shoot target="'.retCorrTiroParata($objFullSfida->SFIDATO->tiri->o2).'" />
                    <shoot target="'.retCorrTiroParata($objFullSfida->SFIDATO->tiri->o3).'" />
                    <shoot target="'.retCorrTiroParata($objFullSfida->SFIDATO->tiri->o4).'" />
                    <shoot target="'.retCorrTiroParata($objFullSfida->SFIDATO->tiri->o5).'" />
                </shoots>
                <keeps>
                    <keep target="'.retCorrTiroParata($objFullSfida->SFIDATO->parate->o1).'" />
                    <keep target="'.retCorrTiroParata($objFullSfida->SFIDATO->parate->o2).'" />
                    <keep target="'.retCorrTiroParata($objFullSfida->SFIDATO->parate->o3).'" />
                    <keep target="'.retCorrTiroParata($objFullSfida->SFIDATO->parate->o4).'" />
                    <keep target="'.retCorrTiroParata($objFullSfida->SFIDATO->parate->o5).'" />
                </keeps>
            </player>
        </players>
    </game>';
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



Flight::route('GET /users/active', function() { global $dm_utente;

  $users = $dm_utente->getUsernameOnline ();
  $users = sanitizeUsersPicture($users);
  echo FastJSON::convert( $users );

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









Flight::start();
?>