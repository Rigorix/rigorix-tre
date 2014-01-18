<?php
session_start();
/*
 * Facebook
 */
define ( "FB_APP_ID", "208222219208475");
define ( "FB_SECRET", "f1f51dbd33c7d16697d08cfad39fb87d");

$fb_config = array();
$fb_config["appId"] = FB_APP_ID;
$fb_config["secret"] = FB_SECRET;
$fb_config["fileUpload"] = false; // optional

require_once( 'classes/facebook-php-sdk/src/facebook.php' );




if ( $_SESSION["logged"] == true ):
	echo "sono loggato\n";
else:
	echo "non sono loggato, controllo FB\n";
endif;


?>
