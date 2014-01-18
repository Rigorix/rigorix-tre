<?php
session_start();
require 'dbconfig.php';

class UserClass {

    function checkUser($uid, $oauth_provider, $username)
	{
        $query = mysql_query("SELECT * FROM `utente` WHERE {$oauth_provider}_uid = '$uid'") or die(mysql_error());
        $result = mysql_fetch_array($query);
        if (!empty($result)) {
            # User is already present
            header('Location: /index.php?activity=login_by_id&id=' . $result["id_utente"]);
        } else {
            #user not present. Insert a new Record
			$query = mysql_query("INSERT INTO `utente` ({$oauth_provider}_uid, username) VALUES ($uid, '$username')") or die(mysql_error());
			$q = "SELECT * FROM `utente` WHERE {$oauth_provider}_uid = $uid";
			$query = mysql_query( $q );
            $result = mysql_fetch_array($query);
            return $result;
        }
        return $result;
    }

}

?>
