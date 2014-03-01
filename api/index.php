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
require_once 'flight/Flight.php';

require_once 'classes/Helper.php';
require_once 'classes/activity.php';
require_once 'classes/user.class.php';
require_once 'classes/messages.class.php';
require_once 'classes/rewards.class.php';
require_once 'classes/sfide.class.php';
require_once 'classes/error.class.php';

/*
 * Api ruoutes
 */
require_once 'routes/routes.users.php';
require_once 'routes/routes.messages.php';
require_once 'routes/routes.sfide.php';



Flight::route('GET /badges', function($count) {
  echo Rewards::badges()->active()->get()->toJson();
});

Flight::route('GET /test', function() {
  Flight::halt(200, "Api is working");
});

Flight::route('GET /test/getimage', function() {
  getUserPicture("http://cdn.sstatic.net/stackoverflow/img/sprites.png?v=6");
});

Flight::map('error', function(Exception $ex){
  // Handle error
  echo $ex->getTraceAsString();
});
Flight::set('flight.log_errors', true);

Flight::start();
?>