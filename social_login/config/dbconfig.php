<?php

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'rigorix_rigorix');
define('DB_PASSWORD', 'rigorix_tre_!!');
define('DB_DATABASE', 'rigorix_tre');
$connection = mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD) or die(mysql_error());
$database = mysql_select_db(DB_DATABASE) or die(mysql_error());
?>
