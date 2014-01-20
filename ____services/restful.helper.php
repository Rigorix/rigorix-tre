require_once("../dm/dm_generic_mysql.php");
require_once("../dm/dm_utente.php");

$sql_debug = true;
$db_name = "rigorix_tre";
$db_conn = mysql_pconnect ("localhost", "rigorix_rigorix", "rigorix_tre_!!");
mysql_select_db ( $db_name );

$db         = new dm_generic_mysql( $db_conn, $db_name, $sql_debug );
$dm_utente  = new dm_utente( $db_conn, $db_name, $sql_debug );

function sanitizeUsersPicture($users) {
  return $users;
}