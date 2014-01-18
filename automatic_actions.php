<?php
require_once('classes/core.php');

/*
 * 	Gestione operazioni automatiche
 */


/*
 * 	Conferma registrazione andata a buon fine,
 * 	Utente loggato,
 * 	Redirigo alla index.php
 */

 if ( $activity->check_success ( 430 ) && strpos($_SERVER['PHP_SELF'], "index") === false )
	header ("Location: index.php?action_queue=CONFIRM_SUBSCRIPTION_OK");


if ( $activity->check_success ( 125 ) && strpos($_SERVER['PHP_SELF'], "index") === false )
	header ("Location: index.php?action_queue=CONFIRM_UNSUBSCRIPTION_OK");

/*
 * 	Errore nella conferma registrazione,
 * 	Vado alla index ma segnalo l'errore
 *
if ( $activity->check_error ( 430 ) && strpos($_SERVER['PHP_SELF'], "index") === false ) {
	header ("Location: index.php?action_queue=ERROR_ON_CONFIRM_SUBSCRIPTION");
}*/
?>