<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Content-type: application/json');
session_start();

require_once 'flight/Flight.php';
require_once("../dm/dm_generic_mysql.php");
require_once("../dm/dm_utente.php");

$sql_debug = true;
$db_name = "rigorix_tre";
$db_conn = mysql_pconnect ("localhost", "rigorix_rigorix", "rigorix_tre_!!");
mysql_select_db ( $db_name );

$db         = new dm_generic_mysql( $db_conn, $db_name, $sql_debug );
$dm_utente  = new dm_utente( $db_conn, $db_name, $sql_debug );




/// Users
Flight::route('GET /users/all', function($count) { global $dm_utente;

  $users = $dm_utente->getRankingUtenti ( 100 );
  echo json_encode( $users );

});


Flight::route('GET /users/top/@count', function($count) { global $dm_utente;

  $users = $dm_utente->getRankingUtenti ( $count );
  echo json_encode( $users );

});


Flight::start();



?>