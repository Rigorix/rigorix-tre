<?php
//error_reporting(E_ALL);
//ini_set( 'display_errors','1');
error_reporting(0);
ini_set( 'display_errors','0');

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, PUT, OPTIONS');
header('Content-type: application/json');

$env = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/.env'));

require_once 'database.php';
require_once '../classes/fastjson.php';
require_once '../classes/logger.php';
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
  $db_conn = mysql_pconnect ($env->DB->host, $env->DB->username, $env->DB->password);
  mysql_select_db ( $env->DB->name );
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

  if ( $sfide != null && $sfide->count() > 0):
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
    Flight::notFound();
  endif;
});

Flight::route('GET /users/top/@count', function($count) { global $dm_utente;
//  echo Users::top()->take($count)->get();

  $users = $dm_utente->getRankingUtenti ( $count );
  $users = sanitizeUsersPicture($users);
  echo FastJSON::convert( $users );
});

Flight::route('GET /users/@id_utente', function($id_utente) { ;
  _log("API", "/users/$id_utente");
  echo FastJSON::convert( getUserObjectExtended($id_utente) );
});

Flight::route('GET /users/@id_utente/messages', function($id_utente) {
  echo  Users::find($id_utente)->messages->toJson();
});

Flight::route('GET /users/@id_utente/messages/unread', function($id_utente) {
  echo Messages::receiver($id_utente)->unread()->get()->toJson();
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

Flight::route('GET /users/@id_utente/basic', function($id_utente) {
  $user = Users::find($id_utente);
  echo Flight::json(array(
    "id_utente"   => intval($id_utente),
    "username"    => $user->getAttribute("username"),
    "picture"     => sanitizeUserPicture($user->getAttribute("picture")),
    "punteggio"   => $user->getAttribute("punteggio_totale"),
    "attivo"      => $user->getAttribute("attivo")
  ));
});

Flight::route('GET /users/search/@attribute/@search', function($attribute, $search) {
  echo  Users::searchAttribute($attribute, $search)->get()->toJson();
});

// POSTS -----

Flight::route('POST /users/create', function() {
  _log ("API POST /users/create", FastJSON::convert($_POST));
  _log ("API POST /users/create", "Found " . Users::findBySocialId($_POST['id'])->get()->count() . " with id " . $_POST['id']);
  if (Users::findBySocialId($_POST['id'])->get()->count() == 0):
    try {
      $newUser                  = new Users;
      $newUser->attivo          = 0;
      $newUser->social_provider = $_POST['provider'];
      $newUser->social_uid      = $_POST['id'];
      $newUser->social_url      = $_POST['link'];
      $newUser->username        = str_replace(" ", "_", $_POST['name']);
      $newUser->picture         = $_POST['picture'];
      $newUser->nome            = $_POST['given_name'];
      $newUser->cognome         = $_POST['family_name'];
      $newUser->sesso           = strtoupper(substr($_POST['gender'], 0, 1));
      $newUser->email           = $_POST['email'];

      $newUser->save();

      Flight::json(array("id_utente" => $newUser->id_utente));
    } catch (Exception $e) {
      Flight::error($e);
    }
  else:
    Flight::halt(409, "Trying to create a user that is already there");
  endif;
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

    $userObj = Users::find($user->id_utente);
    $userObj->attivo = 0;
    $userObj->save();

    Flight::json(array("status" => "success", "id_utente" => "{$user->id_utente}"));
  else:
    Flight::halt(406, "Cannot accept to unsubscribe a user that already requested to subscribe");
  endif;
});


Flight::route('POST /users/@id_utente/logout', function($id_utente) {
  Flight::redirect($_SERVER["HTTP_REFERER"] . "?logout={$id_utente}");
});

Flight::route('POST /users/@id_utente', function($id_utente) {
  $postdata = file_get_contents("php://input");
  $data = json_decode($postdata);

  _log ("API POST /users/$id_utente", FastJSON::convert($data));

  $userData = ( isset($data->db_object) ) ? $data->db_object : $data;

  $result = Users::find($userData->id_utente)->update((array)$userData);

  _log ("API POST /users/$id_utente", "done");

  echo FastJSON::convert( getUserObjectExtended($userData->id_utente) );
});




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

  echo Rewards::badges()->active()->get()->toJson();
});



/// Sfide
Flight::route('GET /sfide/archivio/@id_utente', function($id_utente) {

  echo Sfide::user($id_utente)->done()->get()->toJson();

});

Flight::route('GET /sfide/pending/@id_utente', function($id_utente) {

  echo Sfide::user($id_utente)->pending()->get()->toJson();

});

Flight::route('POST /sfide/set', function() {

  $postdata = file_get_contents("php://input");
  $data = json_decode($postdata);

  $sfidaMatrix = json_decode($data->sfida_matrix);
  $sfidaObject = $data->sfida;

  $risposta = true;

  var_dump($sfidaObject, $sfidaMatrix);

  if ( !isset($sfidaObject->id_sfida) || $sfidaObject->id_sfida === false ):
    $sfida = Sfide::create(array(
      "id_sfidante" => intval($sfidaObject->id_sfidante),
      "id_sfidato"  => intval($sfidaObject->id_avversario)
    ));
    $id_sfida = $sfida->getAttribute("id_sfida");
    $risposta = false;
  else:
    $id_sfida = $sfidaObject->id_sfida;
  endif;

  $tiri = SfideTiri::create(array(
    "id_sfida"  => $id_sfida,
    "id_utente" => $sfidaObject->id_utente ? $sfidaObject->id_utente : $sfidaObject->id_sfidante,
    "o1"        => $sfidaMatrix->tiro1 + 1,
    "o2"        => $sfidaMatrix->tiro1 + 1,
    "o3"        => $sfidaMatrix->tiro1 + 1,
    "o4"        => $sfidaMatrix->tiro1 + 1,
    "o5"        => $sfidaMatrix->tiro1 + 1
  ));

  $parate = SfideParate::create(array(
    "id_sfida"  => $id_sfida,
    "id_utente" => $sfidaObject->id_utente ? $sfidaObject->id_utente : $sfidaObject->id_sfidante,
    "o1"        => $sfidaMatrix->parata1 + 1,
    "o2"        => $sfidaMatrix->parata1 + 1,
    "o3"        => $sfidaMatrix->parata1 + 1,
    "o4"        => $sfidaMatrix->parata1 + 1,
    "o5"        => $sfidaMatrix->parata1 + 1
  ));

  if ( $tiri->getAttribute("id_tiri") && $parate->getAttribute("id_parate") ):
    $sfidaUpdate = Sfide::find($id_sfida);
    $sfidaUpdate->stato = $risposta == false ? 1 : 2;
    $sfidaUpdate->save();

    Flight::halt(200, array("status" => "success", "id_sfida" => $id_sfida));
  else:
    Flight::error();
  endif;



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




Flight::route('GET /users/@id_utente/@attribute', function($id_utente, $attribute) {
  $userAttr = Users::find($id_utente);
  $attrValue = ( $userAttr != null ) ? $userAttr->getAttribute($attribute) : "- sconosciuto -";

  Flight::json( array("username" => $attrValue, "id_utente" => $id_utente) );
});

/// AUTH
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









Flight::route('GET /test', function() {
  Flight::halt(200, "Api is working");
});

Flight::map('error', function(Exception $ex){
  // Handle error
  echo $ex->getTraceAsString();
});
Flight::set('flight.log_errors', true);

Flight::start();
?>