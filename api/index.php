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

require_once 'classes/Helper.php';
require_once 'classes/user.class.php';
require_once 'classes/messages.class.php';
require_once 'classes/rewards.class.php';
require_once 'classes/sfide.class.php';
require_once 'classes/error.class.php';

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



/// User ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
// GETS -----

require_once 'users.php';

require_once 'messages.php';



/// Messages ///////////////////////////////////////////////////////////////////////////////////////////////////////////





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