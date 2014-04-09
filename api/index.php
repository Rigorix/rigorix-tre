<?php
error_reporting(0);
ini_set( 'display_errors','0');

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, PUT, OPTIONS');
header('Content-type: application/json');

$env = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/.env'));

sleep(3);

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
require_once 'routes/routes.generic.php';
require_once 'routes/routes.users.php';
require_once 'routes/routes.messages.php';
require_once 'routes/routes.sfide.php';
require_once 'routes/routes.admin.php';

Flight::route("GET /test", function () {
  echo "Api works!!";
  return false;
});

Flight::set("BOT_ID", 1);
Flight::after("start", function () {

  Flight::checkPeriodicActions();
});



Flight::start();
?>